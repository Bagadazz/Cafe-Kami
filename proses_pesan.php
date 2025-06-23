<?php
include 'database.php';
$ringkasan = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_pelanggan'];
    $menu_id = $_POST['menu_id'];
    $jumlah = $_POST['jumlah'];

    // Get menu details
    $menu_query = mysqli_query($conn, "SELECT nama, harga FROM menu WHERE id=$menu_id");
    $menu = mysqli_fetch_assoc($menu_query);
    $harga = $menu['harga'];
    $nama_menu = $menu['nama'];
    $total_harga = $harga * $jumlah;

    // Generate unique order ID
    $order_id = 'ORD' . date('YmdHis') . rand(100, 999);

    // Insert pesanan
    mysqli_query($conn, "INSERT INTO pesanan (order_id, nama_pelanggan, menu_id, jumlah, total_harga, status, tanggal) 
                     VALUES ('$order_id', '$nama', $menu_id, $jumlah, $total_harga, 'belum_dibayar', NOW())");

    $ringkasan = [
        'order_id' => $order_id,
        'nama' => $nama,
        'menu_id' => $menu_id,
        'nama_menu' => $nama_menu,
        'jumlah' => $jumlah,
        'harga' => $harga,
        'total' => $total_harga,
        'tanggal' => date('d/m/Y H:i:s')
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Pesanan - Café Kami</title>
    <link rel="stylesheet" href="assets/pesan.css">
</head>
<body>

<?php if ($ringkasan): ?>
    <div class="container">
        <h2>🧾 Ringkasan Pesanan</h2>
        
        <div id="receipt-content">
            <p><strong>Order ID:</strong> <span><?= $ringkasan['order_id'] ?></span></p>
            <p><strong>Nama:</strong> <span><?= $ringkasan['nama'] ?></span></p>
            <p><strong>Menu:</strong> <span><?= $ringkasan['nama_menu'] ?></span></p>
            <p><strong>Jumlah:</strong> <span><?= $ringkasan['jumlah'] ?> item</span></p>
            <p><strong>Harga Satuan:</strong> <span>Rp <?= number_format($ringkasan['harga'], 0, ',', '.') ?></span></p>
            <p><strong>Total Harga:</strong> <span>Rp <?= number_format($ringkasan['total'], 0, ',', '.') ?></span></p>
            <p><strong>Tanggal:</strong> <span><?= $ringkasan['tanggal'] ?></span></p>
        </div>
        
        <div class="payment-section">
            <h3>💳 Metode Pembayaran</h3>
            
            <!-- QR Code untuk pembayaran -->
            <div class="qr-container">
                <h4>📱 Scan QR untuk Pembayaran</h4>
                <img src="assets/payment_qr.jpeg" alt="QR Payment">
                <p><small>💰 atau bayar di kasir</small></p>
            </div>
        </div>
        
        <div class="button-group">
            <a href="index.php" class="button">🏠 Kembali</a>
            <button onclick="printReceipt()" class="button print-button">🖨️ Print Struk</button>
            <a href="print_struk.php?order_id=<?= $ringkasan['order_id'] ?>" target="_blank" class="button print-button">📥 Download</a>
        </div>
    </div>

    <script>
        function printReceipt() {
            // Buka jendela baru untuk print
            var printWindow = window.open('print_struk.php?order_id=<?= $ringkasan['order_id'] ?>', '_blank', 'width=800,height=600');
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>

<?php else: ?>
    <div class="container">
        <h2>⚠️ Tidak Ada Data Pesanan</h2>
        <p style="text-align: center; margin: 2rem 0;">
            <span>Tidak ada data pesanan yang ditemukan.</span>
        </p>
        <div class="button-group">
            <a href="index.php" class="button">🏠 Kembali ke Beranda</a>
        </div>
    </div>
<?php endif; ?>

</body>
</html>