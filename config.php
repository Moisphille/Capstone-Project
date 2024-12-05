<?php
// Konfigurasi untuk koneksi ke database
$host = "localhost"; // Ganti dengan host database Anda
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "safar"; // Nama database Anda

// Membuat koneksi ke database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Periksa koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
