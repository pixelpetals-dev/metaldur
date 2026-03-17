<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';

rbac_guard('users.delete');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

csrf_guard();
header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);

if ($id === 0 && isset($_SERVER['REQUEST_URI'])) {
    if (preg_match('~/delete/(\d+)~', $_SERVER['REQUEST_URI'], $m)) {
        $id = (int)$m[1];
    }
}

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}

$pdo->prepare('DELETE FROM user_permissions WHERE user_id = ?')->execute([$id]);
$pdo->prepare('DELETE FROM user_roles WHERE user_id = ?')->execute([$id]);
$pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);

echo json_encode(['success' => true]);
exit;
