<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../../session.php';

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized.']));
}

$currentPassword = $input['current_password'] ?? '';
$newPassword = $input['new_password'] ?? '';
$confirmPassword = $input['confirm_password'] ?? '';

if (empty($currentPassword) || empty($newPassword) || $newPassword !== $confirmPassword) {
    http_response_code(400);
    exit(json_encode(['success' => false, 'message' => 'Invalid input. Please check your passwords.']));
}

if (strlen($newPassword) < 8) {
    http_response_code(400);
    exit(json_encode(['success' => false, 'message' => 'New password must be at least 8 characters long.']));
}

try {
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'message' => 'Your current password is incorrect.']));
    }

    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $updateStmt->execute([$newHash, $userId]);

    echo json_encode(['success' => true, 'message' => 'Password changed successfully.']);

} catch (Throwable $e) {
    error_log("Password change failed for user {$userId}: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'A server error occurred.']);
}