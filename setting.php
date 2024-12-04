<?php
session_start();

// Pastikan pengguna sudah login dengan mengecek session email
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
include 'config.php';

$email = $_SESSION['email']; // Ambil email dari session

// Proses upload foto profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $file_name = $_FILES['profile_picture']['name'];
    $file_tmp = $_FILES['profile_picture']['tmp_name'];
    $file_size = $_FILES['profile_picture']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Tentukan ekstensi yang diperbolehkan
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($file_ext, $allowed_extensions)) {
        if ($file_size < 5000000) { // Maksimal ukuran file 5MB
            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_dir = 'uploads/';
            move_uploaded_file($file_tmp, $upload_dir . $new_file_name);
            
            // Update database dengan nama file baru
            $update_query = "UPDATE users SET profile_picture = '$new_file_name' WHERE email = '$email'";
            if (mysqli_query($conn, $update_query)) {
                echo "<script>alert('Profile picture updated successfully!');</script>";
            } else {
                echo "<script>alert('Failed to update profile picture.');</script>";
            }
        } else {
            echo "<script>alert('File size exceeds the limit.');</script>";
        }
    } else {
        echo "<script>alert('Invalid file type. Only JPG, PNG, and GIF are allowed.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFAR - Settings</title>
    <link rel="stylesheet" href="style_setting.css">
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
                <h1>Settings</h1>
            </div>
            <div class="profile-container">
                <h2>Update Profile</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="profile-info">
                        <label for="full_name">Full Name:</label>
                        <input type="text" name="full_name" value="<?php echo $_SESSION['full_name']; ?>" readonly>
                    </div>
                    <div class="profile-info">
                        <label for="password">New Password:</label>
                        <input type="password" name="password">
                    </div>
                    <div class="profile-info">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" name="confirm_password">
                    </div>
                    <div class="profile-info">
                        <label for="profile_picture">Upload Profile Picture:</label>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                    </div>
                    <div class="actions">
                        <button type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
