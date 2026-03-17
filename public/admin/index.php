<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$path    = $request->getPathInfo();
$method  = $request->getMethod();

if (!defined('APP_INIT')) {
    define('APP_INIT', true);
}
define('MODULES_DIR', realpath(__DIR__ . '/modules'));
define('MODULE_CACHE', __DIR__ . '/.cache_modules.json');
define('MODULE_CACHE_TTL', 300);

function send_json(int $code, array $payload): void {
    $r = new Response(json_encode($payload), $code, ['Content-Type' => 'application/json']);
    $r->send();
    exit;
}

function log_security_event(string $message, array $ctx = []): void {
    $line = sprintf("[%s] %s %s\n", date('c'), $message, json_encode($ctx));
    @file_put_contents(__DIR__ . '/logs/security.log', $line, FILE_APPEND | LOCK_EX);
}

$path = rawurldecode($path);
$path = preg_replace('#/+#', '/', $path);
$path = rtrim($path, '/');
if ($path === '') $path = '/';

if (preg_match('#^/(login|logout)\.php$#i', $path, $m)) {
    require __DIR__ . "/{$m[1]}.php";
    exit;
}

if (strpos($path, '/super-admin/') === 0) {
    return false;
}

if (empty($_SESSION['user_id'])) {
    if (stripos($path, '/modules/') === 0 && stripos($path, '/api/') !== false) {
        send_json(401, ['error' => 'Unauthenticated']);
    }
    header('Location: /admin/login.php');
    exit;
}

function discover_modules(): array {
    $cacheFile = MODULE_CACHE;
    if (file_exists($cacheFile)) {
        $meta = @json_decode(@file_get_contents($cacheFile), true);
        if (is_array($meta) && isset($meta['time']) && (time() - $meta['time'] < MODULE_CACHE_TTL) && isset($meta['modules'])) {
            return $meta['modules'];
        }
    }

    $modules = [];
    $modulesRoot = MODULES_DIR;
    if ($modulesRoot && is_dir($modulesRoot)) {
        foreach (scandir($modulesRoot) as $entry) {
            if ($entry === '.' || $entry === '..') continue;
            $full = $modulesRoot . DIRECTORY_SEPARATOR . $entry;
            if (is_dir($full) && preg_match('/^[a-z0-9_\-]+$/i', $entry)) {
                $modules[] = $entry;
            }
        }
    }
    @file_put_contents($cacheFile, json_encode(['time' => time(), 'modules' => $modules]), LOCK_EX);
    return $modules;
}

$validModules = discover_modules();

$segments = array_values(array_filter(explode('/', ltrim($path, '/'))));
$first = $segments[0] ?? null;

if ($first === 'modules') {
    $module = $segments[1] ?? null;
    $action = $segments[2] ?? 'index';
    $id     = $segments[3] ?? null;
} else {
    $module = $segments[0] ?? 'dashboard';
    $action = $segments[1] ?? 'index';
    $id     = $segments[2] ?? null;
}

$validName = '/^[a-z0-9_\-]+$/i';
if (!is_string($module) || !preg_match($validName, $module) || !is_string($action) || !preg_match($validName, $action)) {
    log_security_event('Invalid module/action', ['path' => $path, 'module' => $module, 'action' => $action]);
    http_response_code(400);
    exit('Bad Request');
}

if (!in_array($module, $validModules, true)) {
    log_security_event('Unknown module', ['module' => $module]);
    http_response_code(404);
    exit('Not Found');
}

if ($id !== null && $id !== '') {
    if (!ctype_digit($id)) {
        log_security_event('Invalid ID', ['id' => $id, 'module' => $module]);
        http_response_code(400);
        exit('Invalid ID');
    }
    $id = (int)$id;
}

$moduleFile = __DIR__ . "/modules/{$module}/{$action}.php";
$moduleFileReal = realpath($moduleFile);

if ($moduleFileReal === false || strpos($moduleFileReal, MODULES_DIR) !== 0 || !is_file($moduleFileReal) || !is_readable($moduleFileReal)) {
    log_security_event('Missing module file', ['module'=>$module, 'action'=>$action]);
    http_response_code(404);
    exit('Not Found');
}

$isApi = strpos($moduleFileReal, DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR) !== false;
if ($isApi) {
    if (!in_array($method, ['GET','POST'], true)) {
        http_response_code(405);
        header('Allow: GET, POST');
        exit;
    }
}

$moduleName = $module;
$actionName = $action;
$resourceId = $id;

$requestMeta = [
    'ip' => $request->getClientIp(),
    'ua' => $request->headers->get('User-Agent'),
    'method' => $method,
    'path' => $path,
];

require $moduleFileReal;
exit;