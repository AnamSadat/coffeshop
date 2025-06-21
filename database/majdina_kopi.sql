-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 30, 2024 at 03:39 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `majdina`
--

-- --------------------------------------------------------

--
-- Table structure for table `harga`
--

CREATE TABLE `harga` (
  `id` int NOT NULL,
  `nominal` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `harga`
--

INSERT INTO `harga` (`id`, `nominal`) VALUES
(1, 50000),
(2, 7000),
(3, 6000),
(10012, 2000),
(10013, 19000),
(10014, 15000),
(10015, 30000),
(10016, 20000),
(10017, 41000),
(10018, 56000),
(10019, 60000);

-- --------------------------------------------------------

--
-- Table structure for table `kopi`
--

CREATE TABLE `kopi` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga_id` int DEFAULT NULL,
  `stok_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kopi`
--

INSERT INTO `kopi` (`id`, `nama`, `harga_id`, `stok_id`) VALUES
(1, 'Moccacino', 1, 1),
(2, 'Kopi Robusta', 2, 2),
(13, 'Good Day', 10012, 29),
(14, 'Hot Espresso', 10013, 30),
(15, 'Americano', 10014, 31),
(16, 'Matcha Frappe ', 10015, 32),
(17, 'Café Dolce', 10016, 33),
(18, 'Tea Latte', 10017, 34),
(19, 'Caffe Mocha', 10018, 35),
(20, 'Caramel Macchiato', 10019, 36);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `email_pelanggan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `email_pelanggan`) VALUES
(1, 'Estiawan Putra S.E.I', 'jumadi55@permadi.co'),
(2, 'Gangsa Budiyanto ', 'pyolanda@hariyah.tv'),
(3, 'Alambana Simanjuntak', 'wahyu.hutasoit@mandasari.go.id'),
(4, 'Humaira Padmasari S.Gz', 'kasim41@andriani.mil.id'),
(5, 'Silvia Oktaviani S.Ked', 'malik.namaga@gmail.co.id'),
(6, 'Anastasia Usamah S.Farm', 'usyi.prayoga@widiastuti.desa.id'),
(7, 'Luwes Satya Budiyanto S.E.', 'mahendra.zamira@gmail.co.id'),
(8, 'Jamal Nugroho M.Kom.', 'lwasita@gmail.co.id'),
(9, 'Ade Damanik S.Sos', 'indra46@wibisono.mil.id'),
(10, 'Raihan Mansur', 'hidayat.emin@saputra.biz');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int NOT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `id_kopi` int DEFAULT NULL,
  `jumlah_pesanan` int NOT NULL,
  `waktu_pesan` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `id_kopi`, `jumlah_pesanan`, `waktu_pesan`) VALUES
(1, 1, 1, 2, '2024-01-28 03:30:00'),
(3, 2, 2, 46, '2024-01-29 14:54:43'),
(4, 3, 13, 5, '2024-01-29 16:03:58'),
(5, 4, 14, 3, '2024-01-29 16:03:58'),
(6, 5, 15, 1, '2024-01-29 16:03:58'),
(7, 6, 16, 3, '2024-01-29 16:03:58'),
(8, 7, 17, 6, '2024-01-29 16:03:58'),
(9, 8, 18, 2, '2024-01-29 16:03:58'),
(10, 9, 19, 2, '2024-01-29 16:03:58');

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `id` int NOT NULL,
  `jumlah` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stok`
--

INSERT INTO `stok` (`id`, `jumlah`) VALUES
(1, 90),
(2, 50),
(3, 75),
(29, 20),
(30, 10),
(31, 15),
(32, 16),
(33, 18),
(34, 30),
(35, 83),
(36, 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kopi`
--
ALTER TABLE `kopi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `harga_id` (`harga_id`),
  ADD KEY `stok_id` (`stok_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_kopi` (`id_kopi`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `harga`
--
ALTER TABLE `harga`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10020;

--
-- AUTO_INCREMENT for table `kopi`
--
ALTER TABLE `kopi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kopi`
--
ALTER TABLE `kopi`
  ADD CONSTRAINT `kopi_ibfk_1` FOREIGN KEY (`harga_id`) REFERENCES `harga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kopi_ibfk_2` FOREIGN KEY (`stok_id`) REFERENCES `stok` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_kopi`) REFERENCES `kopi` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
