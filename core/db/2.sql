-- This updated schema introduces a 'product_lots' table to handle specific batches
-- of products from different purchases, allowing for traceability by purchase code.

-- Users Table
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

-- Invite Codes Table
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

-- Login Logs Table
CREATE TABLE login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45),
    username VARCHAR(100),
    status ENUM('ok', 'wrong pass', 'wrong captcha', 'unknown'),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Ban List Table
CREATE TABLE bans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    user_id INT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Categories Table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Products Table
-- Note: The 'qty' and 'used_qty' columns are now moved to the 'product_lots' table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    tag VARCHAR(60),
    part_number VARCHAR(80) NOT NULL UNIQUE,      -- The unique identifier for the type of product
    mfg VARCHAR(80),                            -- Manufacturer
    company_cmt TEXT,                            -- company comment
    -- 'location' is now a general location for the part number, not a specific pack
    location VARCHAR(100),                         
    rf BOOLEAN DEFAULT FALSE,                      
    status ENUM('available', 'unavailable') DEFAULT 'available',
    user_id INT,                               -- Submitter's user ID
    category_id INT,                           -- Connects to the last child of categories
    date_code YEAR,                               -- Use the YEAR data type for year values
    recieve_code VARCHAR(80),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- Foreign Key Constraints
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- New Table: suppliers
CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255),
    phone VARCHAR(50),
    email VARCHAR(100),
    address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- New Table: product_lots
-- This table tracks individual batches (packs) of products with unique purchase codes and tags.
-- This is the core solution to your problem.
CREATE TABLE product_lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,                  -- Links to the general product
    purchase_code VARCHAR(70) NOT NULL,       -- The specific purchase code
    tag VARCHAR(100) NOT NULL UNIQUE,         -- The unique tag for the physical pack
    supplier_id INT,                          -- New foreign key to track the supplier
    qty_received INT UNSIGNED DEFAULT 0,      -- The original quantity in this lot
    qty_available INT UNSIGNED DEFAULT 0,     -- The current quantity left in this lot
    location VARCHAR(100),                    -- The specific location of this pack
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
);

-- Images Table
CREATE TABLE images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size INT UNSIGNED,
    file_extension VARCHAR(10),
    is_cover TINYINT(1) DEFAULT 0,
    mime_type VARCHAR(50),
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- PDFs Table
CREATE TABLE pdfs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size INT UNSIGNED,
    file_extension VARCHAR(10),
    mime_type VARCHAR(50),
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Modified Table: stock_receipts
-- Now links directly to the 'product_lots' record that was created
CREATE TABLE stock_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_lot_id INT,                       -- Link to the specific pack/lot created
    user_id INT,
    qty_received INT NOT NULL,
    remarks TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_lot_id) REFERENCES product_lots(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Modified Table: stock_issues
-- Now links directly to the 'product_lots' record from which the item was taken
CREATE TABLE stock_issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_lot_id INT,                       -- Link to the specific pack/lot used
    user_id INT,
    issued_to INT,
    qty_issued INT NOT NULL,
    remarks TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_lot_id) REFERENCES product_lots(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (issued_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Uploaded CSVs Table
CREATE TABLE uploaded_csvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_size INT NOT NULL,
    status ENUM('pending', 'checked', 'processed') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Features Table
CREATE TABLE features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(60) NOT NULL,
    data_type ENUM('varchar(50)', 'decimal(12,3)', 'TEXT', 'boolean') DEFAULT 'varchar(50)',
    unit VARCHAR(50) DEFAULT NULL,
    is_required BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Product Feature Values Table
CREATE TABLE product_feature_values (
    product_id INT NOT NULL,
    feature_id INT NOT NULL,
    value TEXT NOT NULL,
    unit VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (product_id, feature_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (feature_id) REFERENCES features(id) ON DELETE CASCADE
);

-- Projects Table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_name VARCHAR(40),
    date_code VARCHAR(50),
    employer VARCHAR(60),
    purchase_code VARCHAR(70),
    designators TEXT,
    status ENUM('pending', 'finished') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Project Products Table
CREATE TABLE project_products (
    project_id INT NOT NULL,
    product_id INT NOT NULL,
    used_qty INT UNSIGNED NOT NULL,
    remarks TEXT,
    PRIMARY KEY (project_id, product_id),
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
