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
    
    /**
     * Check if an event has already started
     * 
     * @param int $eventId Event ID
     * @return bool True if event has started, false otherwise
     * @throws Exception If database query fails
     */
    public function hasEventStarted($eventId)
    {
        try {
            $event = $this->getById($eventId);
            if (!$event) {
                return false;
            }
            
            // If status is 'upcoming', allow editing/deleting regardless of date
            if ($event['status'] === 'upcoming') {
                return false;
            }
            
            // If status is 'past', don't allow editing/deleting
            if ($event['status'] === 'past') {
                return true;
            }
            
            // Parse the event date to check if it has started
            $eventDate = $this->parseEventDate($event['date']);
            $currentDate = new DateTime();
            
            return $eventDate <= $currentDate;
        } catch (Exception $e) {
            error_log('Event Model hasEventStarted() error: ' . $e->getMessage());
            throw new Exception('Failed to check event start status: ' . $e->getMessage());
        }
    }
    
    /**
     * Parse event date string to DateTime object
     * Handles various date formats used in the system
     * 
     * @param string $dateString Date string from database
     * @return DateTime Parsed date object
     */
    private function parseEventDate($dateString)
    {
        try {
            // Handle different date formats
            if (preg_match('/(\w+ \d{1,2})-\d{1,2}, (\d{4})/', $dateString, $matches)) {
                // Format: "November 12-13, 2025" -> use start date
                $date = DateTime::createFromFormat('F j, Y', $matches[1] . ', ' . $matches[2]);
                return $date ?: new DateTime();
            } elseif (preg_match('/(\w+ \d{1,2}, \d{4})/', $dateString, $matches)) {
                // Format: "November 12, 2025"
                $date = DateTime::createFromFormat('F j, Y', $matches[1]);
                return $date ?: new DateTime();
            } elseif (preg_match('/(\d{4}-\d{2}-\d{2})/', $dateString, $matches)) {
                // Format: "2025-11-12"
                $date = DateTime::createFromFormat('Y-m-d', $matches[1]);
                return $date ?: new DateTime();
            } else {
                // Try to parse as is, or return current date if fails
                $date = strtotime($dateString);
                return $date ? new DateTime(date('Y-m-d', $date)) : new DateTime();
            }
        } catch (Exception $e) {
            error_log('Date parsing error: ' . $e->getMessage());
            return new DateTime(); // Return current date if parsing fails
        }
    }
    
    /**
     * Validate if date is within allowed range (today to 6 months from now)
     * 
     * @param string $dateString Date string to validate
     * @return bool True if date is valid, false otherwise
     */
    public function isValidEventDate($dateString)
    {
        try {
            $eventDate = $this->parseEventDate($dateString);
            $today = new DateTime();
            $today->setTime(0, 0, 0); // Set to start of day
            
            $maxDate = new DateTime();
            $maxDate->add(new DateInterval('P6M')); // Add 6 months
            
            // For events marked as 'past', allow any date (for testing purposes)
            // But for 'upcoming' events, enforce date validation
            return $eventDate >= $today && $eventDate <= $maxDate;
        } catch (Exception $e) {
            error_log('Date validation error: ' . $e->getMessage() . ' for date: ' . $dateString);
            return false;
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
