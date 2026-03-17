<?php
declare(strict_types=1);

$currentUrl = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
if ($currentUrl === '/' || $currentUrl === '') {
    $currentUrl = urlBase(BASE_URL . '/dashboard');
}

function canView(string $perm): bool {
    return erpHasPermission($perm);
}

function urlBase(string $url): string {
    $u = rtrim($url, '/');
    return $u === '' ? '/' : $u;
}

function isParentActive(string $parentUrl, string $currentUrl): bool {
    $p = urlBase($parentUrl);
    return $currentUrl === $p || str_starts_with($currentUrl, $p . '/');
}

$menu = [
    [
        'label' => 'Dashboard',
        'icon'  => 'ki-solid ki-abstract-20',
        'url'   => BASE_URL . '/sa-dashboard',
        'perm'  => 'sa-dashboard.view',
    ],
    [
        'label'    => 'Users',
        'icon'     => 'ki-solid ki-user',
        'url'      => BASE_URL . '/users',
        'children' => [
            [
                'label' => 'List Users',
                'url'   => BASE_URL . '/users',
                'perm'  => 'users.view',
            ],
            [
                'label' => 'Create User',
                'url'   => BASE_URL . '/users/create',
                'perm'  => 'users.create',
            ],
        ],
    ],
    [
        'label'    => 'Roles',
        'icon'     => 'ki-solid ki-data',
        'url'      => BASE_URL . '/roles',
        'children' => [
            [
                'label' => 'List Roles',
                'url'   => BASE_URL . '/roles',
                'perm'  => 'roles.view',
            ],
            [
                'label' => 'Create Role',
                'url'   => BASE_URL . '/roles/create',
                'perm'  => 'roles.create',
            ],
        ],
    ],
    [
        'label' => 'Permissions',
        'icon'  => 'ki-solid ki-gear',
        'url'   => BASE_URL . '/permissions',
        'perm'  => 'permissions.view',
    ],
    [
        'label' => 'ERP Dashboard',
        'icon'  => 'ki-solid ki-abstract-32',
        'url'   => '/admin/dashboard',
        'perm'  => 'dashboard.view',
    ],
];
?>
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3">
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" data-kt-menu="true">

                <?php foreach ($menu as $item):
                    $children = $item['children'] ?? [];
                    $visibleChildren = [];

                    if ($children) {
                        foreach ($children as $c) {
                            if (canView($c['perm'])) $visibleChildren[] = $c;
                        }
                        if (!$visibleChildren) continue;
                    } else {
                        if (!canView($item['perm'])) continue;
                    }

                    $hasChildren = !empty($visibleChildren);
                    $parentUrl = $item['url'] ?? '';
                    $parentActive = $hasChildren ? isParentActive($parentUrl, $currentUrl) : false;
                    $isSingleActive = !$hasChildren && $currentUrl === urlBase($item['url']);
                ?>

                <?php if ($hasChildren): ?>
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion <?= $parentActive ? 'here show' : '' ?>">
                        <span class="menu-link <?= $parentActive ? 'active' : '' ?>">
                            <span class="menu-icon"><i class="<?= $item['icon'] ?> fs-2"></i></span>
                            <span class="menu-title"><?= htmlspecialchars($item['label']) ?></span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div class="menu-sub menu-sub-accordion">
                            <?php foreach ($visibleChildren as $child): ?>
                                <div class="menu-item">
                                    <a class="menu-link" href="<?= htmlspecialchars($child['url']) ?>">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title"><?= htmlspecialchars($child['label']) ?></span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="menu-item">
                        <a class="menu-link <?= $isSingleActive ? 'active' : '' ?>" href="<?= htmlspecialchars($item['url']) ?>">
                            <span class="menu-icon"><i class="<?= $item['icon'] ?> fs-2"></i></span>
                            <span class="menu-title"><?= htmlspecialchars($item['label']) ?></span>
                        </a>
                    </div>
                <?php endif; ?>

                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>
