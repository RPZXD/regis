<?php
/**
 * Simple Router Class
 * Handles URL routing to controllers
 */
class Router {
    private static $routes = [];
    
    /**
     * Register a GET route
     */
    public static function get($path, $callback) {
        self::$routes['GET'][$path] = $callback;
    }
    
    /**
     * Register a POST route
     */
    public static function post($path, $callback) {
        self::$routes['POST'][$path] = $callback;
    }
    
    /**
     * Dispatch the current request
     */
    public static function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path if needed
        $basePath = '/regis/';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath) - 1);
        }
        
        // Default to index if root
        if ($uri === '/' || $uri === '') {
            $uri = '/index';
        }
        
        // Check for exact match
        if (isset(self::$routes[$method][$uri])) {
            return self::call(self::$routes[$method][$uri]);
        }
        
        // Check for pattern match with parameters
        foreach (self::$routes[$method] ?? [] as $route => $callback) {
            $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([^/]+)', $route);
            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                array_shift($matches);
                return self::call($callback, $matches);
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        echo "404 - Page Not Found";
    }
    
    /**
     * Call the callback function or controller method
     */
    private static function call($callback, $params = []) {
        if (is_string($callback)) {
            // Controller@method format
            list($controller, $method) = explode('@', $callback);
            require_once __DIR__ . '/../controllers/' . $controller . '.php';
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }
        
        // Closure callback
        return call_user_func_array($callback, $params);
    }
}
