<?php
use Casbin\Enforcer;

function rbac_load_user_permissions(PDO $pdo, int $uid): array
{
    $e = getCasbinEnforcer($pdo);

    $stmt = $pdo->query("SELECT name FROM permissions");
    $all = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $final = [];
    foreach ($all as $perm) {
        if (!str_contains($perm, '.')) continue;
        [$obj, $act] = explode('.', $perm, 2);

        if ($e->enforce((string)$uid, $obj, $act)) {
            $final[] = $perm;
        }
    }
    return $final;
}

function rbac_bootstrap_user(PDO $pdo): void
{
    $uid = $_SESSION['user_id'] ?? 0;

    if (!$uid) {
        $_SESSION['permissions'] = [];
        return;
    }
    if (!isset($_SESSION['permissions']) || !is_array($_SESSION['permissions'])) {
        $_SESSION['permissions'] = rbac_load_user_permissions($pdo, $uid);
    }
}

function rbac_guard(string $permission): void
{
    if (!in_array($permission, $_SESSION['permissions'] ?? [], true)) {
        http_response_code(403);
        exit('Access denied');
    }
}

function rbac_guard_silent(string $permission): bool
{
    return in_array($permission, $_SESSION['permissions'] ?? [], true);
}

function erpHasPermission(string $permission, array $context = []): bool
{
    $uid = $_SESSION['user_id'] ?? 0;
    if (!$uid) return false;

    if (in_array($permission, $_SESSION['permissions'] ?? [], true)) {
        return true;
    }

    if (!str_contains($permission, '.')) return false;
    [$obj, $act] = explode('.', $permission, 2);

    $e = getCasbinEnforcer($GLOBALS['pdo']);

    return $e->enforce(
        (string)$uid,
        $obj,
        $act,
        ...array_values($context)
    );
}

function checkPanelPermission(PDO $pdo, int $userId, string $permKey, string $panel): bool
{
    if ($userId !== ($_SESSION['user_id'] ?? 0)) return false;
    if (!isset($_SESSION['panel_permissions'][$panel])) {
        $_SESSION['panel_permissions'][$panel] = [];
    }
    return in_array($permKey, $_SESSION['panel_permissions'][$panel], true);
}
