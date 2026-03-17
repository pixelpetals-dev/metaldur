<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';

rbac_guard('roles.create');

$name = '';
$error = '';
$createdName = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $error = 'Role name is required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO roles (name) VALUES (:n)");
        $stmt->execute(['n' => $name]);
        header("Location: /admin/super-admin/roles/create?success=" . urlencode($name));
        exit;
    }
}

if (!empty($_GET['success'])) {
    $createdName = $_GET['success'];
}

require_once SA_INCLUDES_PATH . '/header.php';

function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
  <div id="kt_content_container" class="container-xxl">

    <div class="card card-flush shadow-sm">
      <div class="card-header py-5">
        <h3 class="card-title fw-bold fs-3 mb-0">Create New Role</h3>
      </div>

      <form method="post" class="form">
        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">

        <div class="card-body">

          <?php if ($createdName): ?>
            <div class="alert alert-success mb-7">
              Role “<?= e($createdName) ?>” created successfully.
            </div>
          <?php endif; ?>

          <?php if ($error): ?>
            <div class="alert alert-danger mb-7"><?= e($error) ?></div>
          <?php endif; ?>

          <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Role Name</label>
            <input type="text"
                   name="name"
                   class="form-control form-control-solid"
                   placeholder="Enter role name"
                   value="<?= e($name) ?>"
                   required>
          </div>

        </div>

        <div class="card-footer d-flex justify-content-end py-6 px-9">
          <a href="/super-admin/roles" class="btn btn-light me-3">Cancel</a>
          <button type="submit" class="btn btn-primary">Save Role</button>
        </div>
      </form>

    </div>

  </div>
</div>

<?php require_once SA_INCLUDES_PATH . '/footer.php'; ?>