<?php
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database (silakan sesuaikan dengan konfigurasi database Anda)
$servername = "localhost"; // Nama host database
$username = "root"; // Username database
$password = ""; // Password database
$dbname = "safar"; // Nama database

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data absensi dari database untuk user yang sedang login
$email = $_SESSION['email'];
$month = date('n'); // Bulan saat ini
$year = date('Y'); // Tahun saat ini

$sql = "SELECT date, status FROM absensi WHERE email = '$email' AND MONTH(date) = '$month' AND YEAR(date) = '$year'";
$result = $conn->query($sql);

$absensi_data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $absensi_data[$row['date']] = $row['status'];
    }
} else {
    $message = "Tidak ada data absensi untuk bulan ini.";
}

// Tutup koneksi database
$conn->close();

// Fungsi untuk membuat tabel laporan absensi
function create_report_table($month, $year, $absensi_data) {
    $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dayOfWeek = date('N', $firstDayOfMonth) - 1;

    $table = '<table class="report">';
    
    // Header hari
    $table .= '<tr class="days">';
    foreach ($daysOfWeek as $day) {
        $table .= "<th>$day</th>";
    }
    $table .= '</tr><tr>';

    // Tambahkan sel kosong sebelum hari pertama bulan ini
    if ($dayOfWeek > 0) {
        $table .= str_repeat('<td class="empty"></td>', $dayOfWeek);
    }

    $currentDay = 1;
    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $table .= '</tr><tr>';
            $dayOfWeek = 0;
        }

        $currentDate = "$year-$month-" . str_pad($currentDay, 2, '0', STR_PAD_LEFT);
        $status = isset($absensi_data[$currentDate]) ? $absensi_data[$currentDate] : 'Tidak ada data';
        $status_color = ($status == 'Hadir') ? 'green' : (($status == 'Absen') ? 'red' : 'gray');

        $table .= "<td><span class='date'>$currentDay</span><br><span class='status' style='color: $status_color;'>$status</span></td>";
        $currentDay++;
        $dayOfWeek++;
    }

    // Tambahkan sel kosong di akhir baris
    if ($dayOfWeek != 0) {
        $remainingDays = 7 - $dayOfWeek;
        $table .= str_repeat('<td class="empty"></td>', $remainingDays);
    }

    $table .= '</tr>';
    $table .= '</table>';

    return $table;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Reports</title>
    <link rel="stylesheet" href="style_reports.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .report {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .report th, .report td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .report th {
            background-color: #f5f5f5;
        }
        .report td .date {
            font-weight: bold;
        }
        .status {
            font-size: 14px;
        }
        .message {
            text-align: center;
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Laporan Absensi Bulan <?php echo date('F Y'); ?></h1>

    <?php 
    if (isset($message)) {
        echo "<div class='message'>$message</div>";
    } else {
        echo create_report_table($month, $year, $absensi_data);
    }
    ?>
</div>
</body>
</html>
