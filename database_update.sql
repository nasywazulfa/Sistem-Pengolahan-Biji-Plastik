-- =====================================================
-- UPDATE DATABASE - TAMBAHAN TABEL
-- SISTEM INFORMASI PENGOLAHAN LIMBAH PLASTIK
-- =====================================================

USE db_plastik;

-- =====================================================
-- 1. TABEL log_aktivitas (Log Semua Aktivitas)
-- =====================================================
CREATE TABLE IF NOT EXISTS log_aktivitas (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    aktivitas VARCHAR(255) NOT NULL,
    tabel VARCHAR(50) NULL,
    data_id INT(11) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    INDEX idx_tabel (tabel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- 2. TABEL notifikasi (Notifikasi Sistem)
-- =====================================================
CREATE TABLE IF NOT EXISTS notifikasi (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    judul VARCHAR(100) NOT NULL,
    pesan TEXT NOT NULL,
    tipe ENUM('info', 'success', 'warning', 'danger') DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- 3. CEK HASIL
-- =====================================================
SELECT '✅ Tabel log_aktivitas berhasil dibuat!' AS 'INFO';
SELECT '✅ Tabel notifikasi berhasil dibuat!' AS 'INFO';

-- Tampilkan semua tabel
SHOW TABLES;