<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';

rbac_guard('roles.view');

$stmt = $pdo->query("
  SELECT r.id, r.name, COUNT(ur.user_id) AS user_count
    FROM roles r
    LEFT JOIN user_roles ur ON ur.role_id = r.id
   GROUP BY r.id, r.name
   ORDER BY r.name
");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once SA_INCLUDES_PATH . '/header.php';

function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
  <div id="kt_content_container" class="container-xxl">

    <div class="d-flex justify-content-between align-items-center mb-7">
      <h1 class="fs-2 fw-bold">Roles</h1>

      <?php if (rbac_guard_silent('roles.create')): ?>
        <a href="/super-admin/roles/create" class="btn btn-primary px-7 py-3">
          Create Role
        </a>
      <?php endif; ?>
    </div>

    <div class="row g-6">
      <?php foreach ($roles as $r): ?>
        <div class="col-md-4">
          <div class="card card-flush shadow-sm h-100">

            <div class="card-header">
              <h3 class="card-title fw-bold fs-4 mb-0"><?= e($r['name']) ?></h3>
            </div>

            <div class="card-body">

              <div class="mb-4 fs-6 text-muted">
                <?= (int)$r['user_count'] ?> user<?= ((int)$r['user_count'] !== 1 ? 's' : '') ?>
              </div>

              <div class="d-flex flex-wrap gap-3">
                <?php if (rbac_guard_silent('roles.view')): ?>
                  <a href="/super-admin/roles/view/<?= $r['id'] ?>"
                     class="btn btn-light btn-sm px-4">View</a>
                <?php endif; ?>

                <?php if (rbac_guard_silent('roles.edit')): ?>
                  <a href="/super-admin/roles/edit/<?= $r['id'] ?>"
                     class="btn btn-primary btn-sm px-4">Edit</a>
                <?php endif; ?>

                <?php if (rbac_guard_silent('roles.delete')): ?>
                  <form method="post"
                        action="/super-admin/roles/delete/<?= $r['id'] ?>"
                        onsubmit="return confirm('Delete this role?');">
                    <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                    <button type="submit" class="btn btn-danger btn-sm px-4">
                      Delete
                    </button>
                  </form>
                <?php endif; ?>
              </div>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<?php require_once SA_INCLUDES_PATH . '/footer.php'; ?>