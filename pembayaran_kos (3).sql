-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2024 at 11:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pembayaran_kos`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `telp` char(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `username`, `password`, `telp`) VALUES
(1, 'fandi aziz', '$2y$10$LA96rvOnyi5aSX00XD12u.XKx98PrdsVGGY.AwOoe1jLlVS7vP18O', '629');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_anggota`
--

CREATE TABLE `tbl_anggota` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `telp` char(14) NOT NULL,
  `telp_kerabat` char(14) NOT NULL,
  `alamat` text NOT NULL,
  `tgl_kost` date NOT NULL,
  `biaya_tambahan` float NOT NULL,
  `keterangan` text NOT NULL,
  `jenis_sewa` set('bulanan','3 bulan','1 tahun') NOT NULL,
  `id_kamar` int(11) NOT NULL,
  `active` int(1) NOT NULL,
  `tanggal_tidak_aktif` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_anggota`
--

INSERT INTO `tbl_anggota` (`id`, `nama`, `telp`, `telp_kerabat`, `alamat`, `tgl_kost`, `biaya_tambahan`, `keterangan`, `jenis_sewa`, `id_kamar`, `active`, `tanggal_tidak_aktif`) VALUES
(1, 'CEK 1 TAHUN', '2133', '43423', 'fsafas', '2022-04-01', 234234, '432432', '1 tahun', 1, 1, NULL),
(5, 'CEK 3 BULAN', '0895392518509', '0895392518509', '0895392518509', '2023-01-01', 0, 'fds', '3 bulan', 2, 1, NULL),
(6, 'FANDI AZIZ PRATAMA', '0895392518509', '0895392518509', 'karanganyar', '2024-02-05', 0, 'fdsfd', 'bulanan', 7, 1, NULL),
(7, 'DANNY', '62895392518509', '08980980', 'solo', '2023-04-06', 0, '-', '1 tahun', 5, 1, NULL),
(8, 'FANDI', '082147774094', '082147774094', '082147774094', '2023-04-01', 0, '-', 'bulanan', 4, 1, NULL),
(9, 'CEK GJ', '0895392518509', '0895392518509', 'SOLO', '2023-04-06', 0, '-', 'bulanan', 8, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_detail_pembayaran`
--

CREATE TABLE `tbl_detail_pembayaran` (
  `id` int(11) NOT NULL,
  `id_pembayaran` int(11) NOT NULL,
  `bayar` float NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_detail_pembayaran`
--

INSERT INTO `tbl_detail_pembayaran` (`id`, `id_pembayaran`, `bayar`, `tanggal`, `id_admin`) VALUES
(1, 2, 600000, '2024-04-06 07:56:33', 1),
(2, 3, 30000, '2024-04-06 08:12:18', 1),
(3, 3, 270000, '2024-04-06 08:12:24', 1),
(4, 4, 600000, '2024-04-06 08:26:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kamar`
--

CREATE TABLE `tbl_kamar` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `lantai` enum('atas','bawah') NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `harga` float NOT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_kamar`
--

INSERT INTO `tbl_kamar` (`id`, `nama`, `lantai`, `spesifikasi`, `harga`, `id_kategori`) VALUES
(1, 'G1-A', 'atas', 'cek aja', 600000, 3),
(2, 'G1-B', 'atas', 'cek aja', 100000, 3),
(3, 'G1-C', 'atas', 'Lantai Atas', 600000, 4),
(4, 'G1-1', 'atas', '-', 600000, 4),
(5, 'G1-D', 'atas', 'cek aja', 600000, 4),
(6, 'G1-E', 'atas', '-', 600000, 4),
(7, 'G1-F', 'atas', '-', 600000, 4),
(8, 'G1-J', 'atas', '-', 600000, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kategori_kamar`
--

CREATE TABLE `tbl_kategori_kamar` (
  `id` int(11) NOT NULL,
  `kategori` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_kategori_kamar`
--

INSERT INTO `tbl_kategori_kamar` (`id`, `kategori`) VALUES
(3, 'KAMAR MANDI DALAM'),
(4, 'KAMAR MANDI LUAR'),
(5, 'AC');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pembayaran`
--

CREATE TABLE `tbl_pembayaran` (
  `id` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_kamar` int(11) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jatuh_tempo` date DEFAULT NULL,
  `tanggal_mulai_sewa` date DEFAULT NULL,
  `total_sewa` float NOT NULL,
  `total_biaya_tambahan` float NOT NULL,
  `total_bayar` float NOT NULL,
  `status` enum('lunas','cicil','proses') NOT NULL,
  `tipe_pembayaran` enum('bulanan','3 bulan','1 tahun') NOT NULL,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pembayaran`
--

INSERT INTO `tbl_pembayaran` (`id`, `id_anggota`, `id_kamar`, `tanggal`, `jatuh_tempo`, `tanggal_mulai_sewa`, `total_sewa`, `total_biaya_tambahan`, `total_bayar`, `status`, `tipe_pembayaran`, `id_admin`) VALUES
(2, 6, 7, '2024-04-06 07:56:33', '2024-04-05', '2024-02-05', 600000, 0, 600000, 'lunas', 'bulanan', 1),
(3, 5, 2, '2024-04-06 08:12:24', '2024-04-01', '2023-01-01', 300000, 0, 300000, 'lunas', '3 bulan', 1),
(4, 6, 7, '2024-04-06 08:26:36', '2024-02-05', '2024-02-05', 600000, 0, 600000, 'lunas', 'bulanan', 1),
(5, 5, 2, '2024-04-06 08:37:30', '2023-01-01', '2023-01-01', 300000, 0, 300000, 'proses', '3 bulan', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_anggota`
--
ALTER TABLE `tbl_anggota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indexes for table `tbl_detail_pembayaran`
--
ALTER TABLE `tbl_detail_pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pembayaran` (`id_pembayaran`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `tbl_kamar`
--
ALTER TABLE `tbl_kamar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `tbl_kategori_kamar`
--
ALTER TABLE `tbl_kategori_kamar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `id_admin` (`id_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_anggota`
--
ALTER TABLE `tbl_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_detail_pembayaran`
--
ALTER TABLE `tbl_detail_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_kamar`
--
ALTER TABLE `tbl_kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_kategori_kamar`
--
ALTER TABLE `tbl_kategori_kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_anggota`
--
ALTER TABLE `tbl_anggota`
  ADD CONSTRAINT `tbl_anggota_ibfk_1` FOREIGN KEY (`id_kamar`) REFERENCES `tbl_kamar` (`id`);

--
-- Constraints for table `tbl_detail_pembayaran`
--
ALTER TABLE `tbl_detail_pembayaran`
  ADD CONSTRAINT `tbl_detail_pembayaran_ibfk_1` FOREIGN KEY (`id_pembayaran`) REFERENCES `tbl_pembayaran` (`id`),
  ADD CONSTRAINT `tbl_detail_pembayaran_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `tbl_admin` (`id`);

--
-- Constraints for table `tbl_kamar`
--
ALTER TABLE `tbl_kamar`
  ADD CONSTRAINT `tbl_kamar_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `tbl_kategori_kamar` (`id`);

--
-- Constraints for table `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  ADD CONSTRAINT `tbl_pembayaran_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_anggota` (`id`),
  ADD CONSTRAINT `tbl_pembayaran_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `tbl_admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
