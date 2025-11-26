<?php
/**
 * Product Controller
 */
require_once APP_PATH . '/models/Product.php';
require_once APP_PATH . '/models/Category.php';
require_once APP_PATH . '/models/Brand.php';

class ProductController
{
    public function index()
    {
        $category = Request::get('category');
        $search = Request::get('search');
        $brand = Request::get('brand');
        $page = Request::get('page', 1);
        $perPage = 12;
        
        // If brand parameter exists, redirect to brand page
        if ($brand) {
            $this->brand($brand);
            return;
        }
        
        // Get products from database
        $allProducts = $this->getProductsFromDatabase($category, $search);
        
        // Paginate products
        $offset = ($page - 1) * $perPage;
        $paginatedProducts = array_slice($allProducts, $offset, $perPage);
        
        $data = [
            'title' => 'Industrial Tools & Equipment - Modern Zone Trading',
            'description' => 'Browse our extensive range of industrial tools including cutting tools, CNC machine holders, measuring instruments, power tools and accessories from leading brands.',
            'products' => $paginatedProducts,
            'categories' => $this->getCategoriesFromDatabase(),
            'currentPage' => $page,
            'totalPages' => max(1, ceil(count($allProducts) / $perPage)),
            'currentCategory' => $category,
            'searchQuery' => $search,
            'totalProducts' => count($allProducts),
        ];
        
        View::render('pages/products/index', $data);
    }
    
    public function brand($slug)
    {
        $db = Database::getInstance();
        
        // Get brand from database
        $brand = $db->selectOne(
            "SELECT * FROM brands WHERE slug = :slug AND status = 'active'",
            ['slug' => $slug]
        );
        
        // If brand not found, show 404
        if (!$brand) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }
        
        // Get products for this brand from database
        $products = $this->getProductsByBrandFromDatabase($brand['id']);
        
        // Get product categories for this brand
        $brandCategories = $this->getBrandCategoriesFromDatabase($brand['id']);
        
        $data = [
            'title' => $brand['name'] . ' Products - Modern Zone Trading | Authorized Distributor',
            'description' => 'Shop ' . $brand['name'] . ' industrial tools at Modern Zone Trading. Authorized distributor in Saudi Arabia offering genuine products with warranty.',
            'brand' => $brand,
            'products' => $products,
            'categories' => $this->getCategoriesFromDatabase(),
            'brand_categories' => $brandCategories,
        ];
        
        View::render('pages/brands/detail', $data);
    }
    
    public function category($slug)
    {
        $db = Database::getInstance();
        
        // Get category from database
        $category = $db->selectOne(
            "SELECT * FROM categories WHERE slug = :slug AND status = 'active'",
            ['slug' => $slug]
        );
        
        // If category not found, show 404
        if (!$category) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }
        
        // Get products for this category
        $products = $this->getProductsFromDatabase($slug, null);
        
        $data = [
            'title' => $category['name'] . ' - Modern Zone Trading | Industrial Tools',
            'description' => ($category['description'] ?? '') . ' Available at Modern Zone Trading, Saudi Arabia.',
            'category' => $category,
            'products' => $products,
            'categories' => $this->getCategoriesFromDatabase(),
        ];
        
        View::render('pages/categories/detail', $data);
    }
    
    
    private function getProductsByBrandFromDatabase($brandId)
    {
        $db = Database::getInstance();
        
        // Get all products for this brand
        $products = $db->select("
            SELECT p.*, 
                   c.name as category_name,
                   c.slug as category_slug
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.brand_id = :brand_id 
            AND p.status = 'active'
            ORDER BY c.name, p.name
        ", ['brand_id' => $brandId]);
        
        // Format products for display
        return array_map(function($product) {
            // Handle image path
            $imagePath = '';
            if (!empty($product['image'])) {
                if (strpos($product['image'], 'public/') === 0) {
                    $imagePath = BASE_URL . '/' . $product['image'];
                } else {
                    $imagePath = View::asset('images/products/' . $product['image']);
                }
            } else {
                $imagePath = View::asset('images/placeholder.svg');
            }
            
            return [
                'id' => $product['id'],
                'title' => $product['name'],
                'slug' => $product['slug'],
                'image' => $imagePath,
                'price' => $product['price'],
                'category' => $product['category_slug'] ?? '',
                'category_name' => $product['category_name'] ?? '',
                'description' => $product['description'] ?? '',
                'sku' => $product['sku'],
                'stock' => $product['quantity'] > 0
            ];
        }, $products);
    }
    
    private function getBrandCategoriesFromDatabase($brandId)
    {
        $db = Database::getInstance();
        
        // Get categories that have products for this brand
        $categories = $db->select("
            SELECT c.id, c.name, c.slug, COUNT(p.id) as product_count
            FROM categories c
            INNER JOIN products p ON c.id = p.category_id
            WHERE p.brand_id = :brand_id 
            AND p.status = 'active'
            AND c.status = 'active'
            GROUP BY c.id, c.name, c.slug
            ORDER BY c.name
        ", ['brand_id' => $brandId]);
        
        return $categories;
    }
    
    public function show($slug)
    {
        $product = $this->getProductBySlug($slug);
        
        if (!$product) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }
        
        $data = [
            'title' => $product['title'] . ' - Modern Zone Trading | Industrial Tools',
            'description' => $product['description'] . ' Available at Modern Zone Trading, Saudi Arabia.',
            'product' => $product,
            'related_products' => $this->getRelatedProducts($product['id']),
        ];
        
        View::render('pages/products/detail', $data);
    }
    
    private function getProductsFromDatabase($category = null, $search = null)
    {
        $db = Database::getInstance();
        
        // Build query
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       c.slug as category_slug,
                       b.name as brand_name,
                       p.image as image
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.status = 'active'";
        
        $params = [];
        
        // Add category filter
        if ($category) {
            $sql .= " AND c.slug = :category";
            $params['category'] = $category;
        }
        
        // Add search filter
        if ($search) {
            $sql .= " AND (p.name LIKE :search OR p.description LIKE :search OR p.sku LIKE :search_sku)";
            $params['search'] = "%{$search}%";
            $params['search_sku'] = $search;
        }
        
        $sql .= " ORDER BY p.featured DESC, p.best_seller DESC, p.created_at DESC";
        
        $products = $db->select($sql, $params);
        
        // Format products for display (same as HomePage)
        return array_map(function($product) {
            // Handle image path
            $imagePath = '';
            if (!empty($product['image'])) {
                if (strpos($product['image'], 'public/') === 0) {
                    $imagePath = BASE_URL . '/' . $product['image'];
                } else {
                    $imagePath = View::asset('images/products/' . $product['image']);
                }
            } else {
                $imagePath = View::asset('images/placeholder.svg');
            }
            
            return [
                'id' => $product['id'],
                'title' => $product['name'],
                'slug' => $product['slug'],
                'image' => $imagePath,
                'price' => $product['price'],
                'category' => $product['category_slug'] ?? '',
                'category_name' => $product['category_name'] ?? '',
                'brand_name' => $product['brand_name'] ?? '',
                'description' => $product['description'] ?? '',
                'featured' => $product['featured'],
                'best_seller' => $product['best_seller'],
                'sku' => $product['sku']
            ];
        }, $products);
    }
    
    private function getProductBySlug($slug)
    {
        $products = $this->getProductsFromDatabase();
        
        foreach ($products as $product) {
            if ($product['slug'] === $slug) {
                // Add detailed description and specs based on product type
                $product['full_description'] = 'This is a high-quality professional tool designed for demanding industrial applications. Manufactured with premium materials and engineered for maximum durability, it delivers consistent performance even in the toughest working conditions. Perfect for professional workshops, manufacturing facilities, and serious DIY enthusiasts who demand the best.';
                
                // Category-specific specifications
                if (strpos($slug, 'drill') !== false || strpos($slug, 'chuck') !== false) {
                    $product['specifications'] = [
                        'Brand' => 'Professional Grade',
                        'Material' => 'High-Speed Steel / Hardened Steel',
                        'Coating' => 'Titanium Nitride (TiN)',
                        'Precision Grade' => 'DIN 338',
                        'Warranty' => '2 Years',
                        'Origin' => 'Germany',
                        'Package' => 'Storage Case Included',
                    ];
                } elseif (strpos($slug, 'carbide') !== false || strpos($slug, 'end-mill') !== false) {
                    $product['specifications'] = [
                        'Brand' => 'Professional Grade',
                        'Material' => 'Solid Carbide',
                        'Coating' => 'TiAlN (Titanium Aluminum Nitride)',
                        'Helix Angle' => '35-40°',
                        'Hardness' => 'HRC 92-95',
                        'Warranty' => '2 Years',
                        'Origin' => 'Germany',
                    ];
                } elseif (strpos($slug, 'burr') !== false) {
                    $product['specifications'] = [
                        'Brand' => 'Professional Grade',
                        'Material' => 'Tungsten Carbide',
                        'Shank Diameter' => '6mm',
                        'Max RPM' => '35,000',
                        'Application' => 'Metal, Steel, Aluminum',
                        'Warranty' => '2 Years',
                        'Origin' => 'USA',
                    ];
                } elseif (strpos($slug, 'blade') !== false || strpos($slug, 'saw') !== false) {
                    $product['specifications'] = [
                        'Brand' => 'Professional Grade',
                        'Material' => 'Bi-Metal (M42)',
                        'TPI' => '10-14 Variable',
                        'Width' => '27mm',
                        'Length' => '2725mm',
                        'Warranty' => '1 Year',
                        'Origin' => 'Sweden',
                    ];
                } else {
                    $product['specifications'] = [
                        'Brand' => 'Professional Grade',
                        'Material' => 'Premium Grade Steel',
                        'Hardness' => 'HRC 58-62',
                        'Precision' => 'ISO 2768-m',
                        'Warranty' => '2 Years',
                        'Origin' => 'Germany',
                        'Certification' => 'ISO 9001',
                    ];
                }
                
                // Fetch product gallery images from product_images table
                $db = Database::getInstance();
                $galleryImages = $db->select(
                    "SELECT image_path FROM product_images 
                     WHERE product_id = :product_id 
                     ORDER BY is_primary DESC, sort_order ASC",
                    ['product_id' => $product['id']]
                );
                
                // Build images array
                $product['images'] = [];
                
                // Add main image first
                if (!empty($product['image'])) {
                    $product['images'][] = $product['image'];
                }
                
                // Add gallery images
                foreach ($galleryImages as $img) {
                    $imagePath = '';
                    if (strpos($img['image_path'], 'public/') === 0) {
                        $imagePath = BASE_URL . '/' . $img['image_path'];
                    } else {
                        $imagePath = View::asset('images/products/' . $img['image_path']);
                    }
                    $product['images'][] = $imagePath;
                }
                
                // If no images at all, use placeholder
                if (empty($product['images'])) {
                    $product['images'][] = View::asset('images/placeholder.svg');
                }
                
                return $product;
            }
        }
        
        return null;
    }
    
    private function getRelatedProducts($productId)
    {
        $products = $this->getProductsFromDatabase();
        return array_filter($products, function($p) use ($productId) {
            return $p['id'] !== $productId;
        });
    }
    
    private function getCategoriesFromDatabase()
    {
        $db = Database::getInstance();
        
        // Get categories with product counts
        $categories = $db->select("
            SELECT c.*, COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active'
            WHERE c.status = 'active'
            GROUP BY c.id
            ORDER BY c.sort_order, c.name
        ");
        
        return array_map(function($category) {
            return [
                'slug' => $category['slug'],
                'name' => $category['name'],
                'count' => $category['product_count']
            ];
        }, $categories);
    }
}
