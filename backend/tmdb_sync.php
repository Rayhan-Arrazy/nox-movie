<?php
/**
 * tmdb_scrape.php
 *
 * For each movie with a tmdb_id, fetches the TMDB movie page HTML
 * and extracts the current poster and backdrop image paths.
 * No API key required — just reads public HTML pages.
 */

$db = mysqli_connect('localhost', 'root', '', 'moviestream_db');

$r = mysqli_query($db, "SELECT id, title, tmdb_id FROM movies WHERE tmdb_id > 0 ORDER BY id");
$movies = [];
while ($row = mysqli_fetch_assoc($r))
    $movies[] = $row;

$p = 'https://image.tmdb.org/t/p/w500';
$bd = 'https://image.tmdb.org/t/p/w1280';
$updated = 0;

foreach ($movies as $movie) {
    $tmdbId = $movie['tmdb_id'];
    $dbId = $movie['id'];
    $title = $movie['title'];

    // Fetch TMDB movie page
    $url = "https://www.themoviedb.org/movie/{$tmdbId}";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Accept: text/html,application/xhtml+xml',
        ],
    ]);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code !== 200 || empty($html)) {
        echo "❌ [{$dbId}] {$title} — HTTP {$code}\n";
        continue;
    }

    // Extract poster_path from og:image or poster img
    $posterPath = null;
    $backdropPath = null;

    // og:image usually has the poster: /t/p/w500/xxxxx.jpg
    if (preg_match('/content="https:\/\/image\.tmdb\.org\/t\/p\/w\d+(\/.+?\.jpg)"/i', $html, $m)) {
        $posterPath = $m[1];
    }

    // Look for backdrop in the page background or data attributes
    // Usually in: style="background-image: url('/t/p/w1920_and_h800_multi_faces/xxxx.jpg')"
    if (preg_match('/\/t\/p\/w\d+_and_h\d+_multi_faces(\/.+?\.jpg)/i', $html, $m2)) {
        $backdropPath = $m2[1];
    }
    // Alternative: look for /t/p/original/xxxx.jpg pattern for backdrop
    if (!$backdropPath && preg_match_all('/\/t\/p\/original(\/.+?\.jpg)/i', $html, $m3)) {
        // First original is usually the backdrop
        foreach ($m3[1] as $path) {
            if ($path !== $posterPath) {
                $backdropPath = $path;
                break;
            }
        }
    }

    // Fallback: use poster as backdrop too
    if (!$backdropPath)
        $backdropPath = $posterPath;

    if (!$posterPath) {
        echo "⚠️  [{$dbId}] {$title} — couldn't extract poster from HTML\n";
        continue;
    }

    $posterUrl = $p . $posterPath;
    $backdropUrl = $bd . ($backdropPath ?: $posterPath);

    $posterEsc = mysqli_real_escape_string($db, $posterUrl);
    $backdropEsc = mysqli_real_escape_string($db, $backdropUrl);

    mysqli_query($db, "UPDATE movies SET
        poster_url   = '$posterEsc',
        backdrop_url = '$backdropEsc',
        updated_at   = NOW()
    WHERE id = $dbId");

    echo "✅ [{$dbId}] {$title} — poster={$posterPath} backdrop={$backdropPath}\n";
    $updated++;

    usleep(300000); // 300ms delay to be nice
}

echo "\nUpdated: $updated / " . count($movies) . "\n";
