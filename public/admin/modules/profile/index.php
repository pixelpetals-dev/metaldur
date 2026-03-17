<?php
declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    header("Location: /login.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT u.*, r.name AS role_name
     FROM users u
     LEFT JOIN user_roles ur ON u.id = ur.user_id
     LEFT JOIN roles r ON ur.role_id = r.id
     WHERE u.id = ?"
);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit('User not found.');
}

$avatar = !empty($user['photo'])
    ? '/admin/assets/media/users/' . htmlspecialchars((string)$user['photo'], ENT_QUOTES, 'UTF-8')
    : '/admin/assets/media/logos/favicon.ico';

$title = 'My Profile';
require_once INCLUDES_PATH . '/header.php';
?>
<div class="row g-5 gx-xl-10 mt-5">
<div class="container-xxl" id="kt_content_container">
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <img id="profile_avatar_img" src="<?= $avatar ?>" alt="image" />
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                    <?= htmlspecialchars((string)$user['fullname'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                                <span class="badge badge-light-success ms-2 fs-8 fw-bold">
                                    <?= htmlspecialchars(ucfirst((string)$user['role_name']), ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <span class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <?= htmlspecialchars((string)$user['email'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#kt_profile_details_view">Overview</a>
                </li>
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#kt_profile_settings">Settings</a>
                </li>
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#kt_profile_password">Change Password</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="kt_profile_details_view" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header cursor-pointer">
                    <h3 class="card-title m-0">Profile Details</h3>
                </div>
                <div class="card-body p-9">
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">Full Name</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">
                                <?= htmlspecialchars((string)$user['fullname'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">Contact Phone</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">
                                <?= htmlspecialchars((string)$user['phone'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">Email</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">
                                <?= htmlspecialchars((string)$user['email'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-semibold text-muted">Username</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">
                                <?= htmlspecialchars((string)$user['username'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="kt_profile_settings" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <h3 class="card-title fw-bold fs-3 mb-1">Profile Settings</h3>
                </div>
                <div class="card-body">
                    <form id="kt_profile_details_form" class="form">
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline" data-kt-image-input="true">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('<?= $avatar ?>')"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change">
                                        <input type="file" name="photo" accept=".png,.jpg,.jpeg,.gif" />+
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Full Name</label>
                            <div class="col-lg-8">
                                <input type="text" name="fullname" class="form-control form-control-lg form-control-solid"
                                       value="<?= htmlspecialchars((string)$user['fullname'], ENT_QUOTES, 'UTF-8') ?>" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Phone</label>
                            <div class="col-lg-8">
                                <input type="tel" name="phone" class="form-control form-control-lg form-control-solid"
                                       value="<?= htmlspecialchars((string)$user['phone'], ENT_QUOTES, 'UTF-8') ?>" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Email</label>
                            <div class="col-lg-8">
                                <input type="email" name="email" class="form-control form-control-lg form-control-solid"
                                       value="<?= htmlspecialchars((string)$user['email'], ENT_QUOTES, 'UTF-8') ?>" />
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="kt_profile_password" role="tabpanel">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <h3 class="card-title fw-bold fs-3 mb-1">Change Password</h3>
                </div>
                <div class="card-body">
                    <form id="kt_profile_password_form" class="form">
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Current Password</label>
                            <div class="col-lg-8">
                                <input type="password" name="current_password" class="form-control form-control-lg form-control-solid" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">New Password</label>
                            <div class="col-lg-8">
                                <input type="password" name="new_password" class="form-control form-control-lg form-control-solid" />
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Confirm New Password</label>
                            <div class="col-lg-8">
                                <input type="password" name="confirm_password" class="form-control form-control-lg form-control-solid" />
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
$pageScripts = ['/admin/modules/profile/js/profile.js'];
require_once INCLUDES_PATH . '/footer.php';
?>
