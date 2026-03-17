<?php
declare(strict_types=1);
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure',   isset($_SERVER['HTTPS']) ? '1' : '0');
ini_set('session.cookie_samesite','Strict');
ini_set('session.use_strict_mode', '1');

require_once __DIR__ . '/../vendor/autoload.php';

$db = require __DIR__ . '/../config/db.php';

$pdo = new PDO($db['dsn'], $db['username'], $db['password'], [PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION,]);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

require_once __DIR__ . '/functions.rbac.php';
$rbac = buildRbac($pdo);
