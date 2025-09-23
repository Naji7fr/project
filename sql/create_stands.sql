-- Create stands table
CREATE TABLE IF NOT EXISTS stands (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  company VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  location VARCHAR(100) NOT NULL,
  booth_number VARCHAR(20) NOT NULL,
  contact_email VARCHAR(255) NOT NULL,
  contact_phone VARCHAR(50) NOT NULL,
  website VARCHAR(255),
  logo_url VARCHAR(500) NOT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample stands data
INSERT INTO stands (name, company, category, description, location, booth_number, contact_email, contact_phone, website, logo_url, status) VALUES
('Nike Premium Store', 'Nike Inc.', 'Basketball Shoes', 'Official Nike store featuring the latest sneaker releases, limited editions, and exclusive collaborations. Experience the newest Air Jordan, Air Max, and Dunk collections.', 'Hall A', 'A-001', 'info@nike.com', '+1-800-NIKE', 'https://nike.com', 'https://logos-world.net/wp-content/uploads/2020/04/Nike-Logo.png', 'active'),

('Adidas Originals', 'Adidas AG', 'Lifestyle Sneakers', 'Discover the heritage of Adidas with classic and modern interpretations of iconic silhouettes. From Stan Smith to Yeezy, find your perfect pair.', 'Hall A', 'A-012', 'contact@adidas.com', '+49-9132-84-0', 'https://adidas.com', 'https://logos-world.net/wp-content/uploads/2020/04/Adidas-Logo.png', 'active'),

('StockX Authentication', 'StockX LLC', 'Authentication', 'Professional sneaker authentication services. Get your rare finds verified by our expert team using advanced authentication technology.', 'Hall B', 'B-005', 'auth@stockx.com', '+1-313-800-7625', 'https://stockx.com', 'https://logos-world.net/wp-content/uploads/2020/11/StockX-Logo.png', 'active'),

('Sneaker Customization Lab', 'Custom Kicks Co.', 'Customization', 'Transform your sneakers into unique art pieces. Our artists create custom designs, paint jobs, and modifications while you wait.', 'Hall C', 'C-018', 'custom@sneaklab.com', '+1-555-CUSTOM', 'https://sneaklab.com', 'https://via.placeholder.com/200x100/FF6B6B/FFFFFF?text=Custom+Lab', 'active'),

('Vintage Sneaker Vault', 'Retro Kicks Ltd.', 'Vintage', 'Rare and vintage sneakers from the 80s, 90s, and 2000s. Find deadstock classics and hard-to-find retro releases in pristine condition.', 'Hall A', 'A-025', 'vintage@retrokicks.com', '+44-20-7946-0958', 'https://retrokicks.com', 'https://via.placeholder.com/200x100/4ECDC4/FFFFFF?text=Vintage+Vault', 'active'),

('Sneaker Care Station', 'CleanKicks Pro', 'Maintenance', 'Professional sneaker cleaning, restoration, and protection services. Keep your collection looking fresh with our premium care products.', 'Hall B', 'B-032', 'care@cleankicks.com', '+1-555-CLEAN', 'https://cleankicks.com', 'https://via.placeholder.com/200x100/45B7D1/FFFFFF?text=Clean+Kicks', 'active'),

('Jordan Brand Experience', 'Nike Inc.', 'Premium', 'Exclusive Jordan Brand showcase featuring the latest releases, retro collections, and limited edition drops. Meet Jordan Brand athletes and designers.', 'Hall A', 'A-008', 'jordan@nike.com', '+1-800-JORDAN', 'https://jordan.com', 'https://logos-world.net/wp-content/uploads/2020/09/Jordan-Logo.png', 'active'),

('New Balance Lifestyle', 'New Balance Inc.', 'Running Shoes', 'Discover the perfect blend of performance and style with New Balance lifestyle sneakers. From 990s to collaborations with top designers.', 'Hall C', 'C-014', 'info@newbalance.com', '+1-800-NBshoes', 'https://newbalance.com', 'https://logos-world.net/wp-content/uploads/2020/09/New-Balance-Logo.png', 'active'),

('Sneaker Trading Post', 'TradeKicks Exchange', 'Trading', 'Buy, sell, and trade sneakers with fellow enthusiasts. Fair market prices and secure transactions guaranteed.', 'Hall B', 'B-021', 'trade@tradekicks.com', '+1-555-TRADE', 'https://tradekicks.com', 'https://via.placeholder.com/200x100/F7DC6F/000000?text=Trade+Post', 'active'),

('Converse All Star Hub', 'Converse Inc.', 'Canvas Sneakers', 'Classic canvas sneakers and modern interpretations. From Chuck Taylor All Stars to contemporary collaborations with artists and designers.', 'Hall C', 'C-007', 'info@converse.com', '+1-800-CONVERSE', 'https://converse.com', 'https://logos-world.net/wp-content/uploads/2020/04/Converse-Logo.png', 'active'),

('Vans Skate Shop', 'Vans Inc.', 'Skate Shoes', 'Authentic skate culture and street style. Discover classic Vans models, limited collaborations, and the latest skate-inspired designs.', 'Hall B', 'B-015', 'info@vans.com', '+1-800-VANS', 'https://vans.com', 'https://logos-world.net/wp-content/uploads/2020/04/Vans-Logo.png', 'active');
