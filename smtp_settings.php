<?php
return [
    // SMTP provider settings.
    'SMTP_HOST' => 'smtp.gmail.com',
    'SMTP_PORT' => '587',
    'SMTP_SECURE' => 'tls', // none, tls, or ssl
    'SMTP_AUTH' => '1', // 1 = username/password auth, 0 = no auth

    // Sender mailbox login (static sender account, not user's entered email).
    'SMTP_USER' => 'lmacherla123@gmail.com',
    'SMTP_PASS' => 'PASTE_16_CHAR_GOOGLE_APP_PASSWORD_HERE',

    // Sender details (usually same as SMTP_USER for Gmail).
    'FORGOT_FROM_EMAIL' => 'lmacherla123@gmail.com',
    'FORGOT_FROM_NAME' => 'Aura.stream',
    'SITE_NAME' => 'Aura.stream',

    // Use 1 only for local troubleshooting TLS cert issues.
    'SMTP_ALLOW_INSECURE_TLS' => '0',
];
