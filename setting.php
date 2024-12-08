<?php
session_start();
include 'config.php'; // Koneksi ke database

$email = $_SESSION['email']; // Ambil email pengguna dari session

// Proses upload avatar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $file_name = $_FILES['profile_picture']['name'];
    $file_tmp = $_FILES['profile_picture']['tmp_name'];
    $file_size = $_FILES['profile_picture']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Ekstensi yang diperbolehkan
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($file_ext, $allowed_extensions)) {
        if ($file_size < 5000000) { // Maksimal ukuran file 5MB
            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_dir = 'uploads/';
            move_uploaded_file($file_tmp, $upload_dir . $new_file_name);

            // Update avatar di database
            $query = "UPDATE users SET profile_picture = '$new_file_name' WHERE email = '$email'";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Avatar updated successfully!');</script>";
            } else {
                echo "<script>alert('Failed to update avatar in database.');</script>";
            }
        } else {
            echo "<script>alert('File size exceeds the limit.');</script>";
        }
    } else {
        echo "<script>alert('Invalid file type. Only JPG, PNG, and GIF allowed.');</script>";
    }
}

// Ambil data pengguna dari database
$query = "SELECT full_name, profile_picture FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="style_setting.css">
    <link rel="shortcut icon" href="safar-logo-fav.ico" type="image/x-icon">
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
            <h1>Settings</h1>
            <div class="profile-container">
                <div class="profile-info">
                    <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
                    <h3><?php echo $user['full_name']; ?></h3>
                </div>
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <label for="profile_picture">Upload Avatar:</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                    <button type="submit">Update Avatar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
