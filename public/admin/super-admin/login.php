<?php
require_once __DIR__ . '/../bootstrap.php';

$error = '';
$loginSuccess = false;
$redirectURL  = '/admin/super-admin/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_guard_request($_POST['_csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid CSRF');
    }

    $username = strtolower(trim($_POST['email'] ?? ''));
    $pass     = $_POST['password'] ?? '';

    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email.';
    } else {

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password_hash'])) {

            $userId = (int)$user['id'];

            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId;

            unset($_SESSION['permissions'], $_SESSION['panel_permissions']);
            rbac_bootstrap_user($pdo);

            if (in_array('admin.access', $_SESSION['permissions'], true)) {
                $loginSuccess = true;
            } else {
                unset($_SESSION['user_id']);
                unset($_SESSION['permissions'], $_SESSION['panel_permissions']);
                $error = 'You are not authorised to access the super-admin panel.';
            }

        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sign In – Metaldur - Super-Admin</title>
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex, nocache">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex, nocache">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex, nocache">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="/admin/assets/plugins/global/plugins.bundle.css" rel="stylesheet" />
    <link href="/admin/assets/css/style.bundle.css" rel="stylesheet" />
    <link rel="shortcut icon" href="/admin/assets/media/logos/favicon.ico" />
    <style>
        body{background-image:url('/admin/assets/media/auth/bg6-dark.jpg');background-size:cover;}
    </style>
    <script>if(window.top!==window.self){window.top.location=window.self.location;}</script>
</head>
<body id="kt_body" class="app-blank">
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <div class="d-flex flex-column flex-column-fluid flex-lg-row">
        <!-- Left Branding -->
        <div class="d-flex flex-center w-lg-50 p-10">
            <div class="d-flex flex-column text-center">
                <a href="/index.php" class="mb-7">
                    <img src="/admin/assets/media/logos/logo.png" alt="Logo" width="200"/>
                </a>
                <h2 class="text-white fw-normal m-0">Metaldur – Super-Admin Login</h2>
            </div>
        </div>

        <!-- Login Card -->
        <div class="d-flex flex-column flex-lg-row-auto justify-content-center p-10">
            <div class="bg-body rounded-4 shadow-sm p-10" style="max-width:590px;width:100%;">
                <?php if ($loginSuccess): ?>
                    <div class="text-center">
                        <h1 class="text-success fw-bolder mb-5">Login Successful!</h1>
                        <p class="mb-5">Redirecting you&hellip;</p>
                    </div>
                    <script>
                        setTimeout(()=>location.href="<?= htmlspecialchars($redirectURL) ?>",20);
                    </script>
                <?php else: ?>
                    <h1 class="text-gray-900 fw-bolder mb-5 text-center">Super-Admin Panel</h1>
                    <?php if ($error): ?>
                        <div class="alert alert-danger mb-5"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form class="form w-100" method="post" action="/admin/super-admin/login.php" novalidate>
                        <div class="fv-row mb-8">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                            <input type="email" name="email" placeholder="Email"
                                   class="form-control bg-transparent" required />
                        </div>
                        <div class="fv-row mb-8">
                            <input type="password" name="password" placeholder="Password"
                                   class="form-control bg-transparent" required />
                        </div>
                        <div class="d-grid mb-10">
                            <button class="btn btn-primary" type="submit">
                                <span class="indicator-label">Sign In</span>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="/admin/assets/plugins/global/plugins.bundle.js"></script>
<script src="/admin/assets/js/scripts.bundle.js"></script>
</body>
</html>
