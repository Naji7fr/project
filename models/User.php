<?php

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function authenticateByType($username, $password, $loginType) {
        if ($loginType === 'admin') {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
        } else {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? AND role = 'user'");
        }
        
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function createUser($username, $password, $email, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $hashedPassword, $email, $role]);
    }
}
