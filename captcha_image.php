<?php
session_start();

$answer = '';
if (
    isset($_SESSION['forgot_password']) &&
    is_array($_SESSION['forgot_password']) &&
    isset($_SESSION['forgot_password']['captcha']['answer'])
) {
    $answer = (string)$_SESSION['forgot_password']['captcha']['answer'];
}

if ($answer === '') {
    $answer = '------';
}

$answer = preg_replace('/[^A-Z0-9]/', '', strtoupper($answer));
if ($answer === '') {
    $answer = '------';
}

$width = 260;
$height = 84;
$charCount = strlen($answer);
$slot = (int)floor(($width - 30) / max($charCount, 1));

header('Content-Type: image/svg+xml; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<svg xmlns="http://www.w3.org/2000/svg" width="<?= $width ?>" height="<?= $height ?>" viewBox="0 0 <?= $width ?> <?= $height ?>">
  <rect width="100%" height="100%" rx="8" fill="#0f1117"/>
  <defs>
    <linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#8b5cf6"/>
      <stop offset="100%" stop-color="#06b6d4"/>
    </linearGradient>
  </defs>
  <?php for ($i = 0; $i < 9; $i++): ?>
    <?php
      $x1 = random_int(0, $width);
      $x2 = random_int(0, $width);
      $y1 = random_int(0, $height);
      $y2 = random_int(0, $height);
      $opacity = random_int(12, 38) / 100;
    ?>
    <line x1="<?= $x1 ?>" y1="<?= $y1 ?>" x2="<?= $x2 ?>" y2="<?= $y2 ?>" stroke="#ffffff" stroke-opacity="<?= $opacity ?>" stroke-width="1"/>
  <?php endfor; ?>
  <?php for ($i = 0; $i < 24; $i++): ?>
    <?php
      $cx = random_int(0, $width);
      $cy = random_int(0, $height);
      $r = random_int(1, 2);
      $opacity = random_int(8, 35) / 100;
    ?>
    <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="<?= $r ?>" fill="#ffffff" fill-opacity="<?= $opacity ?>"/>
  <?php endfor; ?>

  <?php for ($i = 0; $i < $charCount; $i++): ?>
    <?php
      $char = htmlspecialchars($answer[$i], ENT_QUOTES, 'UTF-8');
      $x = 16 + ($i * $slot) + random_int(0, 7);
      $y = random_int(48, 64);
      $rot = random_int(-20, 20);
      $fontSize = random_int(30, 36);
    ?>
    <text x="<?= $x ?>" y="<?= $y ?>" font-family="monospace" font-size="<?= $fontSize ?>" font-weight="700"
          fill="url(#g)" transform="rotate(<?= $rot ?> <?= $x ?> <?= $y ?>)"><?= $char ?></text>
  <?php endfor; ?>
</svg>
