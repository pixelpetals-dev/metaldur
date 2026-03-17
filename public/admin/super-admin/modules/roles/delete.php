<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';

rbac_guard('roles.delete');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

csrf_guard();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $pdo->prepare('DELETE FROM role_permissions WHERE role_id = ?')->execute([$id]);
    $pdo->prepare('DELETE FROM user_roles WHERE role_id = ?')->execute([$id]);
    $pdo->prepare('DELETE FROM roles WHERE id = ?')->execute([$id]);
}

header('Location: /super-admin/roles');
exit;
