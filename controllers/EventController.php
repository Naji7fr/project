<?php

require_once __DIR__ . '/../models/Event.php';

class EventController {
    private $eventModel;
    
    public function __construct() {
        $this->eventModel = new Event();
    }

    public function index() {
        // SERVER-SIDE: Try-catch for event retrieval in EventController index() method
        try {
            // Check for status query parameter
            $status = $_GET['status'] ?? null;
            $events = $this->eventModel->getAll($status);
            $this->jsonResponse($events);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in EventController index() method
            error_log('EventController index() error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Events niet beschikbaar',
                'message' => 'Er is een serverfout opgetreden bij het ophalen van events.'
            ], 500);
        }
    }
    
    public function show($id) {
        // SERVER-SIDE: Try-catch for single event retrieval in EventController show() method
        try {
            $event = $this->eventModel->getById($id);
            if (!$event) {
                $this->jsonResponse(['error' => 'Event not found'], 404);
                return;
            }
            $this->jsonResponse($event);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in EventController show() method
            error_log('EventController show() error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Event niet beschikbaar',
                'message' => 'Er is een serverfout opgetreden bij het ophalen van het event.'
            ], 500);
        }
    }
    
    public function showByCity($city) {
        // SERVER-SIDE: Try-catch for city-based event search in EventController showByCity() method
        try {
            $event = $this->eventModel->getByCity($city);
            if (!$event) {
                $this->jsonResponse(['error' => 'Event not found for this city'], 404);
                return;
            }
            $this->jsonResponse($event);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in EventController showByCity() method
            error_log('EventController showByCity() error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Event niet beschikbaar',
                'message' => 'Er is een serverfout opgetreden bij het zoeken naar events in deze stad.'
            ], 500);
        }
    }
    
    public function create() {
        
        // SERVER-SIDE: Try-catch for event creation in EventController create() method
        try {
            // SERVER-SIDE VALIDATION: Validate JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->jsonResponse(['error' => 'Invalid JSON data'], 400);
                return;
            }
            
            // SERVER-SIDE VALIDATION: Basic validation only
            if (empty($input['title']) || empty($input['city']) || empty($input['date']) || 
                empty($input['location']) || empty($input['description']) || 
                empty($input['price']) || empty($input['image_url'])) {
                $this->jsonResponse(['error' => 'All fields are required'], 400);
                return;
            }
            
            // SERVER-SIDE VALIDATION: Validate date range (only for upcoming events)
            if ($input['status'] !== 'past' && !$this->eventModel->isValidEventDate($input['date'])) {
                $this->jsonResponse([
                    'error' => 'Invalid date',
                    'message' => 'Event date must be between today and 6 months from now.'
                ], 400);
                return;
            }
            
            $eventId = $this->eventModel->create($input);
            $this->jsonResponse(['id' => $eventId, 'message' => 'Event created successfully'], 201);
        } catch (PDOException $e) {
            // SERVER-SIDE: Catch database constraint violations in EventController create() method
            error_log('EventController create() PDO error: ' . $e->getMessage());
            // Handle database constraint violations
            if ($e->getCode() == 23000) { // Integrity constraint violation
                $errorMessage = $e->getMessage();
                
                if (strpos($errorMessage, 'unique_city') !== false) {
                    $this->jsonResponse(['error' => 'You already added an event for this city'], 409);
                } else if (strpos($errorMessage, 'Duplicate entry') !== false) {
                    $this->jsonResponse(['error' => 'This event already exists'], 409);
                } else {
                    $this->jsonResponse(['error' => 'Database constraint violation'], 409);
                }
            } else {
                $this->jsonResponse(['error' => 'Database error occurred'], 500);
            }
        } catch (Exception $e) {
            // SERVER-SIDE: Catch general errors in EventController create() method
            error_log('EventController create() error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Event kon niet worden aangemaakt',
                'message' => 'Er is een serverfout opgetreden bij het aanmaken van het event.'
            ], 500);
        }
    }
    
    public function update($id) {
        // SERVER-SIDE: Try-catch for event update in EventController update() method
        try {
            $event = $this->eventModel->getById($id);
            if (!$event) {
                $this->jsonResponse(['error' => 'Event not found'], 404);
                return;
            }
            
            // Check if event has already started
            if ($this->eventModel->hasEventStarted($id)) {
                $this->jsonResponse([
                    'error' => 'Event kan niet worden gewijzigd',
                    'message' => 'Het event is al gestart en kan niet meer worden gewijzigd.'
                ], 403);
                return;
            }
            
            // SERVER-SIDE VALIDATION: Validate JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->jsonResponse(['error' => 'Invalid JSON data'], 400);
                return;
            }
            
            // SERVER-SIDE VALIDATION: Validate required fields
            $errors = $this->validateEvent($input, true);
            if (!empty($errors)) {
                $this->jsonResponse(['errors' => $errors], 422);
                return;
            }
            
            // SERVER-SIDE VALIDATION: Validate date range for updates (only for upcoming events)
            if ($input['status'] !== 'past' && !$this->eventModel->isValidEventDate($input['date'])) {
                $this->jsonResponse([
                    'error' => 'Invalid date',
                    'message' => 'Event date must be between today and 6 months from now.'
                ], 400);
                return;
            }
            
            $this->eventModel->update($id, $input);
            $this->jsonResponse(['message' => 'Event successfully updated']);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in EventController update() method
            error_log('EventController update() error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Event kon niet worden bijgewerkt',
                'message' => 'Er is een serverfout opgetreden bij het bijwerken van het event.'
            ], 500);
        }
    }
    
    public function destroy($id) {
        // SERVER-SIDE: Try-catch for event deletion in EventController destroy() method
        try {
            $event = $this->eventModel->getById($id);
            if (!$event) {
                $this->jsonResponse(['error' => 'Event not found'], 404);
                return;
            }
            
            // Check if event has already started
            if ($this->eventModel->hasEventStarted($id)) {
                $this->jsonResponse([
                    'error' => 'Event kan niet worden verwijderd',
                    'message' => 'Het event is al gestart en kan niet meer worden verwijderd.'
                ], 403);
                return;
            }
            
            $this->eventModel->delete($id);
            $this->jsonResponse(['message' => 'Event deleted successfully']);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in EventController destroy() method
            error_log('EventController destroy() error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Event kon niet worden verwijderd',
                'message' => 'Er is een serverfout opgetreden bij het verwijderen van het event.'
            ], 500);
        }
    }
    
    // SERVER-SIDE VALIDATION IS HERE
    private function validateEvent($data, $requireStatus = false) {
        $errors = [];
        
        if (empty($data['title'])) $errors[] = 'Title is required';
        if (empty($data['city'])) $errors[] = 'City is required';
        if (empty($data['date'])) $errors[] = 'Date is required';
        if (empty($data['location'])) $errors[] = 'Location is required';
        if (empty($data['description'])) $errors[] = 'Description is required';
        if (empty($data['price'])) $errors[] = 'Price is required';
        if (empty($data['image_url'])) $errors[] = 'Image URL is required';
        
        if (!empty($data['image_url']) && !filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Image URL must be a valid URL';
        }
        
        if ($requireStatus && !in_array($data['status'], ['upcoming', 'past'])) {
            $errors[] = 'Status must be either upcoming or past';
        }
        
        return $errors;
    }
    
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
