<?php
require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../../../../../config/InventoryManager.php';
$mySecret = 'metaldur@5253'; 
if (($_GET['secret'] ?? '') !== $mySecret) {
    http_response_code(403);
    exit('Forbidden: Invalid Secret');
}
$json = file_get_contents('php://input');
file_put_contents(
    __DIR__ . '/webhook_debug.log',
    date('Y-m-d H:i:s') . "\n" . $json . "\n\n",
    FILE_APPEND
);
$data = json_decode($json, true);
if (!$data) {
    http_response_code(200);
    exit('Webhook Validated');
}
$manager = new InventoryManager($pdo);
try {
    if (isset($data['item'])) {
        $item = $data['item'];
        $success = $manager->syncItem($item);
        if ($success) {
            echo "Success: Item Synced";
        } else {
            echo "Ignored: Item filtered out by brand/category rules";
        }
    } 
    elseif (isset($data['status']) && $data['status'] == 'inactive') {
        echo "Item marked inactive";
    }
    else {
        echo "No valid item data found";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
    file_put_contents(__DIR__ . '/webhook_error.log', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", FILE_APPEND);
}
?>