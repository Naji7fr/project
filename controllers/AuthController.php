<?php

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['username']) || empty($input['password'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Username and password are required'], 400);
            return;
        }
        
        $loginType = $input['loginType'] ?? 'admin';
        $user = $this->userModel->authenticateByType($input['username'], $input['password'], $loginType);
        
        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Login successful',
                'role' => $user['role']
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid username or password'], 401);
        }
    }
    
    public function logout() {
        session_start();
        session_destroy();
        
        $this->jsonResponse(['success' => true, 'message' => 'Logged out successfully']);
    }
    
    public function checkAuth() {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'admin') {
            return false;
        }
        return true;
    }
    
    public function requireAuth() {
        if (!$this->checkAuth()) {
            header('Location: /login');
            exit;
        }
    }
    
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
