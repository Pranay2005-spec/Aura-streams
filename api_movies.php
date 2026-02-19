<?php
require_once __DIR__ . '/database.php';
session_start();

header('Content-Type: application/json; charset=utf-8');

function send_json($payload, $status = 200) {
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

if (!empty($db_connect_error)) {
    send_json(['error' => 'Database connection failed', 'details' => $db_connect_error], 500);
}

function read_json_body() {
    $raw = file_get_contents('php://input');
    if (!$raw) {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function normalize_text($value) {
    return trim((string)$value);
}

function ensure_watch_later_table($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS watch_later (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        tmdb_id VARCHAR(32) DEFAULT '',
        media_type VARCHAR(10) DEFAULT 'movie',
        season INT NOT NULL DEFAULT 0,
        episode INT NOT NULL DEFAULT 0,
        poster VARCHAR(255) DEFAULT '',
        duration VARCHAR(50) DEFAULT '',
        year VARCHAR(10) DEFAULT '',
        badge_rating VARCHAR(10) DEFAULT '',
        rating_score VARCHAR(10) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_watch_later_item (user_id, title, media_type, season, episode),
        INDEX idx_watch_later_user_created (user_id, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
    $conn->query("UPDATE watch_later SET season = 0 WHERE season IS NULL");
    $conn->query("UPDATE watch_later SET episode = 0 WHERE episode IS NULL");
    $conn->query("ALTER TABLE watch_later MODIFY season INT NOT NULL DEFAULT 0");
    $conn->query("ALTER TABLE watch_later MODIFY episode INT NOT NULL DEFAULT 0");
}

function require_user_id() {
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    if ($user_id <= 0) {
        send_json(['error' => 'Login required'], 401);
    }
    return $user_id;
}

function watch_later_payload($data) {
    $title = normalize_text($data['title'] ?? '');
    $tmdb_id = normalize_text($data['tmdb_id'] ?? $data['tmdbId'] ?? '');
    $media_type = normalize_text($data['media_type'] ?? $data['mediaType'] ?? 'movie');
    $media_type = strtolower($media_type) === 'tv' ? 'tv' : 'movie';
    $season = isset($data['season']) && (int)$data['season'] > 0 ? (int)$data['season'] : 0;
    $episode = isset($data['episode']) && (int)$data['episode'] > 0 ? (int)$data['episode'] : 0;
    $poster = normalize_text($data['poster'] ?? '');
    $duration = normalize_text($data['duration'] ?? '');
    $year = normalize_text($data['year'] ?? '');
    $badge_rating = normalize_text($data['badge_rating'] ?? $data['badgeRating'] ?? '');
    $rating_score = normalize_text($data['rating_score'] ?? $data['ratingScore'] ?? '');

    return [
        'title' => $title,
        'tmdb_id' => $tmdb_id,
        'media_type' => $media_type,
        'season' => $season,
        'episode' => $episode,
        'poster' => $poster,
        'duration' => $duration,
        'year' => $year,
        'badge_rating' => $badge_rating,
        'rating_score' => $rating_score
    ];
}

function normalize_key($value) {
    $value = strtolower((string)$value);
    $value = preg_replace('/\\.[a-z0-9]+$/', '', $value);
    $value = str_replace(['_', '-', '.'], ' ', $value);
    $value = preg_replace('/\\s+/', ' ', $value);
    $value = preg_replace('/[^a-z0-9 ]/', '', $value);
    return trim($value);
}

function is_blocked_title($title) {
    $blocked = [
        'anayam rasoolam',
        'anaya rasoolam',
        'annayum rasoolum',
        'annayum rasoolam'
    ];
    $key = normalize_key($title);
    return $key !== '' && in_array($key, $blocked, true);
}

function get_media_overrides($title) {
    $key = normalize_key($title);
    if ($key === '') {
        return ['poster' => '', 'video' => ''];
    }
    $poster_map = [
        '500 days of summer' => 'Movies/500days.jpg',
        'american psycho' => 'Genere/psycho.jpg',
        'breaking bad' => 'WebSeries/breaking.jpg',
        'dexter' => 'WebSeries/dexter.jpg',
        'eternal sunshine of the spotless mind' => 'Movies/eternal.jfif',
        'fight club' => 'Movies/fightclub.jpg',
        'final destination' => 'Movies/final.jpg',
        'game of thrones' => 'WebSeries/got.jpg',
        'inception' => 'Movies/inception.jpg',
        'i saw the devil' => 'Movies/isaw.jpg',
        'memory' => 'Movies/memory.jpg',
        'memories of murder' => 'Movies/memory.jpg',
        'oldboy' => 'Movies/oldboy.png',
        'rom com picks' => 'Genere/romcom.webp',
        'romcom picks' => 'Genere/romcom.webp',
        'se7en' => 'Movies/seven.jpg',
        'shawshank redemption' => 'Movies/shawshank.jpg',
        'the shawshank redemption' => 'Movies/shawshank.jpg',
        'sopranos' => 'WebSeries/sopranos.jpg',
        'stranger things' => 'Genere/sci-fi.jpg',
        'the dark knight' => 'Movies/dark knight.jpg',
        'the godfather' => 'Movies/the godfather.png',
        'the lord of the rings' => 'WebSeries/lord of rings.jpg',
        'whiplash' => 'Movies/whiplash.jpg',
        'you' => 'WebSeries/you.jpg'
    ];
    $video_map = [
        '500 days of summer' => 'videos/500_Days_of_Summer.mp4',
        'american psycho' => 'videos/American_Psycho.mp4',
        'breaking bad' => 'videos/Breaking_Bad.mp4',
        'dexter' => 'videos/Dexter.mp4',
        'eternal sunshine of the spotless mind' => 'videos/Eternal_Sunshine.mp4',
        'fight club' => 'videos/Fight_Club.mp4',
        'final destination' => 'videos/Final_Destination.mp4',
        'game of thrones' => 'videos/Game_of_Thrones.mp4',
        'inception' => 'videos/Inception.mp4',
        'i saw the devil' => 'videos/_I_SAW_THE_DEVIL.mp4',
        'memories of murder' => 'videos/MEMORIES_OF_MURDER.mp4',
        'oldboy' => 'videos/OLDBOY.mp4',
        'se7en' => 'videos/Seven.mp4',
        'shawshank redemption' => 'videos/The_Shawshank_Redemption.mp4',
        'the shawshank redemption' => 'videos/The_Shawshank_Redemption.mp4',
        'sopranos' => 'videos/The_Sopranos.mp4',
        'the dark knight' => 'videos/The_Dark_Knight.mp4',
        'the godfather' => 'videos/THE_GODFATHER.mp4',
        'the lord of the rings' => 'videos/The_Lord_of_the_Rings.mp4',
        'whiplash' => 'videos/Whiplash.mp4',
        'you' => 'videos/YOU.mp4'
    ];
    return [
        'poster' => $poster_map[$key] ?? '',
        'video' => $video_map[$key] ?? ''
    ];
}

function media_path_exists($path) {
    $path = trim((string)$path);
    if ($path === '') {
        return false;
    }
    if (preg_match('~^https?://~i', $path)) {
        return true;
    }
    $full_path = __DIR__ . '/' . ltrim($path, "/\\");
    return file_exists($full_path);
}

function build_media_map($dir, $prefix) {
    $map = [];
    if (!is_dir($dir)) {
        return $map;
    }
    $files = glob($dir . '/*');
    if (!$files) {
        return $map;
    }
    foreach ($files as $file) {
        if (!is_file($file)) {
            continue;
        }
        $base = basename($file);
        $key = normalize_key($base);
        if ($key === '') {
            continue;
        }
        $map[$key] = $prefix . '/' . $base;
    }
    return $map;
}

function build_media_map_multi($entries) {
    $map = [];
    foreach ($entries as $entry) {
        $dir = $entry['dir'] ?? '';
        $prefix = $entry['prefix'] ?? '';
        if ($dir === '' || $prefix === '') {
            continue;
        }
        $map = array_merge($map, build_media_map($dir, $prefix));
    }
    return $map;
}

function find_best_match_key($title_key, $map) {
    if ($title_key === '' || empty($map)) {
        return '';
    }
    if (isset($map[$title_key])) {
        return $title_key;
    }
    $best_key = '';
    $best_score = PHP_INT_MAX;
    foreach ($map as $key => $_path) {
        if ($key === '') {
            continue;
        }
        if (strpos($key, $title_key) !== false || strpos($title_key, $key) !== false) {
            return $key;
        }
        $score = levenshtein($title_key, $key);
        if ($score < $best_score) {
            $best_score = $score;
            $best_key = $key;
        }
    }
    $threshold = max(2, (int)floor(strlen($title_key) * 0.25));
    return $best_score <= $threshold ? $best_key : '';
}

function resolve_media_path($title, $map) {
    $aliases = [
        'memories of murder' => ['memories of murder', 'memory'],
        'memory' => ['memory'],
        'game of thrones' => ['got'],
        'rom com picks' => ['romcom'],
        'romcom picks' => ['romcom'],
        'stranger things' => ['sci fi', 'scifi'],
        'the dark knight' => ['dark knight'],
        'the lord of the rings' => ['lord of rings', 'lord of the rings'],
        'the godfather' => ['the godfather'],
        'i saw the devil' => ['isaw', 'i saw'],
        '500 days of summer' => ['500days'],
        'shawshank redemption' => ['shawshank'],
        'the shawshank redemption' => ['shawshank']
    ];
    $key = normalize_key($title);
    if ($key === '') {
        return '';
    }
    $keys = [$key];
    if (isset($aliases[$key])) {
        $keys = array_merge($keys, $aliases[$key]);
    }
    $keys = array_values(array_unique(array_filter($keys)));
    foreach ($keys as $candidate) {
        if (isset($map[$candidate])) {
            return $map[$candidate];
        }
    }
    foreach ($keys as $candidate) {
        $best_key = find_best_match_key($candidate, $map);
        if ($best_key !== '') {
            return $map[$best_key];
        }
    }
    return '';
}

function is_placeholder_poster($poster, $title) {
    $poster = strtolower(trim((string)$poster));
    if ($poster === '') {
        return true;
    }
    if (!media_path_exists($poster)) {
        return true;
    }
    $title_key = normalize_key($title);
    if (strpos($poster, 'fightclub') !== false && $title_key !== 'fight club') {
        return true;
    }
    if ($title_key !== '') {
        $poster_key = normalize_key(basename($poster));
        if ($poster_key !== '' && $poster_key !== $title_key) {
            if (strpos($poster_key, $title_key) === false && strpos($title_key, $poster_key) === false) {
                $score = levenshtein($title_key, $poster_key);
                $threshold = max(2, (int)floor(strlen($title_key) * 0.25));
                if ($score > $threshold) {
                    return true;
                }
            }
        }
    }
    return false;
}

function is_placeholder_video($video, $title) {
    $video = strtolower(trim((string)$video));
    if ($video === '') {
        return true;
    }
    if (!media_path_exists($video)) {
        return true;
    }
    if (strpos($video, 'sintel_trailer-480p') !== false) {
        return true;
    }
    $title_key = normalize_key($title);
    if ($title_key !== '') {
        $video_key = normalize_key(basename($video));
        if ($video_key !== '' && $video_key !== $title_key) {
            if (strpos($video_key, $title_key) === false && strpos($title_key, $video_key) === false) {
                return true;
            }
        }
    }
    return false;
}

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $result = $conn->query("SELECT * FROM movies ORDER BY created_at DESC, id DESC");
    $movies = [];
    $video_map = null;
    $poster_map = null;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if (is_blocked_title($row['title'] ?? '')) {
                continue;
            }
            $row['featured'] = (int)$row['featured'] === 1;
            $row['views'] = (int)$row['views'];
            $row['genres'] = strlen($row['genres'] ?? '') ? array_map('trim', explode(',', $row['genres'])) : [];
            if (is_placeholder_video($row['video'] ?? '', $row['title'] ?? '') || is_placeholder_poster($row['poster'] ?? '', $row['title'] ?? '')) {
                if ($video_map === null) {
                    $video_map = build_media_map(__DIR__ . '/videos', 'videos');
                }
                if ($poster_map === null) {
                    $poster_map = build_media_map_multi([
                        ['dir' => __DIR__ . '/Movies', 'prefix' => 'Movies'],
                        ['dir' => __DIR__ . '/NewArrivals', 'prefix' => 'NewArrivals'],
                        ['dir' => __DIR__ . '/WebSeries', 'prefix' => 'WebSeries'],
                        ['dir' => __DIR__ . '/Genere', 'prefix' => 'Genere'],
                        ['dir' => __DIR__ . '/Thumbnails', 'prefix' => 'Thumbnails']
                    ]);
                }
                if (is_placeholder_video($row['video'] ?? '', $row['title'] ?? '')) {
                    $resolved_video = resolve_media_path($row['title'] ?? '', $video_map);
                    if ($resolved_video !== '') {
                        $row['video'] = $resolved_video;
                    } else {
                        $row['video'] = '';
                    }
                }
                if (is_placeholder_poster($row['poster'] ?? '', $row['title'] ?? '')) {
                    $resolved_poster = resolve_media_path($row['title'] ?? '', $poster_map);
                    if ($resolved_poster !== '') {
                        $row['poster'] = $resolved_poster;
                    }
                }
            }
            $overrides = get_media_overrides($row['title'] ?? '');
            if (!empty($overrides['poster']) && media_path_exists($overrides['poster'])) {
                $row['poster'] = $overrides['poster'];
            }
            if (!empty($overrides['video']) && media_path_exists($overrides['video'])) {
                $row['video'] = $overrides['video'];
            }
            $movies[] = $row;
        }
    }
    send_json(['movies' => $movies]);
}

if ($action === 'auto_match') {
    $force = isset($_GET['force']) && $_GET['force'] == '1';
    $video_dir = __DIR__ . '/videos';
    $video_map = build_media_map($video_dir, 'videos');
    $poster_map = build_media_map_multi([
        ['dir' => __DIR__ . '/Movies', 'prefix' => 'Movies'],
        ['dir' => __DIR__ . '/NewArrivals', 'prefix' => 'NewArrivals'],
        ['dir' => __DIR__ . '/WebSeries', 'prefix' => 'WebSeries'],
        ['dir' => __DIR__ . '/Genere', 'prefix' => 'Genere'],
        ['dir' => __DIR__ . '/Thumbnails', 'prefix' => 'Thumbnails']
    ]);

    $result = $conn->query("SELECT id, title, video, poster FROM movies ORDER BY id ASC");
    $updated = 0;
    $matched_videos = 0;
    $matched_posters = 0;

    if ($result) {
        $stmt = $conn->prepare("UPDATE movies SET video = ?, poster = ? WHERE id = ?");
        if (!$stmt) {
            send_json(['error' => 'Database error', 'details' => $conn->error], 500);
        }

        while ($row = $result->fetch_assoc()) {
            $video = $row['video'] ?? '';
            $poster = $row['poster'] ?? '';
            $new_video = $video;
            $new_poster = $poster;

            if ($force || is_placeholder_video($new_video, $row['title'] ?? '')) {
                $resolved_video = resolve_media_path($row['title'] ?? '', $video_map);
                if ($resolved_video !== '') {
                    $new_video = $resolved_video;
                    $matched_videos++;
                }
            }
            if ($force || is_placeholder_poster($new_poster, $row['title'] ?? '')) {
                $resolved_poster = resolve_media_path($row['title'] ?? '', $poster_map);
                if ($resolved_poster !== '') {
                    $new_poster = $resolved_poster;
                    $matched_posters++;
                }
            }

            if ($new_video !== $video || $new_poster !== $poster) {
                $stmt->bind_param("ssi", $new_video, $new_poster, $row['id']);
                if ($stmt->execute()) {
                    $updated++;
                }
            }
        }
    }

    send_json([
        'success' => true,
        'updated' => $updated,
        'matched_videos' => $matched_videos,
        'matched_posters' => $matched_posters
    ]);
}

if ($action === 'by_title') {
    $title = normalize_text($_GET['title'] ?? '');
    if ($title === '') {
        send_json(['error' => 'Missing title'], 400);
    }
    if (is_blocked_title($title)) {
        send_json(['movie' => null]);
    }
    $stmt = $conn->prepare("SELECT * FROM movies WHERE LOWER(title) = LOWER(?) LIMIT 1");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie = $result ? $result->fetch_assoc() : null;
    if (!$movie) {
        send_json(['movie' => null]);
    }
    $movie['featured'] = (int)$movie['featured'] === 1;
    $movie['views'] = (int)$movie['views'];
    $movie['genres'] = strlen($movie['genres'] ?? '') ? array_map('trim', explode(',', $movie['genres'])) : [];
    if (is_placeholder_video($movie['video'] ?? '', $movie['title'] ?? '') || is_placeholder_poster($movie['poster'] ?? '', $movie['title'] ?? '')) {
        $video_map = build_media_map(__DIR__ . '/videos', 'videos');
        $poster_map = build_media_map_multi([
            ['dir' => __DIR__ . '/Movies', 'prefix' => 'Movies'],
            ['dir' => __DIR__ . '/NewArrivals', 'prefix' => 'NewArrivals'],
            ['dir' => __DIR__ . '/WebSeries', 'prefix' => 'WebSeries'],
            ['dir' => __DIR__ . '/Genere', 'prefix' => 'Genere'],
            ['dir' => __DIR__ . '/Thumbnails', 'prefix' => 'Thumbnails']
        ]);
        if (is_placeholder_video($movie['video'] ?? '', $movie['title'] ?? '')) {
            $resolved_video = resolve_media_path($movie['title'] ?? '', $video_map);
            if ($resolved_video !== '') {
                $movie['video'] = $resolved_video;
            } else {
                $movie['video'] = '';
            }
        }
        if (is_placeholder_poster($movie['poster'] ?? '', $movie['title'] ?? '')) {
            $resolved_poster = resolve_media_path($movie['title'] ?? '', $poster_map);
            if ($resolved_poster !== '') {
                $movie['poster'] = $resolved_poster;
            }
        }
    }
    $overrides = get_media_overrides($movie['title'] ?? '');
    if (!empty($overrides['poster']) && media_path_exists($overrides['poster'])) {
        $movie['poster'] = $overrides['poster'];
    }
    if (!empty($overrides['video']) && media_path_exists($overrides['video'])) {
        $movie['video'] = $overrides['video'];
    }
    send_json(['movie' => $movie]);
}

if ($action === 'save') {
    $data = read_json_body();
    $id = isset($data['id']) ? (int)$data['id'] : 0;
    $title = normalize_text($data['title'] ?? '');
    $tmdb_id = normalize_text($data['tmdb_id'] ?? $data['tmdbId'] ?? '');
    $media_type = normalize_text($data['media_type'] ?? $data['mediaType'] ?? '');
    $season = isset($data['season']) ? (int)$data['season'] : 0;
    $episode = isset($data['episode']) ? (int)$data['episode'] : 0;
    $poster = normalize_text($data['poster'] ?? '');
    $video = normalize_text($data['video'] ?? '');
    $duration = normalize_text($data['duration'] ?? '');
    $year = normalize_text($data['year'] ?? '');
    $badge_rating = normalize_text($data['badge_rating'] ?? '');
    $rating_score = normalize_text($data['rating_score'] ?? '');
    $rating_count = normalize_text($data['rating_count'] ?? '');
    $description = normalize_text($data['description'] ?? '');
    $genres = $data['genres'] ?? '';
    $featured = !empty($data['featured']) ? 1 : 0;

    if ($title === '' || $poster === '') {
        send_json(['error' => 'Title and poster are required'], 400);
    }
    if (is_blocked_title($title)) {
        send_json(['error' => 'Title is not allowed'], 400);
    }

    $genres_value = '';
    if (is_array($genres)) {
        $genres_value = implode(', ', array_filter(array_map('trim', $genres)));
    } else {
        $genres_value = normalize_text($genres);
    }

    if ($featured === 1) {
        $conn->query("UPDATE movies SET featured = 0");
    }

    if ($media_type === '') {
        $media_type = 'movie';
    }
    $season_value = $season > 0 ? $season : null;
    $episode_value = $episode > 0 ? $episode : null;

    if ($id > 0) {
        $stmt = $conn->prepare(
            "UPDATE movies SET title=?, tmdb_id=?, media_type=?, season=?, episode=?, poster=?, video=?, duration=?, year=?, badge_rating=?, rating_score=?, rating_count=?, description=?, genres=?, featured=? WHERE id=?"
        );
        if (!$stmt) {
            send_json(['error' => 'Database error', 'details' => $conn->error], 500);
        }
        $stmt->bind_param(
            "sssii" . "sssssssss" . "ii",
            $title,
            $tmdb_id,
            $media_type,
            $season_value,
            $episode_value,
            $poster,
            $video,
            $duration,
            $year,
            $badge_rating,
            $rating_score,
            $rating_count,
            $description,
            $genres_value,
            $featured,
            $id
        );
        if (!$stmt->execute()) {
            send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
        }
        send_json(['success' => true, 'id' => $id]);
    }

    $stmt = $conn->prepare("SELECT id FROM movies WHERE title = ? LIMIT 1");
    if (!$stmt) {
        send_json(['error' => 'Database error', 'details' => $conn->error], 500);
    }
    $stmt->bind_param("s", $title);
    if (!$stmt->execute()) {
        send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
    }
    $result = $stmt->get_result();
    $existing = $result ? $result->fetch_assoc() : null;

    if ($existing) {
        $existing_id = (int)$existing['id'];
        $stmt = $conn->prepare(
            "UPDATE movies SET tmdb_id=?, media_type=?, season=?, episode=?, poster=?, video=?, duration=?, year=?, badge_rating=?, rating_score=?, rating_count=?, description=?, genres=?, featured=? WHERE id=?"
        );
        if (!$stmt) {
            send_json(['error' => 'Database error', 'details' => $conn->error], 500);
        }
        $stmt->bind_param(
            "ssii" . "sssssssss" . "ii",
            $tmdb_id,
            $media_type,
            $season_value,
            $episode_value,
            $poster,
            $video,
            $duration,
            $year,
            $badge_rating,
            $rating_score,
            $rating_count,
            $description,
            $genres_value,
            $featured,
            $existing_id
        );
        if (!$stmt->execute()) {
            send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
        }
        send_json(['success' => true, 'id' => $existing_id]);
    }

    $stmt = $conn->prepare(
        "INSERT INTO movies (title, tmdb_id, media_type, season, episode, poster, video, duration, year, badge_rating, rating_score, rating_count, description, genres, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    if (!$stmt) {
        send_json(['error' => 'Database error', 'details' => $conn->error], 500);
    }
    $stmt->bind_param(
        "sssii" . "sssssssss" . "i",
        $title,
        $tmdb_id,
        $media_type,
        $season_value,
        $episode_value,
        $poster,
        $video,
        $duration,
        $year,
        $badge_rating,
        $rating_score,
        $rating_count,
        $description,
        $genres_value,
        $featured
    );
    if (!$stmt->execute()) {
        send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
    }
    send_json(['success' => true, 'id' => $conn->insert_id]);
}

if ($action === 'delete') {
    $data = read_json_body();
    $id = isset($data['id']) ? (int)$data['id'] : 0;
    if ($id <= 0) {
        send_json(['error' => 'Missing id'], 400);
    }
    $stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
    if (!$stmt) {
        send_json(['error' => 'Database error', 'details' => $conn->error], 500);
    }
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
    }
    send_json(['success' => true]);
}

if ($action === 'increment_view') {
    $data = read_json_body();
    $title = normalize_text($data['title'] ?? '');
    if ($title === '') {
        send_json(['error' => 'Missing title'], 400);
    }
    $stmt = $conn->prepare("UPDATE movies SET views = views + 1 WHERE LOWER(title) = LOWER(?)");
    if (!$stmt) {
        send_json(['error' => 'Database error', 'details' => $conn->error], 500);
    }
    $stmt->bind_param("s", $title);
    if (!$stmt->execute()) {
        send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
    }
    send_json(['success' => true]);
}

if ($action === 'set_featured') {
    $data = read_json_body();
    $id = isset($data['id']) ? (int)$data['id'] : 0;
    if ($id <= 0) {
        send_json(['error' => 'Missing id'], 400);
    }
    $conn->query("UPDATE movies SET featured = 0");
    $stmt = $conn->prepare("UPDATE movies SET featured = 1 WHERE id = ?");
    if (!$stmt) {
        send_json(['error' => 'Database error', 'details' => $conn->error], 500);
    }
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
    }
    send_json(['success' => true]);
}

if ($action === 'watch_later_list') {
    $user_id = require_user_id();
    ensure_watch_later_table($conn);
    $stmt = $conn->prepare("SELECT id, title, tmdb_id, media_type, season, episode, poster, duration, year, badge_rating, rating_score, created_at FROM watch_later WHERE user_id = ? ORDER BY created_at DESC, id DESC");
    if (!$stmt) {
        send_json(['error' => 'Database error', 'details' => $conn->error], 500);
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
    }
    $result = $stmt->get_result();
    $movies = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row;
        }
    }
    send_json(['movies' => $movies]);
}

if ($action === 'watch_later_status') {
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    if ($user_id <= 0) {
        send_json(['saved' => false, 'logged_in' => false]);
    }
    ensure_watch_later_table($conn);
    $payload = watch_later_payload($_GET);
    if ($payload['title'] === '') {
        send_json(['saved' => false, 'logged_in' => true]);
    }
    $season = (int)$payload['season'];
    $episode = (int)$payload['episode'];
    $media_type = $payload['media_type'];
    $title = $payload['title'];
    $stmt = $conn->prepare("SELECT id FROM watch_later WHERE user_id = ? AND LOWER(title) = LOWER(?) AND media_type = ? AND season = ? AND episode = ? LIMIT 1");
    if (!$stmt) {
        send_json(['error' => 'Database error', 'details' => $conn->error], 500);
    }
    $stmt->bind_param("issii", $user_id, $title, $media_type, $season, $episode);
    if (!$stmt->execute()) {
        send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
    }
    $result = $stmt->get_result();
    $saved = $result && $result->num_rows > 0;
    send_json(['saved' => $saved, 'logged_in' => true]);
}

if ($action === 'watch_later_add') {
    $user_id = require_user_id();
    ensure_watch_later_table($conn);
    $data = read_json_body();
    $payload = watch_later_payload($data);
    if ($payload['title'] === '') {
        send_json(['error' => 'Missing title'], 400);
    }

    $stmt = $conn->prepare("INSERT INTO watch_later (user_id, title, tmdb_id, media_type, season, episode, poster, duration, year, badge_rating, rating_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE tmdb_id = VALUES(tmdb_id), poster = VALUES(poster), duration = VALUES(duration), year = VALUES(year), badge_rating = VALUES(badge_rating), rating_score = VALUES(rating_score)");
    if (!$stmt) {
        send_json(['error' => 'Database error', 'details' => $conn->error], 500);
    }
    $stmt->bind_param(
        "isssiisssss",
        $user_id,
        $payload['title'],
        $payload['tmdb_id'],
        $payload['media_type'],
        $payload['season'],
        $payload['episode'],
        $payload['poster'],
        $payload['duration'],
        $payload['year'],
        $payload['badge_rating'],
        $payload['rating_score']
    );
    if (!$stmt->execute()) {
        send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
    }
    send_json(['success' => true, 'saved' => true]);
}

if ($action === 'watch_later_remove') {
    $user_id = require_user_id();
    ensure_watch_later_table($conn);
    $data = read_json_body();
    $payload = watch_later_payload($data);
    if ($payload['title'] === '') {
        send_json(['error' => 'Missing title'], 400);
    }

    $season = (int)$payload['season'];
    $episode = (int)$payload['episode'];
    $media_type = $payload['media_type'];
    $title = $payload['title'];
    $stmt = $conn->prepare("DELETE FROM watch_later WHERE user_id = ? AND LOWER(title) = LOWER(?) AND media_type = ? AND season = ? AND episode = ?");
    if (!$stmt) {
        send_json(['error' => 'Database error', 'details' => $conn->error], 500);
    }
    $stmt->bind_param("issii", $user_id, $title, $media_type, $season, $episode);
    if (!$stmt->execute()) {
        send_json(['error' => 'Database error', 'details' => $stmt->error], 500);
    }
    send_json(['success' => true, 'saved' => false]);
}

send_json(['error' => 'Unknown action'], 400);





