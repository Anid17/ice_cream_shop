DROP DATABASE IF EXISTS icecream_data;
CREATE DATABASE icecream_data CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE icecream_data;

-- USERS
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- CATEGORIES
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- PRODUCTS
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- PRODUCT-CATEGORIES (many-to-many)
CREATE TABLE product_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  category_id INT NOT NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ORDERS
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total DECIMAL(10,2) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- REVIEWS
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  order_id INT DEFAULT NULL,
  rating INT CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- SEED DATA
INSERT INTO users (username, email, password_hash)
VALUES ('anid','anid@example.com','pass123'),
       ('ilma','ilma@example.com','pass456');

INSERT INTO categories (name)
VALUES ('Ice cream'),('Waffles'),('Sundaes'),('Coffee');

INSERT INTO products (name, description, price)
VALUES ('Vanilla','Classic vanilla ice cream',2.00),
       ('Chocolate','Rich chocolate ice cream',2.00),
       ('Strawberry','Fresh strawberry ice cream',2.00),
       ('Nutella Waffle','Waffle with Nutella',8.00),
       ('Espresso','Strong espresso coffee',2.00);

INSERT INTO product_categories (product_id, category_id)
VALUES (1,1),(2,1),(3,1),(4,2),(5,4);

INSERT INTO orders (user_id, total)
VALUES (1,4.00),(2,10.00);

INSERT INTO reviews (user_id, product_id, order_id, rating, comment)
VALUES (1,1,1,5,'Absolutely delicious vanilla!'),
       (2,4,2,4,'Nutella waffle was great!');
