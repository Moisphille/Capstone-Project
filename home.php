<?php
session_start();

// Pastikan user sudah login dengan mengecek session email
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fungsi untuk membuat kalender dengan event absensi
function create_calendar_with_events($month, $year) {
    $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dayOfWeek = date('N', $firstDayOfMonth) - 1; // 0 untuk Senin
    $calendar = '<table class="calendar">';
    
    // Header hari
    $calendar .= '<tr class="days">';
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th>$day</th>";
    }
    $calendar .= '</tr><tr>';
    
    // Tambahkan sel kosong sebelum hari pertama bulan ini
    if ($dayOfWeek > 0) {
        $calendar .= str_repeat('<td class="empty"></td>', $dayOfWeek);
    }

    $currentDay = 1;
    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $calendar .= '</tr><tr>';
            $dayOfWeek = 0;
        }

        // Placeholder: Ganti ini dengan data absensi nyata dari database
        $event = ($currentDay % 2 == 0) ? "<div class='event'>Hadir</div>" : "<div class='event'>Absen</div>";
        $calendar .= "<td><span class='date'>$currentDay</span>$event</td>";
        $currentDay++;
        $dayOfWeek++;
    }

    // Tambahkan sel kosong di akhir baris
    if ($dayOfWeek != 0) {
        $remainingDays = 7 - $dayOfWeek;
        $calendar .= str_repeat('<td class="empty"></td>', $remainingDays);
    }

    $calendar .= '</tr>';
    $calendar .= '</table>';

    return $calendar;
}

$month = date('n'); // Bulan saat ini
$year = date('Y'); // Tahun saat ini
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style_home.css">
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
        <div class="calendar-container">
            <h2>Calendar</h2>
            <?php echo create_calendar_with_events($month, $year); ?>
        </div>
        <div class="actions">
            <a href="verify_face.php">Start Face Recognition</a>
            <a href="#">View Reports</a>
        </div>
    </div>
</div>
</body>
</html>
