<?php
require_once __DIR__ . '/../../../bootstrap.php';
rbac_guard('price-lists.edit'); 
$input = json_decode(file_get_contents('php://input'), true);
if (!csrf_guard_request($input['_csrf'] ?? '')) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF Token']);
    exit;
}
set_time_limit(300);
$zohoConfig = require_once __DIR__ . '/../../../../../config/zoho-config.php';
require_once __DIR__ . '/../../../../../config/ZohoClient.php';
require_once __DIR__ . '/../../../../../config/InventoryManager.php';
try {
    $zoho = new ZohoClient($zohoConfig);
    $manager = new InventoryManager($pdo);
    $page = 1;
    $hasMore = true;
    $totalSaved = 0;
    while ($hasMore) {
        $response = $zoho->getItems($page);
        if (!isset($response['items'])) {
            throw new Exception("API Error on page $page");
        }
        foreach ($response['items'] as $item) {
            if ($manager->syncItem($item)) {
                $totalSaved++;
            }
        }
        if (isset($response['page_context']['has_more_page']) && $response['page_context']['has_more_page']) {
            $page++;
        } else {
            $hasMore = false;
        }
    }
    echo json_encode(['success' => true, 'count' => $totalSaved]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>