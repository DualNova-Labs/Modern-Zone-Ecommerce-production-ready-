<?php
/**
 * HTTP Request Helper
 */
class Request
{
    public static function get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
    
    public static function post($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
    
    public static function all()
    {
        return array_merge($_GET, $_POST);
    }
    
    public static function has($key)
    {
        return isset($_GET[$key]) || isset($_POST[$key]);
    }
    
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    public static function isPost()
    {
        return self::method() === 'POST';
    }
    
    public static function isGet()
    {
        return self::method() === 'GET';
    }
}
