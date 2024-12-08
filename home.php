<?php
session_start();

// Cek apakah session sudah dimulai dan user_id ada di sesi
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php'; // Koneksi ke database

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];
$date = date('Y-m-d'); // Tanggal hari ini

// Ambil data kehadiran untuk bulan ini
$query = "SELECT * FROM attendance WHERE user_id = '$user_id' AND MONTH(date) = MONTH(CURRENT_DATE())";
$result = mysqli_query($conn, $query);
$attendance_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $attendance_data[$row['date']] = $row['status'];
}

// Ambil avatar (profile_picture) pengguna
$query = "SELECT profile_picture FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
if ($result) {
    $user_data = mysqli_fetch_assoc($result);
    $avatar = !empty($user_data['profile_picture']) ? $user_data['profile_picture'] : 'default-avatar.png';
} else {
    echo "Error: " . mysqli_error($conn);
}

// Simulasikan hasil verifikasi wajah (Hadir atau Absen)
$status = "Hadir";  // Ganti dengan logika deteksi kehadiran berdasarkan wajah

// Insert data ke tabel attendance jika belum ada data untuk hari ini
$query_check = "SELECT * FROM attendance WHERE user_id = '$user_id' AND date = '$date'";
$result_check = mysqli_query($conn, $query_check);

if (mysqli_num_rows($result_check) == 0) {
    // Jika data belum ada, maka simpan data kehadiran
    $query_insert = "INSERT INTO attendance (user_id, date, status) VALUES ('$user_id', '$date', '$status')";
    mysqli_query($conn, $query_insert);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAFAR - Dashboard</title>
    <link rel="stylesheet" href="style_home.css">
    <link rel="shortcut icon" href="safar-logo-fav.ico" type="image/x-icon">
    <style>
        /* Navbar Styles */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #333;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar .logo {
            display: flex;
            align-items: center;
        }
        .navbar .logo img {
            height: 40px;
            margin-right: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }

        /* Kalender Styles */
        .calendar-container {
            margin: 20px;
            text-align: center;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .calendar-header button {
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
        }
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        .day {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .present {
            background-color: #4caf50;
            color: white;
        }
        .absent {
            background-color: #f44336;
            color: white;
        }
        .not-recorded {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="logo.png" alt="Logo">
            <span style="color: white;">SAFAR</span>
        </div>
        <div class="nav-links">
            <a href="home.php">Dashboard</a>
            <a href="profile.php">Profile</a>
            <a href="setting.php">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="main-content">
        <h1>Welcome, <?php echo $_SESSION['full_name']; ?>!</h1>
        <p>Email: <?php echo $_SESSION['email']; ?></p>

        <!-- Avatar -->
        <div class="avatar-container">
            <img src="uploads/<?php echo $avatar; ?>" alt="Avatar" class="avatar">
        </div>

        <!-- Formulir Kehadiran -->
        <div class="attendance">
            <h2>Ayo Absen biar ga dikira mangkir :) </h2>
            <form action="verify_face.php" method="POST">
                <button type="submit" name="start_recognition">Start Absen</button>
            </form>
        </div>

        <!-- Kalender -->
        <div class="calendar-container">
            <div class="calendar-header">
                <button id="prevMonth">&lt; Prev</button>
                <h2 id="currentMonthYear"></h2>
                <button id="nextMonth">Next &gt;</button>
            </div>
            <div class="calendar" id="calendar"></div>
        </div>
    </div>

    <script>
        const calendarContainer = document.getElementById('calendar');
        const monthYearDisplay = document.getElementById('currentMonthYear');
        const prevMonthButton = document.getElementById('prevMonth');
        const nextMonthButton = document.getElementById('nextMonth');

        let currentDate = new Date();

        function renderCalendar(date) {
            calendarContainer.innerHTML = ''; // Hapus kalender sebelumnya
            const year = date.getFullYear();
            const month = date.getMonth();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDayOfMonth = new Date(year, month, 1).getDay();

            // Tampilkan bulan dan tahun
            const monthNames = [
                "January", "February", "March", "April", "May", "June", 
                "July", "August", "September", "October", "November", "December"
            ];
            monthYearDisplay.textContent = `${monthNames[month]} ${year}`;

            // Tambahkan kotak kosong di awal kalender
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyDiv = document.createElement('div');
                calendarContainer.appendChild(emptyDiv);
            }

            // Tambahkan tanggal ke kalender
            for (let day = 1; day <= daysInMonth; day++) {
                const dayDiv = document.createElement('div');
                const dateString = new Date(year, month, day).toISOString().split('T')[0];
                dayDiv.textContent = day;
                dayDiv.classList.add('day');

                // Menandai status kehadiran
                const attendanceData = <?php echo json_encode($attendance_data); ?>;
                if (attendanceData[dateString] === 'Hadir') {
                    dayDiv.classList.add('present');
                } else if (attendanceData[dateString] === 'Absen') {
                    dayDiv.classList.add('absent');
                } else {
                    dayDiv.classList.add('not-recorded');
                }

                calendarContainer.appendChild(dayDiv);
            }
        }

        // Navigasi bulan
        prevMonthButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        nextMonthButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });

        // Render kalender awal
        renderCalendar(currentDate);
    </script>
</body>
</html>
