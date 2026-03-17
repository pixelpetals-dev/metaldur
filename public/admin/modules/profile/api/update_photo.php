<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../../session.php';

header('Content-Type: application/json');

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized.']));
}

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['photo'];
    
    if ($file['size'] > 2 * 1024 * 1024) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'File is too large. Max 2MB.']));
    }
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF allowed.']));
    }

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/media/users/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $newFilename = 'user_' . $userId . '.' . $extension;
    $destination = $uploadDir . $newFilename;

    try {
        $oldPhotos = glob($uploadDir . 'user_' . $userId . '.*');
        foreach ($oldPhotos as $oldPhoto) {
            if (is_file($oldPhoto)) {
                unlink($oldPhoto);
            }
        }

        // Move the newly uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Update database with just the new photo filename
            $stmt = $pdo->prepare("UPDATE users SET photo = ? WHERE id = ?");
            $stmt->execute([$newFilename, $userId]);
            
            // ** FIX: Return a correct, web-accessible URL path **
            $webPath = '/assets/media/users/' . $newFilename;
            echo json_encode(['success' => true, 'message' => 'Profile photo updated.', 'filepath' => $webPath]);

        } else {
            throw new Exception('Failed to move uploaded file. Check directory permissions.');
        }
    } catch (Throwable $e) {
        error_log("Photo upload failed for user {$userId}: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No file uploaded or an error occurred.']);
}