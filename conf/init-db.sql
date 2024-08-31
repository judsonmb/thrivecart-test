CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(10) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

INSERT INTO products (name, code, price) VALUES
('Red Widget', 'R01', 32.95),
('Green Widget', 'G01', 24.95),
('Blue Widget', 'B01', 7.95);