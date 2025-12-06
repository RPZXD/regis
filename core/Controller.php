<?php
/**
 * Base Controller Class
 * All controllers should extend this class
 */
class Controller {
    protected $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/Database.php';
        $database = new Database_Regis();
        $this->db = $database->getConnection();
    }
    
    /**
     * Render a view with data
     */
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: " . $view);
        }
    }
    
    /**
     * Render a view with layout
     */
    protected function render($view, $data = [], $layout = 'layouts/app') {
        extract($data);
        ob_start();
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        }
        $content = ob_get_clean();
        require __DIR__ . '/../views/' . $layout . '.php';
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to another URL
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }
    
    /**
     * Get POST data safely
     */
    protected function input($key, $default = null) {
        return isset($_POST[$key]) ? strip_tags($_POST[$key]) : $default;
    }
    
    /**
     * Get GET parameter safely
     */
    protected function query($key, $default = null) {
        return isset($_GET[$key]) ? strip_tags($_GET[$key]) : $default;
    }
    
    /**
     * Check if request is POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Check if request is AJAX
     */
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
