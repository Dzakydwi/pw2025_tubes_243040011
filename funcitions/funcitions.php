<?php
    function koneksi()
{
    $conn = mysqli_connect('localhost', 'root', 'dzakyjakiDwi15', 'pw2025_tubes_243040011');
    return $conn;
}
?>