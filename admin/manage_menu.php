<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'database.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $nama = mysqli_real_escape_string($conn, $_POST['nama']);
                $harga = (int)$_POST['harga'];
                $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
                $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
                $status = $_POST['status'] ?? 'tersedia';
                
                $query = "INSERT INTO menu (nama, harga, kategori, deskripsi, status) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sisss", $nama, $harga, $kategori, $deskripsi, $status);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Menu berhasil ditambahkan!";
                } else {
                    $error = "Gagal menambahkan menu: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $nama = mysqli_real_escape_string($conn, $_POST['nama']);
                $harga = (int)$_POST['harga'];
                $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
                $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
                $status = $_POST['status'];
                
                $query = "UPDATE menu SET nama=?, harga=?, kategori=?, deskripsi=?, status=? WHERE id=?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sisssi", $nama, $harga, $kategori, $deskripsi, $status, $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Menu berhasil diupdate!";
                } else {
                    $error = "Gagal mengupdate menu: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                
                // Cek apakah menu masih digunakan dalam pesanan
                $check_query = "SELECT COUNT(*) as count FROM pesanan WHERE menu_id = ?";
                $check_stmt = mysqli_prepare($conn, $check_query);
                mysqli_stmt_bind_param($check_stmt, "i", $id);
                mysqli_stmt_execute($check_stmt);
                $result = mysqli_fetch_assoc(mysqli_stmt_get_result($check_stmt));
                mysqli_stmt_close($check_stmt);
                
                if ($result['count'] > 0) {
                    $error = "Menu tidak dapat dihapus karena masih ada pesanan yang menggunakan menu ini!";
                } else {
                    $delete_query = "DELETE FROM menu WHERE id = ?";
                    $delete_stmt = mysqli_prepare($conn, $delete_query);
                    mysqli_stmt_bind_param($delete_stmt, "i", $id);
                    
                    if (mysqli_stmt_execute($delete_stmt)) {
                        $success = "Menu berhasil dihapus!";
                    } else {
                        $error = "Gagal menghapus menu: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($delete_stmt);
                }
                break;
        }
    }
}

// Get all menus
$menus = mysqli_query($conn, "SELECT * FROM menu ORDER BY kategori, nama");

// Get categories for filter
$categories = mysqli_query($conn, "SELECT DISTINCT kategori FROM menu WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori");

// Get statistics
$total_menus = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM menu"));
$tersedia_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM menu WHERE status = 'tersedia'"));
$habis_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM menu WHERE status = 'habis'"));
$tidak_tersedia_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM menu WHERE status = 'tidak tersedia'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        
        .header {
            background-color: #8B4513;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 1.8rem;
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .nav-links a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .section h2 {
            color: #8B4513;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #8B4513;
            padding-bottom: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary { background-color: #8B4513; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-secondary { background-color: #6c757d; color: white; }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 1rem;
            position: relative;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-close {
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #8B4513;
            position: sticky;
            top: 0;
        }
        
        tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-tersedia { background-color: #28a745; color: white; }
        .status-habis { background-color: #dc3545; color: white; }
        .status-tidak-tersedia { background-color: #6c757d; color: white; }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .close:hover {
            color: #8B4513;
        }
        
        .search-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .search-filter input,
        .search-filter select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-item {
            background: linear-gradient(135deg, #8B4513, #A0522D);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-item h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-item p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .no-data {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .search-filter {
                flex-direction: column;
                align-items: stretch;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Kelola Menu</h1>
        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="orders.php">Pesanan</a>
            <a href="menu.php" class="active">Menu</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <!-- Statistics Summary -->
        <div class="stats-summary">
            <div class="stat-item">
                <h3><?php echo $total_menus; ?></h3>
                <p>Total Menu</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $tersedia_count; ?></h3>
                <p>Tersedia</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $habis_count; ?></h3>
                <p>Habis</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $tidak_tersedia_count; ?></h3>
                <p>Tidak Tersedia</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Add Menu Section -->
        <div class="section">
            <h2>Tambah Menu Baru</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nama">Nama Menu</label>
                        <input type="text" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" id="harga" name="harga" min="0" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Appetizer">Appetizer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="habis">Habis</option>
                            <option value="tidak tersedia">Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi menu..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Tambah Menu</button>
            </form>
        </div>

        <!-- Menu List Section -->
        <div class="section">
            <h2>Daftar Menu</h2>
            
            <!-- Search and Filter -->
            <div class="search-filter">
                <input type="text" id="searchInput" placeholder="Cari menu..." onkeyup="filterMenu()">
                <select id="categoryFilter" onchange="filterMenu()">
                    <option value="">Semua Kategori</option>
                    <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $category['kategori']; ?>"><?php echo $category['kategori']; ?></option>
                    <?php endwhile; ?>
                </select>
                <select id="statusFilter" onchange="filterMenu()">
                    <option value="">Semua Status</option>
                    <option value="tersedia">Tersedia</option>
                    <option value="habis">Habis</option>
                    <option value="tidak tersedia">Tidak Tersedia</option>
                </select>
            </div>
            
            <div class="table-container">
                <table id="menuTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Menu</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($menus) > 0): ?>
                            <?php while ($menu = mysqli_fetch_assoc($menus)): ?>
                                <tr>
                                    <td><?php echo $menu['id']; ?></td>
                                    <td><?php echo htmlspecialchars($menu['nama']); ?></td>
                                    <td>Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($menu['kategori']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo str_replace(' ', '-', $menu['status']); ?>">
                                            <?php echo ucfirst($menu['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($menu['deskripsi'], 0, 50)) . (strlen($menu['deskripsi']) > 50 ? '...' : ''); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-warning" onclick="editMenu(<?php echo htmlspecialchars(json_encode($menu)); ?>)">Edit</button>
                                            <button class="btn btn-danger" onclick="deleteMenu(<?php echo $menu['id']; ?>, '<?php echo htmlspecialchars($menu['nama']); ?>')">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="no-data">Belum ada menu yang ditambahkan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Menu Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Menu</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editNama">Nama Menu</label>
                        <input type="text" id="editNama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="editHarga">Harga (Rp)</label>
                        <input type="number" id="editHarga" name="harga" min="0" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editKategori">Kategori</label>
                        <select id="editKategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Appetizer">Appetizer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select id="editStatus" name="status" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="habis">Habis</option>
                            <option value="tidak tersedia">Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="editDeskripsi">Deskripsi</label>
                    <textarea id="editDeskripsi" name="deskripsi" rows="3"></textarea>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">Update Menu</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeleteModal()">&times;</span>
            <h2>Konfirmasi Hapus</h2>
            <p>Apakah Anda yakin ingin menghapus menu "<span id="deleteMenuName"></span>"?</p>
            <form method="POST" action="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteId">
                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editMenu(menu) {
            document.getElementById('editId').value = menu.id;
            document.getElementById('editNama').value = menu.nama;
            document.getElementById('editHarga').value = menu.harga;
            document.getElementById('editKategori').value = menu.kategori;
            document.getElementById('editStatus').value = menu.status;
            document.getElementById('editDeskripsi').value = menu.deskripsi;
            document.getElementById('editModal').style.display = 'block';
        }

        function deleteMenu(id, nama) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteMenuName').textContent = nama;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function filterMenu() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const table = document.getElementById('menuTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                if (cells.length > 0) {
                    const nama = cells[1].textContent.toLowerCase();
                    const kategori = cells[3].textContent;
                    const status = cells[4].textContent.toLowerCase().trim();
                    
                    const matchesSearch = nama.includes(searchInput);
                    const matchesCategory = categoryFilter === '' || kategori === categoryFilter;
                    const matchesStatus = statusFilter === '' || status.includes(statusFilter);
                    
                    if (matchesSearch && matchesCategory && matchesStatus) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target === editModal) {
                editModal.style.display = 'none';
            }
            if (event.target === deleteModal) {
                deleteModal.style.display = 'none';
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>