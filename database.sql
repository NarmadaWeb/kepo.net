CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    speed VARCHAR(50) NOT NULL,
    description TEXT,
    monthly_price DECIMAL(10, 2) NOT NULL,
    installation_fee DECIMAL(10, 2) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE technicians (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    employee_id VARCHAR(50) NOT NULL UNIQUE,
    phone VARCHAR(20),
    status ENUM('online', 'offline') DEFAULT 'offline',
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    package_id INT NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100),
    district VARCHAR(100),
    status ENUM('pending', 'waiting_payment', 'paid', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    total_amount DECIMAL(10, 2) NOT NULL,
    installation_date DATETIME,
    technician_id INT,
    ktp_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (package_id) REFERENCES packages(id),
    FOREIGN KEY (technician_id) REFERENCES technicians(id)
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    transaction_id VARCHAR(100),
    payment_type VARCHAR(50),
    gross_amount DECIMAL(10, 2),
    transaction_status VARCHAR(50),
    transaction_time DATETIME,
    settlement_time DATETIME,
    snap_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE coverage_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    status ENUM('strong', 'medium', 'low') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE odp_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    area_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    capacity INT DEFAULT 16,
    used INT DEFAULT 0,
    status ENUM('active', 'full', 'maintenance') DEFAULT 'active',
    FOREIGN KEY (area_id) REFERENCES coverage_areas(id)
);

-- Insert sample admin
INSERT INTO users (name, email, password, role) VALUES ('Admin Kepo', 'admin@kepo.net', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample packages
INSERT INTO packages (name, speed, description, monthly_price, installation_fee) VALUES
('Starter', '20 Mbps', 'Cocok untuk 3-5 perangkat, Streaming HD lancar', 150000, 200000),
('Pro', '50 Mbps', 'Cocok untuk 5-10 perangkat, 4K Streaming & Gaming', 250000, 150000),
('Enterprise', '100 Mbps', '10+ Perangkat aktif, Ideal untuk kantor', 450000, 0);
