<?php
session_start();

// Proteksi: Pastikan admin sudah login
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query untuk menghapus data
    $sql = "DELETE FROM calon_siswa WHERE id='$id'";

    if (mysqli_query($koneksi, $sql)) {
        // Redirect kembali ke halaman daftar siswa dengan pesan sukses
        header("Location: daftar_siswa.php?pesan=hapus_sukses");
        exit;
    } else {
        // Redirect dengan pesan gagal
        header("Location: daftar_siswa.php?pesan=hapus_gagal");
        exit;
    }
} else {
    // Jika tidak ada ID, arahkan kembali
    header("Location: daftar_siswa.php");
    exit;
}
?>