-- Membuat database safar jika belum ada
-- CREATE DATABASE IF NOT EXISTS safar;

-- Menggunakan database safar
USE safc8432_safar;

-- Membuat tabel users dengan kolom yang sesuai
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- ID Pengguna (otomatis meningkat)
    email VARCHAR(100) NOT NULL UNIQUE,     -- Email pengguna (unique agar tidak duplikat)
    password VARCHAR(255) NOT NULL,         -- Password yang di-hash
    full_name VARCHAR(100) NOT NULL,        -- Nama lengkap pengguna
    nim_nip VARCHAR(50) NOT NULL,           -- NIM atau NIP pengguna
    profile_picture VARCHAR(255),           -- Kolom untuk menyimpan nama file avatar
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Waktu pendaftaran
);

-- Membuat tabel attendance untuk menyimpan data kehadiran
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- ID Kehadiran (otomatis meningkat)
    user_id INT NOT NULL,                   -- ID Pengguna (mengacu ke tabel users)
    date DATE NOT NULL,                     -- Tanggal kehadiran
    status ENUM('Hadir', 'Absen') NOT NULL, -- Status Kehadiran (Hadir/Absen)
    face_verification_status ENUM('Pending', 'Verified', 'Failed') DEFAULT 'Pending', -- Status verifikasi wajah
    face_similarity FLOAT(5, 2) DEFAULT NULL, -- Kesamaan wajah dalam bentuk nilai (0-1)
    FOREIGN KEY (user_id) REFERENCES users(id) -- Relasi dengan tabel users
);

-- Membuat tabel user_images untuk menyimpan gambar referensi wajah pengguna
CREATE TABLE IF NOT EXISTS user_images (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- ID Gambar Referensi (otomatis meningkat)
    user_id INT NOT NULL,                   -- ID Pengguna yang meng-upload gambar
    image_path VARCHAR(255) NOT NULL,       -- Path file gambar referensi
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Waktu upload gambar
    FOREIGN KEY (user_id) REFERENCES users(id) -- Relasi dengan tabel users
);
