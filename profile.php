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

// Baca data pengguna dari database
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Proses hapus akun
if (isset($_GET['delete']) && $_GET['delete'] == 'yes') {
    $delete_query = "DELETE FROM users WHERE email = '$email'";

    if (mysqli_query($conn, $delete_query)) {
        // Hapus session dan redirect ke halaman login
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        echo "<script>alert('Terjadi kesalahan dalam menghapus akun.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFAR - Profile</title>
    <link rel="stylesheet" href="style_profile.css">
    <link rel="shortcut icon" href="safar-logo-fav.ico" type="image/x-icon">
    <style>
        /* Gaya tambahan untuk avatar dan gambar profil */
        .profile-container img {
            border-radius: 50%;
        }

        .profile-container .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 40px;
            color: white;
            font-weight: bold;
        }
    </style>
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
                <h1>Profile</h1>
            </div>
            <div class="profile-container">
                <h2>User Information</h2>
                <table class="profile-info">
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?php echo $user['email']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Full Name:</strong></td>
                        <td><?php echo $user['full_name']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Profile Picture:</strong></td>
                        <td>
                            <?php
                            // Cek apakah foto profil ada
                            if (!empty($user['profile_picture'])) {
                                echo "<img src='uploads/" . $user['profile_picture'] . "' alt='Profile Picture' width='100' height='100' style='border-radius: 50%;'>";
                            } else {
                                // Ambil inisial dari nama lengkap
                                $initials = strtoupper($user['full_name'][0]);
                                echo "<div class='avatar'>$initials</div>";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
                <div class="actions">
                    <a href="setting.php">Update Profile</a>
                    <a href="profile.php?delete=yes" class="delete-btn" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
