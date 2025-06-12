<?php
session_start();

// Simulasi data - dalam aplikasi nyata, data ini dari database
$categories = [
    ['id' => 1, 'name' => 'Antibiotik', 'description' => 'Obat untuk infeksi bakteri'],
    ['id' => 2, 'name' => 'Analgesik', 'description' => 'Obat pereda nyeri'],
    ['id' => 3, 'name' => 'Vitamin', 'description' => 'Suplemen vitamin dan mineral'],
    ['id' => 4, 'name' => 'Kardiovaskular', 'description' => 'Obat jantung dan pembuluh darah']
];

$medicines = [
    ['id' => 1, 'name' => 'Amoxicillin 500mg', 'category_id' => 1, 'stock' => 100, 'price' => 15000, 'expired' => '2025-12-31'],
    ['id' => 2, 'name' => 'Paracetamol 500mg', 'category_id' => 2, 'stock' => 250, 'price' => 5000, 'expired' => '2026-06-15'],
    ['id' => 3, 'name' => 'Vitamin C 1000mg', 'category_id' => 3, 'stock' => 80, 'price' => 25000, 'expired' => '2025-08-20'],
    ['id' => 4, 'name' => 'Captopril 25mg', 'category_id' => 4, 'stock' => 60, 'price' => 12000, 'expired' => '2025-10-10']
];

$users = [
    ['id' => 1, 'username' => 'admin', 'email' => 'admin@health.com', 'role' => 'Admin', 'status' => 'Active'],
    ['id' => 2, 'username' => 'dr_smith', 'email' => 'smith@health.com', 'role' => 'Doctor', 'status' => 'Active'],
    ['id' => 3, 'username' => 'nurse_jane', 'email' => 'jane@health.com', 'role' => 'Nurse', 'status' => 'Active'],
    ['id' => 4, 'username' => 'pharmacist', 'email' => 'pharma@health.com', 'role' => 'Pharmacist', 'status' => 'Inactive']
];

$admins = [
    ['id' => 1, 'username' => 'superadmin', 'email' => 'super@health.com', 'last_login' => '2025-06-12 08:30:00'],
    ['id' => 2, 'username' => 'admin2', 'email' => 'admin2@health.com', 'last_login' => '2025-06-11 14:20:00'],
    ['id' => 3, 'username' => 'admin3', 'email' => 'admin3@health.com', 'last_login' => '2025-06-10 16:45:00']
];

// Statistik
$total_medicines = count($medicines);
$total_categories = count($categories);
$total_users = count($users);
$low_stock_count = 0;
foreach($medicines as $med) {
    if($med['stock'] < 50) $low_stock_count++;
}

// Fungsi helper
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function getCategoryName($category_id, $categories) {
    foreach($categories as $cat) {
        if($cat['id'] == $category_id) return $cat['name'];
    }
    return 'Unknown';
}

function getStockStatus($stock) {
    if($stock < 20) return ['danger', 'Stok Rendah'];
    if($stock < 50) return ['warning', 'Stok Menipis'];
    return ['success', 'Stok Aman'];
}

// Handle form submissions (simulasi)
$message = '';
if($_POST) {
    $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Operasi berhasil dilakukan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health System - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            background: linear-gradient(135deg, #2c5282 0%, #2a4365 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: #e2e8f0;
            border-radius: 8px;
            margin: 3px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            background-color: #3182ce;
            color: white;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .card:hover { transform: translateY(-2px); }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card.medicines {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .stat-card.categories {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card.users {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card.alerts {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        .content-section {
            display: none;
        }
        .content-section.active {
            display: block;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3182ce;
            box-shadow: 0 0 0 0.2rem rgba(49, 130, 206, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
            border: none;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        .modal-content {
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3 text-center">
                    <i class="fas fa-heartbeat fa-2x text-white mb-2"></i>
                    <h5 class="text-white">Health System</h5>
                    <small class="text-light">Admin Panel</small>
                </div>
                <nav class="nav flex-column p-3">
                    <a class="nav-link active" href="#" onclick="showSection('dashboard')">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="#" onclick="showSection('medicines')">
                        <i class="fas fa-pills me-2"></i>Obat
                    </a>
                    <a class="nav-link" href="#" onclick="showSection('categories')">
                        <i class="fas fa-tags me-2"></i>Kategori
                    </a>
                    <a class="nav-link" href="#" onclick="showSection('users')">
                        <i class="fas fa-users me-2"></i>Users
                    </a>
                    <a class="nav-link" href="#" onclick="showSection('admins')">
                        <i class="fas fa-user-shield me-2"></i>Admin
                    </a>
                    <a class="nav-link" href="#" onclick="showSection('settings')">
                        <i class="fas fa-cog me-2"></i>Settings
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <?php echo $message; ?>

                <!-- Dashboard Section -->
                <div id="dashboard" class="content-section active">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-heartbeat text-primary me-2"></i>Health Dashboard</h2>
                        <div>
                            <span class="me-3">Selamat datang, Admin Health!</span>
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card stat-card medicines">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-subtitle mb-2">Total Obat</h6>
                                            <h3 class="card-title"><?php echo $total_medicines; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-pills fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card stat-card categories">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-subtitle mb-2">Kategori</h6>
                                            <h3 class="card-title"><?php echo $total_categories; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-tags fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card stat-card users">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-subtitle mb-2">Total Users</h6>
                                            <h3 class="card-title"><?php echo $total_users; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card stat-card alerts">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-subtitle mb-2">Stok Rendah</h6>
                                            <h3 class="card-title"><?php echo $low_stock_count; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-clock text-primary me-2"></i>Aktivitas Terbaru</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item border-0">
                                            <i class="fas fa-plus text-success me-2"></i>
                                            Obat "Amoxicillin 500mg" ditambahkan
                                            <small class="text-muted d-block">2 jam yang lalu</small>
                                        </div>
                                        <div class="list-group-item border-0">
                                            <i class="fas fa-edit text-warning me-2"></i>
                                            Kategori "Antibiotik" diperbarui
                                            <small class="text-muted d-block">4 jam yang lalu</small>
                                        </div>
                                        <div class="list-group-item border-0">
                                            <i class="fas fa-user-plus text-info me-2"></i>
                                            User baru "dr_wilson" ditambahkan
                                            <small class="text-muted d-block">1 hari yang lalu</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-chart-pie text-primary me-2"></i>Ringkasan Stok</h5>
                                </div>
                                <div class="card-body">
                                    <?php foreach($medicines as $med): 
                                        $status = getStockStatus($med['stock']);
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span><?php echo $med['name']; ?></span>
                                        <span class="badge bg-<?php echo $status[0]; ?>"><?php echo $med['stock']; ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medicines Section -->
                <div id="medicines" class="content-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-pills text-primary me-2"></i>Manajemen Obat</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                            <i class="fas fa-plus me-2"></i>Tambah Obat
                        </button>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Obat</th>
                                            <th>Kategori</th>
                                            <th>Stok</th>
                                            <th>Harga</th>
                                            <th>Kadaluarsa</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($medicines as $med): 
                                            $status = getStockStatus($med['stock']);
                                        ?>
                                        <tr>
                                            <td><?php echo $med['id']; ?></td>
                                            <td><?php echo $med['name']; ?></td>
                                            <td><?php echo getCategoryName($med['category_id'], $categories); ?></td>
                                            <td><?php echo $med['stock']; ?></td>
                                            <td><?php echo formatRupiah($med['price']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($med['expired'])); ?></td>
                                            <td><span class="badge bg-<?php echo $status[0]; ?>"><?php echo $status[1]; ?></span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Section -->
                <div id="categories" class="content-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-tags text-primary me-2"></i>Manajemen Kategori</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori
                        </button>
                    </div>

                    <div class="row">
                        <?php foreach($categories as $cat): ?>
                        <div class="col-lg-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title"><?php echo $cat['name']; ?></h5>
                                            <p class="card-text text-muted"><?php echo $cat['description']; ?></p>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#updateCategoryModal">
                                                    <i class="fas fa-edit me-2"></i>Update</a></li>
                                                <li><a class="dropdown-item text-danger" href="#">
                                                    <i class="fas fa-trash me-2"></i>Hapus</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Users Section -->
                <div id="users" class="content-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-users text-primary me-2"></i>Manajemen Users</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-user-plus me-2"></i>Tambah User
                        </button>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo $user['username']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td><span class="badge bg-info"><?php echo $user['role']; ?></span></td>
                                            <td>
                                                <span class="badge bg-<?php echo $user['status'] == 'Active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo $user['status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-warning" title="Reset Password">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus User" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admins Section -->
                <div id="admins" class="content-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-user-shield text-primary me-2"></i>Manajemen Admin</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Last Login</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($admins as $admin): ?>
                                        <tr>
                                            <td><?php echo $admin['id']; ?></td>
                                            <td><?php echo $admin['username']; ?></td>
                                            <td><?php echo $admin['email']; ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($admin['last_login'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus Admin" onclick="deleteAdmin(<?php echo $admin['id']; ?>)">
                                                    <i class="fas fa-user-times"></i> Hapus Admin
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div id="settings" class="content-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-cog text-primary me-2"></i>Pengaturan</h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-key text-primary me-2"></i>Ubah Password</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Password Lama</label>
                                            <input type="password" class="form-control" name="old_password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password Baru</label>
                                            <input type="password" class="form-control" name="new_password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" name="confirm_password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-cogs text-primary me-2"></i>Pengaturan Sistem</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="notifications" checked>
                                        <label class="form-check-label" for="notifications">
                                            Notifikasi Email
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="lowstock" checked>
                                        <label class="form-check-label" for="lowstock">
                                            Alert Stok Rendah
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="expired">
                                        <label class="form-check-label" for="expired">
                                            Alert Obat Kadaluarsa
                                        </label>
                                    </div>
                                    <button class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Simpan Pengaturan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Medicine Modal -->
    <div class="modal fade" id="addMedicineModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-pills me-2"></i>Tambah Obat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Obat</label>
                            <input type="text" class="form-control" name="medicine_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Stok</label>
                                    <input type="number" class="form-control" name="stock" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Harga</label>
                                    <input type="number" class="form-control" name="price" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" class="form-control" name="expired_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Obat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-tags me-2"></i>Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" name="category_name" required>
                        </div>  
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add User Modal -->
     <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Nurse">Nurse</option>
                                <option value="Pharmacist">Pharmacist</option>
                                <option value="Receptionist">Receptionist</option>
                                <option value="Lab Technician">Lab Technician</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Function to show different sections
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => section.classList.remove('active'));
            
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            
            // Update active nav link
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => link.classList.remove('active'));
            event.target.classList.add('active');
        }

        // Function to delete user with confirmation
        function deleteUser(userId) {
            if(confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                // In real application, this would make an AJAX call or form submission
                alert('User dengan ID ' + userId + ' berhasil dihapus!');
                // Here you would typically reload the page or update the table
                location.reload();
            }
        }

        // Function to delete admin with confirmation
        function deleteAdmin(adminId) {
            if(confirm('Apakah Anda yakin ingin menghapus admin ini? Tindakan ini tidak dapat dibatalkan!')) {
                if(confirm('Konfirmasi sekali lagi: Hapus admin dengan ID ' + adminId + '?')) {
                    // In real application, this would make an AJAX call or form submission
                    alert('Admin dengan ID ' + adminId + ' berhasil dihapus!');
                    // Here you would typically reload the page or update the table
                    location.reload();
                }
            }
        }

        // Function to reset user password
        function resetPassword(userId) {
            if(confirm('Reset password untuk user ini?')) {
                // Generate random password or show modal for new password
                const newPassword = prompt('Masukkan password baru:');
                if(newPassword) {
                    alert('Password berhasil direset!');
                    // In real application, this would update the database
                }
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if(alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                }
            });
        }, 5000);

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if(!field.value.trim()) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });
                    
                    if(!isValid) {
                        e.preventDefault();
                        alert('Mohon lengkapi semua field yang wajib diisi!');
                    }
                });
            });
        });

        // Password strength validation
        function validatePassword(password) {
            const minLength = 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /\d/.test(password);
            const hasNonalphas = /\W/.test(password);
            
            if(password.length < minLength) {
                return 'Password minimal 8 karakter';
            }
            if(!hasUpperCase) {
                return 'Password harus mengandung huruf besar';
            }
            if(!hasLowerCase) {
                return 'Password harus mengandung huruf kecil';
            }
            if(!hasNumbers) {
                return 'Password harus mengandung angka';
            }
            if(!hasNonalphas) {
                return 'Password harus mengandung karakter khusus';
            }
            return '';
        }

        // Real-time password validation
        document.addEventListener('input', function(e) {
            if(e.target.type === 'password' && e.target.name === 'new_password') {
                const password = e.target.value;
                const errorMsg = validatePassword(password);
                
                let feedback = e.target.parentNode.querySelector('.invalid-feedback');
                if(!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    e.target.parentNode.appendChild(feedback);
                }
                
                if(errorMsg) {
                    e.target.classList.add('is-invalid');
                    feedback.textContent = errorMsg;
                } else {
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                    feedback.textContent = '';
                }
            }
        });

        // Confirm password validation
        document.addEventListener('input', function(e) {
            if(e.target.name === 'confirm_password') {
                const password = document.querySelector('input[name="new_password"]').value;
                const confirmPassword = e.target.value;
                
                if(password !== confirmPassword) {
                    e.target.classList.add('is-invalid');
                    let feedback = e.target.parentNode.querySelector('.invalid-feedback');
                    if(!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        e.target.parentNode.appendChild(feedback);
                    }
                    feedback.textContent = 'Konfirmasi password tidak cocok';
                } else {
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                }
            }
        });

        // Search functionality (basic)
        function searchTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const table = document.getElementById(tableId);
            const rows = table.getElementsByTagName('tr');
            
            input.addEventListener('keyup', function() {
                const filter = input.value.toUpperCase();
                
                for(let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let found = false;
                    
                    for(let j = 0; j < cells.length; j++) {
                        const cell = cells[j];
                        if(cell.innerHTML.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                    
                    row.style.display = found ? '' : 'none';
                }
            });
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Stock alert system
        function checkLowStock() {
            const medicines = <?php echo json_encode($medicines); ?>;
            const lowStockItems = medicines.filter(med => med.stock < 20);
            
            if(lowStockItems.length > 0) {
                console.log('Peringatan: ' + lowStockItems.length + ' obat dengan stok rendah');
                // You could show a notification here
            }
        }

        // Check for expired medicines
        function checkExpiredMedicines() {
            const medicines = <?php echo json_encode($medicines); ?>;
            const today = new Date();
            const expiredItems = medicines.filter(med => {
                const expiredDate = new Date(med.expired);
                const diffTime = expiredDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                return diffDays <= 30; // Within 30 days
            });
            
            if(expiredItems.length > 0) {
                console.log('Peringatan: ' + expiredItems.length + ' obat akan/sudah kadaluarsa');
                // You could show a notification here
            }
        }

        // Run checks on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkLowStock();
            checkExpiredMedicines();
        });

        // Export functionality (basic)
        function exportToCSV(tableId, filename) {
            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            for(let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cols = row.querySelectorAll('td, th');
                let csvRow = [];
                
                for(let j = 0; j < cols.length - 1; j++) { // Skip last column (actions)
                    csvRow.push('"' + cols[j].innerText + '"');
                }
                csv.push(csvRow.join(','));
            }
            
            const csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
            const downloadLink = document.createElement('a');
            downloadLink.download = filename;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        // Print functionality
        function printSection(sectionId) {
            const printContent = document.getElementById(sectionId).innerHTML;
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = '<div class="container-fluid">' + printContent + '</div>';
            window.print();
            document.body.innerHTML = originalContent;
            location.reload(); // Reload to restore event listeners
        }
    </script>

    <style>
        @media print {
            .sidebar, .btn, .modal { display: none !important; }
            .col-md-10 { width: 100% !important; }
            body { background: white !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .is-valid {
            border-color: #28a745 !important;
        }
        
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        
        .loading {
            pointer-events: none;
            opacity: 0.6;
        }
        
        .fade-in {
            animation: fadeIn 0.5s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</body>
</html>