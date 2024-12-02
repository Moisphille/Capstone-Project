<?php
session_start();

// Pastikan user sudah login dengan mengecek session email
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fungsi untuk membuat kalender dengan event dummy
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
        // Tambahkan event dummy di tanggal tertentu
        $event = ($currentDay % 5 == 0) ? "<div class='event'>Meeting</div>" : '';
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFDEAD; /* Navajo White */
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 20%;
            background-color: #FFE4C4; /* Bisque */
            color: #000;
            padding: 20px;
        }
        .sidebar h2 {
            color: #8B4513; /* Saddle Brown */
            margin-bottom: 20px;
        }
        .sidebar a {
            text-decoration: none;
            color: #000;
            display: block;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #D2B48C; /* Tan */
        }
        .sidebar a:hover {
            background-color: #F5DEB3; /* Wheat */
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
        .header {
            background-color: #F5DEB3; /* Wheat */
            color: #000;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .calendar-container {
            background-color: #FFE4C4; /* Bisque */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .calendar {
            width: 100%;
            border-collapse: collapse;
        }
        .calendar th, .calendar td {
            width: 14.28%;
            text-align: center;
            padding: 10px;
            border: 1px solid #D2B48C; /* Tan */
        }
        .calendar th {
            background-color: #F5DEB3; /* Wheat */
            color: #8B4513; /* Saddle Brown */
        }
        .calendar td {
            vertical-align: top;
        }
        .calendar .date {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .calendar .event {
            background-color: #FFDEAD; /* Navajo White */
            color: #8B4513; /* Saddle Brown */
            border-radius: 5px;
            padding: 5px;
            margin-top: 5px;
            font-size: 12px;
        }
        .actions {
            margin-top: 20px;
        }
        .actions a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #D2B48C; /* Tan */
            color: #8B4513; /* Saddle Brown */
            text-decoration: none;
            border-radius: 5px;
        }
        .actions a:hover {
            background-color: #FFDEAD; /* Navajo White */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h2>SAFAR</h2>
        <a href="#">Dashboard</a>
        <a href="#">Profile</a>
        <a href="#">Settings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?php echo $_SESSION['email']; ?>!</h1>
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
