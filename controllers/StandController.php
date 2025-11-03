<?php

require_once __DIR__ . '/../models/Stand.php';

class StandController {
    private $standModel;
    
    public function __construct() {
        $this->standModel = new Stand();
    }
    
    public function index() {
        // SERVER-SIDE: Try-catch for stands retrieval in StandController index() method
        try {
            // Simulate server error for unhappy scenario testing
            if (isset($_GET['simulate_error']) && $_GET['simulate_error'] === 'true') {
                throw new Exception('Simulated server error');
            }
            
            $category = $_GET['category'] ?? null;
            $stands = $this->standModel->getAll('active');
            
            if ($category) {
                $stands = array_filter($stands, function($stand) use ($category) {
                    return $stand['category'] === $category;
                });
            }
            
            $this->jsonResponse($stands);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in StandController index() method
            error_log('StandController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Stands niet beschikbaar',
                'message' => 'Er is een serverfout opgetreden. Probeer het later opnieuw.'
            ], 500);
        }
    }
    
    public function show($id) {
        // SERVER-SIDE: Try-catch for single stand retrieval in StandController show() method
        try {
            $stand = $this->standModel->getById($id);
            if (!$stand) {
                $this->jsonResponse(['error' => 'Stand not found'], 404);
                return;
            }
            $this->jsonResponse($stand);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in StandController show() method
            error_log('StandController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Stand niet beschikbaar',
                'message' => 'Er is een serverfout opgetreden.'
            ], 500);
        }
    }
    
    public function getCategories() {
        // SERVER-SIDE: Try-catch for categories retrieval in StandController getCategories() method
        try {
            $categories = $this->standModel->getAllCategories();
            $this->jsonResponse($categories);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in StandController getCategories() method
            error_log('StandController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Categorieën niet beschikbaar',
                'message' => 'Er is een serverfout opgetreden.'
            ], 500);
        }
    }
    
    public function store() {
        // SERVER-SIDE: Try-catch for stand creation in StandController store() method
        try {
            // SERVER-SIDE VALIDATION: Validate JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            // SERVER-SIDE VALIDATION: Validate required fields
            $errors = $this->validateStand($input);
            if (!empty($errors)) {
                $this->jsonResponse(['errors' => $errors], 422);
                return;
            }
            
            $id = $this->standModel->create($input);
            $this->jsonResponse(['id' => $id, 'message' => 'Stand created successfully']);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in StandController store() method
            error_log('StandController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Stand kon niet worden aangemaakt',
                'message' => 'Er is een serverfout opgetreden.'
            ], 500);
        }
    }
    
    public function update($id) {
        // SERVER-SIDE: Try-catch for stand update in StandController update() method
        try {
            $stand = $this->standModel->getById($id);
            if (!$stand) {
                $this->jsonResponse(['error' => 'Stand not found'], 404);
                return;
            }
            
            // SERVER-SIDE VALIDATION: Validate JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            // SERVER-SIDE VALIDATION: Validate required fields
            $errors = $this->validateStand($input, true);
            if (!empty($errors)) {
                $this->jsonResponse(['errors' => $errors], 422);
                return;
            }
            
            $this->standModel->update($id, $input);
            $this->jsonResponse(['message' => 'Stand updated successfully']);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in StandController update() method
            error_log('StandController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Stand kon niet worden bijgewerkt',
                'message' => 'Er is een serverfout opgetreden.'
            ], 500);
        }
    }
    
    public function destroy($id) {
        // SERVER-SIDE: Try-catch for stand deletion in StandController destroy() method
        try {
            $stand = $this->standModel->getById($id);
            if (!$stand) {
                $this->jsonResponse(['error' => 'Stand not found'], 404);
                return;
            }
            
            $this->standModel->delete($id);
            $this->jsonResponse(['message' => 'Stand deleted successfully']);
        } catch (Exception $e) {
            // SERVER-SIDE: Catch errors in StandController destroy() method
            error_log('StandController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Stand kon niet worden verwijderd',
                'message' => 'Er is een serverfout opgetreden.'
            ], 500);
        }
    }
    
    private function validateStand($data, $requireAll = false) {
        $errors = [];
        
        if (empty($data['name'])) $errors[] = 'Name is required';
        if (empty($data['company'])) $errors[] = 'Company is required';
        if (empty($data['category'])) $errors[] = 'Category is required';
        if (empty($data['description'])) $errors[] = 'Description is required';
        if (empty($data['location'])) $errors[] = 'Location is required';
        if (empty($data['booth_number'])) $errors[] = 'Booth number is required';
        if (empty($data['contact_email'])) $errors[] = 'Contact email is required';
        if (empty($data['contact_phone'])) $errors[] = 'Contact phone is required';
        if (empty($data['logo_url'])) $errors[] = 'Logo URL is required';
        
        if (!empty($data['contact_email']) && !filter_var($data['contact_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Contact email must be a valid email address';
        }
        
        if (!empty($data['website']) && !filter_var($data['website'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Website must be a valid URL';
        }
        
        if (!empty($data['logo_url']) && !filter_var($data['logo_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Logo URL must be a valid URL';
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
