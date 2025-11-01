<?php
/**
 * Application Configuration
 */

return [
    'app' => [
        'name' => 'APTools',
        'url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . BASE_URL,
        'version' => '1.0.0',
    ],
    
    'paths' => [
        'assets' => '/public/assets',
    ],
    
    'brand' => [
        'primary_color' => '#e74c3c',
        'secondary_color' => '#2c3e50',
        'accent_color' => '#3498db',
    ],
    
    'contact' => [
        'email' => 'info@modernzonetrading.com',
        'email_secondary' => 'dormer.sa@gmail.com',
        'phone' => '012 6811 391',
        'address' => 'Abdul Rahman I. Modern Zone Br. Est, Old Makkah Road, Kilo-01, Bab Makkah, Jeddah, Saudi Arabia',
        'working_hours' => 'Sunday - Thursday: 9:00 AM - 6:00 PM',
    ],
    
    'social' => [
        'facebook' => '#',
        'twitter' => '#',
        'instagram' => '#',
        'linkedin' => '#',
    ],
];
