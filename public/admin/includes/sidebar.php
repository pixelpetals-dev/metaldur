<?php
declare(strict_types=1);

$currentUrl = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
if ($currentUrl === '') $currentUrl = '/';
if ($currentUrl === '/') $currentUrl = '/dashboard';

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
        'icon'  => 'ki-solid ki-element-11',
        'url'   => BASE_URL . '/dashboard/',
        'perm'  => 'dashboard.view',
    ],
    
    [
        'label' => 'Price Lists',
        'icon'  => 'ki-solid ki-abstract-39',
        'url'   => BASE_URL . '/price-lists',
        'children' => [
            [
                'label' => 'Metaldur Price List',
                'url'   => BASE_URL . '/price-lists/',
                'perm'  => 'price-lists.view',
            ],
        ],
    ],
    
    [
        'label' => 'Super-Admin Dashboard',
        'icon'  => 'ki-solid ki-abstract-42',
        'url'   => BASE_URL . '/super-admin/sa-dashboard',
        'perm'  => 'sa-dashboard.view',
    ],
    
    
];

$rendered = [];
$currentSection = 0;
$rendered[$currentSection] = [];

foreach ($menu as $item) {

    if (isset($item['section'])) {
        $currentSection = $item['section'];
        $rendered[$currentSection] = [];
        continue;
    }

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

    $rendered[$currentSection][] = [
        'item' => $item,
        'children' => $visibleChildren
    ];
}
?>
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3">
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" data-kt-menu="true">

                <?php foreach ($rendered as $section => $items): ?>

                    <?php if ($section !== 0 && !empty($items)): ?>
                        <div class="menu-item pt-5">
                            <span class="menu-section text-white text-uppercase fs-7 fw-bold">
                                <?= htmlspecialchars($section) ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($items as $row):
                        $item = $row['item'];
                        $children = $row['children'];

                        $hasChildren = !empty($children);
                        $parentActive = $hasChildren ? isParentActive($item['url'], $currentUrl) : false;
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
                                <?php foreach ($children as $child): ?>
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

                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>
