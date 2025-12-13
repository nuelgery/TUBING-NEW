<?php
session_start();
include 'koneksi.php';

// Cek session admin
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=Silakan login terlebih dahulu");
    exit;
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

switch($aksi) {
    case 'tambah':
        tambahJadwal($koneksi);
        break;
    case 'edit':
        editJadwal($koneksi);
        break;
    case 'hapus':
        hapusJadwal($koneksi);
        break;
    default:
        header("location:dashboard_admin.php?pesan=Aksi tidak valid");
        exit;
}

function tambahJadwal($koneksi) {
    $customer_name = mysqli_real_escape_string($koneksi, $_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($koneksi, $_POST['customer_phone']);
    $schedule_date = mysqli_real_escape_string($koneksi, $_POST['schedule_date']);
    $location = mysqli_real_escape_string($koneksi, $_POST['location']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $notes = mysqli_real_escape_string($koneksi, $_POST['notes']);
    
    // VALIDASI: Cek apakah kloter di tanggal tersebut sudah ada
    $check_query = "SELECT id FROM schedules WHERE schedule_date = '$schedule_date' AND location = '$location'";
    $check_result = mysqli_query($koneksi, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        header("location:dashboard_admin.php?pesan=Error: Kloter $location pada tanggal $schedule_date sudah terisi. Silakan pilih kloter atau tanggal lain.");
        exit;
    }
    
    $query = "INSERT INTO schedules (customer_name, customer_phone, schedule_date, location, status, notes) 
              VALUES ('$customer_name', '$customer_phone', '$schedule_date', '$location', '$status', '$notes')";
    
    if(mysqli_query($koneksi, $query)) {
        header("location:dashboard_admin.php?pesan=Jadwal berhasil ditambahkan");
    } else {
        header("location:dashboard_admin.php?pesan=Gagal menambahkan jadwal: " . mysqli_error($koneksi));
    }
}

function editJadwal($koneksi) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $customer_name = mysqli_real_escape_string($koneksi, $_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($koneksi, $_POST['customer_phone']);
    $schedule_date = mysqli_real_escape_string($koneksi, $_POST['schedule_date']);
    $location = mysqli_real_escape_string($koneksi, $_POST['location']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $notes = mysqli_real_escape_string($koneksi, $_POST['notes']);
    
    // VALIDASI: Cek apakah kloter di tanggal tersebut sudah ada (kecuali data yang sedang diedit)
    $check_query = "SELECT id FROM schedules WHERE schedule_date = '$schedule_date' AND location = '$location' AND id != '$id'";
    $check_result = mysqli_query($koneksi, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        header("location:dashboard_admin.php?pesan=Error: Kloter $location pada tanggal $schedule_date sudah terisi. Silakan pilih kloter atau tanggal lain.");
        exit;
    }
    
    $query = "UPDATE schedules SET 
              customer_name = '$customer_name',
              customer_phone = '$customer_phone',
              schedule_date = '$schedule_date',
              location = '$location',
              status = '$status',
              notes = '$notes',
              updated_at = NOW()
              WHERE id = '$id'";
    
    if(mysqli_query($koneksi, $query)) {
        header("location:dashboard_admin.php?pesan=Jadwal berhasil diupdate");
    } else {
        header("location:dashboard_admin.php?pesan=Gagal mengupdate jadwal: " . mysqli_error($koneksi));
    }
}

function hapusJadwal($koneksi) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    $query = "DELETE FROM schedules WHERE id = '$id'";
    
    if(mysqli_query($koneksi, $query)) {
        header("location:dashboard_admin.php?pesan=Jadwal berhasil dihapus");
    } else {
        header("location:dashboard_admin.php?pesan=Gagal menghapus jadwal: " . mysqli_error($koneksi));
    }
}
?>