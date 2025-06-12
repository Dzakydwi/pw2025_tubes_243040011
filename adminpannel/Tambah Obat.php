<?php
include 'koneksi.php';

$nama = $_POST['nama_obat'];
$deskripsi = $_POST['deskripsi'];
$harga = $_POST['harga'];

$query = "INSERT INTO obat (nama, deskripsi, harga) VALUES ('$nama', '$deskripsi', '$harga')";
if ($koneksi->query($query)) {
    header("Location: ../admin-dashboard.php");
} else {
    echo "Gagal: " . $koneksi->error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>