<?php
$stmt = $pdo->prepare(
    'SELECT username, email, photo, fullname
       FROM users
      WHERE id = ?'
);
$stmt->execute([$_SESSION['user_id']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

$fullName = trim(
    ($userData['fullname'] ?? '')
);
$userName  = $fullName !== '' 
           ? $fullName 
           : ($userData['username'] ?? 'Super Admin');

$userEmail = $userData['email'] ?? 'admin@example.com';

if (!empty($userData['photo'])) {
    $userPhoto = ASSETS_URL . "/media/users/{$userData['photo']}";
} else {
    $userPhoto = ASSETS_URL . '/media/avatars/blank.png';
}
?>

<!DOCTYPE html>
<!--
Author: Simply Five Studio
Product Name: Simply Five Studio ERP
Website: https://www.simplyfivestudio.com
Contact: hello@simplyfivestudio.com
Follow: https://twitter.com/letssimplyfive
Like: www.facebook.com/simplyfivestudio
-->

<html lang="en">
	<head>
		<title>Metaldur - ERP</title>
		<meta charset="utf-8" />
		<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex, nocache">
        <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex, nocache">
        <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex, nocache">
		<meta name="description" content="ERP Web Application for Manufacturing, Trading and SME Business to manage their daily business administration tasks." />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Metaldur - ERP" />
		<meta property="og:url" content="https://metaldur.in" />
		<meta property="og:site_name" content="Metaldur - ERP" />
		<link rel="canonical" href="https://metaldur.in" />
		<link rel="shortcut icon" href="<?= ASSETS_URL ?>/media/logos/favicon.ico" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <link href="<?= ASSETS_URL ?>/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
        <link href="<?= ASSETS_URL ?>/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <link href="<?= ASSETS_URL ?>/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="<?= ASSETS_URL ?>/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<script>if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
		<style>.menu-title{color:white !important;} .menu-icon i{color:white !important;} .menu-arrow:after{background-color:white !important;}.app-sidebar-minimize .menu-section {display: none !important;}.form-control {background-color: #f5f8fa !important; border: 0.8px solid #8EC640 !important;}.form-control:focus {background-color: #eef2f7 !important;box-shadow: none !important; border: 0.7px solid #8EC640 !important;} input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button {-webkit-appearance: none;margin: 0;}input[type=number] {-moz-appearance: textfield;}</style>
	</head>
	
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default app-sidebar-minimize" data-kt-app-sidebar-minimize="on">
		
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>

		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
					<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container" style="background:#0D3354 !important;">
						<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
							<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
								<i class="ki-solid ki-abstract-14 text-black fs-2 fs-md-1">
								</i>
							</div>
						</div>
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
							<a href="index.php" class="d-lg-none">
								<img alt="Logo" src="<?= ASSETS_URL ?>/media/logos/logo.png" class="h-30px" />
							</a>
						</div>
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
							<h3 class="mt-8" style="color:#FFF !important;">Metaldur - Admin Panel </h3>
							</div>
							<div class="app-navbar flex-shrink-0">
								<div class="app-navbar-item ms-1 ms-md-4">
								</div>
								
<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
    <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
        <img src="<?= htmlspecialchars($userPhoto) ?>" class="rounded-3" alt="User" />
    </div>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <div class="symbol symbol-50px me-5">
                    <img alt="User" src="<?= htmlspecialchars($userPhoto) ?>" />
                </div>
                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">
                        <?= htmlspecialchars($userName) ?>
                    </div>
                    <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                        <?= htmlspecialchars($userEmail) ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="separator my-2"></div>
        <div class="menu-item px-5">
            <a href="<?= BASE_URL ?>/profile" class="menu-link px-5">My Profile</a>
        </div>
        <div class="separator my-2"></div>
        <div class="menu-item px-5">
            <a href="<?= BASE_URL ?>/logout.php" class="menu-link px-5">Sign Out</a>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
<a href="<?= BASE_URL ?>/" style="left:50%;right:50%;margin:auto;">
<img alt="Logo" src="<?= ASSETS_URL ?>/media/logos/logo.png" class="h-60px app-sidebar-logo-default" />
<img alt="Logo" src="<?= ASSETS_URL ?>/media/logos/logo-small.png" class="h-45px app-sidebar-logo-minimize" />
</a>
<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate active" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize" data-kt-toggle-save-state="false">
	<i class="ki-duotone ki-black-left-line fs-3 rotate-180">
		<span class="path1"></span>
		<span class="path2"></span>
	</i>
</div>
</div>

<?php require INCLUDES_PATH . '/sidebar.php'; ?>						
</div>
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
<div class="d-flex flex-column flex-column-fluid">
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-fluid">