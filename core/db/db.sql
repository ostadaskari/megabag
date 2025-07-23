CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NULL,
    family VARCHAR(100) NULL,
    email VARCHAR(100) NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role ENUM('admin', 'manager', 'user') NOT NULL,
    nickname VARCHAR(100) DEFAULT NULL,
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
    username VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    status ENUM('ok', 'wrong pass', 'wrong captcha', 'unknown') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Ban List Table
CREATE TABLE bans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    username VARCHAR(50),
    ban_time DATETIME NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    user_id INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);






