<?php
/**
 * Deya PHP Router
 */
class Router
{
    private $routes = [];
    
    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }
    
    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }
    
    private function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
        ];
    }
    
    public function dispatch($uri, $method)
    {
        // Remove base path if exists
        $basePath = BASE_URL;
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        if (empty($uri)) {
            $uri = '/';
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $pattern = $this->convertToRegex($route['path']);
                
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    
                    if (is_callable($route['callback'])) {
                        call_user_func_array($route['callback'], $matches);
                        return;
                    }
                    
                    if (is_string($route['callback'])) {
                        $this->callController($route['callback'], $matches);
                        return;
                    }
                }
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        View::render('errors/404');
    }
    
    private function convertToRegex($path)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    private function callController($callback, $params)
    {
        list($controller, $method) = explode('@', $callback);
        
        // Handle subdirectory controllers (e.g., admin/AdminDashboardController)
        $controllerPath = $controller;
        $controllerClass = $controller;
        
        if (strpos($controller, '/') !== false) {
            // Extract the class name from the path
            $parts = explode('/', $controller);
            $controllerClass = end($parts);
            $controllerPath = $controller;
        }
        
        $controllerFile = APP_PATH . '/controllers/' . $controllerPath . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                if (method_exists($controllerInstance, $method)) {
                    call_user_func_array([$controllerInstance, $method], $params);
                    return;
                }
            }
        }
        
        throw new Exception("Controller or method not found: {$callback}");
    }
}
