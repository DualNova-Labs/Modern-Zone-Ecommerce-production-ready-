<?php
/**
 * Product Controller
 */
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
        
        // Get all products
        $allProducts = $this->getProducts($category, $search);
        
        // If no products found with category filter and category was specified, show all products
        if (empty($allProducts) && $category) {
            $allProducts = $this->getProducts(null, $search);
        }
        
        $data = [
            'title' => 'Industrial Tools & Equipment - Modern Zone Trading',
            'description' => 'Browse our extensive range of industrial tools including cutting tools, CNC machine holders, measuring instruments, power tools and accessories from leading brands.',
            'products' => $allProducts,
            'categories' => $this->getCategories(),
            'currentPage' => $page,
            'totalPages' => max(1, ceil(count($allProducts) / $perPage)),
            'currentCategory' => $category,
            'searchQuery' => $search,
        ];
        
        View::render('pages/products/index', $data);
    }
    
    public function brand($slug)
    {
        // Load brands data
        $brandsData = $this->getBrandsData();
        $brand = null;
        
        // Find the specific brand
        foreach ($brandsData['brands'] as $b) {
            if ($b['slug'] === $slug) {
                $brand = $b;
                break;
            }
        }
        
        // If brand not found, show 404
        if (!$brand) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }
        
        // Get products for this brand
        $products = $this->getProductsByBrand($slug);
        
        $data = [
            'title' => $brand['name'] . ' Products - Modern Zone Trading | Authorized Distributor',
            'description' => 'Shop ' . $brand['name'] . ' industrial tools at Modern Zone Trading. Authorized distributor in Saudi Arabia offering genuine products with warranty.',
            'brand' => $brand,
            'products' => $products,
            'categories' => $this->getCategories(),
        ];
        
        View::render('pages/brands/detail', $data);
    }
    
    public function category($slug)
    {
        // Load category data from JSON
        $categoryData = $this->getCategoryData($slug);
        
        // If category not found, show 404
        if (!$categoryData) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }
        
        $data = [
            'title' => $categoryData['name'] . ' - Modern Zone Trading | Industrial Tools',
            'description' => $categoryData['description'] . ' Available at Modern Zone Trading, Saudi Arabia.',
            'category' => $categoryData,
            'products' => $categoryData['products'],
            'categories' => $this->getCategories(),
        ];
        
        View::render('pages/categories/detail', $data);
    }
    
    private function getBrandsData()
    {
        $jsonPath = __DIR__ . '/../data/brands.json';
        if (!file_exists($jsonPath)) {
            return ['brands' => []];
        }
        
        $jsonContent = file_get_contents($jsonPath);
        return json_decode($jsonContent, true);
    }
    
    private function getProductsData()
    {
        $jsonPath = __DIR__ . '/../data/products.json';
        if (!file_exists($jsonPath)) {
            return ['products' => []];
        }
        
        $jsonContent = file_get_contents($jsonPath);
        return json_decode($jsonContent, true);
    }
    
    private function getCategoryData($categorySlug)
    {
        // Load category data from JSON file
        $jsonPath = __DIR__ . '/../data/categories/' . $categorySlug . '.json';
        
        if (!file_exists($jsonPath)) {
            return null;
        }
        
        $jsonContent = file_get_contents($jsonPath);
        return json_decode($jsonContent, true);
    }
    
    private function getProductsByBrand($brandSlug)
    {
        // Load products from JSON file for this brand
        $jsonPath = __DIR__ . '/../data/products/' . $brandSlug . '.json';
        
        if (!file_exists($jsonPath)) {
            // If brand-specific file doesn't exist, return empty array
            return [];
        }
        
        $jsonContent = file_get_contents($jsonPath);
        $brandData = json_decode($jsonContent, true);
        
        if (!$brandData) {
            return [];
        }
        
        // Check if brand has categories (major brands) or direct products (simple brands)
        if (isset($brandData['categories'])) {
            // Flatten all products from all categories
            $products = [];
            foreach ($brandData['categories'] as $category => $categoryProducts) {
                $products = array_merge($products, $categoryProducts);
            }
            return $products;
        } elseif (isset($brandData['products'])) {
            // Return products directly for simple brands
            return $brandData['products'];
        }
        
        return [];
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
    
    private function getProducts($category = null, $search = null)
    {
        // Mock data - replace with database query
        $products = [
            // Featured Products
            [
                'id' => 1,
                'title' => 'HSS Drill Bits Set',
                'slug' => 'hss-drill-bits-set',
                'image' => View::asset('images/products/hss-drill-bits.png'),
                'price' => 189.00,
                'category' => 'drill-bits',
                'description' => 'Professional 25-piece HSS drill bit set for metal and steel',
            ],
            [
                'id' => 2,
                'title' => 'Conical Drills',
                'slug' => 'conical-drills',
                'image' => View::asset('images/products/conical-drills.png'),
                'price' => 125.00,
                'category' => 'drill-bits',
                'description' => 'High-quality step drill bits for precise hole enlargement',
            ],
            [
                'id' => 3,
                'title' => 'Key Type Drill Chuck',
                'slug' => 'key-drill-chuck',
                'image' => View::asset('images/products/key-drill-chuck.png'),
                'price' => 145.00,
                'category' => 'drill-chucks',
                'description' => 'Heavy-duty keyed drill chuck for maximum grip and precision',
            ],
            [
                'id' => 4,
                'title' => 'Keyless Drill Chuck',
                'slug' => 'keyless-drill-chuck',
                'image' => View::asset('images/products/keyless-drill-chuck.png'),
                'price' => 185.00,
                'category' => 'drill-chucks',
                'description' => 'Quick-change keyless drill chuck for fast bit changes',
            ],
            // Best Selling Products
            [
                'id' => 11,
                'title' => 'Carbide End Mills Set',
                'slug' => 'carbide-end-mills-set',
                'image' => View::asset('images/products/carbide-end-mills.png'),
                'price' => 425.00,
                'category' => 'end-mills',
                'description' => 'Professional carbide end mills for precision milling operations',
            ],
            [
                'id' => 12,
                'title' => 'Tungsten Carbide Rotary Burrs',
                'slug' => 'tungsten-carbide-rotary-burrs',
                'image' => View::asset('images/products/rotary-burrs.png'),
                'price' => 215.00,
                'category' => 'rotary-burrs',
                'description' => 'High-speed rotary burr set for metal working and shaping',
            ],
            [
                'id' => 13,
                'title' => 'Band Saw Blades',
                'slug' => 'band-saw-blades',
                'image' => View::asset('images/products/bandsaw-blades.png'),
                'price' => 165.00,
                'category' => 'blades',
                'description' => 'Premium quality band saw blades for cutting various materials',
            ],
            [
                'id' => 14,
                'title' => 'Step Drill Bits',
                'slug' => 'step-drill-bits',
                'image' => View::asset('images/products/step-drills.png'),
                'price' => 195.00,
                'category' => 'drill-bits',
                'description' => 'Multi-size step drill bits perfect for sheet metal work',
            ],
            [
                'id' => 15,
                'title' => 'Brazed Tool Set',
                'slug' => 'brazed-tool-set',
                'image' => View::asset('images/products/brazed-tools.png'),
                'price' => 385.00,
                'category' => 'brazed-tools',
                'description' => 'Professional brazed cutting tools for turning operations',
            ],
            [
                'id' => 16,
                'title' => 'Precision Bushes',
                'slug' => 'precision-bushes',
                'image' => View::asset('images/products/precision-bushes.png'),
                'price' => 95.00,
                'category' => 'bushes',
                'description' => 'High-precision guide bushes for dies and molds',
            ],
        ];
        
        // Filter by category
        if ($category) {
            $products = array_filter($products, function($p) use ($category) {
                return $p['category'] === $category;
            });
        }
        
        // Filter by search
        if ($search) {
            $products = array_filter($products, function($p) use ($search) {
                return stripos($p['title'], $search) !== false || 
                       stripos($p['description'], $search) !== false;
            });
        }
        
        return array_values($products);
    }
    
    private function getProductBySlug($slug)
    {
        $products = $this->getProducts();
        
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
                
                $product['images'] = [
                    $product['image'],
                    $product['image'],
                    $product['image'],
                ];
                return $product;
            }
        }
        
        return null;
    }
    
    private function getRelatedProducts($productId)
    {
        $products = $this->getProducts();
        return array_filter($products, function($p) use ($productId) {
            return $p['id'] !== $productId;
        });
    }
    
    private function getCategories()
    {
        return [
            ['slug' => 'drill-bits', 'name' => 'Drill Bits', 'count' => 3],
            ['slug' => 'drill-chucks', 'name' => 'Drill Chucks', 'count' => 2],
            ['slug' => 'end-mills', 'name' => 'End Mills', 'count' => 1],
            ['slug' => 'rotary-burrs', 'name' => 'Rotary Burrs', 'count' => 1],
            ['slug' => 'blades', 'name' => 'Blades & Saws', 'count' => 1],
            ['slug' => 'brazed-tools', 'name' => 'Brazed Tools', 'count' => 1],
            ['slug' => 'bushes', 'name' => 'Bushes', 'count' => 1],
        ];
    }
}
