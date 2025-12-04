<?php
session_start();
// Proteksi: Jika admin belum login, arahkan kembali ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';

// Menghapus data jika ada aksi hapus
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query hapus
    $sql_hapus = "DELETE FROM calon_siswa WHERE id = '$id_hapus'";

    if (mysqli_query($koneksi, $sql_hapus)) {
        $pesan = "<p class='success-msg'>✅ Data pendaftar berhasil dihapus.</p>";
    } else {
        $pesan = "<p class='error-msg'>❌ Gagal menghapus data: " . mysqli_error($koneksi) . "</p>";
    }
}

// Query untuk mengambil semua data calon siswa
$sql = "SELECT * FROM calon_siswa ORDER BY tanggal_daftar DESC";
$result = mysqli_query($koneksi, $sql);

// Set pesan sukses setelah redirect dari form_pendaftaran (jika ada)
$pesan_sukses = "";
if (isset($_SESSION['pesan'])) {
    $pesan_sukses = $_SESSION['pesan'];
    unset($_SESSION['pesan']);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Siswa Pendaftar - Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* CSS KHUSUS UNTUK HALAMAN INI */
        .header-laporan h1 {
            margin-bottom: 5px;
        }

        /* Media Print: CSS ini hanya berlaku saat perintah cetak dijalankan */
        @media print {

            /* Sembunyikan elemen manajemen (tombol, tautan, kolom aksi) */
            .btn-management,
            .aksi-kolom,
            .tombol-print,
            .back-link,
            .logout-link,
            .header-laporan h2 {
                display: none;
            }

            /* Atur margin halaman saat dicetak */
            @page {
                margin: 1.5cm;
            }

            /* Pastikan background header tabel muncul saat dicetak */
            th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            /* Tampilkan header yang disembunyikan untuk cetak */
            .header-laporan h1.print-only {
                display: block !important;
                margin-top: 0;
            }
        }

        /* Secara default, sembunyikan judul khusus cetak */
        .header-laporan h1.print-only {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-laporan">
            <h1>HALAMAN ADMINISTRATOR</h1>
            <h2>Daftar Calon Siswa SDN Kampung Bulak 4</h2>
            <h1 class="print-only">LAPORAN DATA CALON SISWA SDN KAMPUNG BULAK 4</h1>
        </div>
        <hr>

        <?php
        // Tampilkan pesan sukses dari proses CRUD
        if (!empty($pesan)) {
            echo $pesan;
        }
        if (!empty($pesan_sukses)) {
            echo $pesan_sukses;
        }
        ?>

        <div class="btn-management">
            <p>
                <a href="form_pendaftaran.php" style="margin-right: 15px;">➕ Input Data Pendaftar Baru</a>

                <button onclick="window.print()" class="tombol-print"
                    style="background-color: #007bff; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9em;">
                    🖨️ Cetak Laporan Data Siswa
                </button>
            </p>
            <p class="logout-link" style="text-align: right; margin-top: -30px;">
                Anda login sebagai: **<?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>** |
                <a href="logout.php" style="color: #dc3545;">Keluar (Logout)</a>
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Tgl Lahir</th>
                    <th>J/K</th>
                    <th>Alamat</th>
                    <th>Nama Ortu</th>
                    <th>No Telp</th>
                    <th>Waktu Daftar</th>
                    <th class="aksi-kolom">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                        echo "<td>" . date('d-m-Y', strtotime($row['tgl_lahir'])) . "</td>";
                        echo "<td>" . $row['jenis_kelamin'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_ayah']) . " / " . htmlspecialchars($row['nama_ibu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['no_telp_ortu']) . "</td>";
                        echo "<td>" . $row['tanggal_daftar'] . "</td>";
                        // Kolom Aksi (Disembunyikan saat cetak)
                        echo "<td class='aksi-kolom'>";
                        echo "<a href='edit.php?id=" . $row['id'] . "' class='aksi-link edit'>Edit</a> | ";
                        echo "<a href='daftar_siswa.php?aksi=hapus&id=" . $row['id'] . "' class='aksi-link hapus' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align: center;'>Belum ada data pendaftar.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>