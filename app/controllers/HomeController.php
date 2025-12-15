<?php
/**
 * Home Controller
 */
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/models/Banner.php';
require_once APP_PATH . '/models/Product.php';

class HomeController
{
    private $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new Banner();
    }

    public function index()
    {
        $data = [
            'title' => 'Modern Zone Trading - Leading Industrial Tools Supplier in Saudi Arabia',
            'description' => 'Modern Zone Trading offers cutting tools, CNC machine holders, measuring tools, power tools and industrial equipment in Saudi Arabia. Authorized distributor of Dormer, Sandvik Coromant, Seco, Pramet, Kyocera.',
            'banners' => $this->bannerModel->getActiveBanners(),
            'featured_products' => $this->getFeaturedProducts(),
            'best_selling' => $this->getBestSellingProducts(),
            'categories' => $this->getCategories(),
            'brands' => $this->getBrands(),
        ];

        View::render('pages/home', $data);
    }

    private function getFeaturedProducts()
    {
        $products = Product::getFeaturedProducts(8);

        // Format products for display
        return array_map(function ($product) {
            // Handle image path - if it starts with 'public/', use BASE_URL, otherwise use View::asset
            $imagePath = '';
            if (!empty($product['image'])) {
                if (strpos($product['image'], 'public/') === 0) {
                    // Image path already includes 'public/', so just add BASE_URL
                    $imagePath = BASE_URL . '/' . $product['image'];
                } else {
                    // Legacy path, use View::asset
                    $imagePath = View::asset('images/products/' . $product['image']);
                }
            } else {
                // No image, use placeholder
                $imagePath = View::asset('images/placeholder.svg');
            }

            return [
                'id' => $product['id'],
                'title' => $product['name'],
                'slug' => $product['slug'],
                'image' => $imagePath,
                'price' => $product['price'],
                'description' => $product['description'] ?? '',
                'category' => $product['category_name'] ?? '',
                'brand' => $product['brand_name'] ?? ''
            ];
        }, $products);
    }

    private function getCategories()
    {
        return [
            [
                'icon' => 'cut',
                'title' => 'Cutting Tools',
                'description' => 'Precision cutting tools for all applications',
                'link' => View::url('products?category=cutting-tools'),
                'image' => View::asset('images/categories/cutting-tools.jpg'),
            ],
            [
                'icon' => 'circle-notch',
                'title' => 'Abrasives',
                'description' => 'High-quality abrasive products',
                'link' => View::url('products?category=abrasives'),
                'image' => View::asset('images/categories/abrasives.jpg'),
            ],
            [
                'icon' => 'tools',
                'title' => 'Blades and Cutters',
                'description' => 'Professional blades and cutting solutions',
                'link' => View::url('products?category=blades-cutters'),
                'image' => View::asset('images/categories/blades-cutters.jpg'),
            ],
            [
                'icon' => 'hammer',
                'title' => 'Hardware and Power tools',
                'description' => 'Complete range of hardware and power tools',
                'link' => View::url('products?category=hardware-power-tools'),
                'image' => View::asset('images/categories/hardware-power-tools.jpg'),
            ],
        ];
    }

    private function getBestSellingProducts()
    {
        $products = Product::getBestSellingProducts(8);

        // Format products for display
        return array_map(function ($product) {
            $originalPrice = !empty($product['compare_price']) && $product['compare_price'] > $product['price']
                ? $product['compare_price']
                : null;

            $discount = $originalPrice
                ? round((($originalPrice - $product['price']) / $originalPrice) * 100)
                : null;

            // Handle image path - if it starts with 'public/', use BASE_URL, otherwise use View::asset
            $imagePath = '';
            if (!empty($product['image'])) {
                if (strpos($product['image'], 'public/') === 0) {
                    // Image path already includes 'public/', so just add BASE_URL
                    $imagePath = BASE_URL . '/' . $product['image'];
                } else {
                    // Legacy path, use View::asset
                    $imagePath = View::asset('images/products/' . $product['image']);
                }
            } else {
                // No image, use placeholder
                $imagePath = View::asset('images/placeholder.svg');
            }

            return [
                'id' => $product['id'],
                'title' => $product['name'],
                'slug' => $product['slug'],
                'image' => $imagePath,
                'price' => $product['price'],
                'original_price' => $originalPrice,
                'discount' => $discount,
                'description' => $product['description'] ?? '',
                'category' => $product['category_name'] ?? '',
                'brand' => $product['brand_name'] ?? ''
            ];
        }, $products);
    }

    private function getBrands()
    {
        $db = Database::getInstance();
        $brands = $db->select(
            "SELECT id, name, slug, logo, description 
             FROM brands 
             WHERE status = 'active' 
             ORDER BY sort_order ASC, name ASC"
        );

        // Format brands for display
        return array_map(function ($brand) {
            // Handle logo path
            $logoPath = '';
            if (!empty($brand['logo'])) {
                if (strpos($brand['logo'], 'public/') === 0) {
                    $logoPath = BASE_URL . '/' . $brand['logo'];
                } else {
                    $logoPath = View::asset('images/brands/' . $brand['logo']);
                }
            } else {
                $logoPath = View::asset('images/placeholder.svg');
            }

            return [
                'id' => $brand['id'],
                'name' => $brand['name'],
                'slug' => $brand['slug'],
                'logo' => $logoPath,
                'description' => $brand['description'] ?? ''
            ];
        }, $brands);
    }

    private function getProducts()
    {
        $productsFile = __DIR__ . '/../data/products.json';
        if (file_exists($productsFile)) {
            $productsData = json_decode(file_get_contents($productsFile), true);
            return $productsData['products'] ?? [];
        }
        return [];
    }
}
