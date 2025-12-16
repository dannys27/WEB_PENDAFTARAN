<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
include 'koneksi.php';
$nama_admin = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : "Admin";

// Ambil data untuk laporan
$sql = "SELECT * FROM calon_siswa ORDER BY nama_lengkap ASC";
$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Siswa</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* --- PENGATURAN KHUSUS PRINT --- */
        @media print {

            /* Sembunyikan Sidebar, Topbar, dan Tombol agar tidak ikut terprint */
            .sidebar,
            .topbar,
            .btn-print,
            .aksi-kolom {
                display: none !important;
            }

            /* Hilangkan background abu-abu dan margin dashboard agar full kertas */
            .main-content {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .dashboard-container {
                padding: 0 !important;
            }

            /* Hilangkan box-shadow kartu agar terlihat seperti dokumen kertas biasa */
            .card-table {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }

            /* Pastikan tabel memenuhi lebar kertas */
            table {
                width: 100% !important;
                border: 1px solid #000 !important;
                /* Tambahkan garis hitam tipis agar jelas di kertas */
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 8px !important;
                color: black !important;
            }

            body {
                background: white !important;
            }
        }
    </style>
</head>

<body>

    <div class="dashboard-wrapper">
        <nav class="sidebar">
            <h3>🏫 SDN Bulak 4</h3>
            <ul>
                <li><a href="daftar_siswa.php">📊 Dashboard</a></li>
                <li><a href="form_pendaftaran.php">👤 Tambah Siswa</a></li>
                <li><a href="laporan.php" class="active">📄 Laporan</a></li>
                <li style="margin-top: 50px;"><a href="logout.php" style="color: #ff7675;">🚪 Logout</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <header class="topbar">
                <div class="breadcrumb">Home / Laporan</div>
                <div>👤 <strong><?php echo htmlspecialchars($nama_admin); ?></strong></div>
            </header>

            <main class="dashboard-container">
                <div class="card-table">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h1 style="margin: 0;">LAPORAN DATA CALON SISWA</h1>
                        <h2 style="margin: 5px 0;">SDN KAMPUNG BULAK 4</h2>
                        <p>Tahun Ajaran 2025/2026</p>
                        <hr style="border: 1px solid black; margin-top: 10px;">
                    </div>

                    <button onclick="window.print()" class="btn-print"
                        style="background: #28a745; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; margin-bottom: 20px; font-weight: bold;">
                        🖨️ Cetak Laporan Ke Kertas/PDF
                    </button>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 30px;">No</th>
                                <th>Nama Lengkap</th>
                                <th>Tgl Lahir</th>
                                <th>J/K</th>
                                <th>Alamat</th>
                                <th>No Telp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td align="center"><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['tgl_lahir'])); ?></td>
                                    <td><?= $row['jenis_kelamin']; ?></td>
                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    <td><?= htmlspecialchars($row['no_telp_ortu']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

</body>

</html>