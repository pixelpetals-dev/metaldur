<?php
use Casbin\Enforcer;
use Casbin\Model\Model;

function getCasbinEnforcer(PDO $pdo): Enforcer
{
    static $e = null;
    if ($e !== null) return $e;

    $model = Model::newModelFromFile(SRC_PATH . '/casbin-model.conf');
    $e = new Enforcer($model);
    $stmt = $pdo->query("
        SELECT r.name AS role, p.name AS perm
        FROM role_permissions rp
        JOIN roles r ON r.id = rp.role_id
        JOIN permissions p ON p.id = rp.permission_id
    ");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        [$obj, $act] = explode('.', $row['perm'], 2);
        $e->addPolicy($row['role'], $obj, $act, 'allow');
    }
    $stmt = $pdo->query("
        SELECT u.id AS uid, p.name AS perm, up.is_allowed
        FROM user_permissions up
        JOIN users u ON u.id = up.user_id
        JOIN permissions p ON p.id = up.permission_id
    ");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        [$obj, $act] = explode('.', $row['perm'], 2);
        $effect = ($row['is_allowed'] == 1) ? 'allow' : 'deny';
        $e->addPolicy((string)$row['uid'], $obj, $act, $effect);
    }
    $stmt = $pdo->query("
        SELECT u.id AS uid, r.name AS role
        FROM user_roles ur
        JOIN users u ON u.id = ur.user_id
        JOIN roles r ON r.id = ur.role_id
    ");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $e->addGroupingPolicy((string)$row['uid'], $row['role']);
    }
    return $e;
}
