<?php
session_start();
include 'koneksi.php'; // Pastikan file ini ada

// Enhanced session check
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=Silakan login terlebih dahulu");
    exit;
}

// --- AMBIL DATA JADWAL DARI DATABASE ---
$query = "SELECT * FROM schedules ORDER BY schedule_date ASC";
$result = mysqli_query($koneksi, $query);
$data_schedules = [];

// Array khusus untuk dikirim ke Javascript (Mapping agar sesuai script.js)
$js_schedules = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data_schedules[] = $row; // Simpan data asli untuk tabel PHP
    
    // Mapping data DB ke format yang diminta script.js
    $js_schedules[] = [
        'id' => $row['id'],
        'customer' => $row['customer_name'],
        'service' => $row['notes'] ? $row['notes'] : 'River Tubing', // Default text jika kosong
        'date' => $row['schedule_date'],
        'time' => '', // Kosongkan karena kolom time sudah dihapus
        'location' => $row['location']
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <h2>Rivertubing Tawangmangu</h2>
            </div>
            <div class="menu">
                <div class="menu-item active" data-page="dashboard">
                    <span class="menu-icon"></span>
                    <span>Dashboard</span>
                </div>
                <div class="menu-item" data-page="schedule">
                    <span class="menu-icon"></span>
                    <span>Kelola Jadwal</span>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header">
                <h1 id="pageTitle">Dashboard</h1>
                <div class="user-info">
                    <span>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?>!</span>
                    <div>
                        <a href="logout.php" class="logout-btn">Logout</a>
                    </div>
                </div>
            </div>

            <div class="content">
                <!-- Tampilkan pesan sukses/error -->
                <?php if(isset($_GET['pesan'])): ?>
                    <div class="alert-message" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                        <?php echo htmlspecialchars($_GET['pesan']); ?>
                    </div>
                <?php endif; ?>

                <div id="dashboard" class="page active">
                    <div class="welcome-banner">
                        <h2>Hello! "こんにちは"</h2>
                        <p>jangan lupa bahagia</p>
                    </div>
                    <div class="quick-actions">
                        <div class="action-card" data-page="schedule" onclick="tambahJadwal()">
                            <div class="action-text">
                                <h3>Buat Jadwal Baru</h3>
                                <p>Tambah jadwal customer</p>
                            </div>
                        </div>
                    </div>

                    <div class="schedule-section">
                        <div class="section-header">
                            <h3>Jadwal Akan Datang</h3>
                            <a href="#" class="view-all" data-page="schedule">Lihat Semua →</a>
                        </div>
                        <div id="upcomingSchedules"></div>
                    </div>
                </div>

                <div id="schedule" class="page">
                    <div class="page-header">
                        <h2>Kelola Jadwal</h2>
                        <button class="btn" onclick="toggleForm()">+ Tambah Jadwal</button>
                    </div>
                    
                    <div class="form-container" id="formJadwal" style="display: none;">
                        <h3 id="formTitle">Tambah Jadwal Baru</h3>
                        <form action="proses_jadwal.php" method="POST">
                            <input type="hidden" name="id" id="inputId">
                            <input type="hidden" name="aksi" id="inputAksi" value="tambah">
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Nama Customer</label>
                                    <input type="text" name="customer_name" id="inputNama" required>
                                </div>
                                <div class="form-group">
                                    <label>No. HP</label>
                                    <input type="text" name="customer_phone" id="inputHp">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="schedule_date" id="inputTanggal" required onchange="filterKloterByDate()">
                                </div>
                                <div class="form-group">
                                    <label>Lokasi</label>
                                    <select name="location" id="inputLokasi" required>
                                        <option value="">Pilih Kloter</option>
                                        <option value="kloter 1">Kloter 1</option>
                                        <option value="kloter 2">Kloter 2</option>
                                        <option value="kloter 3">Kloter 3</option>
                                        <option value="kloter 4">Kloter 4</option>
                                        <option value="kloter 5">Kloter 5</option>
                                        <option value="kloter 6">Kloter 6</option>
                                    </select>
                                    <small style="color: #666;">Kloter yang sudah terisi akan otomatis tersembunyi</small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="inputStatus">
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Catatan / Layanan</label>
                                <textarea name="notes" id="inputNotes" rows="2"></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn">Simpan Data</button>
                                <button type="button" class="btn btn-cancel" onclick="toggleForm()">Batal</button>
                            </div>
                        </form>
                    </div>

                    <div class="schedule-section">
                        <?php if(count($data_schedules) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach($data_schedules as $row): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['customer_name']); ?></strong><br>
                                        <small><?= htmlspecialchars($row['customer_phone']); ?></small>
                                    </td>
                                    <td>
                                        <?= date('d M Y', strtotime($row['schedule_date'])); ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['location']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $row['status']; ?>">
                                            <?= htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick='editData(<?= json_encode($row); ?>)'>Edit</button>
                                        <a href="proses_jadwal.php?aksi=hapus&id=<?= $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                            <div class="empty-state">
                                <p>Belum ada jadwal. Silakan tambah jadwal baru.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <script src="../javascript/script.js"></script>
    <script>
        // Mengisi variabel 'schedules' di script.js dengan data asli dari PHP
        var schedules = <?php echo json_encode($js_schedules); ?>;
        
        // Fungsi untuk filter kloter berdasarkan tanggal
        function filterKloterByDate() {
            const tanggalInput = document.getElementById('inputTanggal');
            const lokasiSelect = document.getElementById('inputLokasi');
            const selectedDate = tanggalInput.value;
            
            if (!selectedDate) return;
            
            // Reset semua opsi
            const allOptions = lokasiSelect.querySelectorAll('option');
            allOptions.forEach(option => {
                if (option.value !== '') {
                    option.style.display = 'block';
                    option.disabled = false;
                }
            });
            
            // Cari kloter yang sudah terisi pada tanggal tersebut
            const terisiKloter = [];
            schedules.forEach(schedule => {
                if (schedule.date === selectedDate) {
                    terisiKloter.push(schedule.location);
                }
            });
            
            // Nonaktifkan kloter yang sudah terisi
            allOptions.forEach(option => {
                if (option.value !== '' && terisiKloter.includes(option.value)) {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            });
            
            // Jika kloter yang dipilih sekarang ternyata sudah terisi, reset pilihan
            if (terisiKloter.includes(lokasiSelect.value)) {
                lokasiSelect.value = '';
                alert('Kloter yang dipilih sudah terisi pada tanggal tersebut. Silakan pilih kloter lain.');
            }
        }

        // Render ulang jadwal agar data baru muncul di dashboard
        document.addEventListener("DOMContentLoaded", function () {
            renderSchedules();
            
            // Tambah event listener untuk tanggal
            const tanggalInput = document.getElementById('inputTanggal');
            if (tanggalInput) {
                tanggalInput.addEventListener('change', filterKloterByDate);
            }
        });

            onsole.log('=== DEBUG DASHBOARD ===');
            console.log('Data schedules dari PHP:', schedules);
            console.log('Jumlah data:', schedules.length);

            // Paksa render ulang saat halaman loaded
            document.addEventListener("DOMContentLoaded", function () {
                console.log('DOM Loaded - Memanggil renderSchedules()');
                renderSchedules();
            });

            // Juga panggil saat halaman fully loaded
            window.addEventListener('load', function() {
                console.log('Window Loaded - Memanggil renderSchedules() lagi');
                renderSchedules();
            });
    </script>
</body>
</html>