<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../../session.php';

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['success' => false, 'message' => 'Invalid request method.']));
}

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized.']));
}

try {
    $stmt = $pdo->prepare(
        "UPDATE users SET 
            fullname = ?, phone = ?, 
            email = ?
         WHERE id = ?"
    );
    $success = $stmt->execute([
        $_POST['fullname'] ?? null,
        $_POST['phone'] ?? null,
        $_POST['email'] ?? null,
        $userId
    ]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Profile details updated successfully.']);
    } else {
        throw new Exception("Failed to update profile in database.");
    }

} catch (Throwable $e) {
    error_log("Profile update failed for user {$userId}: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'A server error occurred. Please try again.']);
}