<?php
require_once __DIR__ . '/../../bootstrap.php';
rbac_guard('price-lists.view');
require_once INCLUDES_PATH . '/header.php';

?>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5" id="price-history-app">
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="card-label">Price Fluctuation History</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <table id="kt_history_table" class="table table-row-bordered gy-5">
                    <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th>Date</th>
                            <th>Item Name</th>
                            <th>SKU</th>
                            <th>Old Price</th>
                            <th>New Price</th>
                            <th>Change</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="log in logs" :key="log.id">
                            <td>{{ log.formatted_date }}</td>
                            <td>{{ log.item_name }}</td>
                            <td><code>{{ log.sku }}</code></td>
                            <td>{{ formatCurrency(log.old_price) }}</td>
                            <td :class="getPriceClass(log)">{{ formatCurrency(log.new_price) }}</td>
                            <td>
                                <span :class="getBadgeClass(log)">
                                    {{ getDirectionIcon(log) }} {{ calculateDiff(log) }}
                                </span>
                            </td>
                            <td>{{ log.user_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$jsPath = '/admin/modules/price-lists/js/history-logic.js';
$pageScripts = [$jsPath . '?v=' . filemtime($_SERVER['DOCUMENT_ROOT'] . $jsPath)];
require_once INCLUDES_PATH . '/footer.php';
?>