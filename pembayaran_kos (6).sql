-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Apr 2024 pada 11.06
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

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
-- Struktur dari tabel `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `password` varchar(150) NOT NULL,
  `telp` char(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `username`, `nama`, `password`, `telp`) VALUES
(4, 'admin', 'ADMIN', '$2y$10$kXEro8SRRSK.D60oue7KieumTZLtH7f7NiA0XXBnhiJ5QyuT8wK3W', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_anggota`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_detail_pembayaran`
--

CREATE TABLE `tbl_detail_pembayaran` (
  `id` int(11) NOT NULL,
  `id_pembayaran` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `tipe_pembayaran` enum('tunai','transfer') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `bayar` float NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kamar`
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
-- Dumping data untuk tabel `tbl_kamar`
--

INSERT INTO `tbl_kamar` (`id`, `nama`, `lantai`, `spesifikasi`, `harga`, `id_kategori`) VALUES
(3, 'G1-1', 'bawah', 'Kamar mandi dalam', 800000, 1),
(4, 'G1-2', 'bawah', 'Kamar mandi dalam', 800000, 1),
(6, 'G1-3', 'bawah', 'Kamar mandi dalam', 800000, 1),
(7, 'G1-4', 'bawah', 'Kamar mandi dalam', 800000, 1),
(8, 'G1-5', 'bawah', 'Kamar mandi dalam', 800000, 1),
(9, 'G1-6', 'bawah', 'Kamar mandi dalam', 800000, 1),
(10, 'G1-7', 'bawah', 'Kamar mandi dalam', 800000, 1),
(11, 'G1-A', 'atas', 'Kamar mandi luar', 600000, 2),
(12, 'G1-B', 'atas', 'Kamar mandi luar', 600000, 2),
(13, 'G1-C', 'atas', 'Kamar mandi luar', 600000, 2),
(14, 'G1-D', 'atas', 'Kamar mandi luar', 600000, 2),
(15, 'G1-E', 'atas', 'Kamar mandi luar', 600000, 2),
(16, 'G1-F', 'atas', 'Kamar mandi luar', 600000, 2),
(17, 'G1-G', 'atas', 'Kamar mandi luar', 600000, 2),
(18, 'G1-H', 'atas', 'Kamar mandi luar', 600000, 2),
(19, 'G1-I', 'atas', 'Kamar mandi luar', 600000, 2),
(20, 'G1-J', 'atas', 'Kamar mandi luar', 600000, 2),
(21, 'G1-K', 'atas', 'Kamar mandi luar', 600000, 2),
(22, 'G2-101', 'bawah', 'Kamar mandi dalam', 800000, 1),
(23, 'G2-102', 'bawah', 'Kamar mandi dalam', 800000, 1),
(24, 'G2-103', 'bawah', 'Kamar mandi dalam', 800000, 1),
(25, 'G2-104', 'bawah', 'Kamar mandi dalam', 800000, 1),
(26, 'G2-105', 'bawah', 'Kamar mandi dalam', 800000, 1),
(27, 'G2-106', 'bawah', 'Kamar mandi dalam', 800000, 1),
(30, 'G2-211', 'atas', 'Kamar mandi dalam', 800000, 1),
(31, 'G2-212', 'atas', 'Kamar mandi dalam', 800000, 1),
(32, 'G2-213', 'atas', 'Kamar mandi dalam', 800000, 1),
(33, 'G2-214', 'atas', 'Kamar mandi dalam', 800000, 1),
(34, 'G2-215', 'atas', 'Kamar mandi dalam', 800000, 1),
(35, 'G2-216', 'atas', 'Kamar mandi dalam', 800000, 1),
(36, 'G2-217', 'atas', 'Kamar mandi dalam', 800000, 1),
(37, 'G2-218', 'atas', 'Kamar mandi dalam', 800000, 1),
(38, 'G2-219', 'atas', 'Kamar mandi dalam', 800000, 1),
(39, 'G2-220', 'atas', 'Kamar mandi dalam', 800000, 1),
(40, 'G2-221', 'atas', 'Kamar mandi dalam', 800000, 1),
(41, 'G2-222', 'atas', 'Kamar mandi dalam', 800000, 1),
(47, 'G3-2.1', 'bawah', 'Kamar mandi dalam', 800000, 1),
(48, 'G3-1', 'bawah', 'Kamar mandi luar', 500000, 2),
(49, 'G3-2', 'bawah', 'Kamar mandi luar', 500000, 2),
(50, 'G3-3', 'bawah', 'Kamar mandi luar', 500000, 2),
(51, 'G3-5', 'bawah', 'Kamar mandi luar', 500000, 2),
(52, 'G3-6', 'bawah', 'Kamar mandi luar', 500000, 2),
(53, 'G3-7', 'bawah', 'Kamar mandi luar', 500000, 2),
(54, 'G3-A', 'atas', 'Kamar mandi luar', 500000, 2),
(55, 'G3-B', 'atas', 'Kamar mandi luar', 500000, 2),
(56, 'G3-C', 'atas', 'Kamar mandi luar', 500000, 2),
(57, 'G3-D', 'atas', 'Kamar mandi luar', 500000, 2),
(58, 'G3-E', 'atas', 'Kamar mandi luar', 500000, 2),
(59, 'G3-F', 'atas', 'Kamar mandi luar', 500000, 2),
(60, 'G3-G', 'atas', 'Kamar mandi luar', 500000, 2),
(61, 'G3-H', 'atas', 'Kamar mandi luar', 500000, 2),
(62, 'VIP-1', 'atas', 'Kamar mandi dalam dan AC', 1000000, 3),
(63, 'VIP-2', 'atas', 'Kamar mandi dalam dan AC', 1000000, 3),
(64, 'VIP-3', 'atas', 'Kamar mandi dalam dan AC', 1000000, 3),
(65, 'G3-4', 'atas', 'Kamar mandi luar', 500000, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kategori_kamar`
--

CREATE TABLE `tbl_kategori_kamar` (
  `id` int(11) NOT NULL,
  `kategori` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_kategori_kamar`
--

INSERT INTO `tbl_kategori_kamar` (`id`, `kategori`) VALUES
(1, 'Kamar mandi dalam'),
(2, 'Kamar mandi luar'),
(3, 'Kamar mandi dalam & AC');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pembayaran`
--

CREATE TABLE `tbl_pembayaran` (
  `id` int(11) NOT NULL,
  `no_pembayaran` varchar(50) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_kamar` int(11) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jatuh_tempo` date DEFAULT NULL,
  `tanggal_mulai_sewa` date DEFAULT NULL,
  `total_sewa` float NOT NULL,
  `total_biaya_tambahan` float NOT NULL,
  `total_bayar` float NOT NULL,
  `diskon` float NOT NULL,
  `status` enum('lunas','cicil','proses') NOT NULL,
  `tipe_pembayaran` enum('bulanan','3 bulan','1 tahun') NOT NULL,
  `keterangan` text NOT NULL,
  `keterangan_pembayaran` text DEFAULT NULL,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_anggota`
--
ALTER TABLE `tbl_anggota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indeks untuk tabel `tbl_detail_pembayaran`
--
ALTER TABLE `tbl_detail_pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pembayaran` (`id_pembayaran`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `tbl_kamar`
--
ALTER TABLE `tbl_kamar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `tbl_kategori_kamar`
--
ALTER TABLE `tbl_kategori_kamar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_anggota`
--
ALTER TABLE `tbl_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tbl_detail_pembayaran`
--
ALTER TABLE `tbl_detail_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_kamar`
--
ALTER TABLE `tbl_kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT untuk tabel `tbl_kategori_kamar`
--
ALTER TABLE `tbl_kategori_kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_anggota`
--
ALTER TABLE `tbl_anggota`
  ADD CONSTRAINT `tbl_anggota_ibfk_1` FOREIGN KEY (`id_kamar`) REFERENCES `tbl_kamar` (`id`);

--
-- Ketidakleluasaan untuk tabel `tbl_detail_pembayaran`
--
ALTER TABLE `tbl_detail_pembayaran`
  ADD CONSTRAINT `tbl_detail_pembayaran_ibfk_1` FOREIGN KEY (`id_pembayaran`) REFERENCES `tbl_pembayaran` (`id`),
  ADD CONSTRAINT `tbl_detail_pembayaran_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `tbl_admin` (`id`),
  ADD CONSTRAINT `tbl_detail_pembayaran_ibfk_3` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_anggota` (`id`);

--
-- Ketidakleluasaan untuk tabel `tbl_kamar`
--
ALTER TABLE `tbl_kamar`
  ADD CONSTRAINT `tbl_kamar_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `tbl_kategori_kamar` (`id`);

--
-- Ketidakleluasaan untuk tabel `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  ADD CONSTRAINT `tbl_pembayaran_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_anggota` (`id`),
  ADD CONSTRAINT `tbl_pembayaran_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `tbl_admin` (`id`),
  ADD CONSTRAINT `tbl_pembayaran_ibfk_3` FOREIGN KEY (`id_kamar`) REFERENCES `tbl_kamar` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
