<?php
require_once __DIR__ . '/../bootstrap.php';

if (empty($_SESSION['user_id'])) {
    header('Location: /super-admin/login.php');
    exit;
}

if (!rbac_guard_silent('admin.access')) {
    http_response_code(403);
    exit('Access denied');
}

rbac_guard('admin.access');
