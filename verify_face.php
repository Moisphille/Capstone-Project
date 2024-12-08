<?php
session_start();

$process = null;

// Cek apakah sesi user valid
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php'; // Koneksi ke database

$user_id = $_SESSION['user_id'];

// Fungsi untuk menjalankan skrip Python
function start_python_script() {
    global $process;
    $command = "python python-scripts/final.py";
    
    shell_exec($command);

    $descriptor = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        0 => ["pipe", "r"]
    ];
    $process  = proc_open($command, $descriptor, $pipes);

    if(!is_resource($process)){
        echo "machine learning tidak bekerja";
    }
}

// Fungsi untuk menghentikan skrip Python
function stop_python_script() {
    global $process;

    if (is_resource($process)){
        proc_terminate($process);
        proc_close($process);
        echo "setop dulu bang";
    }
    else{
        echo "tidak ada proses kamera yang berjalan";
    }
    // if
    // $signal_file = 'py';
    // file_put_contents($signal_file, 'stop');
}

// Proses tombol "Start Absen"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_absen'])) {
    start_python_script();
    echo "<p style='color: green;'>Skrip absensi telah dimulai!</p>";
}

// Proses tombol "Submit Absen"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_absen'])) {
    stop_python_script();
    echo "<p style='color: green;'>Skrip absensi telah dihentikan!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Wajah - SAFAR</title>
    <link rel="stylesheet" href="style_verify.css">
    <link rel="shortcut icon" href="safar-logo-fav.ico" type="image/x-icon">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">SAFAR</div>
        <ul class="navbar-links">
            <li><a href="home.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="container">
        <h2>Verifikasi Wajah</h2>
        <p>Silakan verifikasi wajah Anda untuk melakukan absensi. 
            Kamera akan melakukan verifikasi selama 5 detik.</p>

        <!-- Form untuk tombol absensi -->
        <form method="POST" action="">
            <!-- Tombol Mulai Absensi -->
            <button type="submit" name="start_absen" class="btn btn-start">Start Absen</button>
            
            <!-- Tombol Submit Absensi -->
            <button type="submit" name="submit_absen" class="btn btn-submit">Submit Absen</button>
        </form>
    </div>
</body>
</html>
