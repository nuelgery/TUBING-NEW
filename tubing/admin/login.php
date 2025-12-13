<?php
session_start();
// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header("location:dashboard_admin.php");
    exit;
}

if (isset($_GET['pesan'])) {
    $pesan = htmlspecialchars($_GET['pesan']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - River Tubing</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <div class="login-header">
                <div class="login-logo">
                    <img src="../assets/logo.png" alt="Logo" onerror="this.style.display='none'">
                </div>
                <h1>Admin Login</h1>
                <p>River Tubing Tawangmangu</p>
            </div>

            <?php if (isset($pesan)): ?>
                <div class="alert">
                    <?php echo $pesan; ?>
                </div>
            <?php endif; ?>

            <form action="login_prosess.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Masukkan username" 
                        required 
                        autocomplete="username"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password" 
                        required
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" name="login" class="login-btn">
                    Login
                </button>
            </form>

            <div class="back-home">
                <a href="../home.php">← Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>
</body>
</html>