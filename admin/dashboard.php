<?php
include '../database.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Statistik
$hari_ini = date('Y-m-d');
$bulan_ini = date('Y-m');
$totalHari = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) AS total FROM pesanan WHERE DATE(waktu_pesan) = '$hari_ini'"))['total'] ?? 0;
$jumlahPesananHari = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM pesanan WHERE DATE(waktu_pesan) = '$hari_ini'"))['jumlah'] ?? 0;
$totalBulan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) AS total FROM pesanan WHERE DATE_FORMAT(waktu_pesan, '%Y-%m') = '$bulan_ini'"))['total'] ?? 0;
$menuTerlaris = mysqli_fetch_assoc(mysqli_query($conn, "SELECT m.nama, COUNT(*) as total FROM pesanan p JOIN menu m ON p.menu_id = m.id GROUP BY p.menu_id ORDER BY total DESC LIMIT 1"))['nama'] ?? 'Belum ada';

// Grafik (7 hari terakhir)
$chartData = [];
for ($i = 6; $i >= 0; $i--) {
    $tanggal = date('Y-m-d', strtotime("-$i days"));
    $hasil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) AS total FROM pesanan WHERE DATE(waktu_pesan) = '$tanggal'"))['total'] ?? 0;
    $chartData[] = ['tanggal' => $tanggal, 'total' => $hasil];
}

// Handle konfirmasi pembayaran
if (isset($_GET['konfirmasi'])) {
    $id = (int)$_GET['konfirmasi'];
    mysqli_query($conn, "UPDATE pesanan SET status='sudah_dibayar' WHERE id=$id");
    echo "<script>alert('Pembayaran dikonfirmasi!');window.location='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .admin-nav {
            background-color: #8B4513;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        
        .admin-nav ul {
            list-style: none;
            display: flex;
            gap: 1rem;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
        }
        
        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            background-color: rgba(255,255,255,0.1);
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .admin-nav a:hover {
            background-color: rgba(255,255,255,0.2);
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: opacity 0.3s;
        }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        .btn-primary { background-color: #007bff; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        
        .statistik {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid #8B4513;
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            margin: 0 0 0.5rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .value {
            color: #8B4513;
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0;
        }
        
        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
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
            margin-bottom: 1rem;
            border-bottom: 2px solid #8B4513;
            padding-bottom: 0.5rem;
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
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .status-belum-dibayar { background-color: #ffc107; color: black; }
        .status-sudah-dibayar { background-color: #28a745; color: white; }
        
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .admin-nav ul {
                flex-direction: column;
            }
            
            .statistik {
                grid-template-columns: 1fr;
            }
            
            table {
                font-size: 14px;
            }
            
            .btn {
                padding: 8px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Menu -->
    <nav class="admin-nav">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage_menu.php">Kelola Menu</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        <div class="dashboard-header">
            <h1>Dashboard Admin</h1>
            <div>
                <a href="manage_menu.php" class="btn btn-primary">Kelola Menu</a>
                <a href="../logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="statistik">
            <div class="stat-card">
                <h3>Total Hari Ini</h3>
                <p class="value">Rp<?= number_format($totalHari) ?></p>
            </div>
            <div class="stat-card">
                <h3>Pesanan Hari Ini</h3>
                <p class="value"><?= $jumlahPesananHari ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Bulan Ini</h3>
                <p class="value">Rp<?= number_format($totalBulan) ?></p>
            </div>
            <div class="stat-card">
                <h3>Menu Terlaris</h3>
                <p class="value" style="font-size: 1.2rem;"><?= $menuTerlaris ?></p>
            </div>
        </div>

        <!-- Chart -->
        <div class="chart-container">
            <h2>Grafik Penjualan 7 Hari Terakhir</h2>
            <canvas id="chart7hari" width="600" height="200"></canvas>
        </div>

        <!-- Pesanan Belum Dibayar -->
        <div class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2>Daftar Pesanan Belum Dibayar</h2>
                <a href="manage_menu.php" class="btn btn-warning">Edit Menu</a>
            </div>
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pesanan = mysqli_query($conn, "SELECT p.id, p.nama_pelanggan, m.nama AS menu, p.jumlah, p.total_harga, p.status
                                                        FROM pesanan p JOIN menu m ON p.menu_id = m.id
                                                        WHERE p.status = 'belum_dibayar'
                                                        ORDER BY p.waktu_pesan DESC");
                        if (mysqli_num_rows($pesanan) > 0) {
                            while ($row = mysqli_fetch_assoc($pesanan)) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['nama_pelanggan']) . "</td>
                                        <td>" . htmlspecialchars($row['menu']) . "</td>
                                        <td>{$row['jumlah']}</td>
                                        <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                                        <td><span class='status-badge status-belum-dibayar'>Belum Dibayar</span></td>
                                        <td><a href='dashboard.php?konfirmasi={$row['id']}' class='btn btn-success' onclick='return confirm(\"Konfirmasi pembayaran untuk pesanan ini?\")'>Konfirmasi</a></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center; color: #666;'>Tidak ada pesanan yang belum dibayar</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Data Pesanan Terbaru -->
        <div class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2>Data Pesanan Terbaru</h2>
                <a href="all_orders.php" class="btn btn-secondary">Lihat Semua</a>
            </div>
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Waktu Pesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pesanan = mysqli_query($conn, "SELECT p.*, m.nama as menu_nama FROM pesanan p 
                                                        JOIN menu m ON p.menu_id = m.id 
                                                        ORDER BY p.waktu_pesan DESC LIMIT 10");
                        if (mysqli_num_rows($pesanan) > 0) {
                            while($row = mysqli_fetch_assoc($pesanan)) {
                                $statusClass = $row['status'] == 'sudah_dibayar' ? 'status-sudah-dibayar' : 'status-belum-dibayar';
                                $statusText = $row['status'] == 'sudah_dibayar' ? 'Sudah Dibayar' : 'Belum Dibayar';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                            <td><?= htmlspecialchars($row['menu_nama']) ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td>Rp<?= number_format($row['total_harga']) ?></td>
                            <td><span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['waktu_pesan'])) ?></td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center; color: #666;'>Belum ada pesanan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Chart configuration
        const ctx = document.getElementById('chart7hari').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($chartData, 'tanggal')) ?>,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: <?= json_encode(array_column($chartData, 'total')) ?>,
                    backgroundColor: 'rgba(139, 69, 19, 0.1)',
                    borderColor: '#8B4513',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#8B4513',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        ticks: {
                            callback: function(value, index) {
                                const date = new Date(this.getLabelForValue(value));
                                return date.toLocaleDateString('id-ID', { 
                                    day: '2-digit', 
                                    month: 'short' 
                                });
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Penjualan: Rp' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Auto refresh setiap 5 menit untuk data real-time
        setTimeout(function() {
            location.reload();
        }, 300000); // 5 minutes
    </script>
</body>
</html>