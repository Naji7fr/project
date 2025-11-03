<?php

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Start
session_start();

// Set security headers
setSecurityHeaders();

// Set CORS headers for API requests
setCorsHeaders();

// Simple router
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove query string
$path = parse_url($requestUri, PHP_URL_PATH);

// Remove /public from path if present
$path = str_replace('/public', '', $path);

// Route API endpoints
if (strpos($path, '/api/events') === 0) {
    require_once __DIR__ . '/../controllers/EventController.php';
    $controller = new EventController();
    $pathParts = explode('/', trim($path, '/'));
    
    switch ($requestMethod) {
        case 'GET':
            if (count($pathParts) === 2) {
                // GET /api/events
                $controller->index();
            } elseif (count($pathParts) === 3) {
                // GET /api/events/{id}
                $controller->show($pathParts[2]);
            } elseif (count($pathParts) === 4 && $pathParts[2] === 'city') {
                // GET /api/events/city/{city}
                $controller->showByCity($pathParts[3]);
            }
            break;
            
        case 'POST':
            if (count($pathParts) === 2) {
                // POST /api/events
                $controller->create();
            }
            break;
            
        case 'PUT':
            if (count($pathParts) === 3) {
                // PUT /api/events/{id}
                $controller->update($pathParts[2]);
            }
            break;
            
        case 'DELETE':
            if (count($pathParts) === 3) {
                // DELETE /api/events/{id}
                $controller->destroy($pathParts[2]);
            }
            break;
    }
} elseif (strpos($path, '/api/stands') === 0) {
    // Handle stands API
    require_once __DIR__ . '/../controllers/StandController.php';
    $controller = new StandController();
    $pathParts = explode('/', trim($path, '/'));
    
    switch ($requestMethod) {
        case 'GET':
            if (count($pathParts) === 2) {
                // GET /api/stands
                $controller->index();
            } elseif (count($pathParts) === 3) {
                // GET /api/stands/{id}
                $controller->show($pathParts[2]);
            } elseif (count($pathParts) === 3 && $pathParts[2] === 'categories') {
                // GET /api/stands/categories
                $controller->getCategories();
            }
            break;
            
        case 'POST':
            if (count($pathParts) === 2) {
                // POST /api/stands
                $controller->store();
            }
            break;
            
        case 'PUT':
            if (count($pathParts) === 3) {
                // PUT /api/stands/{id}
                $controller->update($pathParts[2]);
            }
            break;
            
        case 'DELETE':
            if (count($pathParts) === 3) {
                // DELETE /api/stands/{id}
                $controller->destroy($pathParts[2]);
            }
            break;
    }
} elseif (strpos($path, '/api/sellers') === 0) {
    // Handle sellers API
    require_once __DIR__ . '/../controllers/SellerController.php';
    $controller = new SellerController();
    $pathParts = explode('/', trim($path, '/'));
    
    switch ($requestMethod) {
        case 'GET':
            if (count($pathParts) === 2) {
                // GET /api/sellers
                $controller->index();
            } elseif (count($pathParts) === 3) {
                // GET /api/sellers/{id}
                $controller->show($pathParts[2]);
            }
            break;
            
        case 'POST':
            if (count($pathParts) === 2) {
                // POST /api/sellers
                $controller->store();
            }
            break;
            
        case 'PUT':
            if (count($pathParts) === 3) {
                // PUT /api/sellers/{id}
                $controller->update($pathParts[2]);
            } elseif (count($pathParts) === 4 && $pathParts[3] === 'status') {
                // PUT /api/sellers/{id}/status
                $controller->updateStatus($pathParts[2]);
            }
            break;
            
        case 'DELETE':
            if (count($pathParts) === 3) {
                // DELETE /api/sellers/{id}
                $controller->destroy($pathParts[2]);
            }
            break;
    }
} elseif (strpos($path, '/api/login') === 0 || strpos($path, '/api/logout') === 0) {
    // Handle authentication API
    require_once __DIR__ . '/../controllers/AuthController.php';
    $authController = new AuthController();
    
    if ($path === '/api/login' && $requestMethod === 'POST') {
        $authController->login();
    } elseif ($path === '/api/logout' && $requestMethod === 'POST') {
        $authController->logout();
    }
} elseif ($path === '/login') {
    // Serve the login page
    require_once __DIR__ . '/../views/login.php';
} elseif ($path === '/events') {
    // Serve the events page
    require_once __DIR__ . '/../views/eventspage.php';
} elseif ($path === '/stands') {
    // Serve the stands page
    require_once __DIR__ . '/../views/stands.php';
} elseif ($path === '/sellers') {
    // Check authentication before serving sellers page
    require_once __DIR__ . '/../controllers/AuthController.php';
    $authController = new AuthController();
    $authController->requireAuth();
    
    // Serve the sellers page
    require_once __DIR__ . '/../views/sellers.php';
} elseif ($path === '/sellers/add') {
    // Check authentication before serving add seller page
    require_once __DIR__ . '/../controllers/AuthController.php';
    $authController = new AuthController();
    $authController->requireAuth();
    
    // Serve the add seller page
    require_once __DIR__ . '/../views/add_seller.php';
} elseif ($path === '/admin') {
    // Check authentication before serving admin page
    require_once __DIR__ . '/../controllers/AuthController.php';
    $authController = new AuthController();
    $authController->requireAuth();
    
    // Serve the admin page
    require_once __DIR__ . '/../views/admin.php';
} elseif ($path === '/dashboard') {
    // Check user authentication before serving dashboard
    require_once __DIR__ . '/../controllers/AuthController.php';
    $authController = new AuthController();
    
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        header('Location: /login');
        exit;
    }
    
    // Serve the user dashboard
    require_once __DIR__ . '/../views/dashboard.php';
} else {
    // Serve the home page
    require_once __DIR__ . '/../views/home.php';
}
