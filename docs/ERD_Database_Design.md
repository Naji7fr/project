# Entity Relationship Diagram (ERD) - Sneakerness Database

## Database Overview

The Sneakerness database is designed following normalization principles and supports a comprehensive event management system for sneaker conventions.

## Database Schema

### 1. **USERS** Table
```sql
users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

**Purpose**: Store user authentication and authorization data
**Relationships**: 
- One-to-Many with events (admins can create multiple events)
- One-to-Many with user_activity_logs

### 2. **EVENTS** Table
```sql
events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(500),
    status ENUM('upcoming', 'past') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

**Purpose**: Store sneaker event information
**Relationships**: 
- Many-to-One with users (created by admin users)
- One-to-Many with event_stands (events can have multiple stands)

### 3. **STANDS** Table
```sql
stands (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100) NOT NULL,
    booth_number VARCHAR(20) NOT NULL,
    contact_email VARCHAR(255) NOT NULL,
    contact_phone VARCHAR(50) NOT NULL,
    website VARCHAR(255),
    logo_url VARCHAR(500) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

### Relationship Diagram (Text-based ERD)

```
┌─────────────────┐         ┌─────────────────┐         ┌─────────────────┐
│     USERS       │         │     EVENTS      │         │     STANDS      │
├─────────────────┤         ├─────────────────┤         ├─────────────────┤
│ PK id           │────────▶│ id              │         │ PK id           │
│    username     │    1:N  │    title        │         │    name         │
│    password     │         │    city         │         │    company      │
│    email        │         │    date         │         │    category     │
│    role         │         │    location     │         │    description  │
│    created_at   │         │    description  │         │    location     │
│    updated_at   │         │    price        │         │    booth_number │
└─────────────────┘         │    image_url    │         │    contact_email│
                            │    status       │         │    contact_phone│
                            │    created_at   │         │    website      │
                            │    updated_at   │         │    logo_url     │
                            └─────────────────┘         │    status       │
                                     │                  │    created_at   │
                                     │                  │    updated_at   │
                                     │                  └─────────────────┘
                                     │                           │
                                     │            M:N            │
                                     │   ┌─────────────────┐     │
                                     └──▶│  EVENT_STANDS   │◀────┘
                                         ├─────────────────┤
                                         │ PK id           │
                                         │ FK event_id     │
                                         │ FK stand_id     │
                                         │    created_at   │
                                         └─────────────────┘
```

## Data Integrity Constraints

### Primary Keys
- All tables have auto-incrementing integer primary keys
- Ensures unique identification of each record

### Foreign Keys
- `events.created_by` → `users.id` (logical relationship)
- `event_stands.event_id` → `events.id` (future implementation)
- `event_stands.stand_id` → `stands.id` (future implementation)

### Unique Constraints
- `users.username` - Ensures unique usernames
- `users.email` - Ensures unique email addresses
- `stands.booth_number` - Ensures unique booth numbers per event

### Check Constraints
- `users.role` - Must be 'admin' or 'user'
- `events.status` - Must be 'upcoming' or 'past'
- `events.price` - Must be >= 0
- `stands.status` - Must be 'active' or 'inactive'

## Indexes for Performance

### Primary Indexes
```sql
-- Automatically created with PRIMARY KEY
CREATE INDEX idx_users_pk ON users(id);
CREATE INDEX idx_events_pk ON events(id);
CREATE INDEX idx_stands_pk ON stands(id);
```

### Secondary Indexes
```sql
-- Performance optimization indexes
CREATE INDEX idx_events_status ON events(status);
CREATE INDEX idx_events_date ON events(date);
CREATE INDEX idx_events_city ON events(city);
CREATE INDEX idx_stands_category ON stands(category);
CREATE INDEX idx_stands_status ON stands(status);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_username ON users(username);
```

## Database Normalization

### First Normal Form (1NF)
- ✅ All tables have atomic values
- ✅ No repeating groups
- ✅ Each column contains single values

### Second Normal Form (2NF)
- ✅ All non-key attributes are fully dependent on primary key
- ✅ No partial dependencies

### Third Normal Form (3NF)
- ✅ No transitive dependencies
- ✅ All non-key attributes depend only on primary key

## Security Considerations

### Data Protection
- Passwords stored using bcrypt hashing
- Email addresses validated and sanitized
- SQL injection prevention through prepared statements

### Access Control
- Role-based access (admin/user)
- Session-based authentication
- CSRF token protection

## Future Enhancements

### Potential Additional Tables
1. **event_stands** - Junction table for many-to-many relationship
2. **user_sessions** - Track user login sessions
3. **audit_logs** - Track all database changes
4. **categories** - Normalize stand categories
5. **cities** - Normalize city information

### Proposed event_stands Table
```sql
event_stands (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    stand_id INT NOT NULL,
    assigned_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (stand_id) REFERENCES stands(id) ON DELETE CASCADE,
    UNIQUE KEY unique_event_stand (event_id, stand_id)
)
```

## Database Statistics

### Current Tables: 3
- users: Authentication and authorization
- events: Event management
- stands: Exhibitor information

### Total Relationships: 2
- users → events (1:N)
- events ↔ stands (M:N via future junction table)

### Indexes: 9
- 3 Primary key indexes
- 6 Performance optimization indexes

This ERD design ensures data integrity, optimal performance, and scalability for the Sneakerness event management system.
