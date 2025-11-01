<?php
/**
 * Database Migration CLI Tool
 * 
 * Usage:
 *   php migrate.php           - Run all pending migrations
 *   php migrate.php rollback  - Rollback last batch
 *   php migrate.php reset     - Reset all migrations
 *   php migrate.php refresh   - Reset and re-run all migrations
 *   php migrate.php seed      - Run database seeders
 */

// Bootstrap the application
require_once __DIR__ . '/index.php';
require_once APP_PATH . '/core/Migration.php';

// Get command from CLI arguments
$command = $argv[1] ?? 'migrate';

// Initialize migration system
$migration = new Migration();

echo "Modern Zone Trading - Database Migration Tool\n";
echo "============================================\n\n";

switch ($command) {
    case 'migrate':
        echo "Running migrations...\n";
        $migration->migrate();
        echo "\nMigrations completed.\n";
        break;
        
    case 'rollback':
        echo "Rolling back last batch...\n";
        $migration->rollback();
        echo "\nRollback completed.\n";
        break;
        
    case 'reset':
        echo "Resetting all migrations...\n";
        $migration->reset();
        echo "\nReset completed.\n";
        break;
        
    case 'refresh':
        echo "Refreshing database...\n";
        $migration->refresh();
        echo "\nRefresh completed.\n";
        
        // Run seeders after refresh
        echo "\nRunning seeders...\n";
        runSeeders();
        echo "Seeding completed.\n";
        break;
        
    case 'seed':
        echo "Running seeders...\n";
        runSeeders();
        echo "\nSeeding completed.\n";
        break;
        
    default:
        echo "Unknown command: {$command}\n";
        echo "\nAvailable commands:\n";
        echo "  migrate   - Run all pending migrations\n";
        echo "  rollback  - Rollback last batch\n";
        echo "  reset     - Reset all migrations\n";
        echo "  refresh   - Reset and re-run all migrations\n";
        echo "  seed      - Run database seeders\n";
        break;
}

/**
 * Run database seeders
 */
function runSeeders()
{
    $db = Database::getInstance();
    
    // Check if admin user exists
    $adminExists = $db->selectOne("SELECT id FROM users WHERE email = 'admin@modernzonetrading.com'");
    
    if (!$adminExists) {
        // Insert default admin user (password: Admin@123)
        $db->insert('users', [
            'name' => 'Admin',
            'email' => 'admin@modernzonetrading.com',
            'password' => password_hash('Admin@123', PASSWORD_BCRYPT),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);
        echo "Created admin user: admin@modernzonetrading.com (password: Admin@123)\n";
    }
    
    // Insert sample categories if they don't exist
    $categoriesExist = $db->selectOne("SELECT COUNT(*) as count FROM categories");
    
    if ($categoriesExist['count'] == 0) {
        $categories = [
            ['name' => 'Cutting Tools', 'slug' => 'cutting-tools', 'icon' => 'cut', 'description' => 'Precision cutting tools for all applications', 'sort_order' => 1],
            ['name' => 'Abrasives', 'slug' => 'abrasives', 'icon' => 'circle-notch', 'description' => 'High-quality abrasive products', 'sort_order' => 2],
            ['name' => 'Blades and Cutters', 'slug' => 'blades-cutters', 'icon' => 'tools', 'description' => 'Professional blades and cutting solutions', 'sort_order' => 3],
            ['name' => 'Hardware and Power Tools', 'slug' => 'hardware-power-tools', 'icon' => 'hammer', 'description' => 'Complete range of hardware and power tools', 'sort_order' => 4],
            ['name' => 'Measuring Tools', 'slug' => 'measuring-tools', 'icon' => 'ruler', 'description' => 'Precision measuring instruments', 'sort_order' => 5],
            ['name' => 'Hand Tools', 'slug' => 'hand-tools', 'icon' => 'wrench', 'description' => 'Professional hand tools', 'sort_order' => 6],
        ];
        
        foreach ($categories as $category) {
            $db->insert('categories', $category);
        }
        echo "Created " . count($categories) . " categories\n";
    }
    
    // Insert sample brands if they don't exist
    $brandsExist = $db->selectOne("SELECT COUNT(*) as count FROM brands");
    
    if ($brandsExist['count'] == 0) {
        $brands = [
            ['name' => 'Dormer', 'slug' => 'dormer', 'logo' => 'dormer.png', 'website' => 'https://www.dormerpramet.com'],
            ['name' => 'Sandvik Coromant', 'slug' => 'sandvik-coromant', 'logo' => 'sandvik.png', 'website' => 'https://www.sandvik.coromant.com'],
            ['name' => 'Seco Tools', 'slug' => 'seco-tools', 'logo' => 'seco.png', 'website' => 'https://www.secotools.com'],
            ['name' => 'Pramet', 'slug' => 'pramet', 'logo' => 'pramet.png', 'website' => 'https://www.dormerpramet.com'],
            ['name' => 'Kyocera', 'slug' => 'kyocera', 'logo' => 'kyocera.png', 'website' => 'https://www.kyocera.com'],
            ['name' => 'Walter Tools', 'slug' => 'walter-tools', 'logo' => 'walter.png', 'website' => 'https://www.walter-tools.com'],
            ['name' => 'Kennametal', 'slug' => 'kennametal', 'logo' => 'kennametal.png', 'website' => 'https://www.kennametal.com'],
            ['name' => 'Iscar', 'slug' => 'iscar', 'logo' => 'iscar.png', 'website' => 'https://www.iscar.com'],
        ];
        
        foreach ($brands as $brand) {
            $db->insert('brands', $brand);
        }
        echo "Created " . count($brands) . " brands\n";
    }
    
    // Insert sample products if they don't exist
    $productsExist = $db->selectOne("SELECT COUNT(*) as count FROM products");
    
    if ($productsExist['count'] == 0) {
        // Get category IDs
        $cuttingTools = $db->selectOne("SELECT id FROM categories WHERE slug = 'cutting-tools'");
        $hardwareTools = $db->selectOne("SELECT id FROM categories WHERE slug = 'hardware-power-tools'");
        
        // Get brand IDs
        $dormer = $db->selectOne("SELECT id FROM brands WHERE slug = 'dormer'");
        $sandvik = $db->selectOne("SELECT id FROM brands WHERE slug = 'sandvik-coromant'");
        
        if ($cuttingTools && $dormer) {
            $products = [
                [
                    'category_id' => $cuttingTools['id'],
                    'brand_id' => $dormer['id'],
                    'sku' => 'HSS-DRL-001',
                    'name' => 'HSS Drill Bits Set',
                    'slug' => 'hss-drill-bits-set',
                    'description' => 'Professional 25-piece HSS drill bit set for metal and steel. High-speed steel construction ensures durability and precision.',
                    'specifications' => 'Material: HSS\nSizes: 1mm-13mm\nQuantity: 25 pieces\nCase: Metal storage case included',
                    'price' => 189.00,
                    'compare_price' => 250.00,
                    'quantity' => 50,
                    'featured' => true,
                    'best_seller' => true,
                ],
                [
                    'category_id' => $cuttingTools['id'],
                    'brand_id' => $sandvik['id'],
                    'sku' => 'CBN-END-002',
                    'name' => 'Carbide End Mills Set',
                    'slug' => 'carbide-end-mills-set',
                    'description' => 'Premium carbide end mills for CNC machining. Suitable for various materials including steel, aluminum, and composites.',
                    'specifications' => 'Material: Solid Carbide\nCoating: TiAlN\nFlutes: 4\nSizes: 4mm, 6mm, 8mm, 10mm, 12mm',
                    'price' => 425.00,
                    'quantity' => 30,
                    'featured' => true,
                ],
                [
                    'category_id' => $hardwareTools['id'],
                    'brand_id' => null,
                    'sku' => 'KEY-CHK-003',
                    'name' => 'Key Type Drill Chuck',
                    'slug' => 'key-drill-chuck',
                    'description' => 'Heavy-duty keyed drill chuck for maximum grip and precision. Compatible with most drill presses and machines.',
                    'specifications' => 'Capacity: 1-13mm\nMount: B16 taper\nType: Keyed\nMaterial: Hardened steel',
                    'price' => 145.00,
                    'quantity' => 75,
                ],
            ];
            
            foreach ($products as $product) {
                $db->insert('products', $product);
            }
            echo "Created " . count($products) . " sample products\n";
        }
    }
}

echo "\n";
