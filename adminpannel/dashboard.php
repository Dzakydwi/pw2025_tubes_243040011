<?php
// admin-dashboard.php
session_start();
if (!isset($_SESSION['email'])) {
    header("locattion: ../login.php");
    exit;
}
include 'proses/koneksi.php';

// Ambil data user & admin untuk ditampilkan
$users = $koneksi->query("SELECT email, role FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4 text-center">Dashboard Admin</h2>
  <ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="./Tambah Obat.php">Tambah Obat</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="./Tambah User.php">Tambah User</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="./Tambah Kategori.php">Tambah Kategori</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="./Update Password.php">Update Password</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="./Kelola User dan Admin.php">Kelola User dan Admin</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="./Update kategori.php">Update Kategori</a></li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane container active" id="obat">
      <form action="proses/tambah-obat.php" method="POST">
        <input name="nama_obat" placeholder="Nama Obat" class="form-control mb-2" required>
        <textarea name="deskripsi" placeholder="Deskripsi" class="form-control mb-2"></textarea>
        <input type="number" name="harga" placeholder="Harga" class="form-control mb-2" required>
        <button class="btn btn-primary">Tambah Obat</button>
      </form>
    </div>
    <div class="tab-pane container fade" id="user">
      <form action="proses/tambah-user.php" method="POST">
        <input name="nama" placeholder="Nama" class="form-control mb-2" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
        <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
        <button class="btn btn-success">Tambah User</button>
      </form>
    </div>
    <div class="tab-pane container fade" id="kategori">
      <form action="proses/tambah-kategori.php" method="POST">
        <input name="nama_kategori" placeholder="Nama Kategori" class="form-control mb-2" required>
        <button class="btn btn-info">Tambah Kategori</button>
      </form>
    </div>
    <div class="tab-pane container fade" id="update-password">
      <form action="proses/update-password.php" method="POST">
        <input type="password" name="old_password" placeholder="Password Lama" class="form-control mb-2" required>
        <input type="password" name="new_password" placeholder="Password Baru" class="form-control mb-2" required>
        <button class="btn btn-warning">Update Password</button>
      </form>
    </div>
    <div class="tab-pane container fade" id="kelola-user">
      <table class="table table-bordered">
        <thead><tr><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php while($u = $users->fetch_assoc()): ?>
          <tr>
            <td><?= $u['email'] ?></td>
            <td><?= $u['role'] ?></td>
            <td>
              <a href="proses/hapus-<?= $u['role'] ?>.php?email=<?= $u['email'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="tab-pane container fade" id="update-kategori">
      <form action="proses/update-kategori.php" method="POST">
        <input name="kategori_lama" placeholder="Kategori Lama" class="form-control mb-2" required>
        <input name="kategori_baru" placeholder="Kategori Baru" class="form-control mb-2" required>
        <button class="btn btn-secondary">Update Kategori</button>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
