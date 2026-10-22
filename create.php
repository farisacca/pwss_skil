<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: index.php");
    exit;
}
include "koneksi.php"; // Sesuaikan jalur koneksi jika di dalam folder
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        /* NAVBAR */
        .navbar {
            background: #1f8b4d;
            padding: 15px 0;
            color: #fff;
            width: 100%;
        }

        .nav-content {
            width: 70%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-content a {
            color: #fff;
            margin-left: 20px;
            text-decoration: none;
        }

        /* CONTENT */
        .container {
            margin: 30px auto;
            width: 70%;
            background: #fff;
            padding: 20px;
            border-radius: 6px;
        }

        h2 {
            margin-top: 0;
        }

        /* FORM */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* BUTTON */
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }

        .btn-save {
            background: #1f8b4d;
        }

        .btn-cancel {
            background: #dc3545;
            text-decoration: none;
            padding: 10px 18px;
        }

        .btn-container {
            margin-top: 20px;
        }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="nav-content">
            <div>Online-Shop <?php echo $_SESSION['username']; ?></div>
            <div>
                <a href="index.html">Produk</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">

        <h2>Tambah Produk</h2>
        <hr><br>

        <!-- FORM TAMBAH PRODUK -->
        <form action="#" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="stock" required>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="price" required>
            </div>

            <div class="form-group">
                <label>Foto Produk</label>
                <input type="file" name="image" accept="image/*" required>
            </div>

            <div class="btn-container">
                <button type="submit" name="simpan" class="btn btn-save">Simpan</button>
                <a href="produk.php" class="btn btn-cancel">Batal</a>
            </div>

        </form>

        <?php
        if (isset($_POST['simpan'])) {
            $nama_produk = mysqli_real_escape_string($koneksi, $_POST['name']);
            $jumlah      = mysqli_real_escape_string($koneksi, $_POST['stock']);
            $harga       = mysqli_real_escape_string($koneksi, $_POST['price']);

            $nama_gambar = $_FILES['image']['name'];
            $lokasi_tmp  = $_FILES['image']['tmp_name'];
            
            // Folder tujuan disesuaikan dengan folder 'image' di VS Code kamu
            $folder      = 'image/';

            if (move_uploaded_file($lokasi_tmp, $folder . $nama_gambar)) {
                // Masukkan data ke tabel 'products' dengan kolom (name, price, stock, description, image)
                $sql = "INSERT INTO products (name, price, stock, description, image) 
                        VALUES ('$nama_produk', '$harga', '$jumlah', '-', '$nama_gambar')";
                
                $query = mysqli_query($koneksi, $sql);

                if ($query) {
                    // Jika berhasil, langsung arahkan ke halaman home.php tanpa alert
                    header("location: home.php");
                    exit;
                } else {
                    echo "<p style='color: red; margin-top: 15px;'>Gagal menyimpan ke database: " . mysqli_error($koneksi) . "</p>";
                }
            } else {
                echo "<p style='color: red; margin-top: 15px;'>Gagal meng-upload gambar ke folder image!</p>";
            }
        }
        ?>
    </div>

</body>
</html>
