<?php
include "database.php";
session_start();

$SMTP_SETTINGS = [];
$smtpConfigPath = __DIR__ . DIRECTORY_SEPARATOR . 'smtp_settings.php';
if (file_exists($smtpConfigPath)) {
    $loaded = include $smtpConfigPath;
    if (is_array($loaded)) {
        $SMTP_SETTINGS = $loaded;
    }
}

if (!isset($GLOBALS['SMTP_LAST_ERROR'])) {
    $GLOBALS['SMTP_LAST_ERROR'] = '';
}
if (!isset($GLOBALS['SMTP_LAST_RESPONSE'])) {
    $GLOBALS['SMTP_LAST_RESPONSE'] = '';
}

function smtp_config($key, $default = '') {
    global $SMTP_SETTINGS;
    if (isset($SMTP_SETTINGS[$key]) && $SMTP_SETTINGS[$key] !== '') {
        return $SMTP_SETTINGS[$key];
    }
    $env = getenv($key);
    if ($env !== false && $env !== '') {
        return $env;
    }
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
        return (string)$_SERVER[$key];
    }
    return $default;
}

if (!isset($_SESSION['forgot_password']) || !is_array($_SESSION['forgot_password'])) {
    $_SESSION['forgot_password'] = [];
}

$flow = &$_SESSION['forgot_password'];

if (!isset($flow['stage'])) {
    $flow = [
        'stage' => 'email',
        'email' => '',
        'user_id' => null
    ];
}

function reset_forgot_flow() {
    $_SESSION['forgot_password'] = [
        'stage' => 'email',
        'email' => '',
        'user_id' => null
    ];
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && (($_GET['reset'] ?? '') === '1')) {
    reset_forgot_flow();
    $flow = &$_SESSION['forgot_password'];
}

function strong_password($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Za-z]/', $password)) return false;
    if (!preg_match('/\d/', $password)) return false;
    if (!preg_match('/[^A-Za-z0-9]/', $password)) return false;
    return true;
}

function generate_captcha() {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $length = 6;
    $answer = '';
    for ($i = 0; $i < $length; $i++) {
        $answer .= $chars[random_int(0, strlen($chars) - 1)];
    }
    $spaced = implode(' ', str_split($answer));
    return [
        'question' => $spaced,
        'answer' => $answer,
        'expires_at' => time() + 300
    ];
}

function generate_otp() {
    return (string)random_int(100000, 999999);
}

function load_phpmailer_runtime() {
    $autoloadCandidates = [
        __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
        dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
        getcwd() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php'
    ];

    foreach ($autoloadCandidates as $autoload) {
        if (is_string($autoload) && $autoload !== '' && file_exists($autoload)) {
            require_once $autoload;
            if (class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
                return true;
            }
        }
    }

    $srcCandidates = [
        __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'src',
        dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'src',
        getcwd() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'src'
    ];

    foreach ($srcCandidates as $srcBase) {
        $exceptionFile = $srcBase . DIRECTORY_SEPARATOR . 'Exception.php';
        $phpMailerFile = $srcBase . DIRECTORY_SEPARATOR . 'PHPMailer.php';
        $smtpFile = $srcBase . DIRECTORY_SEPARATOR . 'SMTP.php';
        if (file_exists($exceptionFile) && file_exists($phpMailerFile) && file_exists($smtpFile)) {
            require_once $exceptionFile;
            require_once $phpMailerFile;
            require_once $smtpFile;
            if (class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
                return true;
            }
        }
    }

    $GLOBALS['SMTP_LAST_ERROR'] = 'PHPMailer is missing. Checked: ' . implode(' | ', $srcCandidates);
    return false;
}

function send_otp_email($to, $otp) {
    $fromEmail = trim((string)smtp_config('FORGOT_FROM_EMAIL', ''));
    $fromName = smtp_config('FORGOT_FROM_NAME', 'Aura.stream');
    $siteName = smtp_config('SITE_NAME', 'Aura.stream');
    $subject = $siteName . " Password Reset OTP";
    $message = "Your OTP for password reset is: " . $otp . "\n\nThis OTP expires in 10 minutes.\nIf you did not request this, ignore this email.";

    $smtpHost = trim((string)smtp_config('SMTP_HOST', ''));
    $smtpPort = (int)smtp_config('SMTP_PORT', 587);
    $smtpUser = trim((string)smtp_config('SMTP_USER', ''));
    $smtpPass = (string)smtp_config('SMTP_PASS', '');
    $smtpSecure = strtolower((string)smtp_config('SMTP_SECURE', 'tls'));
    $smtpAuth = smtp_config('SMTP_AUTH', '1') === '1';
    $smtpAuthType = trim((string)smtp_config('SMTP_AUTH_TYPE', ''));

    if (!load_phpmailer_runtime()) {
        if (empty($GLOBALS['SMTP_LAST_ERROR'])) {
            $GLOBALS['SMTP_LAST_ERROR'] = "PHPMailer is missing. Install vendor/phpmailer/phpmailer or run composer require phpmailer/phpmailer";
        }
        return false;
    }

    if (!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
        $GLOBALS['SMTP_LAST_ERROR'] = "PHPMailer class not loaded.";
        return false;
    }

    if ($smtpHost === '') {
        $GLOBALS['SMTP_LAST_ERROR'] = "SMTP config missing. Set SMTP_HOST in smtp_settings.php";
        return false;
    }
    if ($smtpAuth && ($smtpUser === '' || $smtpPass === '')) {
        $GLOBALS['SMTP_LAST_ERROR'] = "SMTP auth enabled, but SMTP_USER/SMTP_PASS missing in smtp_settings.php";
        return false;
    }

    // Gmail app passwords are shown with spaces in UI; strip all whitespace safely.
    if (stripos($smtpHost, 'gmail.com') !== false) {
        $smtpPass = preg_replace('/\s+/', '', $smtpPass);
        if ($smtpAuthType === '') {
            $smtpAuthType = 'LOGIN';
        }
    }
    if ($fromEmail === '') {
        $fromEmail = $smtpUser;
    }

    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $smtpDebugLog = '';
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->Port = $smtpPort;
        $mail->SMTPAuth = $smtpAuth;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        if ($smtpAuthType !== '') {
            $mail->AuthType = $smtpAuthType;
        }
        $mail->Timeout = 25;
        $mail->SMTPDebug = (int)smtp_config('SMTP_DEBUG', 0);
        $mail->Debugoutput = function ($str, $level) use (&$smtpDebugLog) {
            $smtpDebugLog .= "[L" . (int)$level . "] " . trim((string)$str) . " | ";
        };
        if ($smtpSecure === 'ssl' || $smtpSecure === 'smtps') {
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($smtpSecure === 'tls' || $smtpSecure === 'starttls') {
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPSecure = '';
            $mail->SMTPAutoTLS = false;
        }
        if (smtp_config('SMTP_ALLOW_INSECURE_TLS', '0') === '1') {
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
        }
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML(false);
        $sent = $mail->send();
        if (!$sent) {
            $GLOBALS['SMTP_LAST_ERROR'] = 'PHPMailer send failed: ' . $mail->ErrorInfo;
            if ($smtpDebugLog !== '') {
                $GLOBALS['SMTP_LAST_ERROR'] .= ' | Debug: ' . substr($smtpDebugLog, 0, 1200);
            }
            return false;
        }
        return true;
    } catch (\Throwable $e) {
        $GLOBALS['SMTP_LAST_ERROR'] = 'PHPMailer error: ' . $e->getMessage();
        if (
            stripos($smtpHost, 'gmail.com') !== false &&
            stripos($GLOBALS['SMTP_LAST_ERROR'], 'Could not authenticate') !== false
        ) {
            $GLOBALS['SMTP_LAST_ERROR'] .= ' | Gmail tip: use 2-Step Verification + App Password, and set SMTP_PASS to that 16-character app password.';
        }
        if (!empty($mail) && isset($mail->ErrorInfo) && $mail->ErrorInfo !== '') {
            $GLOBALS['SMTP_LAST_ERROR'] .= ' | ErrorInfo: ' . $mail->ErrorInfo;
        }
        if (!empty($smtpDebugLog)) {
            $GLOBALS['SMTP_LAST_ERROR'] .= ' | Debug: ' . substr($smtpDebugLog, 0, 1200);
        }
        return false;
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($db_connect_error)) {
        $error = "Database is not reachable right now.";
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'submit_email') {
            $email = trim($_POST['email'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Enter a valid email address.";
            } else {
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
                if (!$stmt) {
                    $error = "Server error. Please try again.";
                } else {
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows !== 1) {
                        $error = "Account not found.";
                    } else {
                        $user = $result->fetch_assoc();
                        $flow['email'] = $email;
                        $flow['user_id'] = (int)$user['id'];
                        $flow['stage'] = 'captcha';
                        $flow['captcha'] = generate_captcha();
                        $success = "Email verified. Solve captcha to continue.";
                    }
                }
            }
        } elseif ($action === 'verify_captcha' && ($flow['stage'] ?? '') === 'captcha') {
            $captchaInputRaw = (string)($_POST['captcha_answer'] ?? '');
            $captchaInput = strtoupper(preg_replace('/\s+/', '', trim($captchaInputRaw)));
            $captchaAnswer = strtoupper((string)($flow['captcha']['answer'] ?? ''));

            if (empty($flow['captcha']) || time() > (int)$flow['captcha']['expires_at']) {
                $flow['captcha'] = generate_captcha();
                $error = "Captcha expired. Try the new captcha.";
            } elseif ($captchaInput === '' || !hash_equals($captchaAnswer, $captchaInput)) {
                $flow['captcha'] = generate_captcha();
                $error = "Incorrect captcha. Try again.";
            } else {
                $otp = generate_otp();
                $flow['otp'] = $otp;
                $flow['otp_expires_at'] = time() + 600;
                $flow['otp_attempts'] = 0;

                if (send_otp_email($flow['email'], $otp)) {
                    $flow['stage'] = 'otp';
                    $success = "OTP sent to your email.";
                } else {
                    unset($flow['otp'], $flow['otp_expires_at'], $flow['otp_attempts']);
                    $error = "Captcha is correct, but OTP could not be sent. " . ($GLOBALS['SMTP_LAST_ERROR'] ?: 'Check SMTP settings and try again.');
                }
            }
        } elseif ($action === 'refresh_captcha' && ($flow['stage'] ?? '') === 'captcha') {
            $flow['captcha'] = generate_captcha();
            $success = "Captcha refreshed.";
        } elseif ($action === 'resend_otp' && ($flow['stage'] ?? '') === 'otp') {
            $otp = generate_otp();
            $flow['otp'] = $otp;
            $flow['otp_expires_at'] = time() + 600;
            $flow['otp_attempts'] = 0;

            if (send_otp_email($flow['email'], $otp)) {
                $success = "New OTP sent.";
            } else {
                $error = "Unable to send OTP right now. " . ($GLOBALS['SMTP_LAST_ERROR'] ?: 'Check SMTP settings and try again.');
            }
        } elseif ($action === 'verify_otp' && ($flow['stage'] ?? '') === 'otp') {
            $otpInput = trim($_POST['otp'] ?? '');

            if (empty($flow['otp']) || empty($flow['otp_expires_at'])) {
                $error = "OTP session missing. Restart the process.";
                $flow = ['stage' => 'email', 'email' => '', 'user_id' => null];
            } elseif (time() > (int)$flow['otp_expires_at']) {
                $error = "OTP expired. Click resend OTP.";
            } else {
                $flow['otp_attempts'] = (int)($flow['otp_attempts'] ?? 0) + 1;

                if ($flow['otp_attempts'] > 5) {
                    $error = "Too many OTP attempts. Restart the process.";
                    $flow = ['stage' => 'email', 'email' => '', 'user_id' => null];
                } elseif (hash_equals((string)$flow['otp'], $otpInput)) {
                    unset($flow['otp'], $flow['otp_expires_at'], $flow['otp_attempts']);
                    $flow['stage'] = 'reset';
                    $success = "OTP verified. Set your new password.";
                } else {
                    $error = "Incorrect OTP.";
                }
            }
        } elseif ($action === 'reset_password' && ($flow['stage'] ?? '') === 'reset') {
            $newPassword = (string)($_POST['new_password'] ?? '');
            $confirmPassword = (string)($_POST['confirm_password'] ?? '');

            if ($newPassword === '' || $confirmPassword === '') {
                $error = "Both password fields are required.";
            } elseif ($newPassword !== $confirmPassword) {
                $error = "Passwords do not match.";
            } elseif (!strong_password($newPassword)) {
                $error = "Password must be 8+ chars with letter, number and special character.";
            } else {
                $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ? AND email = ? LIMIT 1");
                if (!$stmt) {
                    $error = "Server error. Please try again.";
                } else {
                    $stmt->bind_param("sis", $hashed, $flow['user_id'], $flow['email']);
                    if ($stmt->execute() && $stmt->affected_rows === 1) {
                        $_SESSION['forgot_password'] = [
                            'stage' => 'done',
                            'email' => $flow['email'] ?? ''
                        ];
                        $flow = &$_SESSION['forgot_password'];
                        $success = "Password changed successfully. You can login now.";
                    } else {
                        $error = "Could not update password. Restart forgot password flow.";
                    }
                }
            }
        } elseif ($action === 'restart') {
            $_SESSION['forgot_password'] = ['stage' => 'email', 'email' => '', 'user_id' => null];
            $flow = &$_SESSION['forgot_password'];
            $success = "Process restarted.";
        }
    }
}

$stage = $flow['stage'] ?? 'email';
$emailSafe = htmlspecialchars($flow['email'] ?? '', ENT_QUOTES, 'UTF-8');
$captchaUrl = 'captcha_image.php?t=' . rawurlencode((string)time());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - Aura.stream</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --bg-dark: #09090b;
      --bg-card: rgba(24, 24, 27, 0.86);
      --border-color: rgba(255, 255, 255, 0.12);
      --text-primary: #fafafa;
      --text-secondary: #a1a1aa;
      --gradient-1: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
    }
    body {
      min-height: 100vh;
      margin: 0;
      background: var(--bg-dark);
      color: var(--text-primary);
      font-family: Inter, Segoe UI, Tahoma, sans-serif;
      display: grid;
      place-items: center;
      padding: 20px;
    }
    .card-wrap {
      width: 100%;
      max-width: 430px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 28px 24px;
      box-shadow: 0 25px 40px rgba(0, 0, 0, 0.4);
    }
    .title {
      font-weight: 800;
      margin-bottom: 6px;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .sub {
      color: var(--text-secondary);
      font-size: 0.92rem;
      margin-bottom: 18px;
    }
    .btn-main {
      background: var(--gradient-1);
      border: 0;
      color: #fff;
      width: 100%;
      border-radius: 999px;
      padding: 10px 12px;
      font-weight: 600;
    }
    .btn-ghost {
      width: 100%;
      margin-top: 10px;
      border-radius: 999px;
      border: 1px solid var(--border-color);
      color: var(--text-primary);
      background: transparent;
      padding: 9px 12px;
    }
    a.link {
      color: #8b5cf6;
      text-decoration: none;
    }
    a.link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="card-wrap">
    <h2 class="title">Forgot Password</h2>
    <p class="sub">Recover access to your account</p>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger py-2"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success py-2"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if (!empty($db_connect_error)): ?>
      <div class="alert alert-danger py-2">Database connection failed.</div>
    <?php endif; ?>

    <?php if ($stage === 'email'): ?>
      <form method="post">
        <input type="hidden" name="action" value="submit_email">
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required>
        </div>
        <button type="submit" class="btn-main">Check Email</button>
      </form>
    <?php elseif ($stage === 'captcha'): ?>
      <form method="post">
        <input type="hidden" name="action" value="verify_captcha">
        <div class="mb-2">Email: <strong><?= $emailSafe ?></strong></div>
        <div class="mb-3">
          <label class="form-label">Enter the captcha shown below</label>
          <div class="mb-2 p-2 border rounded" style="background:#111; border-color: rgba(255,255,255,0.2) !important;">
            <img src="<?= htmlspecialchars($captchaUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Captcha" style="display:block; width:100%; max-height:90px; object-fit:contain;">
          </div>
          <input type="text" name="captcha_answer" class="form-control" placeholder="Example: A7C2K9" autocomplete="off" required>
        </div>
        <button type="submit" class="btn-main">Verify Captcha</button>
      </form>
      <form method="post">
        <input type="hidden" name="action" value="refresh_captcha">
        <button type="submit" class="btn-ghost">Refresh Captcha</button>
      </form>
      <form method="post">
        <input type="hidden" name="action" value="restart">
        <button type="submit" class="btn-ghost">Start Over</button>
      </form>
    <?php elseif ($stage === 'otp'): ?>
      <form method="post">
        <input type="hidden" name="action" value="verify_otp">
        <div class="mb-2">OTP sent to: <strong><?= $emailSafe ?></strong></div>
        <div class="mb-3">
          <label class="form-label">Enter OTP</label>
          <input type="text" name="otp" maxlength="6" class="form-control" placeholder="6-digit OTP" required>
        </div>
        <button type="submit" class="btn-main">Verify OTP</button>
      </form>
      <form method="post">
        <input type="hidden" name="action" value="resend_otp">
        <button type="submit" class="btn-ghost">Resend OTP</button>
      </form>
    <?php elseif ($stage === 'reset'): ?>
      <form method="post">
        <input type="hidden" name="action" value="reset_password">
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="new_password" minlength="8" class="form-control" required>
          <small class="text-secondary">Use at least 8 chars with letter, number, special character.</small>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm New Password</label>
          <input type="password" name="confirm_password" minlength="8" class="form-control" required>
        </div>
        <button type="submit" class="btn-main">Change Password</button>
      </form>
    <?php elseif ($stage === 'done'): ?>
      <div class="alert alert-success">Password updated successfully.</div>
      <div class="mb-3">Email: <strong><?= $emailSafe ?></strong></div>
      <a class="btn-main d-inline-block text-center" href="login.php">Back to Login</a>
    <?php endif; ?>

    <?php if ($stage !== 'done'): ?>
      <p class="mt-3 mb-0"><a class="link" href="login.php">Back to login</a></p>
    <?php endif; ?>
  </div>
</body>
</html>
