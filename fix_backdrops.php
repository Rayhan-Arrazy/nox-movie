<?php
/**
 * fix_backdrops.php
 * Re-fetches the latest backdrop_url (and poster_url) from TMDB
 * for every movie that has a tmdb_id stored in the database.
 *
 * Run from the backend folder:
 *   php fix_backdrops.php
 *
 * Requires: TMDB_API_TOKEN set in backend/.env
 *           PDO MySQL extension enabled in PHP
 */

// ─── Load .env manually ─────────────────────────────────────────────────────
$envPath = __DIR__ . '/backend/.env';
if (!file_exists($envPath)) {
    die("ERROR: .env not found at {$envPath}\n");
}

$envVars = [];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with(trim($line), '#') || !str_contains($line, '='))
        continue;
    [$key, $val] = explode('=', $line, 2);
    $envVars[trim($key)] = trim($val, " \t\n\r\0\x0B'\"");
}

$token = $envVars['TMDB_API_TOKEN'] ?? '';
if (empty($token) || $token === 'YOUR_TMDB_READ_ACCESS_TOKEN_HERE') {
    die("ERROR: TMDB_API_TOKEN is not configured in .env\n");
}

// ─── DB connection ───────────────────────────────────────────────────────────
$dbHost = $envVars['database.default.hostname'] ?? 'localhost';
$dbName = $envVars['database.default.database'] ?? 'moviestream_db';
$dbUser = $envVars['database.default.username'] ?? 'root';
$dbPass = $envVars['database.default.password'] ?? '';

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage() . "\n");
}

// ─── TMDB helper ────────────────────────────────────────────────────────────
function tmdbGet(string $endpoint, string $token): ?array
{
    $url = 'https://api.themoviedb.org/3' . $endpoint;
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ],
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($body === false || $code !== 200)
        return null;
    return json_decode($body, true);
}

function buildUrl(string $base, ?string $path, string $size): string
{
    if (empty($path))
        return '';
    return "{$base}/{$size}{$path}";
}

$imageBase = 'https://image.tmdb.org/t/p';

// ─── Fetch all movies with a tmdb_id ────────────────────────────────────────
$movies = $pdo->query("SELECT id, title, tmdb_id FROM movies WHERE tmdb_id IS NOT NULL AND tmdb_id > 0 ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

if (!$movies) {
    die("No movies with tmdb_id found in the database.\n");
}

echo "Found " . count($movies) . " movies to re-sync...\n\n";

$updated = 0;
$skipped = 0;
$failed = 0;

$updateStmt = $pdo->prepare("UPDATE movies SET backdrop_url = :backdrop, poster_url = :poster, updated_at = NOW() WHERE id = :id");

foreach ($movies as $movie) {
    $tmdbId = (int) $movie['tmdb_id'];
    $title = $movie['title'];

    echo "  [{$movie['id']}] {$title} (TMDB #{$tmdbId}) ... ";

    $data = tmdbGet("/movie/{$tmdbId}?language=en-US", $token);

    if (!$data || isset($data['error'])) {
        echo "FAILED (TMDB returned no data)\n";
        $failed++;
        continue;
    }

    $backdropPath = $data['backdrop_path'] ?? null;
    $posterPath = $data['poster_path'] ?? null;

    $backdropUrl = $backdropPath
        ? buildUrl($imageBase, $backdropPath, 'w1280')
        : 'https://placehold.co/1280x720/1a1a2e/818cf8?text=No+Backdrop';

    $posterUrl = $posterPath
        ? buildUrl($imageBase, $posterPath, 'w500')
        : 'https://placehold.co/500x750/1a1a2e/818cf8?text=No+Poster';

    $updateStmt->execute([
        ':backdrop' => $backdropUrl,
        ':poster' => $posterUrl,
        ':id' => $movie['id'],
    ]);

    $rowsAffected = $updateStmt->rowCount();
    if ($rowsAffected > 0) {
        echo "UPDATED ✓\n";
        echo "       Backdrop: {$backdropUrl}\n";
        $updated++;
    } else {
        echo "no change\n";
        $skipped++;
    }

    // Respect TMDB rate limit (40 requests/10s)
    usleep(260000); // ~260ms between requests
}

echo "\n=== Done ===\n";
echo "Updated : {$updated}\n";
echo "Skipped : {$skipped}\n";
echo "Failed  : {$failed}\n";
