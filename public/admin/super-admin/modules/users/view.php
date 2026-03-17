<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';

rbac_guard('users.view');

if ($id <= 0) {
    http_response_code(404);
    exit('User not found');
}

$stmt = $pdo->prepare("
  SELECT u.*, r.name AS role_name
    FROM users u
    LEFT JOIN user_roles ur ON ur.user_id = u.id
    LEFT JOIN roles r ON r.id = ur.role_id
   WHERE u.id = ?
");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    exit('User not found');
}

$userPerms = rbac_load_user_permissions($pdo, (int)$id);

require_once SA_INCLUDES_PATH . '/header.php';

function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

$photo = $user['photo']
    ? ASSETS_URL . '/media/users/' . e($user['photo'])
    : ASSETS_URL . '/media/avatars/blank.png';
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
  <div id="kt_content_container" class="container-xxl">

    <div class="card shadow-sm border-0">
      <div class="card-body p-10">

        <div class="d-flex flex-column flex-md-row align-items-center gap-10">

          <div class="symbol symbol-150px symbol-lg-160px">
            <img src="<?= $photo ?>" class="rounded border shadow-sm" alt="">
          </div>

          <div class="flex-grow-1">
            <h1 class="fw-bold fs-1 mb-2"><?= e($user['fullname']) ?></h1>

            <div class="text-muted fs-5 mb-3"><?= e($user['email']) ?></div>

            <span class="badge badge-light-primary fs-6 px-4 py-3">
              <?= e($user['role_name'] ?? '—') ?>
            </span>
          </div>

        </div>

        <div class="separator my-10"></div>

        <div class="row g-7 mb-10">

          <div class="col-md-6">
            <div class="fs-6 text-muted mb-1">Username</div>
            <div class="fw-semibold fs-5"><?= e($user['username']) ?></div>
          </div>

          <div class="col-md-6">
            <div class="fs-6 text-muted mb-1">Created At</div>
            <div class="fw-semibold fs-5"><?= e($user['created_at']) ?></div>
          </div>

          <div class="col-md-6">
            <div class="fs-6 text-muted mb-1">Phone</div>
            <div class="fw-semibold fs-5"><?= e($user['phone'] ?: '—') ?></div>
          </div>

          <div class="col-md-6">
            <div class="fs-6 text-muted mb-1">Address</div>
            <div class="fw-semibold fs-5"><?= nl2br(e($user['address'] ?: '—')) ?></div>
          </div>

        </div>

        <div class="separator my-10"></div>

        <h3 class="fw-bold fs-3 mb-5">Permissions</h3>

        <div class="d-flex flex-wrap gap-2 mb-10">
          <?php if (empty($userPerms)): ?>
            <span class="badge badge-light-warning px-4 py-3">No Permissions</span>
          <?php else: ?>
            <?php foreach ($userPerms as $perm): ?>
              <span class="badge badge-light-info px-4 py-3 fs-7">
                <?= e($perm) ?>
              </span>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="d-flex justify-content-end">
          <?php if (rbac_guard_silent('users.edit')): ?>
            <a href="/super-admin/users/edit/<?= $id ?>" class="btn btn-light-warning me-3">
              Edit User
            </a>
          <?php endif; ?>
          <?php if (rbac_guard_silent('users.delete')): ?>
            <form method="post" action="/super-admin/users/delete/<?= $id ?>"
                  onsubmit="return confirm('Delete this user?');">
              <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
              <button type="submit" class="btn btn-light-danger">Delete</button>
            </form>
          <?php endif; ?>
        </div>

      </div>
    </div>

  </div>
</div>

<?php require_once SA_INCLUDES_PATH . '/footer.php'; ?>