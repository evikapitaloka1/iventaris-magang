/* ==============================================================================
   BAGIAN 1: STRUKTUR DATABASE (MIGRATIONS)
============================================================================== */

-- 1. Create Users, Password Reset Tokens, dan Sessions Table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);

-- 2. Create Cache dan Cache Locks Table
CREATE TABLE cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration BIGINT NOT NULL,
    INDEX cache_expiration_index (expiration)
);

CREATE TABLE cache_locks (
    `key` VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration BIGINT NOT NULL,
    INDEX cache_locks_expiration_index (expiration)
);

-- 3. Create Jobs, Job Batches, dan Failed Jobs Table
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts SMALLINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX jobs_queue_index (queue)
);

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
);

CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection VARCHAR(255) NOT NULL,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX failed_jobs_connection_queue_failed_at_index (connection, queue, failed_at)
);

-- 4. Create Roles Table
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 5. Add Role ID to Users Table (Alter Table)
ALTER TABLE users 
ADD role_id BIGINT UNSIGNED NOT NULL DEFAULT 2 AFTER id;

ALTER TABLE users 
ADD CONSTRAINT users_role_id_foreign 
FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT;

-- 6. Create Categories Table
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 7. Create Products Table
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    product_code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    location VARCHAR(255) NOT NULL,
    `condition` VARCHAR(50) NOT NULL,
    image_path VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT products_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- 8. Create Borrowings Table
CREATE TABLE borrowings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    borrow_date DATE NOT NULL,
    return_date DATE NULL,
    status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT borrowings_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 9. Create Borrowing Details Table
CREATE TABLE borrowing_details (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    borrowing_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    qty INT NOT NULL DEFAULT 1,
    item_status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT borrowing_details_borrowing_id_foreign FOREIGN KEY (borrowing_id) REFERENCES borrowings(id) ON DELETE CASCADE,
    CONSTRAINT borrowing_details_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

-- 10. Add Avatar to Users Table (Alter Table)
ALTER TABLE users 
ADD avatar VARCHAR(255) NULL AFTER email;

-- 11. Add Due Date to Borrowings Table (Alter Table)
ALTER TABLE borrowings 
ADD due_date DATE NULL AFTER borrow_date;

-- 12. Add Photo to Borrowing Details Table (Alter Table)
ALTER TABLE borrowing_details 
ADD photo VARCHAR(255) NULL AFTER item_status;

-- 13. Create Notifications Table
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id BIGINT UNSIGNED NOT NULL,
    data TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX notifications_notifiable_type_notifiable_id_index (notifiable_type, notifiable_id)
);

-- 14. Add Purpose to Borrowings Table (Alter Table)
ALTER TABLE borrowings 
ADD purpose VARCHAR(255) NULL AFTER due_date;


/* ==============================================================================
   BAGIAN 2: PENGISIAN DATA AWAL (SEEDERS)
============================================================================== */

-- 1. Insert Data ke Tabel Roles (Dibutuhkan sebelum mengisi Users)
INSERT INTO roles (id, name, created_at, updated_at) VALUES
(1, 'Admin', NOW(), NOW()),
(2, 'Staff', NOW(), NOW()),
(3, 'Manager', NOW(), NOW());

-- 2. Insert Data ke Tabel Users
-- Password menggunakan hash "password" milik Laravel
INSERT INTO users (role_id, name, email, password, avatar, created_at, updated_at) VALUES
(1, 'Admin IT', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'images/ava-admin.jpg', NOW(), NOW()),
(2, 'Staff Karyawan', 'staff@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'images/ava-staff.jpg', NOW(), NOW()),
(3, 'Manager Operasional', 'manager@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'images/ava-manager.jpg', NOW(), NOW());

-- 3. Insert Data ke Tabel Categories (Dibutuhkan sebelum mengisi Products)
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES
(1, 'Perangkat Jaringan', 'Router, Switch, Access Point', NOW(), NOW()),
(2, 'Komputer & Server', 'Laptop, PC, Server', NOW(), NOW()),
(3, 'Aksesoris IT', 'Keyboard, Mouse, Kabel', NOW(), NOW());

-- 4. Insert Data ke Tabel Products
INSERT INTO products (category_id, product_code, name, stock, location, `condition`, image_path, created_at, updated_at) VALUES
(1, 'NET-001', 'Cisco Router 2960', 5, 'Ruang Server L2', 'Baik', 'images/router.jpg', NOW(), NOW()),
(2, 'COM-015', 'Lenovo ThinkPad T14', 20, 'Gudang IT', 'Baik', 'images/lenovo.jpg', NOW(), NOW()),
(3, 'ACC-055', 'Kabel UTP Cat6 50m', 15, 'Lemari A', 'Baik', 'images/kabel.jpg', NOW(), NOW());