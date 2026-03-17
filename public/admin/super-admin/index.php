<?php
declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$path    = $request->getPathInfo();
$base    = '/admin/super-admin';

$path = rawurldecode($path);
$path = preg_replace('#/+#', '/', $path);
$path = (substr($path, 0, strlen($base)) === $base) ? substr($path, strlen($base)) : $path;
$path = trim($path, '/');

$segments = array_values(array_filter(explode('/', $path)));

$module = $segments[0] ?? '';

if ($module === 'login' || $module === 'logout') {
    require __DIR__ . "/{$module}.php";
    exit;
}

require __DIR__ . '/session.php';

$module = $module ?: 'sa-dashboard';
$action = $segments[1] ?? 'index';
$id     = $segments[2] ?? null;

if (!preg_match('/^[a-z0-9_\-]+$/i', $module) || !preg_match('/^[a-z0-9_\-]+$/i', $action)) {
    http_response_code(400);
    exit('Bad Request');
}

if ($id !== null && $id !== '' && !ctype_digit($id)) {
    http_response_code(400);
    exit('Invalid ID');
}

$file = __DIR__ . "/modules/{$module}/{$action}.php";
$real = realpath($file);
$root = realpath(__DIR__ . '/modules');

if ($real === false || strpos($real, $root) !== 0 || !is_file($real) || !is_readable($real)) {
    http_response_code(404);
    exit('Not Found');
}

require $real;
exit;
