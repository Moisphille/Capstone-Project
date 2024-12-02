<?php
// Di sini bisa dilakukan integrasi dengan API atau library face recognition
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

echo "Face recognition feature coming soon!";
?>
