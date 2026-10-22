<?php
if(isset($_GET['id'])){
    include "koneksi.php";
    $id = $_GET['id'];
    $sql = "DELETE FROM products WHERE id=$id";
    $query = mysqli_query($koneksi, $sql);
    if($query){
        echo "Data dengan id $id berhasil dihapus";
        header("location:home.php");
    }else{
        echo "Data gagal dihapus";
    }
}else{
    echo "<h1>Not Found</h1>";
}