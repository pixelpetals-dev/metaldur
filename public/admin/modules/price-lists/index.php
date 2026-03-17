<?php
declare(strict_types=1);
require_once __DIR__ . '/../../bootstrap.php';
rbac_guard('price-lists.view');
require_once INCLUDES_PATH . '/header.php';
?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5" id="price-list-app">
    <div id="kt_content_container" class="container-xxl">
        
        <div class="card mb-5">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                
                <div class="d-flex gap-2">
                    <?php if (rbac_guard_silent('price-lists.edit')): ?>
                    <button type="button" class="btn btn-warning btn-sm" @click="syncWithZoho" :disabled="isSyncing">
                        <span v-if="isSyncing" class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        <span v-else><i class="bi bi-arrow-repeat"></i> Sync Zoho</span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="kt_price_table" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-100px">SKU</th>
                                <th class="min-w-200px">Item Name</th>
                                <th class="min-w-100px">Category</th>
                                <th class="min-w-100px">Stock</th>
                                <th class="min-w-100px">Price</th>
                                <th class="text-end min-w-100px">Last Updated</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            <tr v-for="item in items" :key="item.id">
                                <td>{{ item.sku }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-gray-800 fw-bold mb-1 fs-6">{{ item.name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light-info fw-bold">{{ item.category }}</span>
                                </td>
                                <td>
                                    <span :class="{'text-danger': item.stock == 0, 'text-success': item.stock > 0}">
                                        {{ item.stock }}
                                    </span>
                                </td>
                                <td>{{ formatCurrency(item.price) }}</td>
                                <td class="text-end">{{ formatDate(item.updated_at) }}</td>
                            </tr>
                            <tr v-if="items.length === 0">
                                <td colspan="6" class="text-center">No items found. Click Sync to fetch data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="global_csrf" value="<?= csrf_token() ?>">

<?php
$jsPath = '/admin/modules/price-lists/js/price-logic.js';
$pageScripts = [$jsPath . '?v=' . filemtime($_SERVER['DOCUMENT_ROOT'] . $jsPath)];
require_once INCLUDES_PATH . '/footer.php';
?>