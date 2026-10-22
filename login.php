<?php
session_start()
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Online Shop</title>

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
            margin: 40px auto;
            width: 350px;
            background: #fff;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-top: 0;
        }

        /* FORM */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* BUTTON */
        .btn-login {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background: #1f8b4d;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-login:hover {
            background: #176d3a;
        }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="nav-content">
            <div>Online-Shop</div>
            <div></div> <!-- Tidak ada menu di bagian login -->
        </div>
    </div>

    <!-- LOGIN BOX -->
    <div class="container">

        <h2>Login</h2>

        <form action="" method="POST">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button class="btn-login" type="submit" name="login">Login</button>

        </form>
        <?php
        if (isset($_POST['login'])) {
            //sesuaikan jalur file koneksi
            include "koneksi.php";

            $username = mysqli_real_escape_string($koneksi, $_POST['username']);
        
        // ⚠️ CATATAN PENTING:
        // Cek apakah di database password-nya dienkripsi pakai sha1() atau tidak.
        // Kalau password di database aslinya tidak di-sha1, hapus fungsi sha1() di bawah ini dan biarkan jadi $_POST['password'].
        $password = mysqli_real_escape_string($koneksi, sha1($_POST['password']));

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $query = mysqli_query($koneksi, $sql);

        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_array($query);
            $_SESSION['username'] = $data['username'];
            $_SESSION['name'] = $data['name'];
            
            // Mengarahkan ke halaman home / produk sesuai tujuan guru
            header("location: index.php"); 
            exit;
        } else {
            echo "<p style='color: red;'>Login anda gagal, username atau password salah!</p>";
        }
    }
    ?>
        

    </div>

</body>
</html>
