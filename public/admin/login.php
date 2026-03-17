<?php
require __DIR__ . '/bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_guard_request($_POST['_csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid CSRF');
    }

    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass  = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            unset($_SESSION['permissions'], $_SESSION['panel_permissions']);
            rbac_bootstrap_user($pdo);
            header('Location: /admin/dashboard');
            exit;
        } else {
            $error = 'Invalid credentials. Please check and try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sign In – Metaldur ERP</title>
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex, nocache">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex, nocache">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex, nocache">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="/admin/assets/plugins/global/plugins.bundle.css" rel="stylesheet" />
    <link href="/admin/assets/css/style.bundle.css" rel="stylesheet" />
    <link rel="shortcut icon" href="/admin/assets/media/logos/favicon.ico" />
    <style>
    html, body {
        height: 100%;
        margin: 0;
    }
    
    body{
        background-image:url('/admin/assets/media/auth/bg7.jpg');
        background-size:cover;
        background-position:center;
        background-attachment:fixed;
        overflow:hidden;
    }
    
    #kt_app_root,
    .flex-root,
    .flex-column-fluid {
        height:100vh;
        min-height:100vh;
    }
    
    @media (max-width: 991px) {
        #kt_app_root,
        .flex-root,
        .flex-column-fluid {
            height:auto;
            min-height:100vh;
        }
    
        body {
            overflow:auto;
        }
    }
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
                <h2 class="text-white fw-normal m-0">Metaldur – ERP Login</h2>
            </div>
        </div>

        <!-- Login Card -->
        <div class="d-flex flex-column flex-lg-row-auto justify-content-center p-10">
            <div class="bg-body rounded-4 shadow-sm p-10" style="max-width:590px;width:100%;">
            <h1 class="text-gray-900 fw-bolder mb-5 text-center">Welcome to Metaldur ERP</h1>
        
            <?php if ($error): ?>
                <div class="alert alert-danger mb-5"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        
            <form class="form w-100" method="post" action="login.php" novalidate>
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
        
                <div class="fv-row mb-8">
                    <input type="email" name="email" placeholder="Email"
                           class="form-control bg-transparent" required />
                </div>
        
                <div class="fv-row mb-8">
                    <input type="password" name="password" placeholder="Password"
                           class="form-control bg-transparent" required />
                </div>
        
                <div class="d-grid mb-2">
                    <button class="btn btn-primary" type="submit">
                        <span class="indicator-label">Sign In</span>
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

<script src="/admin/assets/plugins/global/plugins.bundle.js"></script>
<script src="/admin/assets/js/scripts.bundle.js"></script>
</body>
</html>
