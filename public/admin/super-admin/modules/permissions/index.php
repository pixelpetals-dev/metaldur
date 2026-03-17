<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';
require_once SRC_PATH . '/permissions_manager.php';

rbac_guard('permissions.view');

$action = $_POST['action'] ?? null;
$type    = $_GET['type'] ?? '';
$id      = (int)($_GET['id'] ?? 0);

$found          = [];
$flatFound      = [];
$stale          = [];
$scanSummary    = [];
$syncSummary    = [];
$manualError    = '';
$manualSuccess  = '';

$scanDirs = [
    'erp' => ERP_MODULES_DIR,
    'sa'  => SA_MODULES_DIR
];

if ($action === 'scan') {
    $dbPerms = $pdo->query("SELECT name FROM permissions")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($scanDirs as $panel => $dir) {
        $found[$panel] = scanModulePermissions($dir, $panel);
    }
    $flatFound = flattenFoundPermNames($found);
    $found = array_map(function($modules) use ($dbPerms) {
        foreach ($modules as $m => $perms) {
            foreach ($perms as $perm => $_) {
                if (in_array($perm, $dbPerms, true)) {
                    unset($modules[$m][$perm]);
                }
            }
            if (empty($modules[$m])) unset($modules[$m]);
        }
        return $modules;
    }, $found);
    $stale = findStalePermissions($pdo, array_keys($flatFound));
}

if ($action === 'sync') {
    foreach ($scanDirs as $panel => $dir) {
        $found[$panel] = scanModulePermissions($dir, $panel);
    }
    $syncSummary = syncPermissions($pdo, $found);
    $flatFound = flattenFoundPermNames($found);
    $stale = findStalePermissions($pdo, array_keys($flatFound));
}

if ($action === 'delete_stale') {
    if (!csrf_guard_request($_POST['_csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid CSRF token');
    }

    $ids = $_POST['stale_ids'] ?? [];
    foreach ($ids as $sid) {
        $pdo->prepare('DELETE FROM permissions WHERE id = ? AND is_manual = 0')
            ->execute([(int)$sid]);
    }
    header('Location: /admin/super-admin/permissions');
    exit;
}

if ($action === 'create_manual') {
    if (!csrf_guard_request($_POST['_csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid CSRF token');
    }

    $name  = trim($_POST['name'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $panel = ($_POST['panel'] ?? 'erp') === 'sa' ? 'sa' : 'erp';

    if ($name === '') {
        $manualError = 'Permission name required.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO permissions (name, description, panel, is_manual)
                 VALUES (:n, :d, :p, 1)'
            );
            $stmt->execute(['n' => $name, 'd' => $desc, 'p' => $panel]);
            $manualSuccess = $name;
        } catch (PDOException $e) {
            $manualError = 'Failed: ' . $e->getMessage();
        }
    }
}

// --- ADDED THIS BLOCK TO HANDLE SAVING PERMISSIONS ---
if ($action === 'save_assignments') {
    if (!csrf_guard_request($_POST['_csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid CSRF token');
    }

    $type = $_POST['type'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    // Get an array of all permission IDs that were checked
    $pids = $_POST['permissions'] ?? []; 

    if (($type === 'role' || $type === 'user') && $id > 0) {
        $pdo->beginTransaction();
        try {
            if ($type === 'role') {
                // For roles: Delete all existing, then insert all checked.
                $pdo->prepare('DELETE FROM role_permissions WHERE role_id = ?')->execute([$id]);

                $stmt = $pdo->prepare('INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)');
                foreach ($pids as $pid) {
                    $stmt->execute([$id, (int)$pid]);
                }

            } elseif ($type === 'user') {
                // For users: This logic saves explicit "allows".
                // It deletes all previous overrides and saves the new "allow" set.
                
                $pdo->prepare('DELETE FROM user_permissions WHERE user_id = ?')->execute([$id]);

                $stmt = $pdo->prepare('INSERT INTO user_permissions (user_id, permission_id, is_allowed) VALUES (?, ?, 1)');
                foreach ($pids as $pid) {
                    $stmt->execute([$id, (int)$pid]);
                }
            }
            
            $pdo->commit();

            // Clear session permissions as they are now stale
            unset($_SESSION['permissions'], $_SESSION['panel_permissions']);

        } catch (Exception $e) {
            $pdo->rollBack();
            // Set an error message to show the user
            $manualError = "Error saving permissions: " . $e->getMessage();
        }
    }
    
    // Redirect back to the same page to show the new state
    // and prevent form re-submission on refresh.
    header("Location: /admin/super-admin/permissions?type=$type&id=$id");
    exit;
}
// --- END OF ADDED BLOCK ---

$users = $pdo->query('SELECT id, username FROM users ORDER BY username')
            ->fetchAll(PDO::FETCH_ASSOC);

$roles = $pdo->query('SELECT id, name FROM roles ORDER BY name')
            ->fetchAll(PDO::FETCH_ASSOC);

$allPerms = $pdo->query(
    'SELECT id, name, description, panel
       FROM permissions
      ORDER BY panel, name'
)->fetchAll(PDO::FETCH_ASSOC);

$userRolePerms = [];
$userOverrides = [];
$checked        = [];
$saModules      = [];
$erpModules     = [];

if (($type === 'user' || $type === 'role') && $id > 0) {

    if ($type === 'user') {
        // role defaults
        $stmt = $pdo->prepare(
            'SELECT rp.permission_id
               FROM role_permissions rp
               JOIN user_roles ur ON ur.role_id = rp.role_id
              WHERE ur.user_id = ?'
        );
        $stmt->execute([$id]);
        $userRolePerms = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));

        // overrides
        $stmt = $pdo->prepare(
            'SELECT permission_id, is_allowed
               FROM user_permissions
              WHERE user_id = ?'
        );
        $stmt->execute([$id]);
        $userOverrides = array_map('intval', $stmt->fetchAll(PDO::FETCH_KEY_PAIR));

    } elseif ($type === 'role') {

        $stmt = $pdo->prepare(
            'SELECT permission_id
               FROM role_permissions
              WHERE role_id = ?'
        );
        $stmt->execute([$id]);
        $userRolePerms = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    foreach ($allPerms as $p) {
        $pid = (int)$p['id'];

        if ($type === 'user') {
            $default = in_array($pid, $userRolePerms, true);
            $checked[$pid] = array_key_exists($pid, $userOverrides)
                ? (bool)$userOverrides[$pid]
                : $default;
        } else {
            $checked[$pid] = in_array($pid, $userRolePerms, true);
        }

        $mod = explode('.', $p['name'], 2)[0];

        if ($p['panel'] === 'sa') {
            $saModules[$mod][] = $p;
        } else {
            $erpModules[$mod][] = $p;
        }
    }
}

require_once SA_INCLUDES_PATH . '/header.php';
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
<div id="kt_content_container" class="container-xxl">

<div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="fs-2 fw-bold">Permission Manager</h1><br>
</div>
<div class="d-flex justify-content-between align-items-center mb-7">
    <span class="label label-warning fs-4 mr-2">Please do not add or delete permissions if you're unsure of what you're doing. You can safely continue to assign permissions to roles and users.</span>
</div>

<div class="card shadow-sm mb-10">
    <div class="card-body d-flex flex-wrap gap-5 align-items-center">

        <form method="post" class="me-3">
            <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="scan">
            <button class="btn btn-primary btn-lg px-7">
                <i class="ki-solid ki-search-list fs-2 me-2"></i> Scan Permissions
            </button>
        </form>

        <form method="post">
            <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="sync">
            <button class="btn btn-light-primary btn-lg px-7">
                <i class="ki-solid ki-scroll fs-2 me-2"></i> Sync to Database
            </button>
        </form>
    </div>
</div>

<?php if (!empty($found) && empty($syncSummary)): ?>
<div class="card shadow-sm mb-10">
    <div class="card-header">
        <h3 class="card-title fs-3 fw-bold">Scan Results</h3>
    </div>

    <div class="card-body">
        <?php foreach ($found as $panel => $modules): ?>
        <div class="mb-10 p-5 border rounded bg-light">
            <h4 class="fw-bold fs-4 mb-4 text-primary"><?= strtoupper($panel) ?> Panel</h4>

            <?php if (empty($modules)): ?>
                <p class="text-muted">No new permissions found in this panel.</p>
            <?php else: ?>
                <?php foreach ($modules as $module => $perms): ?>
                <div class="mb-7">
                    <h5 class="fw-bold mb-3"><?= htmlspecialchars(prettyModuleName($module)) ?></h5>
                    <div class="table-responsive">
                        <table class="table table-row-bordered">
                            <thead>
                                <tr class="fw-semibold text-muted">
                                    <th>Permission</th>
                                    <th class="text-end">Found In</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($perms as $permName => $occ): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($permName) ?></td>
                                    <td class="text-end">
                                        <span class="badge badge-light-primary"><?= count($occ) ?> uses</span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>


<?php if (!empty($syncSummary)): ?>
<div class="card shadow-sm mb-10">
    <div class="card-header">
        <h3 class="card-title fs-3 fw-bold">Sync Summary</h3>
    </div>

    <div class="card-body">

        <p class="fs-5"><strong>Inserted:</strong> <?= count($syncSummary['inserted']) ?></p>
        <p class="fs-5"><strong>Skipped:</strong> <?= count($syncSummary['skipped']) ?></p>

        <?php if (!empty($syncSummary['errors'])): ?>
            <div class="alert alert-danger mt-5">
                <h5 class="fw-bold">Errors:</h5>
                <ul>
                <?php foreach ($syncSummary['errors'] as $err): ?>
                    <li><?= htmlspecialchars($err['perm'] . ' - ' . $err['error']) ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    </div>
</div>
<?php endif; ?>

<?php if (!empty($stale)): ?>
<div class="card shadow-sm mb-10 border border-danger">
    <div class="card-header">
        <h3 class="card-title fs-3 fw-bold text-danger">
            <i class="ki-solid ki-warning fs-2 me-2"></i>
            Stale Permissions Found
        </h3>
    </div>

    <form method="post">
        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
        <input type="hidden" name="action" value="delete_stale">

        <div class="card-body">
            <p class="text-muted mb-4">These permissions exist in DB but are not used in code.</p>

            <div class="table-responsive">
                <table class="table table-row-bordered">
                    <thead>
                        <tr class="fw-semibold text-muted">
                            <th>Permission</th>
                            <th>Delete?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stale as $sp): ?>
                        <tr>
                            <td><?= htmlspecialchars($sp['name']) ?></td>
                            <td width="80">
                                <input type="checkbox" name="stale_ids[]" value="<?= $sp['id'] ?>">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-danger btn-lg px-7">
                <i class="ki-solid ki-trash fs-2 me-2"></i>
                Delete Selected
            </button>
        </div>
    </form>
</div>
<?php endif; ?>

<?php if ($manualSuccess): ?>
<div class="alert alert-success d-flex align-items-center mb-7">
    <i class="ki-solid ki-check-circle fs-2 me-2"></i>
    Manual permission “<?= htmlspecialchars($manualSuccess) ?>” added successfully.
</div>
<?php endif; ?>

<?php if ($manualError): ?>
<div class="alert alert-danger d-flex align-items-center mb-7">
    <i class="ki-solid ki-cross-circle fs-2 me-2"></i>
    <?= htmlspecialchars($manualError) ?>
</div>
<?php endif; ?>

<div class="card shadow-sm mb-10">
    <div class="card-header">
        <h3 class="card-title fs-3 fw-bold">
            <i class="ki-solid ki-plus fs-2 me-2"></i>
            Create Manual Permission
        </h3>
    </div>

    <form method="post">
        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
        <input type="hidden" name="action" value="create_manual">

        <div class="card-body">
            <div class="row g-5">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Permission Name</label>
                    <input name="name" class="form-control form-control-solid" placeholder="ex: quotations.print" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Panel</label>
                    <select name="panel" class="form-select form-select-solid">
                        <option value="erp">ERP</option>
                        <option value="sa">Super Admin</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Description</label>
                    <input name="description" class="form-control form-control-solid" placeholder="Optional description">
                </div>

            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-primary btn-lg px-7">
                <i class="ki-solid ki-check fs-2 me-2"></i> Create Permission
            </button>
        </div>
    </form>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title fs-3 fw-bold">
            Assign Permissions
        </h3>
    </div>

    <div class="card-body">

        <div class="d-flex flex-wrap gap-7 mb-10">
            <div>
                <label class="form-label fw-semibold">User</label>
                <select id="selUser" class="form-select form-select-solid" data-type="user">
                    <option value="">— none —</option>
                    <?php foreach ($users as $u): $uid=(int)$u['id']; ?>
                        <option value="<?= $uid ?>" <?= $type==='user' && $id===$uid ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label fw-semibold">Role</label>
                <select id="selRole" class="form-select form-select-solid" data-type="role">
                    <option value="">— none —</option>
                    <?php foreach ($roles as $r): $rid=(int)$r['id']; ?>
                        <option value="<?= $rid ?>" <?= $type==='role' && $id===$rid ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>


        <?php if (($type==='user' || $type==='role') && $id>0): ?>
        <form method="post">
            <input type="hidden" name="action" value="save_assignments">
            
            <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
            <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
            <input type="hidden" name="id" value="<?= $id ?>">

            <?php if (!empty($saModules)): ?>
            <h3 class="fw-bold fs-3 mb-5 text-primary">Super Admin Permissions</h3>
            <div class="row mb-10">
                <?php foreach ($saModules as $mod => $perms): ?>
                <div class="col-md-4 mb-6">
                    <div class="card h-100 shadow-sm border">
                        <div class="card-header bg-light">
                            <h4 class="card-title fw-bold text-capitalize">
                                <?= htmlspecialchars(prettyModuleName($mod)) ?>
                            </h4>
                        </div>
                        <div class="card-body">
                            <?php foreach ($perms as $p): $pid=(int)$p['id']; ?>
                            <label class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox"
                                       name="permissions[]"
                                       value="<?= $pid ?>"
                                       <?= !empty($checked[$pid]) ? 'checked' : '' ?> />
                                <span class="form-check-label">
                                    <?= htmlspecialchars($p['description'] ?: $p['name']) ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>


            <?php if (!empty($erpModules)): ?>
            <h3 class="fw-bold fs-3 mb-5 text-primary">ERP Permissions</h3>
            <div class="row">
                <?php foreach ($erpModules as $mod => $perms): ?>
                <div class="col-md-4 mb-6">
                    <div class="card h-100 shadow-sm border">
                        <div class="card-header bg-light">
                            <h4 class="card-title fw-bold text-capitalize">
                                <?= htmlspecialchars(prettyModuleName($mod)) ?>
                            </h4>
                        </div>
                        <div class="card-body">
                            <?php foreach ($perms as $p): $pid=(int)$p['id']; ?>
                            <label class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox"
                                       name="permissions[]"
                                       value="<?= $pid ?>"
                                       <?= !empty($checked[$pid]) ? 'checked' : '' ?> />
                                <span class="form-check-label">
                                    <?= htmlspecialchars($p['description'] ?: $p['name']) ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>


            <div class="d-flex justify-content-end mt-10">
                <button class="btn btn-primary btn-lg px-7">
                    <i class="ki-solid ki-check fs-2 me-2"></i>
                    Save Permissions
                </button>
            </div>
        </form>
        <?php endif; ?>

    </div>
</div>

</div>
</div>


<?php require_once SA_INCLUDES_PATH . '/footer.php'; ?>

<script>
function onSelect(el){
    const t = el.dataset.type;
    const v = el.value;
    const u = document.getElementById('selUser');
    const r = document.getElementById('selRole');

    if(t === 'user'){ u.value = v; r.value = ''; }
    if(t === 'role'){ r.value = v; u.value = ''; }

    window.location.search = v ? `?type=${t}&id=${v}` : '';
}

document.getElementById('selUser').addEventListener('change', e => onSelect(e.target));
document.getElementById('selRole').addEventListener('change', e => onSelect(e.target));
</script>