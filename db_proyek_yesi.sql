-- phpMyAdmin SQL Dump
-- Host: localhost
-- Generation Time: Aug 16, 2026
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_proyek_yesi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int(11) NOT NULL,
  `username_admin` varchar(255) NOT NULL,
  `password_admin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `username_admin`, `password_admin`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Minuman Dingin'),
(2, 'Minuman Panas'),
(3, 'Cemilan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id_barang` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `deskripsi_barang` varchar(255) NOT NULL,
  `harga_barang` varchar(255) NOT NULL,
  `stok_barang` int(11) NOT NULL,
  `foto_barang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_barang`
--

INSERT INTO `tb_barang` (`id_barang`, `id_kategori`, `nama_barang`, `deskripsi_barang`, `harga_barang`, `stok_barang`, `foto_barang`) VALUES
(1, 1, 'Mango Oats Jasmine Tea', 'Minuman segar rasa mangga', '16000', 50, 'menu1.jpg'),
(2, 1, 'Boba Sundae', 'Es krim dengan boba manis', '16000', 50, 'menu2.jpg'),
(3, 1, 'Berry Bean Sundae', 'Es krim berry segar', '16000', 50, 'menu3.jpg'),
(4, 1, 'Ice Cream Tea', 'Teh dengan es krim', '13000', 50, 'menu4.jpg'),
(5, 1, 'Lemon Jasmine Tea', 'Teh melati rasa lemon', '12000', 50, 'menu5.jpg'),
(6, 1, 'MI Shake Strawberry', 'Minuman shake segar rasa strawberry', '16000', 50, 'menu6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_meja`
--

CREATE TABLE `tb_meja` (
  `id_meja` int(11) NOT NULL,
  `kode_meja` varchar(255) NOT NULL,
  `nama_meja` varchar(255) NOT NULL,
  `token_qr` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_meja`
--

INSERT INTO `tb_meja` (`id_meja`, `kode_meja`, `nama_meja`, `token_qr`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MEJA-01', 'Meja 1', 'QR-MEJA-01', 'ACTIVE', '2026-08-16 01:00:00', '2026-08-16 01:00:00'),
(2, 'MEJA-02', 'Meja 2', 'QR-MEJA-02', 'ACTIVE', '2026-08-16 01:00:00', '2026-08-16 01:00:00'),
(3, 'MEJA-03', 'Meja 3', 'QR-MEJA-03', 'ACTIVE', '2026-08-16 01:00:00', '2026-08-16 01:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pesanan`
--

CREATE TABLE `tb_pesanan` (
  `id_pesanan` varchar(255) NOT NULL,
  `nama_pesanan` varchar(255) NOT NULL,
  `alamat_pesanan` varchar(255) NOT NULL,
  `no_hp_pesanan` varchar(255) NOT NULL,
  `email_pesanan` varchar(255) NOT NULL,
  `total_harga_pesanan` varchar(255) NOT NULL,
  `status_pesanan` varchar(255) NOT NULL DEFAULT 'Menunggu Pembayaran',
  `tanggal_pesanan` date NOT NULL,
  `jenis_pembayaran` varchar(255) NOT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `bukti_pembayaran` longtext DEFAULT NULL,
  `id_meja` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_pesanan`
--

CREATE TABLE `tb_detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` varchar(255) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah_pesanan` int(11) NOT NULL,
  `subtotal_harga` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`);

ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id_barang`);

ALTER TABLE `tb_meja`
  ADD PRIMARY KEY (`id_meja`),
  ADD UNIQUE KEY `tb_meja_kode_meja_unique` (`kode_meja`),
  ADD UNIQUE KEY `tb_meja_token_qr_unique` (`token_qr`);

ALTER TABLE `tb_pesanan`
  ADD PRIMARY KEY (`id_pesanan`);

ALTER TABLE `tb_detail_pesanan`
  ADD PRIMARY KEY (`id_detail`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `tb_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tb_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `tb_meja`
  MODIFY `id_meja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tb_detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
