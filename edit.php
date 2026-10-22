<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

include "koneksi.php";

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='home.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Proses edit data
if (isset($_POST['simpan'])) {
    $name        = mysqli_real_escape_string($koneksi, $_POST['name']);
    $stock       = mysqli_real_escape_string($koneksi, $_POST['stock']);
    $price       = mysqli_real_escape_string($koneksi, $_POST['price']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);

    $gambar      = $_FILES['image'];
    $nama_gambar = $gambar['name'];
    $size_gambar = $gambar['size'];
    $target_file = 'image/' . $nama_gambar;

    if (is_uploaded_file($gambar['tmp_name']) == false) {
        // Jika gambar tidak diganti
        $sql = "UPDATE products SET name='$name', price='$price', stock='$stock', description='$description' WHERE id='$id'";
        $query = mysqli_query($koneksi, $sql);
        if ($query) {
            echo "<script>window.location.href='home.php';</script>";
            exit;
        }
    } else {
        // Jika gambar diganti
        if ($size_gambar < 3000000) {
            if (move_uploaded_file($gambar['tmp_name'], $target_file)) {
                $sql = "UPDATE products SET name='$name', price='$price', stock='$stock', description='$description', image='$nama_gambar' WHERE id='$id'";
                $query = mysqli_query($koneksi, $sql);
                if ($query) {
                    echo "<script>window.location.href='home.php';</script>";
                    exit;
                }
            } else {
                echo "Data gagal disimpan dikarenakan gambar gagal di-upload";
            }
        } else {
            echo "Mohon maaf ukuran gambar terlalu besar (Maksimal 3MB)";
        }
    }
}

// Menampilkan data berdasarkan id
$sql = "SELECT * FROM products WHERE id='$id'";
$query = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "<script>window.location.href='home.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>

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
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
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
            display: inline-block;
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
            <div>Online-Shop (<?= htmlspecialchars($_SESSION['username']); ?>)</div>
            <div>
                <a href="home.php">Produk</a>
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">

        <h2>Edit Produk</h2>
        <hr><br>

        <form action="" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" value="<?= htmlspecialchars($data['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Jumlah (Stock)</label>
                <input type="number" name="stock" value="<?= htmlspecialchars($data['stock']); ?>" required>
            </div>

            <div class="form-group">
                <label>Harga (Price)</label>
                <input type="number" name="price" value="<?= htmlspecialchars($data['price']); ?>" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description"><?= htmlspecialchars($data['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Foto Produk Saat Ini</label><br>
                <?php if (!empty($data['image'])): ?>
                    <img src="image/<?= htmlspecialchars($data['image']); ?>" width="100px" style="border: 1px solid #ccc; object-fit: cover;"><br><br>
                <?php else: ?>
                    <span>Gambar tidak tersedia</span><br><br>
                <?php endif; ?>

                <label>Ganti Foto (Opsional - Biarkan kosong jika tidak ingin diubah)</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="btn-container">
                <button type="submit" name="simpan" class="btn btn-save">Ubah</button>
                <a href="home.php" class="btn btn-cancel">Batal</a>
            </div>

        </form>

    </div>

</body>
</html>