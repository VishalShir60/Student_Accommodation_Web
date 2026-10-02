-- Database Schema for Student Accommodation Website
-- Project: Student Accommodation Web Application (Enhanced with Nashik & Famous Colleges)

CREATE DATABASE IF NOT EXISTS student_accommodation;
USE student_accommodation;

-- Drop tables if they exist to allow clean re-import
DROP TABLE IF EXISTS interested_users;
DROP TABLE IF EXISTS property_amenities;
DROP TABLE IF EXISTS property_images;
DROP TABLE IF EXISTS amenities;
DROP TABLE IF EXISTS properties;
DROP TABLE IF EXISTS users;

-- 1. Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Properties Table
CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    city VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    gender ENUM('Male', 'Female', 'Unisex') NOT NULL DEFAULT 'Unisex',
    rating DECIMAL(3, 2) NOT NULL DEFAULT 4.0,
    description TEXT NOT NULL,
    main_image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Amenities Table
CREATE TABLE amenities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    icon VARCHAR(50) NOT NULL DEFAULT 'fa-check-circle'
);

-- 4. Property Amenities (Junction Table)
CREATE TABLE property_amenities (
    property_id INT NOT NULL,
    amenity_id INT NOT NULL,
    PRIMARY KEY (property_id, amenity_id),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
);

-- 5. Interested Users (Junction Table for Shortlist)
CREATE TABLE interested_users (
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, property_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- 6. Property Images Table (Gallery Support)
CREATE TABLE property_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- ===================================================
-- SEED DATA
-- ===================================================

-- Seed Amenities
INSERT INTO amenities (id, name, icon) VALUES
(1, 'High-Speed Wi-Fi', 'fa-wifi'),
(2, 'Air Conditioner', 'fa-snowflake'),
(3, '3 Meals Included', 'fa-utensils'),
(4, 'Laundry & Washing', 'fa-shirt'),
(5, '24/7 CCTV Security', 'fa-shield-halved'),
(6, 'Power Backup', 'fa-bolt'),
(7, 'Fitness Gym', 'fa-dumbbell'),
(8, 'Daily Housekeeping', 'fa-broom'),
(9, 'Hot Water Geyser', 'fa-shower'),
(10, 'Study Room', 'fa-book-open');

-- Seed Sample Users (Password for all demo users is 'password123')
INSERT INTO users (id, name, email, password, phone) VALUES
(1, 'Rahul Sharma', 'rahul@example.com', '$2y$10$e8q3.53wL0Z4QnL4M7.8u.x1jXqU/eU3O7.Y5fO3g9i0D5x2Y.4a2', '9876543210'),
(2, 'Priya Patel', 'priya@example.com', '$2y$10$e8q3.53wL0Z4QnL4M7.8u.x1jXqU/eU3O7.Y5fO3g9i0D5x2Y.4a2', '9876543211'),
(3, 'Aman Verma', 'aman@example.com', '$2y$10$e8q3.53wL0Z4QnL4M7.8u.x1jXqU/eU3O7.Y5fO3g9i0D5x2Y.4a2', '9876543212');

-- Seed Properties (Including Nashik & Famous Colleges)
INSERT INTO properties (id, name, city, address, price, gender, rating, description, main_image) VALUES
(1, 'Starlight Premium PG for Men', 'Mumbai', 'Near Mithibai College, Vile Parle West, Mumbai', 14500.00, 'Male', 4.8, 'Starlight Premium PG offers luxury air-conditioned double and triple sharing rooms for male students near Mithibai & NM College. Features high-speed internet, daily cleaning, nutritious home-style meals, and 24/7 security.', 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=800&q=80'),

(2, 'Zolo Habitat Girls PG', 'Bangalore', 'Koramangala 4th Block, Near Forum Mall, Bangalore', 12000.00, 'Female', 4.7, 'Modern, vibrant hostel exclusively for women near Christ University & Jyoti Nivas College. Features biometric entry, full power backup, high-speed Wi-Fi, fully furnished rooms with attached washrooms, and automated laundry services.', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80'),

(3, 'Heritage Student Living', 'Delhi', 'North Campus, Near Delhi University, Hudson Lane, Delhi', 9500.00, 'Unisex', 4.5, 'Spacious co-living space near DU North Campus (SRCC, Hansraj, Hindu College). Includes 3 wholesome meals daily, spacious study lounge, gaming area, and high-speed Wi-Fi.', 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=800&q=80'),

(4, 'Serene Boys Hostel', 'Pune', 'Viman Nagar, Near Symbiosis International University, Pune', 8500.00, 'Male', 4.3, 'Affordable and peaceful accommodation near Symbiosis International University & MIT WPU. Well-ventilated rooms with study desks, high-speed Wi-Fi, daily housekeeping, and 24-hour water supply.', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80'),

(5, 'Blissful Stay Girls Residency', 'Mumbai', 'Powai, Near IIT Bombay, Mumbai', 16000.00, 'Female', 4.9, 'Premium women residency located near IIT Bombay. Offers luxury AC rooms with private balconies, rooftop garden lounge, gym, CCTV surveillance, and 4-time meal facility.', 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80'),

(6, 'Greenwood Co-Living Space', 'Bangalore', 'HSR Layout Sector 1, Bangalore', 13500.00, 'Unisex', 4.6, 'State-of-the-art co-living facility designed for tech students and interns near NIFT Bangalore. Offers high-speed fiber internet, ergonomic work desks, recreational lounge, self-cooking kitchen option, and daily cleaning.', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80'),

(7, 'Royal Scholars PG for Men', 'Delhi', 'South Campus, Satya Niketan, Delhi', 11000.00, 'Male', 4.4, 'Prime location PG in Satya Niketan for DU South Campus (Venkateswara College, ARSD). Comes equipped with AC, geysers, nutritious meals, study room access, and laundry service.', 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80'),

(8, 'Comfort Nest PG', 'Pune', 'FC Road, Shivaji Nagar, Pune', 7800.00, 'Unisex', 4.2, 'Budget-friendly PG near Fergusson College & COEP Pune. Ideal for students seeking clean, peaceful rooms with high-speed internet, power backup, and close proximity to libraries and coaching centers.', 'https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=800&q=80'),

-- NASHIK PG PROPERTIES (Famous Colleges Included)
(9, 'KK Wagh Scholars PG for Men', 'Nashik', 'Panchavati, Near KK Wagh Engineering College, Nashik', 6500.00, 'Male', 4.7, 'Spacious and affordable PG for engineering students right near KK Wagh Institute of Engineering Education & Research (KKWIEER). Features high-speed Wi-Fi, 3 daily home-style Maharashtrian meals, study desks, power backup, and daily cleaning.', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80'),

(10, 'Sandip Campus Girls Residency', 'Nashik', 'Trimbak Road, Near Sandip University, Nashik', 7500.00, 'Female', 4.8, 'Premium female student residency located next to Sandip University & Sandip Foundation Campus. Features 24/7 CCTV security, biometric access, AC double rooms, hot water geyser, study room, and hygienic mess facilities.', 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=800&q=80'),

(11, 'MET Bhujbal Knowledge Hub PG', 'Nashik', 'Adgaon, Near MET League of Colleges, Nashik', 7000.00, 'Unisex', 4.6, 'Modern co-living accommodation for male & female students near MET League of Colleges (Bhujbal Knowledge City). Includes fiber internet, indoor games lounge, laundry, power backup, and daily housekeeping.', 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80'),

(12, 'Godavari Elite Co-Living', 'Nashik', 'College Road, Near BYK & KTHM College, Nashik', 8000.00, 'Unisex', 4.9, 'Prime student accommodation on College Road, Nashik. Walking distance to BYK College of Commerce, KTHM College, and Symbiosis SIOM. Includes 3 wholesome meals, high-speed Wi-Fi, AC, gym, and peaceful study atmosphere.', 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=800&q=80');

-- Seed Property Gallery Images
INSERT INTO property_images (property_id, image_url) VALUES
(1, 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=800&q=80'),
(1, 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=800&q=80'),

(2, 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80'),
(2, 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=800&q=80'),

(3, 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=800&q=80'),

(4, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80'),

(5, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80'),

(6, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80'),

(7, 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80'),

(8, 'https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=800&q=80'),

(9, 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80'),
(9, 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=800&q=80'),

(10, 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=800&q=80'),
(10, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80'),

(11, 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80'),

(12, 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=800&q=80'),
(12, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80');

-- Seed Property Amenities Mapping
INSERT INTO property_amenities (property_id, amenity_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 8), (1, 9),
(2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 8), (2, 9), (2, 10),
(3, 1), (3, 3), (3, 4), (3, 5), (3, 6), (3, 8), (3, 10),
(4, 1), (4, 4), (4, 5), (4, 6), (4, 8), (4, 9),
(5, 1), (5, 2), (5, 3), (5, 4), (5, 5), (5, 6), (5, 7), (5, 8), (5, 9), (5, 10),
(6, 1), (6, 2), (6, 4), (6, 5), (6, 6), (6, 7), (6, 8), (6, 10),
(7, 1), (7, 2), (7, 3), (7, 4), (7, 5), (7, 6), (7, 9),
(8, 1), (8, 4), (8, 5), (8, 6), (8, 8), (8, 9),
-- Nashik Properties
(9, 1), (9, 3), (9, 4), (9, 5), (9, 6), (9, 8), (9, 9), (9, 10),
(10, 1), (10, 2), (10, 3), (10, 4), (10, 5), (10, 6), (10, 8), (10, 9), (10, 10),
(11, 1), (11, 3), (11, 4), (11, 5), (11, 6), (11, 8), (11, 10),
(12, 1), (12, 2), (12, 3), (12, 4), (12, 5), (12, 6), (12, 7), (12, 8), (12, 9), (12, 10);

-- Seed Interested Users
INSERT INTO interested_users (user_id, property_id) VALUES
(1, 1), (1, 5), (1, 9), (2, 2), (2, 10);
