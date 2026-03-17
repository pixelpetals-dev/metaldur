<?php
class InventoryManager {
    private $pdo;
    const ALLOWED_BRAND = null; 
    const NAME_CONTAINS = 'METALDUR'; 
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    private function shouldSyncItem($zohoItem) {
        if (self::ALLOWED_BRAND) {
            $manufacturer = $zohoItem['manufacturer'] ?? ''; 
            if (strcasecmp($manufacturer, self::ALLOWED_BRAND) !== 0) {
                return false; 
            }
        }
        if (self::NAME_CONTAINS) {
            if (stripos($zohoItem['name'], self::NAME_CONTAINS) === false) {
                return false; 
            }
        }
        if (isset($zohoItem['status']) && $zohoItem['status'] != 'active') {
            return false;
        }
        return true; 
    }
    public function syncItem($zohoItem) {
        if (!$this->shouldSyncItem($zohoItem)) {
            return false; 
        }
        $zohoId = $zohoItem['item_id'];
        $sku    = $zohoItem['sku'];
        $name   = $zohoItem['name'];
        $rate   = $zohoItem['rate'];
        $stock  = $zohoItem['stock_on_hand'] ?? 0;
        $category = $zohoItem['category_name'] ?? 'Uncategorized'; 
        $stmt = $this->pdo->prepare("SELECT id, price FROM items WHERE zoho_item_id = ? OR sku = ? LIMIT 1");
        $stmt->execute([$zohoId, $sku]);
        $existing = $stmt->fetch();
        $newCategory = $zohoItem['category_name'] ?? '';
        if ($existing) {
            $localId = $existing['id'];
            $oldPrice = $existing['price'];
            $currentCategory = $existing['category'];
            $finalCategory = (!empty($newCategory)) ? $newCategory : $currentCategory;
            $update = $this->pdo->prepare("UPDATE items SET zoho_item_id = ?, name = ?, category = ?, price = ?, stock = ?, updated_at = NOW() WHERE id = ?");
            $update->execute([$zohoId, $name, $finalCategory, $rate, $stock, $localId]);
            if (floatval($oldPrice) != floatval($rate)) {
                $this->logPriceChange($localId, $oldPrice, $rate);
            }
        } else {
            $insert = $this->pdo->prepare("INSERT INTO items (zoho_item_id, sku, name, category, price, stock, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $insert->execute([$zohoId, $sku, $name, $category, $rate, $stock]);
        }
        return true; 
    }
    private function logPriceChange($itemId, $oldPrice, $newPrice) {
        $stmt = $this->pdo->prepare("INSERT INTO price_logs (item_id, old_price, new_price, user_id, created_at) VALUES (?, ?, ?, 0, NOW())");
        $stmt->execute([$itemId, $oldPrice, $newPrice]);
    }
}
?>