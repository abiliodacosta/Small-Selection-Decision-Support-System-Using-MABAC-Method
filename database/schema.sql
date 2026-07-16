
CREATE DATABASE IF NOT EXISTS dss_mabac_db;
USE dss_mabac_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'user') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE criteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) UNIQUE,
    name VARCHAR(100),
    type ENUM('Cost', 'Benefit'),
    weight DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    nik VARCHAR(20) UNIQUE,
    address TEXT,
    family_members INT,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidate_id INT,
    criterion_id INT,
    score DECIMAL(10,2),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
    FOREIGN KEY (criterion_id) REFERENCES criteria(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@dss.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- password is 'password'
INSERT INTO criteria (code, name, type, weight) VALUES 
('C1', 'Rendimentu Família', 'Cost', 30.00),
('C2', 'Kondisaun Fíziku Uma', 'Benefit', 25.00),
('C3', 'Totál Membru Família', 'Benefit', 20.00),
('C4', 'Status Empregu Xefe Família', 'Cost', 15.00),
('C5', 'Asesu ba Saneamentu / Bee', 'Cost', 10.00);
