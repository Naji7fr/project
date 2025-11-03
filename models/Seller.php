<?php

require_once __DIR__ . '/../config/database.php';

class Seller {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($status = null) {
        try {
            $sql = "SELECT * FROM sellers";
            $params = [];
            
            if ($status) {
                $sql .= " WHERE status = ?";
                $params[] = $status;
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll();
            
            // Return empty array if no sellers found
            return $result ?: [];
        } catch (PDOException $e) {
            // Check if table doesn't exist
            if ($e->getCode() === '42S02') {
                error_log('Sellers table does not exist. Please run the create_sellers.sql script.');
                throw new Exception('Sellers table not found. Please contact administrator.');
            }
            
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while fetching sellers');
        }
    }
    
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM sellers WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while fetching seller');
        }
    }
    
    public function getByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM sellers WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while fetching seller by email');
        }
    }
    
    public function getByName($name, $excludeId = null) {
        try {
            $sql = "SELECT * FROM sellers WHERE LOWER(name) = LOWER(?)";
            $params = [$name];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while checking for duplicate name');
        }
    }
    
    public function getByEmailExcluding($email, $excludeId = null) {
        try {
            $sql = "SELECT * FROM sellers WHERE LOWER(email) = LOWER(?)";
            $params = [$email];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while checking for duplicate email');
        }
    }

    public function getByNameAndEmail($name, $email, $excludeId = null) {
        try {
            $sql = "SELECT * FROM sellers WHERE LOWER(name) = LOWER(?) AND LOWER(email) = LOWER(?)";
            $params = [$name, $email];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while checking for duplicate seller');
        }
    }
    
    public function create($data) {
        try {
            // Check for individual duplicates first
            $existingByName = $this->getByName($data['name']);
            if ($existingByName) {
                throw new Exception('Een verkoper met deze naam bestaat al');
            }
            
            $existingByEmail = $this->getByEmail($data['email']);
            if ($existingByEmail) {
                throw new Exception('Een verkoper met dit e-mailadres bestaat al');
            }
            
            $sql = "INSERT INTO sellers (name, email, phone, company, address, city, postal_code, country, status, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['company'] ?? null,
                $data['address'] ?? null,
                $data['city'] ?? null,
                $data['postal_code'] ?? null,
                $data['country'] ?? null,
                $data['status'] ?? 'active'
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while creating seller');
        }
    }
    
    public function update($id, $data) {
        try {
            // Check for individual duplicates first (excluding current seller)
            $existingByName = $this->getByName($data['name'], $id);
            if ($existingByName) {
                throw new Exception('Een verkoper met deze naam bestaat al');
            }
            
            $existingByEmail = $this->getByEmailExcluding($data['email'], $id);
            if ($existingByEmail) {
                throw new Exception('Een verkoper met dit e-mailadres bestaat al');
            }
            
            $sql = "UPDATE sellers SET name = ?, email = ?, phone = ?, company = ?, address = ?, city = ?, postal_code = ?, country = ?, status = ?, updated_at = NOW() WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['company'],
                $data['address'],
                $data['city'],
                $data['postal_code'],
                $data['country'],
                $data['status'],
                $id
            ]);
        } catch (PDOException $e) {
            // Catch error for duplicate email
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while updating seller');
        }
    }
    
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM sellers WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while deleting seller');
        }
    }
    
    public function updateStatus($id, $status) {
        try {
            $stmt = $this->db->prepare("UPDATE sellers SET status = ?, updated_at = NOW() WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            error_log('Seller model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while updating seller status');
        }
    }
}
