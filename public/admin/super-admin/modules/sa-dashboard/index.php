<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';
rbac_guard('sa-dashboard.view');

// Fetch quick stats
$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalRoles = (int)$pdo->query("SELECT COUNT(*) FROM roles")->fetchColumn();
$totalPerms = (int)$pdo->query("SELECT COUNT(*) FROM permissions")->fetchColumn();

$activeAdmins = (int)$pdo->query("
    SELECT COUNT(*)
    FROM users u
    JOIN user_roles ur ON ur.user_id = u.id
    JOIN roles r ON r.id = ur.role_id
    WHERE r.name = 'Super Admin'
")->fetchColumn();

$recentUsers = $pdo->query("
    SELECT username, email, created_at
    FROM users
    ORDER BY created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$permStats = $pdo->query("
    SELECT panel, COUNT(*) AS cnt
    FROM permissions
    GROUP BY panel
")->fetchAll(PDO::FETCH_KEY_PAIR);

// System stats
$phpVersion = PHP_VERSION;
$memoryLimit = ini_get('memory_limit');
$maxExec = ini_get('max_execution_time');
$uploadMax = ini_get('upload_max_filesize');
$postMax = ini_get('post_max_size');
$stmt = $pdo->query("SELECT VERSION()");
$mysqlVersion = $stmt->fetchColumn() ?: 'Unknown';
$stmt = $pdo->query("
    SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS db_size_mb
    FROM information_schema.TABLES
    WHERE table_schema = DATABASE()
");
$dbSize = $stmt->fetchColumn() ?: 0;

function folderSizeMB(string $dir): float {
    if (!is_dir($dir)) return 0;
    $size = 0;
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $f) {
        if ($f->isFile()) $size += $f->getSize();
    }
    return round($size / 1024 / 1024, 2);
}

$uploadsDir = __DIR__ . '/../../../assets/media';
$uploadsSize = folderSizeMB($uploadsDir);

// Error log size (if exists)
$logFile = __DIR__ . '/../../../error_log';
$errorLogSize = file_exists($logFile)
    ? round(filesize($logFile) / 1024 / 1024, 2)
    : 0;


require_once SA_INCLUDES_PATH . '/header.php';
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
<div id="kt_content_container" class="container-xxl">

<!-- =======================
      PAGE HEADER
======================= -->
<div class="d-flex flex-column flex-xl-row justify-content-between align-items-start mb-10">
    <div>
        <h1 class="fs-2x fw-bold mb-2">Super Admin Dashboard</h1>
        <div class="text-muted fs-5">Welcome back, <?= htmlspecialchars($fullName ?? 'Admin'); ?> 👋</div>
    </div>
</div>


<!-- =======================
     TOP WIDGETS
======================= -->
<div class="row g-5 mb-10">

    <!-- Users -->
    <div class="col-md-3">
        <div class="card card-flush shadow-sm hover-elevate-up">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-45px me-5">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-solid ki-profile-user fs-2x text-primary"></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold"><?= $totalUsers ?></div>
                    <div class="text-muted">Total Users</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles -->
    <div class="col-md-3">
        <div class="card card-flush shadow-sm hover-elevate-up">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-45px me-5">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-solid ki-badge fs-2x text-success"></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold"><?= $totalRoles ?></div>
                    <div class="text-muted">Roles</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions -->
    <div class="col-md-3">
        <div class="card card-flush shadow-sm hover-elevate-up">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-45px me-5">
                    <span class="symbol-label bg-light-info">
                        <i class="ki-solid ki-lock fs-2x text-info"></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold"><?= $totalPerms ?></div>
                    <div class="text-muted">Permissions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Admins -->
    <div class="col-md-3">
        <div class="card card-flush shadow-sm hover-elevate-up">
            <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-45px me-5">
                    <span class="symbol-label bg-light-danger">
                        <i class="ki-solid ki-setting-3 fs-2x text-danger"></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold"><?= $activeAdmins ?></div>
                    <div class="text-muted">Super Admins</div>
                </div>
            </div>
        </div>
    </div>

</div>



<!-- =======================
     PERMISSION DISTRIBUTION
======================= -->
<div class="card shadow-sm mb-10">
    <div class="card-header">
        <h3 class="card-title fw-bold">Permission Distribution</h3>
    </div>
    <div class="card-body">

        <div class="row">

            <div class="col-md-6">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold">Super Admin Panel</span>
                    <span><?= $permStats['sa'] ?? 0 ?></span>
                </div>
                <div class="progress h-10px mb-5">
                    <div class="progress-bar bg-danger" style="width: <?= ($permStats['sa'] ?? 0) * 3 ?>%"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold">ERP Panel</span>
                    <span><?= $permStats['erp'] ?? 0 ?></span>
                </div>
                <div class="progress h-10px mb-5">
                    <div class="progress-bar bg-primary" style="width: <?= ($permStats['erp'] ?? 0) * 3 ?>%"></div>
                </div>
            </div>

        </div>

    </div>
</div>



<!-- =======================
     RECENT USERS + SYSTEM HEALTH
======================= -->
<div class="row g-5">

    <!-- RECENT USERS -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title fw-bold">Recent User Registrations</h3>
            </div>
            <div class="card-body">

                <?php foreach ($recentUsers as $u): ?>
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-45px me-5">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-solid ki-user fs-2 text-primary"></i>
                            </span>
                        </div>
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($u['username']) ?></div>
                            <div class="text-muted"><?= htmlspecialchars($u['email']) ?></div>
                            <div class="text-muted small">Joined: <?= $u['created_at'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($recentUsers)): ?>
                    <div class="text-muted">No recent users.</div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <div class="col-md-6">
    <!-- SYSTEM HEALTH -->
    <div class="card shadow-sm mb-10">
    <div class="card-header">
        <h3 class="card-title fs-3 fw-bold">
            <i class="ki-duotone ki-information fs-2 me-2"></i>
            System Health
        </h3>
    </div>

    <div class="card-body">

        <div class="row g-5">

            <!-- PHP VERSION -->
            <div class="col-md-4">
                <div class="border rounded p-5 bg-light">
                    <h4 class="fw-bold fs-5 mb-1">PHP Version</h4>
                    <div class="fs-3 text-primary fw-bold"><?= $phpVersion ?></div>
                </div>
            </div>

            <!-- MYSQL VERSION -->
            <div class="col-md-4">
                <div class="border rounded p-5 bg-light">
                    <h4 class="fw-bold fs-5 mb-1">MySQL Version</h4>
                    <div class="fs-3 text-success fw-bold"><?= $mysqlVersion ?></div>
                </div>
            </div>

            <!-- DATABASE SIZE -->
            <div class="col-md-4">
                <div class="border rounded p-5 bg-light">
                    <h4 class="fw-bold fs-5 mb-1">Database Size</h4>
                    <div class="fs-3 text-danger fw-bold"><?= $dbSize ?> MB</div>
                </div>
            </div>

        </div>

        <div class="separator my-6"></div>

        <div class="row g-5">

            <!-- MEMORY LIMIT -->
            <div class="col-md-3">
                <div class="border rounded p-5">
                    <h5 class="fw-semibold text-muted">Memory Limit</h5>
                    <div class="fs-4 fw-bold"><?= $memoryLimit ?></div>
                </div>
            </div>

            <!-- EXEC TIME -->
            <div class="col-md-3">
                <div class="border rounded p-5">
                    <h5 class="fw-semibold text-muted">Max Execution</h5>
                    <div class="fs-4 fw-bold"><?= $maxExec ?> sec</div>
                </div>
            </div>

            <!-- UPLOAD LIMIT -->
            <div class="col-md-3">
                <div class="border rounded p-5">
                    <h5 class="fw-semibold text-muted">Upload Limit</h5>
                    <div class="fs-4 fw-bold"><?= $uploadMax ?></div>
                </div>
            </div>

            <!-- POST LIMIT -->
            <div class="col-md-3">
                <div class="border rounded p-5">
                    <h5 class="fw-semibold text-muted">Post Limit</h5>
                    <div class="fs-4 fw-bold"><?= $postMax ?></div>
                </div>
            </div>

        </div>

        <div class="separator my-6"></div>

        <div class="row g-5">

            <!-- UPLOADS FOLDER SIZE -->
            <div class="col-md-6">
                <div class="border rounded p-5 bg-light-warning">
                    <h5 class="fw-semibold mb-1">Uploads Folder Size</h5>
                    <div class="fs-2 fw-bold text-warning"><?= $uploadsSize ?> MB</div>
                </div>
            </div>

            <!-- ERROR LOG SIZE -->
            <div class="col-md-6">
                <div class="border rounded p-5 bg-light-danger">
                    <h5 class="fw-semibold mb-1">Error Log Size</h5>
                    <div class="fs-2 fw-bold text-danger"><?= $errorLogSize ?> MB</div>
                </div>
            </div>

        </div>

    </div>
</div>

</div>
</div>

</div>
</div>

<?php require_once SA_INCLUDES_PATH . '/footer.php'; ?>