<?php

require_once __DIR__ . '/../models/Seller.php';

class SellerController {
    private $sellerModel;
    
    public function __construct() {
        $this->sellerModel = new Seller();
    }
    
    public function index() {
        try {
            $status = $_GET['status'] ?? null;
            $sellers = $this->sellerModel->getAll($status);
            $this->jsonResponse($sellers);
        } catch (Exception $e) {
            error_log('SellerController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Verkopers niet beschikbaar',
                'message' => 'Er is een serverfout opgetreden. Probeer het later opnieuw.'
            ], 500);
        }
    }
    
    public function show($id) {
        try {
            $seller = $this->sellerModel->getById($id);
            if (!$seller) {
                $this->jsonResponse(['error' => 'Verkoper niet gevonden'], 404);
                return;
            }
            $this->jsonResponse($seller);
        } catch (Exception $e) {
            error_log('SellerController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Verkoper niet beschikbaar',
                'message' => 'Er is een serverfout opgetreden.'
            ], 500);
        }
    }
    
    public function store() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Validate fields
            $errors = $this->validateSeller($input);
            if (!empty($errors)) {
                $this->jsonResponse(['errors' => $errors], 422);
                return;
            }
            
            $id = $this->sellerModel->create($input);
            $this->jsonResponse([
                'id' => $id, 
                'message' => 'Verkoper succesvol toegevoegd'
            ]);
        } catch (Exception $e) {
            error_log('SellerController error: ' . $e->getMessage());
            
            // Check if it's a duplicate email error
            if (strpos($e->getMessage(), 'bestaat al') !== false) {
                $this->jsonResponse([
                    'error' => 'Verkoper bestaat al',
                    'message' => $e->getMessage()
                ], 409);
            } else {
                $this->jsonResponse([
                    'error' => 'Verkoper kon niet worden toegevoegd',
                    'message' => 'Er is een serverfout opgetreden.'
                ], 500);
            }
        }
    }
    
    public function update($id) {
        try {
            $seller = $this->sellerModel->getById($id);
            if (!$seller) {
                $this->jsonResponse(['error' => 'Verkoper niet gevonden'], 404);
                return;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Validate required fields
            $errors = $this->validateSeller($input, true);
            if (!empty($errors)) {
                $this->jsonResponse(['errors' => $errors], 422);
                return;
            }
            
            $this->sellerModel->update($id, $input);
            $this->jsonResponse(['message' => 'Verkoper succesvol bijgewerkt']);
        } catch (Exception $e) {
            error_log('SellerController error: ' . $e->getMessage());
            
            if (strpos($e->getMessage(), 'bestaat al') !== false) {
                $this->jsonResponse([
                    'error' => 'Verkoper bestaat al',
                    'message' => $e->getMessage()
                ], 409);
            } else {
                $this->jsonResponse([
                    'error' => 'Verkoper kon niet worden bijgewerkt',
                    'message' => 'Er is een serverfout opgetreden.'
                ], 500);
            }
        }
    }
    
    public function destroy($id) {
        try {
            $seller = $this->sellerModel->getById($id);
            if (!$seller) {
                $this->jsonResponse(['error' => 'Verkoper niet gevonden'], 404);
                return;
            }
            
            $this->sellerModel->delete($id);
            $this->jsonResponse(['message' => 'Verkoper succesvol verwijderd']);
        } catch (Exception $e) {
            error_log('SellerController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Verkoper kon niet worden verwijderd',
                'message' => 'Er is een serverfout opgetreden.'
            ], 500);
        }
    }
    
    public function updateStatus($id) {
        try {
            $seller = $this->sellerModel->getById($id);
            if (!$seller) {
                $this->jsonResponse(['error' => 'Verkoper niet gevonden'], 404);
                return;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            $status = $input['status'] ?? null;
            
            if (!in_array($status, ['active', 'inactive'])) {
                $this->jsonResponse(['error' => 'Ongeldige status'], 400);
                return;
            }
            
            $this->sellerModel->updateStatus($id, $status);
            $this->jsonResponse(['message' => 'Status succesvol bijgewerkt']);
        } catch (Exception $e) {
            error_log('SellerController error: ' . $e->getMessage());
            $this->jsonResponse([
                'error' => 'Status kon niet worden bijgewerkt',
                'message' => 'Er is een serverfout opgetreden.'
            ], 500);
        }
    }
    
    private function validateSeller($data, $requireAll = false) {
        $errors = [];
        
        if (empty($data['name'])) $errors[] = 'Naam is verplicht';
        if (empty($data['email'])) $errors[] = 'E-mailadres is verplicht';
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mailadres moet een geldig e-mailadres zijn';
        }
        
        if (!empty($data['phone']) && !preg_match('/^[\+]?[0-9\s\-\(\)]+$/', $data['phone'])) {
            $errors[] = 'Telefoonnummer heeft een ongeldig formaat';
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
