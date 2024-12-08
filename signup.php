<?php
session_start();
include('config.php');

// Handle Signup
if (isset($_POST['signup'])) {
    $email = $_POST['new_email'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];
    $nim_nip = $_POST['nim_nip'];

    // Validasi password dan konfirmasi password
    if ($password !== $confirm_password) {
        echo "<div class='error-msg'>Passwords do not match. Please try again.</div>";
    } else {
        // Hash password
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        // Cek apakah email sudah ada
        $check_user_sql = "SELECT * FROM users WHERE email='$email'";
        $check_user_result = $conn->query($check_user_sql);
        
        if ($check_user_result->num_rows == 0) {
            // Insert new user with full name and NIM/NIP
            $sql = "INSERT INTO users (email, password, full_name, nim_nip) 
                    VALUES ('$email', '$password_hashed', '$full_name', '$nim_nip')";
            if ($conn->query($sql) === TRUE) {
                // Setelah berhasil signup, arahkan ke login.php
                header("Location: login.php");
                exit(); // Pastikan tidak ada kode lain yang dieksekusi setelah pengalihan
            } else {
                echo "<div class='error-msg'>Error: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='error-msg'>Email already exists. Please choose another.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFAR - Signup</title>
    <link rel="stylesheet" href="style_signup.css">
    <link rel="shortcut icon" href="safar-logo-fav.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-box">
                <h2>Signup</h2>
                <form method="POST" action="">
                    <div class="input-box">
                        <input type="text" name="full_name" required>
                        <label>Full Name</label>
                    </div>
                    <div class="input-box">
                        <input type="text" name="nim_nip" required>
                        <label>NIM/NIP</label>
                    </div>
                    <div class="input-box">
                        <input type="email" name="new_email" required>
                        <label>New Email</label>
                    </div>
                    <div class="input-box">
                        <input type="password" name="new_password" required>
                        <label>New Password</label>
                    </div>
                    <div class="input-box">
                        <input type="password" name="confirm_password" required>
                        <label>Confirm Password</label>
                    </div>
                    <button type="submit" name="signup" class="btn">Signup</button>
                </form>
            </div>

            <div class="form-box">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
