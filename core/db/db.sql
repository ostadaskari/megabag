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
    tag VARCHAR(60),
    part_number VARCHAR(80) NOT NULL UNIQUE,      -- Renamed "p-n" to "part_number"
    mfg VARCHAR(80),                            -- Manufacturer
    qty MEDIUMINT UNSIGNED DEFAULT 0,              -- 0 to 16777215
    company_cmt TEXT,                            -- company comment
    location VARCHAR(100),                         -- location address in stock 
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


CREATE TABLE images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,       -- original filename
    file_path VARCHAR(255) NOT NULL,       -- location on disk/server
    file_size INT UNSIGNED,                -- file weight in bytes
    file_extension VARCHAR(10),            -- helpful to enforce type (e.g., "jpg", "png", "pdf")
    is_cover TINYINT(1) DEFAULT 0,
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
-- This table tracks individual lots of products with unique x-codes,

CREATE TABLE product_lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,            -- Links to the general product
    user_id INT NULL,                   -- User who created/owns the lot (nullable)
    purchase_code VARCHAR(40),          -- The specific purchase code (nullable now)
    x_code VARCHAR(70) NOT NULL UNIQUE, -- The unique x_code for the physical pack
    vrm_x_code VARCHAR(70) UNIQUE,      -- The unique vrm_x_code for the physical pack
    qty_received INT UNSIGNED DEFAULT 0,  -- The original quantity in this lot
    qty_available INT UNSIGNED DEFAULT 0, -- The current quantity left in this lot
    date_code YEAR,                     -- year of made
    lot_location VARCHAR(30),           -- The location of the lot
    project_name VARCHAR(30),           -- The project associated with the lot
    lock BOOLEAN,                       -- Flag to indicate if a lot is locked
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);


CREATE TABLE stock_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_lot_id INT,
    user_id INT,
    qty_received INT NOT NULL,
    remarks TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_lot_id) REFERENCES product_lots(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE stock_issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_lot_id INT,
    user_id INT,
    issued_to INT,
    qty_issued INT NOT NULL,
    remarks TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_lot_id) REFERENCES product_lots(id) ON DELETE SET NULL,
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
-- Features Table
-- This table defines the available features for each category.
-- The `data_type` column is updated to include 'range' and 'multiselect'.
-- A new `metadata` column is added to store configuration details
-- as a JSON object, making the schema more flexible.
--
CREATE TABLE features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(60) NOT NULL,
    data_type ENUM('varchar(50)', 'decimal(15,7)', 'TEXT', 'boolean', 'range', 'multiselect') DEFAULT 'varchar(50)',
    unit VARCHAR(50) DEFAULT NULL,
    is_required BOOLEAN DEFAULT FALSE,
    metadata JSON DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

--
-- Product Feature Values (Pivot Table)
-- This table stores the specific values for each product's features.
-- The `value` column now stores JSON data for complex types.
-- The `unit` column is removed because the unit is already defined in the `features` table.
--
CREATE TABLE product_feature_values (
    product_id INT NOT NULL,
    feature_id INT NOT NULL,
    value JSON NOT NULL, -- Storing the value as JSON for flexibility
    PRIMARY KEY (product_id, feature_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (feature_id) REFERENCES features(id) ON DELETE CASCADE
);

-- each project has many products a
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,  -- New column to link to the users table
    project_name VARCHAR(40),
    employer VARCHAR(60),
    designators TEXT,
    status ENUM('pending', 'finished') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

--
-- The product-project relationship table remains unchanged
-- as it correctly links projects and products.
--
CREATE TABLE project_products (
    project_id INT NOT NULL,
    product_lot_id INT NOT NULL,
    used_qty INT UNSIGNED NOT NULL,
    remarks TEXT,
    PRIMARY KEY (project_id, product_lot_id),
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (product_lot_id) REFERENCES product_lots(id) ON DELETE CASCADE
);







