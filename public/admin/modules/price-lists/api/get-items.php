<?php
require_once __DIR__ . '/../../../bootstrap.php';
rbac_guard('price-lists.view');
$stmt = $pdo->query("SELECT * FROM items ORDER BY name ASC");
$items = $stmt->fetchAll();
$catStmt = $pdo->query("SELECT DISTINCT category FROM items WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);
echo json_encode([
    'items' => $items,
    'categories' => $categories
]);
?>