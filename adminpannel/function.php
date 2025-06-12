<?php
    function koneksi()
{
    $conn = mysqli_connect('localhost', 'root', 'dzakyjakiDwi15', 'pw2025_tubes_243040011');
    return $conn;
}
?>

<?php
// function query($query)
// {
//     $conn = koneksi();
//     $result = mysqli_query($conn, $query);
//     $rows = [];
//     while ($row = mysqli_fetch_assoc($result)) {
//         $rows[] = $row;
//     }
//     return $rows;
// }
?>