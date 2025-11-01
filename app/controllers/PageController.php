<?php
/**
 * Static Pages Controller
 */
class PageController
{
    public function about()
    {
        $data = [
            'title' => 'About Us - Modern Zone Trading | Industrial Tools Supplier in Saudi Arabia',
            'description' => 'Learn about Modern Zone Trading, a leading industrial tools supplier in Saudi Arabia. We specialize in cutting tools, CNC machine holders, measuring tools, and comprehensive industrial solutions.',
        ];
        
        View::render('pages/about', $data);
    }
    
    public function privacy()
    {
        $data = [
            'title' => 'Privacy Policy - Modern Zone Trading',
            'description' => 'Read Modern Zone Trading\'s privacy policy to understand how we collect, use, and protect your personal information.',
        ];
        
        View::render('pages/privacy', $data);
    }
    
    public function terms()
    {
        $data = [
            'title' => 'Terms & Conditions - Modern Zone Trading',
            'description' => 'Review the terms and conditions for using Modern Zone Trading\'s website and purchasing our industrial tools and equipment.',
        ];
        
        View::render('pages/terms', $data);
    }
}
