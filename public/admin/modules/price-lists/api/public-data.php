<?php
require_once __DIR__ . '/../../../bootstrap.php';
try {
    $stmt = $pdo->query("SELECT id, name, sku, category, price, stock FROM items ORDER BY name ASC");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $histStmt = $pdo->query("SELECT item_id, new_price, DATE_FORMAT(created_at, '%d-%m') as date 
                             FROM price_logs 
                             ORDER BY created_at ASC");
    $history = [];
    while ($row = $histStmt->fetch(PDO::FETCH_ASSOC)) {
        $history[$row['item_id']][] = $row;
    }

    echo json_encode(['success' => true, 'items' => $items, 'history' => $history]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false]);
}