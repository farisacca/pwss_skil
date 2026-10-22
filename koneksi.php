<?php
// Membuat koneksi ke database MySQL
$koneksi = mysqli_connect("localhost", "root", "", "db_toko");
// Memeriksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi gagal: ". mysqli_connect_error());
}
?>

