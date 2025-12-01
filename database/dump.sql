-- E-Commerce Database Setup
-- Drop existing and start fresh
DROP DATABASE IF EXISTS ecommerce_db;
CREATE DATABASE ecommerce_db;
USE ecommerce_db;

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Auto-set when record created
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;  -- Supports all characters/emojis

INSERT INTO categories (name, description) VALUES
('Automotive', 'Car parts, accessories, and automotive products'),
('Technology', 'Electronics, gadgets, and tech accessories'),
('Cosmetics', 'Beauty products, skincare, and makeup'),
('Furniture', 'Home and office furniture');

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Stores hashed password, never plain text
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    is_admin TINYINT(1) DEFAULT 0,  -- 0 = regular user, 1 = admin
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Default users (admin password: admin123, user password: password)
INSERT INTO users (email, password, first_name, last_name, is_admin) VALUES
('admin@shop.com', '$2y$10$WQmJzb0G0.uk345rdf0isu7tPIsqKxTIkXT3sSvEvsh0lB5y8/JWG', 'Admin', 'User', 1),
('user@shop.com', '$2y$10$am.I7MDDjzmlfmlAIPOQkuaqp5LmvV.TEDG0tClt6/d5KcuLWE6yy', 'Test', 'User', 0);

-- Products table (linked to categories)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,  -- 10 digits total, 2 after decimal (e.g., 12345.67)
    image_url VARCHAR(500),
    stock INT DEFAULT 0,  -- Quantity available
    active TINYINT(1) DEFAULT 1,  -- 1 = visible in store, 0 = hidden
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Auto-updates when record changes
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,  -- If category deleted, set to NULL
    INDEX idx_category (category_id),  -- Speed up category searches
    INDEX idx_active (active)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample products: 4 per category

-- Automotive products
INSERT INTO products (category_id, name, description, price, stock, image_url) VALUES
(1, 'Car Floor Mats', 'All-weather heavy duty floor mats for cars and trucks. Universal fit with anti-slip backing.', 49.99, 35, 'https://m.media-amazon.com/images/I/81FJ9lYh4UL._AC_.jpg'),
(1, 'LED Headlight Bulbs', 'High-performance LED headlight conversion kit. 6000K white light, plug and play installation.', 89.99, 20, 'https://m.media-amazon.com/images/I/81AxNT9yj5L._SL1500_.jpg'),
(1, 'Tire Pressure Gauge', 'Digital tire pressure gauge with LCD display. Accurate readings for car, truck, and motorcycle tires.', 24.99, 50, 'https://m.media-amazon.com/images/I/61rVKUct5NL.jpg'),
(1, 'Car Phone Mount', 'Magnetic dashboard phone holder with adjustable viewing angles. Compatible with all smartphones.', 19.99, 75, 'https://m.media-amazon.com/images/I/81FGMqO5gML._AC_.jpg'),

-- Technology products
(2, 'Wireless Earbuds', 'True wireless earbuds with active noise cancellation. 24-hour battery life with charging case.', 129.99, 45, 'https://m.media-amazon.com/images/I/7159EmTam6L._AC_.jpg'),
(2, 'Smart Watch Pro', 'Fitness tracker with heart rate monitor, GPS, and sleep tracking. Water resistant up to 50m.', 249.99, 28, 'https://m.media-amazon.com/images/I/71JU-bUt-sL._AC_SL1500_.jpg'),
(2, 'Wireless Keyboard', 'Mechanical RGB keyboard with customizable backlighting. Bluetooth and USB connectivity.', 79.99, 32, 'https://m.media-amazon.com/images/I/61T79euEq2L._AC_SL1500_.jpg'),
(2, 'USB-C Hub Adapter', '7-in-1 USB-C hub with HDMI, USB 3.0, SD card reader, and PD charging. Perfect for laptops.', 39.99, 60, 'https://m.media-amazon.com/images/I/71FyOyxleDL._AC_SL1500_.jpg'),

-- Cosmetics products
(3, 'Vitamin C Serum', 'Anti-aging facial serum with 20% vitamin C. Brightens skin and reduces fine lines.', 34.99, 42, 'https://m.media-amazon.com/images/I/81BIka+CKIL._AC_.jpg'),
(3, 'Makeup Brush Set', 'Professional 12-piece makeup brush set with carrying case. Synthetic bristles, cruelty-free.', 44.99, 38, 'https://m.media-amazon.com/images/I/71hTpC71pLL._AC_.jpg'),
(3, 'Hydrating Face Mask', 'Sheet mask set with hyaluronic acid and collagen. Pack of 10 individually wrapped masks.', 24.99, 55, 'https://cdn.shopify.com/s/files/1/0022/1557/5609/products/SkinRepublicHyaluronicAcid_CollagenFaceMask_10packstack2000x2000_600x.png?v=1606178047'),
(3, 'Natural Lip Balm Set', 'Organic lip balm trio with shea butter and vitamin E. Aloe Vera, Moroccan Argan Oil, and Coconut Oil.', 14.99, 80, 'https://medino-product.imgix.net/dr-organic-lip-balm-gift-set-3-pack-7c8352c8.png?h=686&bg=FFF&auto=format,compress&q=60'),

-- Furniture products
(4, 'Office Desk Chair', 'Ergonomic mesh office chair with lumbar support and adjustable height. 360-degree swivel.', 189.99, 18, 'https://m.media-amazon.com/images/I/615R0pQlY4L._AC_SL1500_.jpg'),
(4, 'Bookshelf 5-Tier', 'Modern wooden bookshelf with 5 shelves. Perfect for books, plants, and decor. Easy assembly.', 119.99, 22, 'https://i5.walmartimages.com/seo/Dextrus-5-Tier-Bookshelf-Sturdy-Wood-Storage-Bookcase-Shelves-with-Metal-Frame-Plant-Display-for-Living-Room-Office-White_eb950bf4-3b8f-4612-b7ad-68ac5eb27018.61685b64c067b274260723cba6785c86.jpeg'),
(4, 'Coffee Table', 'Rustic wood and metal coffee table with lower storage shelf. Fits most living room layouts.', 159.99, 15, 'https://m.media-amazon.com/images/I/71vS6TGjEgL.jpg'),
(4, 'Floor Lamp LED', 'Modern arc floor lamp with dimmable LED light. Adjustable height and angle, energy efficient.', 79.99, 30, 'https://m.media-amazon.com/images/I/616j--FwnVL._AC_SL1500_.jpg');

-- Cosmetics products
(3, 'Vitamin C Serum', 'Anti-aging facial serum with 20% vitamin C. Brightens skin and reduces fine lines.', 34.99, 42, 'https://via.placeholder.com/400x400/6b9b37/ffffff?text=Vitamin+Serum'),
(3, 'Makeup Brush Set', 'Professional 12-piece makeup brush set with carrying case. Synthetic bristles, cruelty-free.', 44.99, 38, 'https://via.placeholder.com/400x400/6b9b37/ffffff?text=Brush+Set'),
(3, 'Hydrating Face Mask', 'Sheet mask set with hyaluronic acid and collagen. Pack of 10 individually wrapped masks.', 24.99, 55, 'https://via.placeholder.com/400x400/6b9b37/ffffff?text=Face+Masks'),
(3, 'Natural Lip Balm Set', 'Organic lip balm trio with shea butter and vitamin E. Vanilla, mint, and cherry flavors.', 14.99, 80, 'https://via.placeholder.com/400x400/6b9b37/ffffff?text=Lip+Balm'),

-- Furniture products
(4, 'Office Desk Chair', 'Ergonomic mesh office chair with lumbar support and adjustable height. 360-degree swivel.', 189.99, 18, 'https://via.placeholder.com/400x400/1b5e20/ffffff?text=Office+Chair'),
(4, 'Bookshelf 5-Tier', 'Modern wooden bookshelf with 5 shelves. Perfect for books, plants, and decor. Easy assembly.', 119.99, 22, 'https://via.placeholder.com/400x400/1b5e20/ffffff?text=Bookshelf'),
(4, 'Coffee Table', 'Rustic wood and metal coffee table with lower storage shelf. Fits most living room layouts.', 159.99, 15, 'https://via.placeholder.com/400x400/1b5e20/ffffff?text=Coffee+Table'),
(4, 'Floor Lamp LED', 'Modern arc floor lamp with dimmable LED light. Adjustable height and angle, energy efficient.', 79.99, 30, 'https://via.placeholder.com/400x400/1b5e20/ffffff?text=Floor+Lamp');

-- Addresses table (for shipping)
CREATE TABLE addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) DEFAULT 'USA',
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (address_id) REFERENCES addresses(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_order_date (order_date)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Order items table (what products are in each order)
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;