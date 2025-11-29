<?php
/**
 * Migration Script: Add type column to categories table
 * Run this file to apply the database changes
 */

// Define APP_PATH
define('APP_PATH', __DIR__ . '/../../app');

// Load environment and database
require_once APP_PATH . '/core/Database.php';

echo "Starting migration: Add type column to categories table...\n\n";

try {
    $db = Database::getInstance();
    
    // Check if type column already exists
    $checkColumn = $db->selectOne("SHOW COLUMNS FROM categories LIKE 'type'");
    
    if ($checkColumn) {
        echo "✓ Type column already exists! Skipping column creation.\n\n";
    } else {
        echo "Adding type column to categories table...\n";
        $db->query("ALTER TABLE categories ADD COLUMN type ENUM('general', 'our-products') DEFAULT 'general' AFTER parent_id");
        echo "✓ Type column added successfully!\n\n";
        
        echo "Adding index for type column...\n";
        $db->query("ALTER TABLE categories ADD INDEX idx_type (type)");
        echo "✓ Index added successfully!\n\n";
    }
    
    // Update existing categories
    echo "Updating existing general categories...\n";
    $generalSlugs = [
        'hand-tools', 'power-tools-electrical', 'other-measuring-instruments',
        'safety', 'machine-shop', 'abrasive', 'welding', 'plumbing',
        'construction', 'uncategorized'
    ];
    
    foreach ($generalSlugs as $slug) {
        $db->query(
            "UPDATE categories SET type = 'general' WHERE slug = :slug",
            ['slug' => $slug]
        );
    }
    echo "✓ General categories updated!\n\n";
    
    echo "Updating existing our-products categories...\n";
    $ourProductsSlugs = [
        'ball-cages', 'band-saw-blades', 'brazed-tool-holders', 'bushes',
        'carbide-hss-drill-bits', 'carbide-hss-end-mills', 'carbide-rotary-burrs',
        'drill-chucks-lathe', 'ejector-pins', 'fibro', 'grooving-threading-cut',
        'hack-saw-blades', 'hole-saw-blades', 'hole-saw-core-cutters',
        'machine-tool-accessories', 'measuring-instruments', 'milling-cutters',
        'pcd-cbn-ceramic-inserts', 'pillars', 'punches', 'reamers-countersinks',
        'springs', 'standard-parts-dies-molds', 'taps-dies', 'turning-holders',
        'turning-inserts', 'u-drills'
    ];
    
    foreach ($ourProductsSlugs as $slug) {
        $db->query(
            "UPDATE categories SET type = 'our-products' WHERE slug = :slug",
            ['slug' => $slug]
        );
    }
    echo "✓ Our-products categories updated!\n\n";
    
    echo "===========================================\n";
    echo "✓ Migration completed successfully!\n";
    echo "===========================================\n";
    
} catch (Exception $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
