<?php
session_start();

// Cek apakah sesi sudah dimulai dan user_id ada di sesi
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php'; // Koneksi ke database

$user_id = $_SESSION['user_id'];

// Fungsi untuk memeriksa apakah gambar wajah cocok dengan user
function verify_face($image_data, $user_id) {
    return true; // Simulasi verifikasi wajah
}

// Proses verifikasi wajah
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['start_recognition'])) {
    if (isset($_POST['image_data'])) {
        $image_data = $_POST['image_data']; // Data gambar dalam format base64

        $verification_successful = verify_face($image_data, $user_id);

        if ($verification_successful) {
            $date = date('Y-m-d');
            $status = 'Hadir'; 

            // Cek apakah data kehadiran untuk hari ini sudah ada
            $query_check = "SELECT * FROM attendance WHERE user_id = '$user_id' AND date = '$date'";
            $result_check = mysqli_query($conn, $query_check);

            if (mysqli_num_rows($result_check) == 0) {
                $query_insert = "INSERT INTO attendance (user_id, date, status) VALUES ('$user_id', '$date', '$status')";
                if (mysqli_query($conn, $query_insert)) {
                    echo "<p style='color: green;'>Kehadiran berhasil dicatat!</p>";
                } else {
                    echo "<p style='color: red;'>Terjadi kesalahan saat mencatat kehadiran: " . mysqli_error($conn) . "</p>";
                }
            } else {
                echo "<p style='color: orange;'>Kehadiran sudah tercatat untuk hari ini.</p>";
            }
        } else {
            echo "<p style='color: red;'>Verifikasi wajah gagal. Silakan coba lagi.</p>";
        }
    }
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
        <p>Silakan verifikasi wajah Anda untuk melakukan absensi.</p>

        <!-- Video Element untuk Menampilkan Kamera -->
        <video id="video" width="640" height="480" autoplay></video>
        <button id="start-button">Start Absen</button>

        <!-- Canvas Element untuk Menyimpan Snapshot -->
        <canvas id="canvas" style="display:none;"></canvas>

        <form id="absen-form" action="verify_face.php" method="POST">
            <input type="hidden" name="image_data" id="image-data">
            <button type="submit" name="start_recognition" id="submit-btn" style="display:none;">Submit Absen</button>
        </form>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const startButton = document.getElementById('start-button');
        const submitBtn = document.getElementById('submit-btn');
        const imageDataInput = document.getElementById('image-data');

        // Fungsi untuk mengakses kamera
        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    video.srcObject = stream;
                })
                .catch(function(error) {
                    console.log('Error accessing camera: ', error);
                });
        }

        // Fungsi untuk mengambil snapshot dari video
        function takeSnapshot() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/png');
            imageDataInput.value = dataURL; // Menyimpan data gambar dalam base64
            submitBtn.style.display = 'inline'; // Tampilkan tombol submit setelah mengambil gambar
        }

        // Event listener untuk memulai absensi
        startButton.addEventListener('click', function() {
            startCamera();
            startButton.style.display = 'none'; // Sembunyikan tombol start setelah diklik
            setTimeout(takeSnapshot, 2000); // Ambil snapshot setelah 2 detik
        });
    </script>
</body>
</html>
