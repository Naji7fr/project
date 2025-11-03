-- Database: sneakerness

CREATE DATABASE IF NOT EXISTS sneakerness;
USE sneakerness;

CREATE TABLE IF NOT EXISTS events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  city VARCHAR(255) NOT NULL,
  date VARCHAR(255) NOT NULL,
  location VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price VARCHAR(50) NOT NULL,
  image_url VARCHAR(500) NOT NULL,
  status ENUM('upcoming','past') NOT NULL DEFAULT 'upcoming',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO events (title, city, date, location, description, price, image_url, status) VALUES
-- UPCOMING EVENTS (2025)
('Berlin', 'Berlin', 'November 12-13, 2025', 'Station Berlin, Luckenwalder Str. 4-6, 10963 Berlin', 'The biggest sneaker event in Germany returns to Berlin with exclusive drops and special guests. Featuring over 150 exhibitors, live customization stations, panel discussions with industry leaders, and rare sneaker auctions.', '€25', 'http://static.photos/retail/1200x630/1', 'upcoming'),
('Amsterdam', 'Amsterdam', 'December 3-4, 2025', 'Westergas, Pazzanistraat 37, 1014 DB Amsterdam', 'Experience the Dutch sneaker culture with exclusive releases and live customization. Special collaborations only available at the Amsterdam event, plus vintage sneaker collectors area.', '€30', 'http://static.photos/retail/1200x630/2', 'upcoming'),
('Paris', 'Paris', 'January 14-15, 2026', 'Carreau du Temple, 4 Rue Eugène Spuller, 75003 Paris', 'The fashion capital meets sneaker culture in this exclusive Parisian event. High-fashion sneaker collaborations, designer talks, and special exhibition tracing sneakers in fashion.', '€35', 'http://static.photos/retail/1200x630/3', 'upcoming'),

-- PAST EVENTS (2023-2024)
('Zurich', 'Zurich', 'February 10-11, 2024', 'Halle 622, Therese-Giehse-Strasse 10, 8050 Zürich', 'Switzerland''s sneaker scene came alive with rare finds and local collaborations. Workshops, local designers, and the best of Swiss sneaker culture.', '€28', 'http://static.photos/retail/1200x630/7', 'past'),
('Warsaw', 'Warsaw', 'March 9-10, 2024', 'EXPO XXI, Prądzyńskiego 12/14, 01-222 Warsaw', 'Sneakerheads in Poland''s capital enjoyed exclusive drops and vibrant streetwear culture. Special guests, local artists, and a huge trading floor.', '€22', 'http://static.photos/retail/1200x630/8', 'past'),
('Dubai', 'Dubai', 'April 20-21, 2024', 'Dubai World Trade Centre, Sheikh Zayed Rd, Dubai', 'Sneaker luxury in Dubai with global brands and desert exclusives. VIP lounges, international speakers, and limited edition releases.', '€40', 'http://static.photos/retail/1200x630/9', 'past'),
('London', 'London', 'June 10-11, 2023', 'ExCeL London, Royal Victoria Dock, London', 'Our first UK edition brought together sneakerheads from across Europe.', '€30', 'http://static.photos/retail/1200x630/4', 'past'),
('Milan', 'Milan', 'April 22-23, 2023', 'Fiera Milano, Strada Statale Sempione, Milan', 'Italian sneaker culture at its finest with exclusive collaborations.', '€28', 'http://static.photos/retail/1200x630/5', 'past'),
('Barcelona', 'Barcelona', 'March 5-6, 2023', 'Fira de Barcelona, Av. Reina Maria Cristina, Barcelona', 'Sunny Barcelona hosted our most vibrant event yet with live music.', '€25', 'http://static.photos/retail/1200x630/6', 'past');

-- Add unique constraint to prevent duplicate cities
-- This will ensure only one event per city can exist

USE sneakerness;

-- Add unique constraint to the city column
ALTER TABLE events 
ADD CONSTRAINT unique_city UNIQUE (city);

-- Alternative: If you want to allow multiple events per city but prevent exact duplicates
-- You could use a composite unique constraint instead:
-- ALTER TABLE events 
-- ADD CONSTRAINT unique_event UNIQUE (title, city, date);
