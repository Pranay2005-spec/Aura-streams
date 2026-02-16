<?php
header('Content-Type: application/json; charset=utf-8');

$videoDir = __DIR__ . '/videos';
$allowed = ['mp4', 'webm', 'ogg'];

if (!is_dir($videoDir)) {
    echo json_encode(['videos' => []]);
    exit;
}

$files = scandir($videoDir);
$videos = [];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        continue;
    }
    $videos[] = [
        'name' => $file,
        'path' => 'videos/' . $file
    ];
}

echo json_encode(['videos' => $videos]);
