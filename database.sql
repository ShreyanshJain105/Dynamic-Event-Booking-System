CREATE DATABASE event_booking;
USE event_booking;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    date DATETIME NOT NULL,
    venue VARCHAR(100) NOT NULL,
    available_seats INT NOT NULL
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Sample Data
INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@example.com', '$2y$10$z8L8z5Q8Qz5Qz5Qz5Qz5Qe5Qz5Qz5Qz5Qz5Qz5Qz5Qz5Qz5Qz5Qz5', 'admin'), -- Password: admin123
('John Doe', 'john@example.com', '$2y$10$z8L8z5Q8Qz5Qz5Qz5Qz5Qe5Qz5Qz5Qz5Qz5Qz5Qz5Qz5Qz5Qz5Qz5', 'user');  -- Password: user123

INSERT INTO events (title, description, date, venue, available_seats) VALUES 
('Tech Conference', 'Annual tech meetup', '2025-03-01 10:00:00', 'Convention Center', 50),
('Music Fest', 'Live music event', '2025-03-15 18:00:00', 'City Park', 100);