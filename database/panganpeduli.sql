CREATE DATABASE IF NOT EXISTS panganpeduli CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE panganpeduli;

DROP TABLE IF EXISTS donations;
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE donations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  donor_name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL,
  title VARCHAR(120) NOT NULL,
  category ENUM('Buah','Sayuran','Roti','Makanan Siap Saji','Bahan Pokok','Lainnya') NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  unit ENUM('porsi','paket','kg','buah','kotak','bungkus') NOT NULL DEFAULT 'porsi',
  location VARCHAR(180) NOT NULL,
  expiry_at DATETIME NOT NULL,
  description VARCHAR(1200) DEFAULT NULL,
  status ENUM('Tersedia','Dipesan','Diambil','Selesai','Ditolak') NOT NULL DEFAULT 'Tersedia',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_status_expiry (status, expiry_at),
  INDEX idx_category (category),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- Demo login: admin@panganpeduli.local / password
-- Password hash for "password" generated with PHP password_hash().
INSERT INTO admins (name, email, password_hash) VALUES
('Administrator', 'admin@panganpeduli.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.');

INSERT INTO donations (donor_name, email, title, category, quantity, unit, location, expiry_at, description, status, created_at) VALUES
('Roti Pagi', 'contoh@panganpeduli.local', 'Roti tawar kemasan', 'Roti', 18, 'paket', 'Jepara Kota', DATE_ADD(NOW(), INTERVAL 2 DAY), 'Kemasan utuh. Mohon periksa label dan kondisi sebelum dibagikan.', 'Tersedia', NOW()),
('Kebun Segar', 'contoh@panganpeduli.local', 'Paket sayuran segar', 'Sayuran', 12, 'paket', 'Bangsri, Jepara', DATE_ADD(NOW(), INTERVAL 1 DAY), 'Sayuran hasil panen. Periksa kondisi dan bersihkan sebelum diolah.', 'Tersedia', NOW()),
('Dapur Berbagi', 'contoh@panganpeduli.local', 'Paket bahan pokok', 'Bahan Pokok', 10, 'paket', 'Mlonggo, Jepara', DATE_ADD(NOW(), INTERVAL 3 DAY), 'Bahan pokok kemasan. Periksa segel dan tanggal kedaluwarsa.', 'Tersedia', NOW());
