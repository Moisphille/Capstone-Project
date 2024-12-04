<?php
session_start();

// Pastikan user sudah login dengan mengecek session email
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Direktori untuk menyimpan file gambar yang di-upload
$uploadDir = 'uploads/';

// Variabel untuk menampilkan pesan error atau sukses
$message = '';

// Proses upload gambar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['reference_image'])) {
    $imageName = $_FILES['reference_image']['name'];
    $imageTmpName = $_FILES['reference_image']['tmp_name'];
    $imagePath = $uploadDir . basename($imageName);

    // Periksa apakah gambar berhasil di-upload
    if (move_uploaded_file($imageTmpName, $imagePath)) {
        $message = "Gambar berhasil di-upload!";
    } else {
        $message = "Maaf, terjadi kesalahan saat mengupload file.";
    }
}

// Fungsi untuk memulai verifikasi wajah menggunakan Python
function startFaceRecognition($imagePath) {
    $pythonScript = "python verify_face.py " . escapeshellarg($imagePath);
    // Menjalankan script Python untuk verifikasi wajah
    $output = shell_exec($pythonScript);
    return $output;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Face</title>
    <link rel="stylesheet" href="style_verify.css">
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h2>SAFAR</h2>
        <a href="home.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="setting.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?php echo $_SESSION['full_name']; ?>!</h1>
            <p>Email: <?php echo $_SESSION['email']; ?></p>
        </div>

        <!-- Form Upload Image -->
        <div class="upload-form">
            <h2>Upload Reference Image</h2>
            <?php if ($message != ''): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <form action="verify_face.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="reference_image" required>
                <button type="submit">Upload</button>
            </form>
        </div>

        <!-- Verifikasi Wajah -->
        <?php if (!empty($imagePath)): ?>
            <div class="verify-action">
                <h2>Start Face Recognition</h2>
                <!-- Button untuk memulai verifikasi wajah menggunakan kamera -->
                <form action="python-scripts\verify_face.py" method="POST">
                    <input type="hidden" name="image_path" value="<?php echo $imagePath; ?>">
                    <button type="submit" name="start_recognition">Start Recognition</button>
                </form>

                <?php
                // Jika tombol Start Recognition ditekan, jalankan proses verifikasi wajah
                if (isset($_POST['start_recognition'])) {
                    $imagePath = $_POST['image_path'];
                    $result = startFaceRecognition($imagePath); // Memulai verifikasi wajah menggunakan Python
                    echo "<p>Hasil Verifikasi: $result</p>";
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
