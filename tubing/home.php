<?php 
//cuman buat notify kalo ada user yang nyoba masuk
if(isset($_GET['pesan'])){
echo "<p style='color:red; font-weight:bold;'>" . htmlspecialchars($_GET['pesan']) . "</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RiverTubing</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

    <nav class="public-navbar">
        <a href="#" class="nav-brand">
            <img src="assets/logo.png" alt="Logo River Tubing" class="navbar-logo-img">
            RiverTubing Tawangmangu
        </a>
        <ul class="nav-menu">
            <li><a href="home.php">Home</a></li>
            <li><a href="jadwal.php">Jadwal</a></li>
            <li><a href="testimoni.php">Testimoni</a></li>
            <li><a href="admin/login.php" class="nav-btn-login">Login</a></li>
        </ul>
    </nav>
    
    <!-- Hero Section -->
    <div class="hero section">
        <img src="assets/2edit.jpg" alt="River Tubing Adventure">
        <div class="hero-content">
            <h1>River Tubing</h1>
            <p>
                River Tubing Tawangmangu merupakan salah satu destinasi wisata yang 
                memacu adrenalin di Karanganyar. River tubing sendiri adalah versi lite 
                dari arum jeram dengan menghanyutkan diri menggunakan ban renang 
                dari hulu ke hilir sungai. Kegiatan ini bisa dilakukan bersama-sama 
                dengan cara menautkan ban satu dengan yang lain.
            </p>
        </div>
    </div>

       <!-- Social Media Section -->
    <section class="social-section">
        <div class="social-container">
            
            <!-- Instagram Box -->
            <div class="social-box instagram-box">
                <div class="logo-wrapper">
                    <img src="assets/ig-logo.png" alt="Instagram Logo" class="social-logo">
                </div>
                <div class="social-text">
                    <h3>Instagram</h3>
                    <p>Temukan lebih banyak di Instagram kami</p>
                    <a href="https://www.instagram.com/rivertubingtawangmangu/" target="_blank" class="social-btn">
                        Kunjungi Instagram
                    </a>
                </div>
            </div>

            <!-- WhatsApp Box -->
            <div class="social-box whatsapp-box">
                <div class="logo-wrapper">
                    <img src="assets/wa-logo.png" alt="WhatsApp Logo" class="social-logo">
                </div>
                <div class="social-text">
                    <h3>WhatsApp</h3>
                    <p>Mau main? Konsul dulu sama admin kami!</p>
                    <a href="https://wa.me/6285713562634" target="_blank" class="social-btn">
                        Hubungi Admin
                    </a>
                </div>
            </div>

        </div>
    </section>

     <!-- Advantages Section -->
    <section id="keuntungan" class="advantages">
        <h2 class="advantages-title">Keuntungan yang Anda Dapatkan</h2>
        
        <div class="advantages-grid">
            <div class="advantage-item">
                <div>
                    <div class="advantage-number">01</div>
                    <p class="advantage-text"><strong>Pemandu aman & berpengalaman</strong></p>
                </div>
                <img src="assets/Pemandu.jpg"  alt="Pemandu">
            </div>

            <div class="advantage-item">
                <img src= "assets/Sungai Bersih.jpg" alt="Peralatan">
                <div>
                    <div class="advantage-number">02</div>
                    <p class="advantage-text"><strong>Sungai Bersih & Pemandangan Asri</strong></p>
                    <div class="accent-box"></div>
                </div>
            </div>

            <div class="advantage-item">
                <div>
                    <div class="advantage-number">03</div>
                    <p class="advantage-text"><strong>Peralatan Lengkap & Aman</strong></p>
                </div>
                <img src="assets/Alat Lengkap.jpg"  alt="Kebersamaan">
            </div>

            <div class="advantage-item">
                <img src="assets/Keluarga.jpg"  alt="Keamanan">
                <div>
                    <div class="advantage-number">04</div>
                    <p class="advantage-text"><strong>Cocok Untuk Keluarga & Rombongan</strong></p>
                    <div class="accent-box red"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>RIVER TUBING</h3>
                <p>Garansi tidak basah uang kembali 100%</p>
            </div>
            <div class="footer-section">
                <h3>Kontak</h3>
                <p>📞 085713562634<br>📧 Tubingtawangmangu@gmail.com<br>📍 Jl. Sekrincing samping Loket 2 Grojogan Sewu Tawangmangu</p>
            </div>
            <div class="footer-section">
                <h3>Jam Operasional</h3>
                <p>Senin - Minggu<br>09:00 - 15:00 WIB<br>Buka Setiap Hari</p>
            </div>
        </div>
        <p>&copy; 2025 River Tubing. Semua Hak Dilindungi.</p>
    </footer>

</body>
</html>