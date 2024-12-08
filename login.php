<?php
// Memulai sesi jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
                // Set session untuk user_id, email, nama, dan NIP/NIM
                $_SESSION['user_id'] = $row['id'];           // Menyimpan user_id ke dalam session
                $_SESSION['email'] = $row['email'];
                $_SESSION['full_name'] = $row['full_name'];  // Ambil kolom nama
                $_SESSION['nip_nim'] = $row['nip_nim'];      // Ambil kolom NIP/NIM

                // Redirect ke dashboard setelah login sukses
                header("Location: home.php");
                exit();
            } else {
                echo "<p style='color:red;'>Password salah.</p>";
            }
        } else {
            echo "<p style='color:red;'>Login gagal. Email atau password salah.</p>";
        }

        // Menutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "<p style='color:red;'>Terjadi kesalahan dalam database.</p>";
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
    <link rel="shortcut icon" href="safar-logo-fav.ico" type="image/x-icon">
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
        <p>Want to know more about the developers behind SAFAR? <a href="#about-developers">Click here to meet the
                team!</a></p>
    </div>

    <!-- About the Developer Section -->
    <div id="about-developers" class="developer-info">
        <h3>About the Developers</h3>

        <div class="developer">
            <img src="syarief.jpg" alt="Developer 1 Photo" class="developer-photo">
            <p><strong>Ahmad Syarief Annur</strong></p>
            <p class="role">Machine Learning Developer</p>
            <p>Halo gaes nama gua AHMAD SYARIEF ANNUR, di panggil syarief
                saya berasal dari semarang dan sekarang tinggal di pangkalanbun kalimantan tengah
                saya sedang menempuh pendidikan di Universitas Terbuka dengan Program Studi Sistem Informasi
                saya sendiri type orang yang suka mencoba hal-hal yang baru.</p>
            <p>kalau ada sumur di ladang boleh
                kita menumpang mandi, kalau nemu error di codingan, boleh lah sama-sama kita cari.</p>
            <p>Jika anda tidak merubah rutinitas anda, anda akan mengulangi nya seumur hidup anda dan pertanyaan
                melahirkan ilmu pengetahuan
                dan bila tidak adanya ilmu, manusia akan seperti binatang.</p>
        </div>

        <div class="developer">
            <img src="aya.jpg" alt="Developer 2 Photo" class="developer-photo">
            <p><strong>Sri Mawaddah Warahmah N</strong></p>
            <p class="role">Full-stack Developer</p>
            <p>Hi Perkenalkan, Saya Aya dari Prodi Sistem Informasi, Universitas Terbuka.
                Saat ini saya sedang mengerjakan project bernama Safar, yang dimana saya adalah full stack developer dan
                koordinator selaku penanggung jawab dalam project ini. Safar sendiri sebuah sistem yang akan membantu
                kita semua
                menjadi lebih produktif, terutama dalam pengelolaan data kehadiran.
                Semoga perjalanan ini memberikan manfaat besar, tidak hanya untuk kami, tapi juga untuk kalian semua
                yang
                mungkin akan menggunakannya nanti. 😊</p>
            <p>Ke pasar membeli durian,
                durian manis dibelah dua.
                Project Safar jadi andalan,
                Semoga sukses untuk kita semua!</p>
            <p>Kami tidak bekerja sendirian. Project Safar adalah perjalanan tim yang dipenuhi dukungan, kolaborasi, dan
                impian yang sama.</p>
        </div>

        <div class="developer">
            <img src="ahmad.jpg" alt="Developer 3 Photo" class="developer-photo">
            <p><strong>Ahmad As'Ad</strong></p>
            <p class="role">Machine Learning Developer</p>
            <p>Halo semuanya! Nama saya Ahmad As'Ad, biasa dipanggil Ahmad. Saya berasal dari Kota Sorong dan saat ini
                tinggal di Jayapura sambil menempuh pendidikan di Universitas Terbuka, Program Studi Sistem Informasi.
                Saya adalah tipe orang yang suka mencoba hal-hal yang menantang dan berisiko.</p>
            <p>Pagi-pagi minum teh hangat, Sambil makan roti selai. Kerja sama bikin semangat, Semua masalah jadi
                selesai.</p>
            <p>Semakin banyak kamu belajar, semakin banyak pengetahuan yang kamu miliki. Ini akan memberimu kepercayaan
                diri dan kemampuan untuk menghadapi berbagai tantangan.</p>
        </div>

        <div class="developer">
            <img src="azis.jpg" alt="Developer 4 Photo" class="developer-photo">
            <p><strong>Abdul Azis</strong></p>
            <p class="role">UI/UX Designer</p>
            <p>Halo semuanya! Nama saya Abdul Azis, biasa dipanggil Azis. Saya berasal dari Kota Bogor dan saat ini
                sedang menempuh pendidikan di Program Studi Sistem Informasi di Universitas Terbuka. Saya orang yang
                suka belajar hal baru dan senang berkolaborasi.</p>
            <p>Ke pasar beli buah rambutan,
                Jangan lupa bawa keranjang.
                Mari kita jalin perkenalan,
                Agar kita bisa saling berteman panjang.</p>
            <p>Kuliah adalah salah satu jalan untuk memperluas wawasan, membangun jaringan, dan membuka pintu menuju
                masa depan yang lebih cerah. Tantangan dalam kuliah adalah kesempatan untuk membentuk karakter,
                sementara keberhasilan adalah hasil dari kerja keras dan ketekunan.</p>
        </div>
    </div>
</body>

</html>