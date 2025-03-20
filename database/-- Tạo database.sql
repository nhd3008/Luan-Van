-- Bảng users
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) UNIQUE NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'user') DEFAULT 'user',
  age INT,
  health_goal VARCHAR(50),
  flavor_preference VARCHAR(50),
  lifestyle VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng health_goals
CREATE TABLE health_goals (
  goal_id INT AUTO_INCREMENT PRIMARY KEY,
  goal_name VARCHAR(100) UNIQUE NOT NULL
);

-- Bảng products
CREATE TABLE products (
  product_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT,
  category VARCHAR(100) NOT NULL DEFAULT 'Chung',
  discount DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  image_url VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng product_health_goals
CREATE TABLE product_health_goals (
  product_id INT,
  goal_id INT,
  PRIMARY KEY (product_id, goal_id),
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
  FOREIGN KEY (goal_id) REFERENCES health_goals(goal_id) ON DELETE CASCADE
);

-- Bảng nutrition_info
CREATE TABLE nutrition_info (
  nutrition_id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT,
  weight VARCHAR(50) NOT NULL,
  calories INT,
  vitamin_c VARCHAR(50),
  sugar DECIMAL(5,2),
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Bảng quiz_results (Chuyển JSON -> TEXT nếu cần)
CREATE TABLE quiz_results (
  quiz_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  recommended_products TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Bảng orders (Lưu thông tin đơn hàng)
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_price DECIMAL(10,2) NOT NULL,
    order_status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Bảng order_items (Lưu chi tiết sản phẩm trong đơn hàng)
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Bảng settings (Lưu thông tin cửa hàng)
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    store_name VARCHAR(255) NOT NULL DEFAULT 'Fruit For Health'
);

-- Thêm tài khoản admin mặc định
-- Thêm tài khoản admin với email 'admin@gmail.com' và mật khẩu 'admin123' (đã mã hóa)
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@gmail.com', '$2a$10$fY2OCfcCmTcSIY8l83IGIuoz8JaAAUtz8.xnrJLyRtl09TJZh1Nb.', 'admin');

-- Thêm cài đặt cửa hàng mặc định
INSERT INTO settings (store_name) VALUES ('Fruit For Health');
