<?php
session_start();
include('config.php');

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mengambil user berdasarkan email
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Jika user ditemukan
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set session email
            $_SESSION['email'] = $email;

            // Redirect ke home.php setelah login berhasil
            header("Location: home.php");
            exit();  // Hentikan eksekusi script lebih lanjut setelah redirect
        } else {
            echo "<div class='error-msg'>Invalid password.</div>";
        }
    } else {
        echo "<div class='error-msg'>User not found.</div>";
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
                    <button type="submit" name="login" class="btn">Login</button>
                </form>
            </div>

            <div class="form-box">
                <p>Don't have an account? <a href="signup.php">Signup here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
