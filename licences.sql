CREATE TABLE licenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    license_key VARCHAR(255) UNIQUE NOT NULL,
    domain VARCHAR(255) NOT NULL,
    product_name VARCHAR(255),
    status ENUM('active', 'blocked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
