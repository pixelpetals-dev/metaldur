<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../session.php';
rbac_guard('profile.manage');

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) { header("Location: /super-admin/login"); exit; }

$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) { exit("User not found."); }

require_once SA_INCLUDES_PATH . '/header.php';
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
<div id="kt_content_container" class="container-xxl">

<!-- TITLE -->
<h1 class="fs-2 fw-bold mb-7">My Profile</h1>

<!-- FLASH -->
<?php if ($success): ?>
<div class="alert alert-success d-flex align-items-center">
    <i class="ki-duotone ki-check fs-2 me-3"></i>
    <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger d-flex align-items-center">
    <i class="ki-duotone ki-cross fs-2 me-3"></i>
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<div class="row">
    <!-- LEFT: SUMMARY CARD -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">

                <div class="symbol symbol-120px mb-4">
                    <img id="profilePreview"
                         src="<?= $user['photo']
                                ? ASSETS_URL . '/media/users/' . htmlspecialchars($user['photo'])
                                : ASSETS_URL . '/media/avatars/blank.png' ?>"
                         class="rounded"
                         style="height:120px;width:120px;object-fit:cover;">
                </div>

                <h2 class="fw-bold fs-3"><?= htmlspecialchars($user['fullname']) ?></h2>
                <div class="text-muted mb-1"><?= htmlspecialchars($user['email']) ?></div>
                <div class="text-muted"><?= htmlspecialchars($user['phone'] ?? '—') ?></div>

                <hr class="my-5">

                <div class="text-muted small mb-2">Member Since</div>
                <div class="fw-semibold">
                    <?= date('d M Y', strtotime($user['created_at'])) ?>
                </div>

            </div>
        </div>
    </div>


    <div class="col-lg-8">

        <div class="card shadow-sm mb-10">
            <div class="card-header">
                <h3 class="card-title fw-bold fs-3">Update Profile</h3>
            </div>

            <form action="/super-admin/profile/update" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">

                <div class="card-body">

                    <div class="row g-5">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="fullname"
                                   class="form-control form-control-solid"
                                   value="<?= htmlspecialchars($user['fullname']) ?>"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email"
                                   class="form-control form-control-solid"
                                   value="<?= htmlspecialchars($user['email']) ?>"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone"
                                   class="form-control form-control-solid"
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Profile Photo</label>
                            <input type="file" name="photo" accept="image/*"
                                   class="form-control form-control-solid"
                                   onchange="previewPhoto(this)">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" rows="3"
                                      class="form-control form-control-solid"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>

                    </div>

                </div>

                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-primary px-7">Save Changes</button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title fw-bold fs-3">Change Password</h3>
            </div>

            <form action="/super-admin/profile/change-password" method="post">
                <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">

                <div class="card-body">

                    <div class="mb-5">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" name="current_password"
                               class="form-control form-control-solid" required>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" name="new_password"
                               class="form-control form-control-solid" minlength="6" required>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" name="confirm_password"
                               class="form-control form-control-solid" minlength="6" required>
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-warning px-7">Update Password</button>
                </div>
            </form>
        </div>

    </div>
</div>

</div>
</div>

<?php require_once SA_INCLUDES_PATH . '/footer.php'; ?>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>