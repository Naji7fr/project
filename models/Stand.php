<?php

require_once __DIR__ . '/../config/database.php';

class Stand {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($status = null) {
        try {
            $sql = "SELECT * FROM stands";
            $params = [];
            
            if ($status) {
                $sql .= " WHERE status = ?";
                $params[] = $status;
            }
            
            $sql .= " ORDER BY booth_number ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Log error and throw custom exception
            error_log('Stand model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while fetching stands');
        }
    }
    
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM stands WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Stand model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while fetching stand');
        }
    }
    
    public function getByCategory($category) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM stands WHERE category = ? AND status = 'active' ORDER BY booth_number ASC");
            $stmt->execute([$category]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Stand model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while fetching stands by category');
        }
    }
    
    public function getAllCategories() {
        try {
            $stmt = $this->db->prepare("SELECT DISTINCT category FROM stands WHERE status = 'active' ORDER BY category ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log('Stand model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while fetching categories');
        }
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO stands (name, company, category, description, location, booth_number, contact_email, contact_phone, website, logo_url, status, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['name'],
                $data['company'],
                $data['category'],
                $data['description'],
                $data['location'],
                $data['booth_number'],
                $data['contact_email'],
                $data['contact_phone'],
                $data['website'] ?? null,
                $data['logo_url'],
                $data['status'] ?? 'active'
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log('Stand model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while creating stand');
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE stands SET name = ?, company = ?, category = ?, description = ?, location = ?, booth_number = ?, contact_email = ?, contact_phone = ?, website = ?, logo_url = ?, status = ?, updated_at = NOW() WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['name'],
                $data['company'],
                $data['category'],
                $data['description'],
                $data['location'],
                $data['booth_number'],
                $data['contact_email'],
                $data['contact_phone'],
                $data['website'],
                $data['logo_url'],
                $data['status'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log('Stand model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while updating stand');
        }
    }
    
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM stands WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Stand model error: ' . $e->getMessage());
            throw new Exception('Database error occurred while deleting stand');
        }
    }
}
