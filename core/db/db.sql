CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NULL,
    family VARCHAR(100) NULL,
    email VARCHAR(100) NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role ENUM('admin', 'manager', 'user') NOT NULL,
    nickname VARCHAR(100) DEFAULT NULL,
    is_blocked BOOLEAN NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE invite_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(100) NOT NULL UNIQUE,
    nickname VARCHAR(100) UNIQUE,
    role ENUM('admin', 'manager', 'user') NOT NULL,
    is_used TINYINT(1) DEFAULT 0,
    generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    used_at DATETIME NULL,

    created_by INT NOT NULL,
    used_by INT DEFAULT NULL,

    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (used_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45),
    username VARCHAR(100),
    status ENUM('ok', 'wrong pass', 'wrong captcha', 'unknown'),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- Ban List Table
DROP TABLE IF EXISTS bans;

CREATE TABLE bans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    user_id INT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    tag VARCHAR(60),
    part_number VARCHAR(80) NOT NULL UNIQUE,        -- Renamed "p-n" to "part_number"
    mfg VARCHAR(80),                         -- Manufacturer
    qty MEDIUMINT UNSIGNED DEFAULT 0,        -- 0 to 16777215
    company_cmt TEXT,                        --  company comment                                                                                                                                                                  
    location VARCHAR(100),                   -- location adrress in stock 
    status VARCHAR(80),                      -- You can later restrict this via ENUM or validation logic

    user_id INT ,                    -- Submitter's user ID
    category_id INT ,                -- Connects to the last child of categories

    date_code ENUM('2024', '2024+'),         -- Extend as needed
    recieve_code VARCHAR(80),

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- Foreign Key Constraints
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,       -- original filename
    file_path VARCHAR(255) NOT NULL,       -- location on disk/server
    file_size INT UNSIGNED,                -- file weight in bytes
    file_extension VARCHAR(10),            -- helpful to enforce type (e.g., "jpg", "png", "pdf")
    mime_type VARCHAR(50),                 -- useful for validation (image/png, application/pdf)
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- when the file was uploaded
 
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE  -- automatically deletes images/PDFs when the product is deleted
);

CREATE TABLE pdfs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,       -- original filename
    file_path VARCHAR(255) NOT NULL,       -- location on disk/server
    file_size INT UNSIGNED,                -- in bytes
    file_extension VARCHAR(10),            -- helpful to enforce type (e.g., "jpg", "png", "pdf") 
    mime_type VARCHAR(50),                 -- useful for validation (image/png, application/pdf) 
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,  -- when the file was uploaded

    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE  -- automatically deletes images/PDFs when the product is deleted
);

CREATE TABLE stock_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    user_id INT,
    qty_received INT NOT NULL,
    remarks TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE stock_issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    user_id INT,
    issued_to INT,
    qty_issued INT NOT NULL,
    remarks TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (issued_to) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE uploaded_csvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,        -- actual saved file name with unique prefix
    original_name VARCHAR(255) NOT NULL,    -- original filename from user
    file_size INT NOT NULL,
    status ENUM('pending', 'checked', 'processed') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

 -- adding some specific features to each category
CREATE TABLE features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(60) NOT NULL,
    data_type ENUM('varchar(50)', 'decimal(12,3)', 'TEXT', 'boolean') DEFAULT 'varchar(50)',
    unit VARCHAR(50) DEFAULT NULL,  -- example: 'kg', 'cm', '%', etc.
    is_required BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
-- each product could have feature values that is belonging to their features
-- Product Feature Values (Pivot Table)
-- Table: product_feature_values
CREATE TABLE product_feature_values (
    product_id INT NOT NULL,
    feature_id INT NOT NULL,
    value TEXT NOT NULL,
    unit VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (product_id, feature_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (feature_id) REFERENCES features(id) ON DELETE CASCADE
);





