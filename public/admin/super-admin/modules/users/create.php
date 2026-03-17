<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';

rbac_guard('users.create');

$title = 'Create New User';

$error = '';
$successUser = '';

$roles = $pdo->query('SELECT id, name FROM roles ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);

$username = '';
$email = '';
$password = '';
$fullname = '';
$phone = '';
$address = '';
$roleId = 0;
$filename = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullname = trim($_POST['fullname'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $roleId   = (int)($_POST['role_id'] ?? 0);

    if ($username === '' || $email === '' || $password === '' || $fullname === '') {
        $error = 'All required fields must be filled.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email.';
    } elseif ($roleId <= 0) {
        $error = 'Please select a role.';
    } else {
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['photo']['size'] > 250 * 1024) {
                $error = 'Photo must be smaller than 250 KB.';
            } else {
                $info = getimagesize($_FILES['photo']['tmp_name']);
                if ($info === false) {
                    $error = 'Uploaded file is not a valid image.';
                } else {
                    $ext      = image_type_to_extension($info[2], false);
                    $safeBase = preg_replace('/[^a-z0-9\-_]/i', '',
                                pathinfo($_FILES['photo']['name'], PATHINFO_FILENAME));
                    $filename = $safeBase . '-' . uniqid() . '.' . $ext;
                    $uploadDir = __DIR__ . '/../../../assets/media/users/';

                    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename)) {
                        $error = 'Failed to move uploaded photo.';
                    }
                }
            }
        }
    }

    if ($error === '') {
        try {
            $stmt = $pdo->prepare('SELECT username, email FROM users WHERE username = :u OR email = :e LIMIT 1');
            $stmt->execute(['u' => $username, 'e' => $email]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                if ($existing['username'] === $username) {
                    $error = 'This username is already taken.';
                } elseif ($existing['email'] === $email) {
                    $error = 'This email is already registered.';
                }
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare(
                    'INSERT INTO users
                        (username, email, password_hash, fullname, phone, address, photo)
                     VALUES
                        (:u, :e, :h, :fn, :ph, :ad, :pf)'
                );
                $stmt->execute([
                    'u'  => $username,
                    'e'  => $email,
                    'h'  => $hash,
                    'fn' => $fullname,
                    'ph' => $phone,
                    'ad' => $address,
                    'pf' => $filename,
                ]);

                $newUserId = (int)$pdo->lastInsertId();
                $stmt = $pdo->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (:uid, :rid)');
                $stmt->execute(['uid' => $newUserId, 'rid' => $roleId]);

                $successUser = $username;
                $username = $email = $password = $fullname = $phone = $address = '';
                $roleId = 0;
                $filename = null;
            }

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                if (str_contains($e->getMessage(), 'username')) {
                    $error = 'This username is already taken.';
                } elseif (str_contains($e->getMessage(), 'email')) {
                    $error = 'This email is already registered.';
                } else {
                    $error = 'A user with this information already exists.';
                }
            } else {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

require SA_INCLUDES_PATH . '/header.php';
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
    <div id="kt_content_container" class="container-xxl">
  <div class="card card-flush w-100">
    <div class="card-header align-items-center py-5 gap-2">
      <h3 class="card-title"><span class="fw-bold fs-3">Create New User</span></h3>
    </div>

    <form method="post" enctype="multipart/form-data" class="form">
      <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">

      <div class="card-body pt-0">

        <?php if ($successUser): ?>
        <div class="alert alert-success">
          User “<?= htmlspecialchars($successUser) ?>” created successfully.
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-6 fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Username</label>
            <input type="text" name="username"
                   class="form-control form-control-solid"
                   value="<?= htmlspecialchars($username) ?>" required>
          </div>

          <div class="col-md-6 fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Email</label>
            <input type="email" name="email"
                   class="form-control form-control-solid"
                   value="<?= htmlspecialchars($email) ?>" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Password</label>
            <input type="password" name="password"
                   class="form-control form-control-solid" required>
          </div>
        </div>

        <div class="fv-row mb-7">
          <label class="fs-6 fw-semibold mb-2">Full Name</label>
          <input type="text" name="fullname"
                 class="form-control form-control-solid"
                 value="<?= htmlspecialchars($fullname) ?>" required>
        </div>

        <div class="row">
          <div class="col-md-6 fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Phone</label>
            <input type="text" name="phone"
                   class="form-control form-control-solid"
                   value="<?= htmlspecialchars($phone) ?>">
          </div>

          <div class="col-md-6 fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Address</label>
            <input type="text" name="address"
                   class="form-control form-control-solid"
                   value="<?= htmlspecialchars($address) ?>">
          </div>
        </div>

        <div class="fv-row mb-7">
          <label class="fs-6 fw-semibold mb-2">Photo (optional, ≤ 250 KB)</label>
          <input type="file" name="photo" accept="image/*" class="form-control form-control-solid">
        </div>

        <div class="fv-row mb-7">
          <label class="fs-6 fw-semibold mb-2">Role</label>
          <select name="role_id" class="form-select form-select-solid" required>
            <option value="">— Select a Role —</option>
            <?php foreach ($roles as $r): ?>
            <option value="<?= $r['id'] ?>" <?= $r['id'] === $roleId ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

      </div>

      <div class="card-footer d-flex justify-content-end py-6 px-9">
        <a href="/super-admin/users" class="btn btn-light me-3">Cancel</a>
        <button type="submit" class="btn btn-primary">Save User</button>
      </div>

    </form>
  </div>
  </div>
</div>

<?php require SA_INCLUDES_PATH . '/footer.php'; ?>
