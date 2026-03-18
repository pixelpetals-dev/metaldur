<?php
if (session_status() === PHP_SESSION_NONE) {

    $sessionLifetime = 12 * 60 * 60;
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    ini_set('session.gc_maxlifetime', (string)$sessionLifetime);
    ini_set('session.cookie_lifetime', (string)$sessionLifetime);

    session_set_cookie_params([
        'lifetime' => $sessionLifetime,
        'path' => '/',
        'domain' => '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    ini_set('session.use_strict_mode','1');
    session_start();
}

if (!defined('APP_INIT')) {
    define('APP_INIT', true);
}

if ($secure) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

ini_set('display_errors', '0');


define('ROOT',             getenv('APP_ROOT') ?: dirname(__DIR__, 2));
define('CONFIG_PATH',      ROOT . '/config');
define('SRC_PATH',         ROOT . '/src');
define('PUBLIC_PATH',      ROOT . '/public/admin');
define('SA_INCLUDES_PATH', PUBLIC_PATH . '/super-admin/includes');
define('INCLUDES_PATH',    PUBLIC_PATH . '/includes');
define('PHOTO_PATH',       PUBLIC_PATH . '/assets/media/users/');
define('ASSETS_URL',       '/admin/assets');
define('BASE_URL',         rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
define('ERP_MODULES_DIR',  PUBLIC_PATH . '/modules');
define('SA_MODULES_DIR',   PUBLIC_PATH . '/super-admin/modules');

require_once ROOT . '/vendor/autoload.php';
require_once SRC_PATH . '/functions.security.php';
require_once SRC_PATH . '/casbin-init.php';
require_once SRC_PATH . '/functions.rbac.php';
require_once SRC_PATH . '/functions.user.php';

$dbCfg = require CONFIG_PATH . '/db.php';
$pdo = new PDO(
    $dbCfg['dsn'],
    $dbCfg['username'],
    $dbCfg['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->exec("SET time_zone = '+05:30'");
date_default_timezone_set('Asia/Kolkata');

rbac_bootstrap_user($pdo);

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Permitted-Cross-Domain-Policies: none');
header('Content-Type: text/html; charset=utf-8');
