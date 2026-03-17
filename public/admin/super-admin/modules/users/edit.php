<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';

rbac_guard('users.edit');

if ($id <= 0) {
    http_response_code(404);
    exit('User not found');
}

$stmt = $pdo->prepare("
    SELECT u.*, ur.role_id
      FROM users u
      LEFT JOIN user_roles ur ON ur.user_id = u.id
     WHERE u.id = ?
");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    exit('User not found');
}

$roles = $pdo->query("SELECT id, name FROM roles ORDER BY name")
             ->fetchAll(PDO::FETCH_ASSOC);

$error     = '';
$username  = $user['username']   ?? '';
$email     = $user['email']      ?? '';
$fullname  = $user['fullname']   ?? '';
$phone     = $user['phone']      ?? '';
$address   = $user['address']    ?? '';
$roleId    = (int)($user['role_id'] ?? 0);
$photoFile = $user['photo']      ?? '';

function e(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_guard_request($_POST['_csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid CSRF');
    }

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $roleId   = (int)($_POST['role_id'] ?? 0);

    if ($username === '' || $email === '' || $fullname === '') {
        $error = 'Required fields cannot be empty.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif ($roleId <= 0) {
        $error = 'Please select a role.';
    } else {
        if (!empty($_FILES['photo']['tmp_name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['photo']['size'] > 250 * 1024) {
                $error = 'Photo exceeds 250 KB.';
            } else {
                $info = getimagesize($_FILES['photo']['tmp_name']);
                if (!$info) {
                    $error = 'Not a valid image.';
                } else {
                    $ext  = image_type_to_extension($info[2], false);
                    $base = preg_replace('/[^a-z0-9\-_]/i', '',
                            pathinfo($_FILES['photo']['name'], PATHINFO_FILENAME));
                    $new  = $base . '-' . uniqid() . '.' . $ext;
                    $dest = __DIR__ . '/../../../assets/media/users/' . $new;

                    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                        $error = 'Failed to upload photo.';
                    } else {
                        $photoFile = $new;
                    }
                }
            }
        }
    }

    if ($error === '') {

        $stmt = $pdo->prepare("
            SELECT id FROM users
            WHERE (username = :u OR email = :e) AND id <> :id
            LIMIT 1
        ");
        $stmt->execute(['u' => $username, 'e' => $email, 'id' => $id]);

        if ($stmt->fetch()) {
            $error = 'Username or email already belongs to another user.';
        } else {

            $sql = "
                UPDATE users SET
                  username = :u,
                  email = :e,
                  fullname = :fn,
                  phone = :ph,
                  address = :ad,
                  photo = :pf
                WHERE id = :id
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'u'  => $username,
                'e'  => $email,
                'fn' => $fullname,
                'ph' => $phone,
                'ad' => $address,
                'pf' => $photoFile,
                'id' => $id,
            ]);

            $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?")->execute([$id]);
            $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)")
                ->execute([$id, $roleId]);

            header('Location: /super-admin/users?updated=1');
            exit;
        }
    }
}

require SA_INCLUDES_PATH . '/header.php';
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
  <div id="kt_content_container" class="container-xxl">

    <div class="card card-flush">
      <div class="card-header py-5">
        <h3 class="card-title fw-bold fs-3">Edit User</h3>
      </div>

      <form method="post" enctype="multipart/form-data" class="form">
        <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">

        <div class="card-body">

          <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
          <?php endif; ?>

          <div class="row">
            <div class="col-md-6 fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Username</label>
              <input type="text" name="username"
                     class="form-control form-control-solid"
                     value="<?= e($username) ?>" required>
            </div>

            <div class="col-md-6 fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Email</label>
              <input type="email" name="email"
                     class="form-control form-control-solid"
                     value="<?= e($email) ?>" required>
            </div>
          </div>

          <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Full Name</label>
            <input type="text" name="fullname"
                   class="form-control form-control-solid"
                   value="<?= e($fullname) ?>" required>
          </div>

          <div class="row">
            <div class="col-md-6 fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Phone</label>
              <input type="text" name="phone"
                     class="form-control form-control-solid"
                     value="<?= e($phone) ?>">
            </div>

            <div class="col-md-6 fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Address</label>
              <input type="text" name="address"
                     class="form-control form-control-solid"
                     value="<?= e($address) ?>">
            </div>
          </div>

          <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Photo (≤250 KB)</label>
            <input type="file" name="photo" accept="image/*"
                   class="form-control form-control-solid">
            <?php if ($photoFile): ?>
              <img src="<?= ASSETS_URL ?>/media/users/<?= e($photoFile) ?>"
                   width="80" class="mt-3 rounded">
            <?php endif; ?>
          </div>

          <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Role</label>
            <select name="role_id" class="form-select form-select-solid" required>
              <option value="">— Select Role —</option>
              <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id'] ?>" <?= $r['id'] === $roleId ? 'selected' : '' ?>>
                  <?= e($r['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>

        <div class="card-footer d-flex justify-content-end py-6 px-9">
          <a href="/super-admin/users" class="btn btn-light me-3">Cancel</a>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>

      </form>
    </div>

  </div>
</div>

<?php require SA_INCLUDES_PATH . '/footer.php'; ?>
