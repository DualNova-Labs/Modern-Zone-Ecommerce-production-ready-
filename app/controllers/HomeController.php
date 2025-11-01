<?php
/**
 * Home Controller
 */
class HomeController
{
    public function index()
    {
        $data = [
            'title' => 'Modern Zone Trading - Leading Industrial Tools Supplier in Saudi Arabia',
            'description' => 'Modern Zone Trading offers cutting tools, CNC machine holders, measuring tools, power tools and industrial equipment in Saudi Arabia. Authorized distributor of Dormer, Sandvik Coromant, Seco, Pramet, Kyocera.',
            'featured_products' => $this->getFeaturedProducts(),
            'best_selling' => $this->getBestSellingProducts(),
            'categories' => $this->getCategories(),
            'testimonials' => $this->getTestimonials(),
            'brands' => $this->getBrands(),
        ];
        
        View::render('pages/home', $data);
    }
    
    private function getFeaturedProducts()
    {
        // Mock data - replace with database query
        return [
            [
                'id' => 1,
                'title' => 'HSS Drill Bits Set',
                'slug' => 'hss-drill-bits-set',
                'image' => View::asset('images/products/hss-drill-bits.png'),
                'price' => 189.00,
                'description' => 'Professional 25-piece HSS drill bit set for metal and steel',
            ],
            [
                'id' => 2,
                'title' => 'Conical Drills',
                'slug' => 'conical-drills',
                'image' => View::asset('images/products/conical-drills.png'),
                'price' => 125.00,
                'description' => 'High-quality step drill bits for precise hole enlargement',
            ],
            [
                'id' => 3,
                'title' => 'Key Type Drill Chuck',
                'slug' => 'key-drill-chuck',
                'image' => View::asset('images/products/key-drill-chuck.png'),
                'price' => 145.00,
                'description' => 'Heavy-duty keyed drill chuck for maximum grip and precision',
            ],
            [
                'id' => 4,
                'title' => 'Keyless Drill Chuck',
                'slug' => 'keyless-drill-chuck',
                'image' => View::asset('images/products/keyless-drill-chuck.png'),
                'price' => 185.00,
                'description' => 'Quick-change keyless drill chuck for fast bit changes',
            ],
        ];
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
        return [
            [
                'id' => 11,
                'title' => 'Carbide End Mills Set',
                'slug' => 'carbide-end-mills-set',
                'image' => View::asset('images/products/carbide-end-mills.png'),
                'price' => 425.00,
                'original_price' => null,
                'discount' => null,
                'description' => 'Professional carbide end mills for precision milling',
            ],
            [
                'id' => 12,
                'title' => 'Tungsten Carbide Rotary Burrs',
                'slug' => 'tungsten-carbide-rotary-burrs',
                'image' => View::asset('images/products/rotary-burrs.png'),
                'price' => 215.00,
                'original_price' => 280.00,
                'discount' => 23,
                'description' => 'High-speed rotary burr set for metal working',
            ],
            [
                'id' => 13,
                'title' => 'Band Saw Blades',
                'slug' => 'band-saw-blades',
                'image' => View::asset('images/products/bandsaw-blades.png'),
                'price' => 165.00,
                'original_price' => null,
                'discount' => null,
                'description' => 'Premium quality band saw blades for various materials',
            ],
            [
                'id' => 14,
                'title' => 'Step Drill Bits',
                'slug' => 'step-drill-bits',
                'image' => View::asset('images/products/step-drills.png'),
                'price' => 195.00,
                'original_price' => 245.00,
                'discount' => 20,
                'description' => 'Multi-size step drill bits for sheet metal',
            ],
            [
                'id' => 15,
                'title' => 'Brazed Tool Set',
                'slug' => 'brazed-tool-set',
                'image' => View::asset('images/products/brazed-tools.png'),
                'price' => 385.00,
                'original_price' => null,
                'discount' => null,
                'description' => 'Professional brazed cutting tools for turning',
            ],
            [
                'id' => 16,
                'title' => 'Precision Bushes',
                'slug' => 'precision-bushes',
                'image' => View::asset('images/products/precision-bushes.png'),
                'price' => 95.00,
                'original_price' => 135.00,
                'discount' => 30,
                'description' => 'High-precision guide bushes for dies and molds',
            ],
        ];
    }
    
    private function getTestimonials()
    {
        return [
            [
                'name' => 'Mohammed Al-Rashid',
                'company' => 'Al-Rashid Manufacturing',
                'text' => 'Modern Zone Trading has been our trusted supplier for years. Their quality industrial tools and service are unmatched.',
                'rating' => 5,
            ],
            [
                'name' => 'Fahad Al-Otaibi',
                'company' => 'Saudi Industrial Solutions',
                'text' => 'Excellent product range and fast delivery. The cutting tools are durable and perform exceptionally well.',
                'rating' => 5,
            ],
            [
                'name' => 'Ahmed Hassan',
                'company' => 'Hassan CNC Workshop',
                'text' => 'Professional service and high-quality tools. Modern Zone Trading is our go-to supplier for all CNC tooling needs.',
                'rating' => 5,
            ],
        ];
    }
    
    private function getBrands()
    {
        $brandsFile = __DIR__ . '/../data/brands.json';
        if (file_exists($brandsFile)) {
            $brandsData = json_decode(file_get_contents($brandsFile), true);
            return $brandsData['brands'] ?? [];
        }
        return [];
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
