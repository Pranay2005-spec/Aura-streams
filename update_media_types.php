<?php
require_once __DIR__ . '/database.php';

if (!empty($db_connect_error)) {
    http_response_code(500);
    echo "Database connection failed: " . $db_connect_error;
    exit;
}

$titles = [
    'Breaking Bad',
    'Sopranos',
    'Game Of Thrones',
    'Dexter',
    'You'
];

$placeholders = implode(',', array_fill(0, count($titles), '?'));
$sql = "UPDATE movies
        SET media_type = 'tv',
            season = IF(season IS NULL OR season = 0, 1, season),
            episode = IF(episode IS NULL OR episode = 0, 1, episode)
        WHERE title IN ($placeholders)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo "Database error: " . $conn->error;
    exit;
}

$types = str_repeat('s', count($titles));
$stmt->bind_param($types, ...$titles);

if (!$stmt->execute()) {
    http_response_code(500);
    echo "Update failed: " . $stmt->error;
    exit;
}

echo "Updated {$stmt->affected_rows} rows.";





