<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';
rbac_guard('profile.manage');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /super-admin/profile?error=Invalid+request");
    exit;
}

if (!csrf_guard_request($_POST['_csrf'] ?? '')) {
    header("Location: /super-admin/profile?error=Invalid+CSRF+token");
    exit;
}

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    header("Location: /super-admin/login");
    exit;
}

// INPUTS
$fullname = trim($_POST['fullname'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$address  = trim($_POST['address'] ?? '');

if ($fullname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /super-admin/profile?error=Invalid+form+data");
    exit;
}

// fetch old
$stmt = $pdo->prepare("SELECT photo FROM users WHERE id = ?");
$stmt->execute([$userId]);
$old = $stmt->fetch(PDO::FETCH_ASSOC);
$oldPhoto = $old['photo'] ?? null;

// photo upload
$newPhoto = $oldPhoto;

if (!empty($_FILES['photo']['name'])) {
    $file = $_FILES['photo'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            header("Location: /super-admin/profile?error=Invalid+image+type");
            exit;
        }

        $newName = 'user_' . $userId . '_' . time() . '.' . $ext;
        $target  = ASSETS_PATH . '/media/users/' . $newName;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            $newPhoto = $newName;

            // delete old photo
            if ($oldPhoto && file_exists(ASSETS_PATH . '/media/users/' . $oldPhoto)) {
                @unlink(ASSETS_PATH . '/media/users/' . $oldPhoto);
            }
        }
    }
}

// UPDATE
$stmt = $pdo->prepare("
    UPDATE users 
       SET fullname = :fn,
           email = :em,
           phone = :ph,
           address = :ad,
           photo = :photoname
     WHERE id = :id
");

$stmt->execute([
    'fn'        => $fullname,
    'em'        => $email,
    'ph'        => $phone,
    'ad'        => $address,
    'photoname' => $newPhoto,
    'id'        => $userId
]);

header("Location: /super-admin/profile?success=Profile+updated+successfully");
exit;
