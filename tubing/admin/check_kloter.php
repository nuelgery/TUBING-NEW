<?php
session_start();
include 'koneksi.php';

// Cek session admin
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

$date = $_GET['date'] ?? '';

if (empty($date)) {
    echo json_encode([]);
    exit;
}

$query = "SELECT location FROM schedules WHERE schedule_date = '$date'";
$result = mysqli_query($koneksi, $query);

$terisiKloter = [];
while ($row = mysqli_fetch_assoc($result)) {
    $terisiKloter[] = $row['location'];
}

echo json_encode($terisiKloter);
?>