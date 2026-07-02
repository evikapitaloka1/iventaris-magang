-- 1. Tabel roles (Manajemen Hak Akses)
CREATE TABLE roles (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE, -- Admin, Staff, Manager
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabel users (Autentikasi & Data Pengguna)
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    role_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
);

-- 3. Tabel categories (Master Data Kategori)
CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tabel products (Master Data Barang)
CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NOT NULL,
    product_code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0 CHECK (stock >= 0),
    location VARCHAR(255) NOT NULL,
    condition VARCHAR(50) NOT NULL, -- Contoh: 'Baik', 'Rusak Ringan', 'Rusak Berat'
    image_path VARCHAR(255), -- Mengakomodasi Bonus Fitur: Upload Gambar Barang
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- 5. Tabel borrowings (Header Data Peminjaman)
CREATE TABLE borrowings (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL, -- Mengambil Nama Peminjam dari tabel users
    borrow_date DATE NOT NULL,
    return_date DATE,
    status VARCHAR(50) NOT NULL, -- Contoh: 'Sedang Dipinjam', 'Selesai'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 6. Tabel borrowing_details (Detail Barang pada Peminjaman)
-- Tabel ini penting jika 1 transaksi peminjaman melibatkan lebih dari 1 barang (keranjang)
CREATE TABLE borrowing_details (
    id BIGSERIAL PRIMARY KEY,
    borrowing_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    qty INTEGER NOT NULL DEFAULT 1,
    item_status VARCHAR(50) NOT NULL, -- Status barang spesifik (misal saat dikembalikan ternyata rusak)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (borrowing_id) REFERENCES borrowings(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

-- ==========================================
-- DATA DUMMY AWAL (Seeder Sederhana)
-- ==========================================

-- Insert Roles
INSERT INTO roles (name) VALUES 
('Admin'), 
('Staff'), 
('Manager');

-- Insert Categories (Kategori IT)
INSERT INTO categories (name, description) VALUES 
('Perangkat Jaringan', 'Router, Switch, Access Point, Firewall'),
('Komputer & Server', 'Laptop Karyawan, PC Desktop, Server Rack'),
('Aksesoris IT', 'Keyboard, Mouse, Kabel UTP, Monitor');

-- Insert Products (Barang IT)
INSERT INTO products (category_id, product_code, name, stock, location, condition) VALUES 
(1, 'NET-RTR-001', 'Cisco Router 2960', 5, 'Ruang Server Lantai 2', 'Baik'),
(2, 'COM-LPT-015', 'Lenovo ThinkPad T14', 20, 'Gudang IT Utama', 'Baik'),
(3, 'ACC-KBL-055', 'Kabel UTP Cat6 50m', 15, 'Lemari Rak IT A', 'Baik');