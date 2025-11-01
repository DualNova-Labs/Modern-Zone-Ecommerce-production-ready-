<?php
/**
 * Our Products Controller
 * Handles the "Our Products" section with categories loaded from JSON
 */
class OurProductsController
{
    public function index()
    {
        $productsData = $this->getProductsData();
        
        $data = [
            'title' => 'Our Products - Modern Zone Trading | Industrial Tools & Equipment',
            'description' => 'Explore our comprehensive range of industrial tools and equipment including cutting tools, measuring instruments, standard parts for dies & molds, and machine accessories.',
            'products' => $productsData['products'] ?? [],
            'categories' => $this->getProductCategories($productsData['products'] ?? []),
        ];
        
        View::render('pages/our-products/index', $data);
    }
    
    public function category($slug)
    {
        $productsData = $this->getProductsData();
        $product = null;
        
        // Find the specific product category
        foreach ($productsData['products'] as $p) {
            if ($p['slug'] === $slug) {
                $product = $p;
                break;
            }
        }
        
        // If product category not found, show 404
        if (!$product) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }
        
        $data = [
            'title' => $product['name'] . ' - Modern Zone Trading | Industrial Tools',
            'description' => $product['description'],
            'product' => $product,
            'relatedProducts' => $this->getRelatedProducts($product['category'], $slug, $productsData['products'] ?? []),
        ];
        
        View::render('pages/our-products/detail', $data);
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
    
    private function getProductCategories($products)
    {
        $categories = [];
        foreach ($products as $product) {
            $category = $product['category'];
            if (!isset($categories[$category])) {
                $categories[$category] = [
                    'name' => $category,
                    'count' => 0,
                ];
            }
            $categories[$category]['count']++;
        }
        return array_values($categories);
    }
    
    private function getRelatedProducts($category, $currentSlug, $products)
    {
        $related = [];
        foreach ($products as $product) {
            if ($product['category'] === $category && $product['slug'] !== $currentSlug) {
                $related[] = $product;
            }
        }
        return array_slice($related, 0, 3); // Return max 3 related products
    }
}
