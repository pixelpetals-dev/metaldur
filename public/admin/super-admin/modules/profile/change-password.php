<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';
rbac_guard('profile.manage');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /super-admin/profile?error=Invalid+request");
    exit;
}

if (!csrf_guard_request($_POST['_csrf'] ?? '')) {
    header("Location: /super-admin/profile?error=Invalid+CSRF+token");
    exit;
}

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) { header("Location: /super-admin/login"); exit; }

$current = $_POST['current_password'] ?? '';
$new     = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (strlen($new) < 6 || $new !== $confirm) {
    header("Location: /super-admin/profile?error=Password+validation+failed");
    exit;
}

// fetch user
$stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($current, $user['password_hash'])) {
    header("Location: /super-admin/profile?error=Current+password+incorrect");
    exit;
}

// update password
$newHash = password_hash($new, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
$stmt->execute([$newHash, $userId]);

// regenerate session to prevent fixation
session_regenerate_id(true);

header("Location: /super-admin/profile?success=Password+updated+successfully");
exit;
