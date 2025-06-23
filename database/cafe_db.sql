-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Jun 2025 pada 11.41
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cafe_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('tersedia','habis','tidak-tersedia') DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama`, `harga`, `kategori`, `deskripsi`, `status`) VALUES
(1, 'Espresso', 20000, 'Minuman', 'Kopi espresso premium dengan rasa yang kuat dan aroma yang nikmat', 'tersedia'),
(2, 'Cappuccino', 25000, 'Minuman', 'Minuman kopi dengan campuran susu steamed yang creamy', 'tersedia'),
(3, 'Croissant', 18000, 'Makanan', 'Roti croissant yang renyah dan buttery, cocok untuk sarapan', 'tersedia'),
(4, 'Sandwich', 30000, 'Makanan', 'Sandwich dengan isian lengkap, daging, sayuran segar dan saus special', 'tersedia'),
(5, 'Lemon Tea', 15000, 'Minuman', 'Teh lemon segar dengan perasan jeruk nipis alami', 'tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `waktu_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('belum_dibayar','sudah_dibayar') DEFAULT 'belum_dibayar',
  `order_id` varchar(50) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id`, `nama_pelanggan`, `menu_id`, `jumlah`, `total_harga`, `waktu_pesan`, `status`, `order_id`, `tanggal`) VALUES
(9, 'michael', 1, 2, 40000, '2025-06-16 18:27:14', 'sudah_dibayar', 'ORD20250616202714838', '2025-06-17 01:27:14'),
(10, 'samuel', 3, 2, 36000, '2025-06-16 19:01:38', 'sudah_dibayar', 'ORD20250616210138931', '2025-06-17 02:01:38'),
(11, 'sisi', 5, 2, 30000, '2025-06-16 20:48:07', 'belum_dibayar', 'ORD20250616224807761', '2025-06-17 03:48:07'),
(12, 'sisi', 5, 2, 30000, '2025-06-16 20:48:19', 'belum_dibayar', 'ORD20250616224819172', '2025-06-17 03:48:19'),
(13, 'sisi', 5, 2, 30000, '2025-06-16 20:52:40', 'belum_dibayar', 'ORD20250616225240861', '2025-06-17 03:52:40'),
(14, 'sisi', 5, 2, 30000, '2025-06-16 20:53:08', 'belum_dibayar', 'ORD20250616225308682', '2025-06-17 03:53:08'),
(15, 'sisi', 5, 2, 30000, '2025-06-16 20:53:43', 'belum_dibayar', 'ORD20250616225343663', '2025-06-17 03:53:43'),
(16, 'sisi', 5, 2, 30000, '2025-06-16 20:53:57', 'belum_dibayar', 'ORD20250616225357727', '2025-06-17 03:53:57'),
(17, 'sisi', 5, 2, 30000, '2025-06-16 20:55:10', 'belum_dibayar', 'ORD20250616225510429', '2025-06-17 03:55:10'),
(18, 'sisi', 5, 2, 30000, '2025-06-16 20:55:21', 'belum_dibayar', 'ORD20250616225521901', '2025-06-17 03:55:21'),
(19, 'sisi', 5, 2, 30000, '2025-06-16 20:55:52', 'belum_dibayar', 'ORD20250616225552865', '2025-06-17 03:55:52'),
(20, 'lulu', 1, 1, 20000, '2025-06-16 20:57:11', 'sudah_dibayar', 'ORD20250616225711549', '2025-06-17 03:57:11'),
(21, 'michael', 1, 1, 20000, '2025-06-23 02:30:14', 'belum_dibayar', 'ORD20250623043014104', '2025-06-23 09:30:14'),
(22, 'dions', 1, 2, 40000, '2025-06-23 06:42:55', 'belum_dibayar', 'ORD20250623084255285', '2025-06-23 13:42:55'),
(23, 'riri', 5, 1, 15000, '2025-06-23 06:45:15', 'sudah_dibayar', 'ORD20250623084515820', '2025-06-23 13:45:15'),
(24, 'lala', 1, 1, 20000, '2025-06-23 06:58:19', 'belum_dibayar', 'ORD20250623085819199', '2025-06-23 13:58:19'),
(25, 'aku', 2, 1, 25000, '2025-06-23 07:17:07', 'belum_dibayar', 'ORD20250623091707317', '2025-06-23 14:17:07'),
(26, 'lili', 1, 1, 20000, '2025-06-23 07:27:43', 'sudah_dibayar', 'ORD20250623092743216', '2025-06-23 14:27:43'),
(27, 'rini', 2, 1, 25000, '2025-06-23 07:47:30', 'sudah_dibayar', 'ORD20250623094730793', '2025-06-23 14:47:30'),
(28, 'roro', 2, 1, 25000, '2025-06-23 07:55:44', 'belum_dibayar', 'ORD20250623095544748', '2025-06-23 14:55:44'),
(29, 'roro', 2, 1, 25000, '2025-06-23 07:57:11', 'belum_dibayar', 'ORD20250623095711985', '2025-06-23 14:57:11'),
(30, 'dono', 3, 1, 18000, '2025-06-23 07:57:29', 'belum_dibayar', 'ORD20250623095729442', '2025-06-23 14:57:29'),
(31, 'lula', 4, 2, 60000, '2025-06-23 08:07:44', 'belum_dibayar', 'ORD20250623100744500', '2025-06-23 15:07:44'),
(32, 'nana', 3, 1, 18000, '2025-06-23 08:13:44', 'belum_dibayar', 'ORD20250623101344852', '2025-06-23 15:13:44'),
(33, 'alucard', 1, 2, 40000, '2025-06-23 08:16:05', 'belum_dibayar', 'ORD20250623101605837', '2025-06-23 15:16:05'),
(34, 'tuti', 1, 1, 20000, '2025-06-23 08:20:25', 'belum_dibayar', 'ORD20250623102025613', '2025-06-23 15:20:25'),
(35, 'amon', 2, 1, 25000, '2025-06-23 08:26:45', 'belum_dibayar', 'ORD20250623102645720', '2025-06-23 15:26:45'),
(36, 'nono', 4, 2, 60000, '2025-06-23 08:53:06', 'sudah_dibayar', 'ORD20250623105306288', '2025-06-23 15:53:06'),
(37, 'uiuiuiui', 3, 4, 72000, '2025-06-23 09:16:30', 'sudah_dibayar', 'ORD20250623111630272', '2025-06-23 16:16:30');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
