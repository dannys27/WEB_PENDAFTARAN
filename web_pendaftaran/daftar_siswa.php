<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';

$nama_admin = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : "Admin";
$pesan = "";
$pesan_sukses = "";

$cari = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : "";

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['id']);
    $sql_hapus = "DELETE FROM calon_siswa WHERE id = '$id_hapus'";
    if (mysqli_query($koneksi, $sql_hapus)) {
        $pesan = "<p style='color:green; margin-bottom:10px;'>✅ Data berhasil dihapus.</p>";
    } else {
        $pesan = "<p style='color:red; margin-bottom:10px;'>❌ Gagal menghapus data.</p>";
    }
}


// 1. Total Semua
$q_total = mysqli_query($koneksi, "SELECT id FROM calon_siswa");
$total_siswa = mysqli_num_rows($q_total);

// 2. Hitung Laki-laki (Mencari teks yang diawali huruf 'L' seperti Laki-laki)
$q_laki = mysqli_query($koneksi, "SELECT id FROM calon_siswa WHERE jenis_kelamin LIKE 'L%'");
$total_laki = mysqli_num_rows($q_laki);

// 3. Hitung Perempuan (Mencari teks yang diawali huruf 'P' seperti Perempuan)
$q_perempuan = mysqli_query($koneksi, "SELECT id FROM calon_siswa WHERE jenis_kelamin LIKE 'P%'");
$total_perempuan = mysqli_num_rows($q_perempuan);

// Ambil Data Siswa untuk Tabel
if (!empty($cari)) {
    $sql = "SELECT * FROM calon_siswa WHERE nama_lengkap LIKE '%$cari%' OR alamat LIKE '%$cari%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM calon_siswa ORDER BY id DESC";
}
$result = mysqli_query($koneksi, $sql);

if (isset($_SESSION['pesan'])) {
    $pesan_sukses = $_SESSION['pesan'];
    unset($_SESSION['pesan']);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Siswa - Dashboard Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .search-container {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .input-cari {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
            width: 220px;
        }

        .btn-reset {
            text-decoration: none;
            color: #666;
            font-size: 1.2rem;
            margin-left: 5px;
        }

        /* CSS Kartu Statistik */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .stat-card small {
            color: #888;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .stat-card h2 {
            margin: 5px 0 0;
            font-size: 1.8rem;
            color: #333;
        }

        @media print {

            .sidebar,
            .topbar,
            .stats-grid,
            .search-container,
            .aksi-kolom,
            .btn-print {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
                width: 100%;
            }

            .card-table {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #000 !important;
                padding: 8px !important;
            }
        }
    </style>
</head>

<body>

    <div class="dashboard-wrapper">
        <nav class="sidebar">
            <h3>🏫 SDN Bulak 4</h3>
            <ul>
                <li><a href="daftar_siswa.php" class="active">📊 Dashboard</a></li>
                <li><a href="form_pendaftaran.php">👤 Tambah Siswa</a></li>
                <li><a href="laporan.php">📄 Laporan</a></li>
                <li style="margin-top: 100px;">
                    <a href="logout.php" style="color: #ffb8b8; font-weight: bold;">🚪 Logout</a>
                </li>
            </ul>
        </nav>

        <div class="main-content">
            <header class="topbar">
                <div class="breadcrumb">Home / Dashboard</div>
                <div class="user-info">
                    <strong>👤 <?= htmlspecialchars($nama_admin); ?></strong>
                </div>
            </header>

            <main class="dashboard-container">
                <div class="stats-grid">
                    <div class="stat-card" style="border-left: 5px solid #4b39b5;">
                        <small>Total Pendaftar</small>
                        <h2><?= $total_siswa; ?></h2>
                    </div>
                    <div class="stat-card" style="border-left: 5px solid #3498db;">
                        <small>Laki-Laki</small>
                        <h2><?= $total_laki; ?></h2>
                    </div>
                    <div class="stat-card" style="border-left: 5px solid #e84393;">
                        <small>Perempuan</small>
                        <h2><?= $total_perempuan; ?></h2>
                    </div>
                </div>

                <div class="card-table">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="color: #333;">Daftar Calon Siswa</h2>
                        <div class="search-container">
                            <form action="" method="GET" style="display: flex; align-items: center; gap: 5px;">
                                <input type="text" name="cari" class="input-cari" placeholder="Cari Nama/Alamat..."
                                    value="<?= htmlspecialchars($cari); ?>">
                                <button type="submit"
                                    style="background: #4b39b5; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold;">🔍
                                    Cari</button>
                                <?php if (!empty($cari)): ?>
                                    <a href="daftar_siswa.php" class="btn-reset">✖</a>
                                <?php endif; ?>
                            </form>
                            <button onclick="window.print()" class="btn-print"
                                style="background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold; margin-left: 10px;">🖨️
                                Cetak</button>
                        </div>
                    </div>

                    <hr style="margin-bottom: 20px; opacity: 0.1;">
                    <?php if (!empty($pesan))
                        echo $pesan; ?>
                    <?php if (!empty($pesan_sukses))
                        echo "<p style='color:green; margin-bottom:10px;'>✅ $pesan_sukses</p>"; ?>

                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th style="width: 110px;">Tgl Lahir</th>
                                <th style="width: 100px;">J/K</th>
                                <th>Alamat</th>
                                <th style="width: 130px;">No Telp</th>
                                <th class="aksi-kolom" style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><strong><?= htmlspecialchars($row['nama_lengkap']); ?></strong></td>
                                        <td><?= date('d-m-Y', strtotime($row['tgl_lahir'])); ?></td>
                                        <td><?= $row['jenis_kelamin']; ?></td>
                                        <td><?= htmlspecialchars($row['alamat']); ?></td>
                                        <td><?= htmlspecialchars($row['no_telp_ortu']); ?></td>
                                        <td class="aksi-kolom">
                                            <a href="edit.php?id=<?= $row['id']; ?>" class="aksi-link edit">📝</a>
                                            <a href="daftar_siswa.php?aksi=hapus&id=<?= $row['id']; ?>" class="aksi-link hapus"
                                                onclick="return confirm('Hapus data ini?')">🗑️</a>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            else: ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; padding: 30px;">
                                        <?php if (!empty($cari)): ?>
                                            Data "<strong><?= htmlspecialchars($cari); ?></strong>" tidak ditemukan. <a
                                                href="daftar_siswa.php">Lihat semua data</a>.
                                        <?php else: ?>
                                            Belum ada data pendaftar.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>

</html>