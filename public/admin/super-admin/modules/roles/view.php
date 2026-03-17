<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';

rbac_guard('roles.view');

if ($id <= 0) {
    http_response_code(404);
    exit('Role not found');
}

$stmt = $pdo->prepare("
    SELECT id, name
      FROM roles
     WHERE id = ?
");
$stmt->execute([$id]);
$role = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$role) {
    http_response_code(404);
    exit('Role not found');
}

$usersStmt = $pdo->prepare("
    SELECT u.id, u.fullname, u.email, u.photo
      FROM users u
      JOIN user_roles ur ON ur.user_id = u.id
     WHERE ur.role_id = ?
     ORDER BY u.fullname
");
$usersStmt->execute([$id]);
$usersWithRole = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

$permStmt = $pdo->prepare("
    SELECT p.name
      FROM role_permissions rp
      JOIN permissions p ON p.id = rp.permission_id
     WHERE rp.role_id = ?
     ORDER BY p.name
");
$permStmt->execute([$id]);
$rolePermissions = $permStmt->fetchAll(PDO::FETCH_COLUMN);

require_once SA_INCLUDES_PATH . '/header.php';

function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
  <div id="kt_content_container" class="container-xxl">

    <div class="card card-flush shadow-sm mb-10">
      <div class="card-header py-5 d-flex justify-content-between align-items-center">
        <h3 class="card-title fw-bold fs-2 mb-0">Role Details</h3>

        <div>
          <?php if (rbac_guard_silent('roles.edit')): ?>
            <a href="/super-admin/roles/edit/<?= $id ?>" class="btn btn-light-warning me-3">Edit</a>
          <?php endif; ?>

          <?php if (rbac_guard_silent('roles.delete')): ?>
            <form method="post"
                  action="/super-admin/roles/delete/<?= $id ?>"
                  style="display:inline"
                  onsubmit="return confirm('Delete this role?');">
              <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
              <button type="submit" class="btn btn-light-danger">Delete</button>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <div class="card-body">

        <div class="mb-10">
          <div class="fs-6 text-muted mb-1">Role Name</div>
          <div class="fw-bold fs-4"><?= e($role['name']) ?></div>
        </div>

        <div class="separator my-10"></div>

        <h4 class="fw-bold fs-3 mb-6">Assigned Permissions</h4>

        <div class="d-flex flex-wrap gap-2 mb-10">
          <?php if (empty($rolePermissions)): ?>
            <span class="badge badge-light-warning">No Permissions Assigned</span>
          <?php else: ?>
            <?php foreach ($rolePermissions as $perm): ?>
              <span class="badge badge-light-info px-4 py-3 fs-7"><?= e($perm) ?></span>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="separator my-10"></div>

        <h4 class="fw-bold fs-3 mb-6">Users With This Role</h4>

        <?php if (empty($usersWithRole)): ?>
          <div class="text-muted">No users are assigned to this role.</div>
        <?php else: ?>
          <div class="row g-6">
            <?php foreach ($usersWithRole as $u): ?>
              <div class="col-md-4">
                <div class="card shadow-sm h-100">
                  <div class="card-body d-flex align-items-center gap-5">

                    <div class="symbol symbol-60px">
                      <img src="<?= $u['photo']
                          ? ASSETS_URL . '/media/users/' . e($u['photo'])
                          : ASSETS_URL . '/media/avatars/blank.png' ?>"
                           class="rounded border" alt="">
                    </div>

                    <div>
                      <div class="fw-bold fs-6"><?= e($u['fullname']) ?></div>
                      <div class="text-muted"><?= e($u['email']) ?></div>
                      <?php if (rbac_guard_silent('users.view')): ?>
                        <a href="/super-admin/users/view/<?= $u['id'] ?>"
                           class="btn btn-sm btn-light mt-2 px-4">View</a>
                      <?php endif; ?>
                    </div>

                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </div>

  </div>
</div>

<?php require_once SA_INCLUDES_PATH . '/footer.php'; ?>