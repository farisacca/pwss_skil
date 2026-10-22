<?php
session_start();

// Cek apakah user sudah login atau belum. Jika belum, lempar kembali ke halaman login.
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

// Panggil file koneksi database
include 'koneksi.php';

// Fitur Pencarian (Search) disesuaikan dengan kolom 'name' di database
if (isset($_GET['cari'])) {
    $pencarian = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $sql = "SELECT * FROM products WHERE name LIKE '%$pencarian%'";
} else {
    $sql = "SELECT * FROM products";
}

$query = mysqli_query($koneksi, $sql);
$total_data = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk</title>

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

        /* isi navbar */
        .nav-content {
            width: 70%;              /* sama dengan container */
            margin: 0 auto;          /* agar berada di tengah */
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
            width: 70%;              /* sama dengan nav-content */
            background: #fff;
            padding: 20px;
            border-radius: 6px;
        }

        h2 {
            margin-top: 0;
        }

        /* BUTTONS */
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }

        .btn-add { background: #007bff;text-decoration: none; }
        .btn-danger { background: #dc3545; }
        .btn-edit { background: #17a2b8; }

        /* SEARCH BOX */
        .search-box {
            float: right;
            margin-bottom: 15px;
        }

        .search-box input {
            padding: 8px;
            width: 260px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-box button {
            padding: 8px 12px;
            background: #1f8b4d;
            color: white;
            border: none;
            border-radius: 4px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background: #212529;
            color: #fff;
            padding: 10px;
            text-align: left;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        img.product-img {
            width: 45px;
            height: 45px;
            border: 1px solid #ccc;
        }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="nav-content">
            <div>Online-Shop <?= htmlspecialchars($_SESSION['name'] ?? $_SESSION['username']); ?></div>
            <div>
                <a href="index.php">Produk</a>
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container">

        <h2>Kelola Produk</h2>

        <a class="btn btn-add" href="create.php">Tambah Data</a>

        <!-- <div class="search-box">
            <input type="text" placeholder="Search" value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
            <button>Go</button>
        </div> -->

        <form action="" method="GET" class="search-box">
            <input type="text" name="cari" placeholder="Search" value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
            <button type="submit">Go</button>
        </form>

        <p>Total Data Produk: <?= $total_data; ?></p>

        <table>
            <tr>
                <th>NO</th>
                <th>PRODUK</th>
                <th>JUMLAH</th>
                <th>HARGA</th>
                <th>DESKRIPSI</th>
                <th>FOTO</th>
                <th>AKSI</th>
            </tr>

            <?php
            if ($total_data > 0) {
                $no = 1;
                while ($row = mysqli_fetch_array($query)) {
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['stock']); ?></td>
                <td>Rp. <?= number_format($row['price'], 0, ',', '.'); ?></td>
                <td><?= htmlspecialchars($row['description']); ?></td>
                <td>
                    <?php if (!empty($row['image'])) : ?>
                
                        <img class="product-img" src="image/<?= htmlspecialchars($row['image']); ?>" alt="Foto Produk">
                    <?php else: ?>
                        <span>Tidak ada foto</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-edit">Edit</a>
                    
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' align='center'>Belum ada data produk.</td></tr>";
            }
            ?>
        </table>

    </div>

</body>
</html>
