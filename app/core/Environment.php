<?php
/**
 * Environment Configuration Loader
 * 
 * Loads environment variables from .env file
 */
class Environment
{
    /**
     * Load environment variables from .env file
     */
    public static function load($path)
    {
        $envFile = $path . '/.env';
        
        if (!file_exists($envFile)) {
            // Try .env.example as fallback
            $envFile = $path . '/.env.example';
            if (!file_exists($envFile)) {
                // Log warning but don't fail
                error_log("Warning: No .env file found at {$path}");
                return;
            }
        }
        
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if ($lines === false) {
            error_log("Warning: Could not read .env file at {$envFile}");
            return;
        }
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse key=value pairs
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes from value
                $value = trim($value, '"\'');
                
                // Set environment variable (always override for consistency)
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
    
    /**
     * Get environment variable
     */
    public static function get($key, $default = null)
    {
        // Try multiple sources
        $value = false;
        
        // Try $_ENV first
        if (isset($_ENV[$key])) {
            $value = $_ENV[$key];
        }
        // Then $_SERVER
        elseif (isset($_SERVER[$key])) {
            $value = $_SERVER[$key];
        }
        // Finally getenv
        else {
            $value = getenv($key);
        }
        
        if ($value === false || $value === '') {
            return $default;
        }
        
        // Convert string boolean values
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
            case 'empty':
            case '(empty)':
                return '';
        }
        
        return $value;
    }
}
