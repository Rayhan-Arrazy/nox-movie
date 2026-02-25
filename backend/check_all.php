<?php
$db = mysqli_connect('localhost', 'root', '', 'moviestream_db');
$r = mysqli_query($db, "SELECT id, title, poster_url, backdrop_url FROM movies ORDER BY id");
$movies = [];
while ($row = mysqli_fetch_assoc($r))
    $movies[] = $row;

$urls = [];
foreach ($movies as $i => $m) {
    $urls[] = ['idx' => $i, 'type' => 'poster', 'url' => $m['poster_url']];
    $urls[] = ['idx' => $i, 'type' => 'backdrop', 'url' => $m['backdrop_url']];
}

$multi = curl_multi_init();
$handles = [];
foreach ($urls as $j => $u) {
    $ch = curl_init($u['url']);
    curl_setopt_array($ch, [
        CURLOPT_NOBODY => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
    ]);
    $handles[$j] = $ch;
    curl_multi_add_handle($multi, $ch);
}
$running = null;
do {
    curl_multi_exec($multi, $running);
    curl_multi_select($multi);
} while ($running > 0);

$byMovie = [];
foreach ($handles as $j => $ch) {
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($code !== 200) {
        $m = $movies[$urls[$j]['idx']];
        $type = $urls[$j]['type'];
        if (!isset($byMovie[$m['id']]))
            $byMovie[$m['id']] = ['title' => $m['title'], 'types' => []];
        $byMovie[$m['id']]['types'][$type] = 1;
    }
    curl_multi_remove_handle($multi, $ch);
    curl_close($ch);
}
curl_multi_close($multi);

file_put_contents('broken_report.json', json_encode($byMovie, JSON_PRETTY_PRINT));

// Simple summary to stdout
echo "Broken movies: " . count($byMovie) . " / " . count($movies) . "\n";
foreach ($byMovie as $id => $info) {
    echo "  ID $id: {$info['title']} — " . implode('+', array_keys($info['types'])) . "\n";
}
