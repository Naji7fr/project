# UML Class Diagram - Sneakerness Application

## Overview

This document presents the UML Class Diagram for the Sneakerness application, following MVC (Model-View-Controller) architecture pattern and PSR-12 coding standards.

## Architecture Layers

### 1. **Model Layer** (Data Access)
### 2. **Controller Layer** (Business Logic)
### 3. **View Layer** (Presentation)
### 4. **Utility Layer** (Supporting Classes)

## UML Class Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              SNEAKERNESS APPLICATION                         │
│                                UML CLASS DIAGRAM                            │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                                MODEL LAYER                                  │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────┐    ┌─────────────────────┐    ┌─────────────────────┐
│      Database       │    │       Event         │    │       Stand         │
├─────────────────────┤    ├─────────────────────┤    ├─────────────────────┤
│ - instance: Database│    │ - db: PDO           │    │ - db: PDO           │
│ - connection: PDO   │    │ - logger: Logger    │    │ - logger: Logger    │
├─────────────────────┤    ├─────────────────────┤    ├─────────────────────┤
│ + getInstance(): DB │    │ + __construct()     │    │ + __construct()     │
│ + getConnection()   │    │ + getAll(?string)   │    │ + getAll(?string)   │
│   : PDO             │    │ + getById(int)      │    │ + getById(int)      │
└─────────────────────┘    │ + getByCity(string) │    │ + getByCategory()   │
           △               │ + create(array): int│    │ + create(array): int│
           │               │ + update(int,array) │    │ + update(int,array) │
           │               │ + delete(int): bool │    │ + delete(int): bool │
           │               │ + checkDuplicate()  │    │ + search(string)    │
           │               └─────────────────────┘    └─────────────────────┘
           │                          △                          △
           │                          │                          │
           └──────────────────────────┼──────────────────────────┘
                                      │
                              ┌─────────────────────┐
                              │        User         │
                              ├─────────────────────┤
                              │ - db: PDO           │
                              │ - logger: Logger    │
                              ├─────────────────────┤
                              │ + __construct()     │
                              │ + authenticate()    │
                              │ + authenticateBy    │
                              │   Type(string,      │
                              │   string, string)   │
                              │ + create(array)     │
                              │ + getById(int)      │
                              │ + updateLastLogin() │
                              └─────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                              CONTROLLER LAYER                               │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────┐    ┌─────────────────────┐    ┌─────────────────────┐
│   EventController   │    │   StandController   │    │   AuthController    │
├─────────────────────┤    ├─────────────────────┤    ├─────────────────────┤
│ - eventModel: Event │    │ - standModel: Stand │    │ - userModel: User   │
│ - logger: Logger    │    │ - logger: Logger    │    │ - logger: Logger    │
│ - validator: Valid. │    │ - validator: Valid. │    │ - validator: Valid. │
├─────────────────────┤    ├─────────────────────┤    ├─────────────────────┤
│ + __construct()     │    │ + __construct()     │    │ + __construct()     │
│ + index()           │    │ + index()           │    │ + login()           │
│ + show(int)         │    │ + show(int)         │    │ + logout()          │
│ + store()           │    │ + store()           │    │ + register()        │
│ + update(int)       │    │ + update(int)       │    │ + requireAuth()     │
│ + destroy(int)      │    │ + destroy(int)      │    │ + requireRole()     │
│ + jsonResponse()    │    │ + filter()          │    │ + jsonResponse()    │
│ + validateInput()   │    │ + jsonResponse()    │    │ + validateInput()   │
└─────────────────────┘    │ + validateInput()   │    └─────────────────────┘
           │               └─────────────────────┘               │
           │                          │                          │
           │                          │                          │
           └──────────────────────────┼──────────────────────────┘
                                      │
                              ┌─────────────────────┐
                              │   BaseController    │
                              ├─────────────────────┤
                              │ # logger: Logger    │
                              │ # validator: Valid. │
                              ├─────────────────────┤
                              │ + __construct()     │
                              │ # jsonResponse()    │
                              │ # validateInput()   │
                              │ # sanitizeInput()   │
                              │ # logAction()       │
                              └─────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                               UTILITY LAYER                                 │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────┐    ┌─────────────────────┐    ┌─────────────────────┐
│       Logger        │    │     Validator       │    │      Security       │
├─────────────────────┤    ├─────────────────────┤    ├─────────────────────┤
│ - instance: Logger  │    │ - errors: array     │    │ + generateToken()   │
│ - logPath: string   │    │ - data: array       │    │ + validateToken()   │
├─────────────────────┤    ├─────────────────────┤    │ + hashPassword()    │
│ + getInstance()     │    │ + __construct()     │    │ + verifyPassword()  │
│ + debug(string)     │    │ + required(string)  │    │ + sanitizeInput()   │
│ + info(string)      │    │ + length(string,    │    │ + preventXSS()      │
│ + warning(string)   │    │   int, int)         │    │ + preventSQLi()     │
│ + error(string)     │    │ + email(string)     │    │ + rateLimiting()    │
│ + critical(string)  │    │ + date(string)      │    └─────────────────────┘
│ + security(string)  │    │ + numeric(string)   │
│ + database(string)  │    │ + url(string)       │
│ + logException()    │    │ + password(string)  │
│ + logUserAction()   │    │ + isValid(): bool   │
└─────────────────────┘    │ + getErrors(): array│
                           │ + sanitize(string)  │
                           └─────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                               CONFIG LAYER                                  │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────┐    ┌─────────────────────┐
│       Config        │    │      Router         │
├─────────────────────┤    ├─────────────────────┤
│ + APP_NAME: string  │    │ - routes: array     │
│ + DB_HOST: string   │    │ - currentRoute: str │
│ + DB_NAME: string   │    ├─────────────────────┤
│ + BASE_URL: string  │    │ + __construct()     │
│ + setCorsHeaders()  │    │ + addRoute()        │
│ + setSecurityHdrs() │    │ + dispatch()        │
└─────────────────────┘    │ + getCurrentRoute() │
                           │ + redirect(string)  │
                           └─────────────────────┘
```

## Class Relationships

### Inheritance Relationships
```
BaseController
    ├── EventController
    ├── StandController
    └── AuthController
```

### Composition Relationships
```
EventController ──◆ Event (has-a)
EventController ──◆ Logger (has-a)
EventController ──◆ Validator (has-a)

StandController ──◆ Stand (has-a)
StandController ──◆ Logger (has-a)
StandController ──◆ Validator (has-a)

AuthController ──◆ User (has-a)
AuthController ──◆ Logger (has-a)
AuthController ──◆ Validator (has-a)
```

### Dependency Relationships
```
All Models ──→ Database (depends-on)
All Models ──→ Logger (depends-on)
All Controllers ──→ Models (depends-on)
```

## Detailed Class Specifications

### Model Classes

#### Event Class
```php
/**
 * Event Model
 * Handles all event-related database operations
 */
class Event
{
    private PDO $db;
    private Logger $logger;
    
    public function __construct()
    public function getAll(?string $status = null): array
    public function getById(int $id): array|false
    public function getByCity(string $city): array|false
    public function create(array $data): int
    public function update(int $id, array $data): bool
    public function delete(int $id): bool
    public function checkDuplicate(string $title, string $date): bool
}
```

#### Stand Class
```php
/**
 * Stand Model
 * Handles all stand-related database operations
 */
class Stand
{
    private PDO $db;
    private Logger $logger;
    
    public function __construct()
    public function getAll(?string $category = null): array
    public function getById(int $id): array|false
    public function getByCategory(string $category): array
    public function create(array $data): int
    public function update(int $id, array $data): bool
    public function delete(int $id): bool
    public function search(string $query): array
}
```

#### User Class
```php
/**
 * User Model
 * Handles user authentication and management
 */
class User
{
    private PDO $db;
    private Logger $logger;
    
    public function __construct()
    public function authenticate(string $username, string $password): array|false
    public function authenticateByType(string $username, string $password, string $type): array|false
    public function create(array $data): int
    public function getById(int $id): array|false
    public function updateLastLogin(int $id): bool
}
```

### Controller Classes

#### BaseController (Abstract)
```php
/**
 * Base Controller
 * Provides common functionality for all controllers
 */
abstract class BaseController
{
    protected Logger $logger;
    protected Validator $validator;
    
    public function __construct()
    protected function jsonResponse(array $data, int $status = 200): void
    protected function validateInput(array $rules): bool
    protected function sanitizeInput(array $data): array
    protected function logAction(string $action, array $data = []): void
}
```

#### EventController
```php
/**
 * Event Controller
 * Handles HTTP requests for event operations
 */
class EventController extends BaseController
{
    private Event $eventModel;
    
    public function __construct()
    public function index(): void
    public function show(int $id): void
    public function store(): void
    public function update(int $id): void
    public function destroy(int $id): void
}
```

### Utility Classes

#### Logger (Singleton)
```php
/**
 * Logger Utility
 * Provides comprehensive logging functionality
 */
class Logger
{
    private static ?Logger $instance = null;
    private string $logPath;
    
    private function __construct()
    public static function getInstance(): Logger
    public function debug(string $message, array $context = []): void
    public function info(string $message, array $context = []): void
    public function warning(string $message, array $context = []): void
    public function error(string $message, array $context = []): void
    public function critical(string $message, array $context = []): void
    public function logException(Exception $e, string $context = ''): void
    public function logUserAction(string $action, ?int $userId, array $data = []): void
}
```

#### Validator
```php
/**
 * Validator Utility
 * Provides comprehensive input validation
 */
class Validator
{
    private array $errors;
    private array $data;
    
    public function __construct(array $data = [])
    public function required(string $field, ?string $message = null): self
    public function length(string $field, int $min, int $max, ?string $message = null): self
    public function email(string $field, ?string $message = null): self
    public function date(string $field, string $format = 'Y-m-d', ?string $message = null): self
    public function numeric(string $field, ?float $min = null, ?float $max = null): self
    public function isValid(): bool
    public function getErrors(): array
    public function getData(): array
}
```

## Design Patterns Used

### 1. **Singleton Pattern**
- `Database` class - Ensures single database connection
- `Logger` class - Provides global logging instance

### 2. **MVC Pattern**
- **Models**: Handle data access and business logic
- **Views**: Handle presentation layer (PHP templates)
- **Controllers**: Handle HTTP requests and coordinate between models and views

### 3. **Factory Pattern**
- Database connection factory in `Database::getInstance()`

### 4. **Strategy Pattern**
- Different validation strategies in `Validator` class

### 5. **Observer Pattern**
- Logging system observes application events

## SOLID Principles Compliance

### Single Responsibility Principle (SRP)
- ✅ Each class has one reason to change
- ✅ Models handle only data access
- ✅ Controllers handle only HTTP logic
- ✅ Utilities handle only their specific functionality

### Open/Closed Principle (OCP)
- ✅ Classes are open for extension, closed for modification
- ✅ BaseController allows extension without modification
- ✅ Validator allows new validation rules without changing existing code

### Liskov Substitution Principle (LSP)
- ✅ Derived controllers can substitute BaseController
- ✅ All models follow the same interface pattern

### Interface Segregation Principle (ISP)
- ✅ Classes don't depend on methods they don't use
- ✅ Specific interfaces for different functionalities

### Dependency Inversion Principle (DIP)
- ✅ High-level modules don't depend on low-level modules
- ✅ Both depend on abstractions (interfaces)

## Security Features

### Input Validation
- Server-side validation in all controllers
- Client-side validation in views
- Database-level constraints

### Authentication & Authorization
- Role-based access control (RBAC)
- Session management
- CSRF protection

### Data Protection
- Password hashing (bcrypt)
- SQL injection prevention (prepared statements)
- XSS prevention (input sanitization)

This UML design ensures maintainable, scalable, and secure code following industry best practices and PSR-12 standards.
