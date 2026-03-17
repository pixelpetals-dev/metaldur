<?php
declare(strict_types=1);
function scanModulePermissions(?string $dir, string $panel = 'erp'): array {
    $results = [];
    if (!$dir || !is_dir($dir)) return $results;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if ($file->isDir()) continue;
        $name = $file->getFilename();
        if (!preg_match('/\.(php|inc|phtml|html|blade\.php)$/i', $name)) continue;
        $content = @file_get_contents($file->getPathname());
        if ($content === false) continue;
        preg_match_all("/rbac_guard(?:_silent)?\s*\(\s*['\"]([a-zA-Z0-9_-]+\.[a-zA-Z0-9_-]+)['\"]\s*\)/", $content, $matches);
        if (empty($matches[1])) continue;
        foreach ($matches[1] as $perm) {
            $perm = trim($perm);
            if ($perm === '') continue;
            [$module] = explode('.', $perm, 2);
            $results[$module][$perm][] = $file->getPathname();
        }
    }
    return $results;
}
function flattenFoundPermNames(array $found): array {
    $flat = [];
    foreach ($found as $panel => $modules) {
        foreach ($modules as $module => $perms) {
            foreach ($perms as $perm => $_occ) {
                $flat[$perm] = $panel;
            }
        }
    }
    return $flat;
}
function autoDescription(string $perm): string {
    $p = explode('.', $perm);
    $a = ucfirst(str_replace(['_','-'], ' ', $p[1] ?? ''));
    $m = ucfirst(str_replace(['_','-'], ' ', $p[0] ?? ''));
    return trim($a.' '.$m);
}
function syncPermissions(PDO $pdo, array $found): array {
    $summary = ['inserted'=>[],'skipped'=>[],'errors'=>[]];
    $flat = flattenFoundPermNames($found);
    if (empty($flat)) return $summary;
    $names = array_keys($flat);
    $placeholders = implode(',', array_fill(0, count($names), '?'));
    $stmt = $pdo->prepare("SELECT name FROM permissions WHERE name IN ($placeholders)");
    $stmt->execute($names);
    $existing = array_flip($stmt->fetchAll(PDO::FETCH_COLUMN));
    $ins = $pdo->prepare('INSERT INTO permissions (name, description, panel, is_manual) VALUES (:n,:d,:p,0)');
    foreach ($flat as $perm => $panel) {
        if (isset($existing[$perm])) { $summary['skipped'][] = $perm; continue; }
        try {
            $ins->execute(['n'=>$perm,'d'=>autoDescription($perm),'p'=>$panel]);
            $summary['inserted'][] = $perm;
        } catch (PDOException $e) {
            $summary['errors'][] = ['perm'=>$perm,'error'=>$e->getMessage()];
        }
    }
    return $summary;
}
function findStalePermissions(PDO $pdo, array $presentPermNames): array {
    if (empty($presentPermNames)) {
        $stmt = $pdo->prepare('SELECT id, name FROM permissions WHERE is_manual = 0');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    $placeholders = implode(',', array_fill(0, count($presentPermNames), '?'));
    $sql = "SELECT id, name FROM permissions WHERE is_manual = 0 AND name NOT IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($presentPermNames));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function prettyModuleName(string $mod): string {
    $mod = str_replace(['_','-'], ' ', $mod);
    $mod = preg_replace('/\s+/', ' ', trim($mod));
    return ucfirst($mod);
}
