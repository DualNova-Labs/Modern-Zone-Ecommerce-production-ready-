<?php
/**
 * Deya PHP View Engine
 */
require_once APP_PATH . '/core/Security.php';
require_once APP_PATH . '/core/Auth.php';

class View
{
    public static function render($view, $data = [])
    {
        // Add auth and security helpers to all views
        $data['auth'] = Auth::getInstance();
        $data['security'] = Security::getInstance();
        
        extract($data);
        
        $viewFile = VIEWS_PATH . '/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewFile)) {
            ob_start();
            include $viewFile;
            $content = ob_get_clean();
            echo $content;
        } else {
            throw new Exception("View not found: {$view}");
        }
    }
    
    public static function component($component, $data = [])
    {
        extract($data);
        
        $componentFile = VIEWS_PATH . '/components/' . $component . '.php';
        
        if (file_exists($componentFile)) {
            include $componentFile;
        } else {
            throw new Exception("Component not found: {$component}");
        }
    }
    
    public static function asset($path)
    {
        return BASE_URL . '/public/assets/' . ltrim($path, '/');
    }
    
    public static function url($path = '')
    {
        return BASE_URL . '/' . ltrim($path, '/');
    }
    
    /**
     * Generate CSRF token field
     */
    public static function csrfField()
    {
        $security = Security::getInstance();
        return $security->csrfField();
    }
    
    /**
     * Generate CSRF meta tag
     */
    public static function csrfMeta()
    {
        $security = Security::getInstance();
        return $security->csrfMetaTag();
    }
    
    /**
     * Check if user is authenticated
     */
    public static function isAuth()
    {
        $auth = Auth::getInstance();
        return $auth->check();
    }
    
    /**
     * Get authenticated user
     */
    public static function user()
    {
        $auth = Auth::getInstance();
        return $auth->user();
    }
}
