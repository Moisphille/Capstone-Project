-- Membuat database safar
CREATE DATABASE safar;

-- Menggunakan database safar
USE safar;

-- Membuat tabel users dengan kolom yang benar
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- ID Pengguna (otomatis meningkat)
    email VARCHAR(100) NOT NULL UNIQUE,     -- Email pengguna (unique agar tidak duplikat)
    password VARCHAR(255) NOT NULL,         -- Password yang di-hash
    full_name VARCHAR(100) NOT NULL,        -- Nama lengkap pengguna
    nim_nip VARCHAR(50) NOT NULL,           -- NIM atau NIP pengguna
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Waktu pendaftaran
);
