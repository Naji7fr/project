<?php

/**
 * Event Model Class
 * 
 * Handles all database operations related to events
 * following PSR-12 coding standards and MVC architecture
 * 
 * @package Sneakerness\Models
 * @author  Development Team
 * @version 1.0.0
 */

require_once __DIR__ . '/../config/database.php';

class Event
{
    /**
     * Database connection instance
     * 
     * @var PDO
     */
    private $db;

    /**
     * Constructor - Initialize database connection
     * 
     * @throws Exception If database connection fails
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieve all events with optional status filtering
     * 
     * @param string|null $status Optional status filter ('upcoming', 'past')
     * @return array Array of event records
     * @throws Exception If database query fails
     */
    public function getAll($status = null)
    {
        // SERVER-SIDE: Try-catch for database query in Event Model getAll() method
        try {
            if ($status === 'upcoming') {
                // Show events that are in the future or contain future dates
                $sql = "SELECT * FROM events WHERE 
                        (date >= CURDATE()) OR 
                        (date LIKE '%2025%') OR 
                        (date LIKE '%2026%') OR
                        (status = 'upcoming')
                        ORDER BY created_at DESC";
                $params = [];
            } elseif ($status === 'past') {
                // Show events that are in the past
                $sql = "SELECT * FROM events WHERE 
                        (date < CURDATE() AND date NOT LIKE '%2025%' AND date NOT LIKE '%2026%') OR
                        (status = 'past')
                        ORDER BY created_at DESC";
                $params = [];
            } else {
                // Show all events
                $sql = "SELECT * FROM events ORDER BY created_at DESC";
                $params = [];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // SERVER-SIDE: Catch database errors in Event Model getAll() method
            error_log('Event Model getAll() error: ' . $e->getMessage());
            throw new Exception('Failed to retrieve events: ' . $e->getMessage());
        }
    }
    
    public function getById($id) {
        // SERVER-SIDE: Try-catch for database query in Event Model getById() method
        try {
            $stmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            // SERVER-SIDE: Catch database errors in Event Model getById() method
            error_log('Event Model getById() error: ' . $e->getMessage());
            throw new Exception('Failed to retrieve event: ' . $e->getMessage());
        }
    }
    
    public function getByCity($city) {
        // SERVER-SIDE: Try-catch for database query in Event Model getByCity() method
        try {
            $stmt = $this->db->prepare("SELECT * FROM events WHERE LOWER(city) = LOWER(?) LIMIT 1");
            $stmt->execute([$city]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            // SERVER-SIDE: Catch database errors in Event Model getByCity() method
            error_log('Event Model getByCity() error: ' . $e->getMessage());
            throw new Exception('Failed to retrieve event by city: ' . $e->getMessage());
        }
    }
    
    public function create($data) {
        // SERVER-SIDE: Try-catch for database insertion in Event Model create() method
        try {
            $sql = "INSERT INTO events (title, city, date, location, description, price, image_url, status, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['title'],
                $data['city'],
                $data['date'],
                $data['location'],
                $data['description'],
                $data['price'],
                $data['image_url'],
                $data['status'] ?? 'upcoming'
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // SERVER-SIDE: Catch database errors in Event Model create() method
            // Re-throw PDOException to handle database constraints in controller
            error_log('Event Model create() error: ' . $e->getMessage());
            throw $e; // Re-throw PDOException to handle constraints in controller
        }
    }
    
    public function update($id, $data) {
        // SERVER-SIDE: Try-catch for database update in Event Model update() method
        try {
            $sql = "UPDATE events SET title = ?, city = ?, date = ?, location = ?, description = ?, price = ?, image_url = ?, status = ?, updated_at = NOW() WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['title'],
                $data['city'],
                $data['date'],
                $data['location'],
                $data['description'],
                $data['price'],
                $data['image_url'],
                $data['status'],
                $id
            ]);
        } catch (PDOException $e) {
            // SERVER-SIDE: Catch database errors in Event Model update() method
            error_log('Event Model update() error: ' . $e->getMessage());
            throw new Exception('Failed to update event: ' . $e->getMessage());
        }
    }
    
    public function delete($id) {
        // SERVER-SIDE: Try-catch for database deletion in Event Model delete() method
        try {
            $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // SERVER-SIDE: Catch database errors in Event Model delete() method
            error_log('Event Model delete() error: ' . $e->getMessage());
            throw new Exception('Failed to delete event: ' . $e->getMessage());
        }
    }
    
    public function checkDuplicate($title, $date, $excludeId = null) {
        // SERVER-SIDE: Try-catch for database query in Event Model checkDuplicate() method
        try {
            $sql = "SELECT COUNT(*) FROM events WHERE LOWER(title) = LOWER(?) AND date = ?";
            $params = [$title, $date];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            // SERVER-SIDE: Catch database errors in Event Model checkDuplicate() method
            error_log('Event Model checkDuplicate() error: ' . $e->getMessage());
            throw new Exception('Failed to check duplicate event: ' . $e->getMessage());
        }
    }
    
}
