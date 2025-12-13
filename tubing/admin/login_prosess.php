<?php 
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Check if user exists
    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
    $cek = mysqli_num_rows($query);

    if($cek > 0){
        $admin = mysqli_fetch_assoc($query);
        
        // Check password (assuming plain text for now)
        if($password == $admin['password']) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['status'] = "login";
            
            // Redirect to dashboard
            header("location:dashboard_admin.php");
            exit;
        } else {
            header("location:login.php?pesan=Password+salah");
            exit;
        }
    } else {
        header("location:login.php?pesan=Username+tidak+ditemukan");
        exit;
    }
} else {
    // If someone accesses this page directly
    header("location:login.php");
    exit;
}
?>