<?php
session_start();

// Koneksi ke database
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Gunakan prepared statement untuk keamanan
    $query = "SELECT * FROM users WHERE email = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, 's', $email);
        
        // Execute statement
        mysqli_stmt_execute($stmt);
        
        // Ambil hasilnya
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 1) {
            // Ambil data pengguna
            $row = mysqli_fetch_assoc($result);

            // Verifikasi password dengan password yang ada di database
            if (password_verify($password, $row['password'])) {
                // Set session untuk email, nama, dan NIP/NIM
                $_SESSION['email'] = $row['email'];
                $_SESSION['full_name'] = $row['full_name'];       // Ambil kolom nama
                $_SESSION['nip_nim'] = $row['nip_nim']; // Ambil kolom NIP/NIM

                // Redirect ke dashboard
                header("Location: home.php");
                exit();
            } else {
                echo "Password salah.";
            }
        } else {
            echo "Login gagal. Email atau password salah.";
        }

        // Menutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Terjadi kesalahan dalam database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFAR - Login</title>
    <link rel="stylesheet" href="style_login.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-box">
                <h2>Login</h2>
                <form method="POST" action="">
                    <div class="input-box">
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <button type="submit" class="btn">Login</button>
                </form>
            </div>

            <div class="form-box">
                <p>Don't have an account? <a href="signup.php">Signup here</a></p>
            </div>
        </div>
    </div>

    <!-- Informasi Developer (Tautan yang menonjolkan bagian developer) -->
    <div class="developer-info-link">
        <p>Want to know more about the developers behind SAFAR? <a href="#about-developers">Click here to meet the team!</a></p>
    </div>

    <!-- About the Developer Section -->
    <div id="about-developers" class="developer-info">
        <h3>About the Developers</h3>

        <div class="developer">
            <img src="aya.jpeg" alt="Developer 1 Photo" class="developer-photo">
            <p><strong>Developer 1</strong></p>
            <p>Motivation: Building a seamless experience for users with face recognition technology.</p>
        </div>

        <div class="developer">
            <img src="syarief.jpeg" alt="Developer 2 Photo" class="developer-photo">
            <p><strong>Developer 2</strong></p>
            <p>Motivation: Creating an efficient attendance system to enhance productivity in workplaces.</p>
        </div>

        <div class="developer">
            <img src="ahmad.jpeg" alt="Developer 3 Photo" class="developer-photo">
            <p><strong>Developer 3</strong></p>
            <p>Motivation: Integrating AI to solve real-world problems and improve daily operations.</p>
        </div>

        <div class="developer">
            <img src="azis.jpeg" alt="Developer 4 Photo" class="developer-photo">
            <p><strong>Developer 4</strong></p>
            <p>Motivation: Innovating with technology to streamline processes and enhance user experience.</p>
        </div>
    </div>
</body>
</html>
