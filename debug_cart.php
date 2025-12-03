<?php
/**
 * Debug Cart Images
 * Temporary file to check what's in the cart
 */

// Load framework
require_once __DIR__ . '/index.php';

// Get cart instance
require_once APP_PATH . '/models/Cart.php';
$cart = Cart::getInstance();

// Get cart items
$items = $cart->getItems();

echo "<h1>Cart Debug Information</h1>";
echo "<h2>Total Items: " . count($items) . "</h2>";

if (empty($items)) {
    echo "<p>Cart is empty!</p>";
} else {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>";
    echo "<th>Product ID</th>";
    echo "<th>Name</th>";
    echo "<th>Image Path</th>";
    echo "<th>Image Preview</th>";
    echo "</tr>";
    
    foreach ($items as $item) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item['product_id']) . "</td>";
        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
        echo "<td>" . htmlspecialchars($item['image'] ?? 'NULL') . "</td>";
        echo "<td>";
        if (!empty($item['image'])) {
            $fullPath = View::asset($item['image']);
            echo "<img src='" . $fullPath . "' width='100' onerror='this.style.border=\"2px solid red\"'>";
            echo "<br><small>Full URL: " . htmlspecialchars($fullPath) . "</small>";
        } else {
            echo "No image";
        }
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

echo "<h2>Database Query Test</h2>";
echo "<p>Testing direct database query...</p>";

// Test direct query
$db = Database::getInstance();
$testItems = $db->select("
    SELECT 
        p.id,
        p.name,
        p.image as product_image,
        (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as product_images_path,
        COALESCE(
            (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1),
            p.image
        ) as final_image
    FROM products p
    LIMIT 5
");

echo "<table border='1' cellpadding='10'>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Name</th>";
echo "<th>products.image</th>";
echo "<th>product_images.image_path</th>";
echo "<th>COALESCE result</th>";
echo "</tr>";

foreach ($testItems as $item) {
    echo "<tr>";
    echo "<td>" . $item['id'] . "</td>";
    echo "<td>" . htmlspecialchars($item['name']) . "</td>";
    echo "<td>" . htmlspecialchars($item['product_image'] ?? 'NULL') . "</td>";
    echo "<td>" . htmlspecialchars($item['product_images_path'] ?? 'NULL') . "</td>";
    echo "<td>" . htmlspecialchars($item['final_image'] ?? 'NULL') . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
