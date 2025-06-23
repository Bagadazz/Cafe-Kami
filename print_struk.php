<?php
include 'database.php';

$order_id = $_GET['order_id'] ?? '';

if (!$order_id) {
    echo "Order ID tidak ditemukan!";
    exit;
}

// Ambil data pesanan
$query = "SELECT p.*, m.nama as nama_menu, m.harga 
          FROM pesanan p 
          JOIN menu m ON p.menu_id = m.id 
          WHERE p.order_id = '$order_id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Pesanan tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - <?= $data['order_id'] ?></title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        body {
            font-family: monospace;
            max-width: 300px;
            margin: 0 auto;
            padding: 10px;
            background: white;
        }
        
        .struk-container {
            border: 1px dashed #000;
            padding: 10px;
            background: white;
        }
        
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .cafe-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .cafe-info {
            font-size: 10px;
            line-height: 1.2;
        }
        
        .order-info {
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .items {
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }
        
        .total-section {
            font-size: 12px;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 10px;
        }
        
        .qr-section {
            text-align: center;
            margin: 10px 0;
        }
        
        .print-button {
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #2E8B57;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="print-button">Print Struk</button>
        <button onclick="window.close()" class="print-button" style="background-color: #8B4513;">Tutup</button>
    </div>

    <div class="struk-container">
        <div class="header">
            <div class="cafe-name">MENU CAFÉ</div>
            <div class="cafe-info">
                Jl. Contoh No. 123<br>
                Telp: (061) 123-4567<br>
                Medan, Sumatera Utara
            </div>
        </div>

        <div class="order-info">
            <div>Order ID: <?= $data['order_id'] ?></div>
            <div>Tanggal: <?= date('d/m/Y H:i:s', strtotime($data['tanggal'])) ?></div>
            <div>Pelanggan: <?= $data['nama_pelanggan'] ?></div>
            <div>Kasir: Admin</div>
        </div>

        <div class="items">
            <div class="item-row">
                <span>Item</span>
                <span>Qty x Harga</span>
                <span>Subtotal</span>
            </div>
            <div style="border-bottom: 1px solid #ccc; margin: 3px 0;"></div>
            <div class="item-row">
                <span><?= $data['nama_menu'] ?></span>
                <span><?= $data['jumlah'] ?> x <?= number_format($data['harga'], 0, ',', '.') ?></span>
                <span><?= number_format($data['total_harga'], 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="total-section">
            <div class="item-row">
                <span>TOTAL:</span>
                <span>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="qr-section">
            <div style="font-size: 10px; margin-bottom: 5px;">Scan untuk Pembayaran:</div>
            <img src="assets/payment_qr.jpeg" alt="QR Payment" style="border: 1px solid #ccc; width: 100px; height: 100px;">
        </div>

        <div class="footer">
            <div>Status: <?= strtoupper($data['status']) ?></div>
            <div style="margin-top: 5px;">
                ================================<br>
                TERIMA KASIH ATAS KUNJUNGAN ANDA<br>
                SELAMAT MENIKMATI!<br>
                ================================
            </div>
        </div>
    </div>

    <script>
        // Auto print jika dipanggil dari tombol print
        if (window.location.search.includes('auto_print=1')) {
            window.onload = function() {
                window.print();
            }
        }
    </script>
</body>
</html>