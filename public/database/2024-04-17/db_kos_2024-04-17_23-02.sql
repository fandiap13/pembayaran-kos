-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: 127.0.0.1	Database: pembayaran_kos
-- ------------------------------------------------------
-- Server version 	5.5.5-10.4.28-MariaDB
-- Date: Wed, 17 Apr 2024 23:02:37 +0700

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbl_admin`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `password` varchar(150) NOT NULL,
  `telp` char(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_admin`
--

LOCK TABLES `tbl_admin` WRITE;
/*!40000 ALTER TABLE `tbl_admin` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_admin` VALUES (1,'fandi','FANDI AZIZ','$2y$10$Q9EWyDwjshoAtsMn.2H5QuLvBROyuiIAxTeNyhQ9.ulqFiQP9zLaS','62895392518509'),(4,'admin','ADMIN','$2y$10$kXEro8SRRSK.D60oue7KieumTZLtH7f7NiA0XXBnhiJ5QyuT8wK3W','');
/*!40000 ALTER TABLE `tbl_admin` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_admin` with 2 row(s)
--

--
-- Table structure for table `tbl_anggota`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_anggota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `tanggal_tidak_aktif` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_kamar` (`id_kamar`),
  CONSTRAINT `tbl_anggota_ibfk_1` FOREIGN KEY (`id_kamar`) REFERENCES `tbl_kamar` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_anggota`
--

LOCK TABLES `tbl_anggota` WRITE;
/*!40000 ALTER TABLE `tbl_anggota` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_anggota` VALUES (1,'FANDI AZIZ PRATAMA','6285234777851','6285234777851','Karanganyar','2024-04-17',0,'-','bulanan',1,1,NULL),(2,'JONI','6285234777851','6285234777851','Karanganyar','2024-04-17',0,'-','bulanan',3,1,NULL),(3,'AZIZ','6285234777851','6285234777851','jungke','2024-04-17',0,'-','bulanan',2,1,NULL);
/*!40000 ALTER TABLE `tbl_anggota` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_anggota` with 3 row(s)
--

--
-- Table structure for table `tbl_detail_pembayaran`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_detail_pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pembayaran` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `tipe_pembayaran` enum('tunai','transfer') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `bayar` float NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pembayaran` (`id_pembayaran`),
  KEY `id_admin` (`id_admin`),
  KEY `id_anggota` (`id_anggota`),
  CONSTRAINT `tbl_detail_pembayaran_ibfk_1` FOREIGN KEY (`id_pembayaran`) REFERENCES `tbl_pembayaran` (`id`),
  CONSTRAINT `tbl_detail_pembayaran_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `tbl_admin` (`id`),
  CONSTRAINT `tbl_detail_pembayaran_ibfk_3` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_anggota` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_detail_pembayaran`
--

LOCK TABLES `tbl_detail_pembayaran` WRITE;
/*!40000 ALTER TABLE `tbl_detail_pembayaran` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_detail_pembayaran` VALUES (1,1,1,'tunai','Tunai',300000,'2024-04-17 09:39:57',4),(2,1,1,'transfer','TRANSFER BRI ',200000,'2024-04-17 09:40:16',4),(4,2,1,'transfer','Transfer BANK BRI ATAS NAMA JON',600000,'2024-04-17 10:03:55',4),(5,4,3,'transfer','bank bri',200000,'2024-04-17 13:54:18',1),(6,4,3,'tunai','',400000,'2024-04-17 13:54:33',1),(7,5,3,'tunai','',600000,'2024-04-17 15:46:46',4);
/*!40000 ALTER TABLE `tbl_detail_pembayaran` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_detail_pembayaran` with 6 row(s)
--

--
-- Table structure for table `tbl_kamar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_kamar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `lantai` enum('atas','bawah') NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `harga` float NOT NULL,
  `id_kategori` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `tbl_kamar_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `tbl_kategori_kamar` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_kamar`
--

LOCK TABLES `tbl_kamar` WRITE;
/*!40000 ALTER TABLE `tbl_kamar` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_kamar` VALUES (1,'G1-1','atas','cek aja',600000,4),(2,'G1-B','atas','cek aja',600000,4),(3,'G1-C','atas','Lantai Atas',600000,4),(4,'G1-A','atas','-',600000,4),(5,'G1-D','atas','cek aja',600000,4),(6,'G1-E','atas','-',600000,4),(7,'G1-F','atas','-',600000,4),(8,'G1-J','atas','-',600000,4),(9,'G1-2','atas','-',600000,4);
/*!40000 ALTER TABLE `tbl_kamar` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_kamar` with 9 row(s)
--

--
-- Table structure for table `tbl_kategori_kamar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_kategori_kamar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_kategori_kamar`
--

LOCK TABLES `tbl_kategori_kamar` WRITE;
/*!40000 ALTER TABLE `tbl_kategori_kamar` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_kategori_kamar` VALUES (3,'Kamar Mandi Dalam'),(4,'Kamar Mandi Luar'),(5,'AC');
/*!40000 ALTER TABLE `tbl_kategori_kamar` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_kategori_kamar` with 3 row(s)
--

--
-- Table structure for table `tbl_pembayaran`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `id_admin` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_anggota` (`id_anggota`),
  KEY `id_admin` (`id_admin`),
  KEY `id_kamar` (`id_kamar`),
  CONSTRAINT `tbl_pembayaran_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `tbl_anggota` (`id`),
  CONSTRAINT `tbl_pembayaran_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `tbl_admin` (`id`),
  CONSTRAINT `tbl_pembayaran_ibfk_3` FOREIGN KEY (`id_kamar`) REFERENCES `tbl_kamar` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_pembayaran`
--

LOCK TABLES `tbl_pembayaran` WRITE;
/*!40000 ALTER TABLE `tbl_pembayaran` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `tbl_pembayaran` VALUES (1,'G1-A-0001',1,1,'2024-04-17 09:40:16','2024-04-17','2024-04-17',600000,0,500000,100000,'lunas','bulanan','Pembayaran kos bulan april',4),(2,'G1-A-0002',1,1,'2024-04-17 10:03:55','2024-05-17','2024-04-17',600000,0,600000,0,'lunas','bulanan','Pembayaran kos bulan mei (langsung 2 bulan dari april)',4),(4,'G1-B-0001',3,2,'2024-04-17 13:54:33','2024-04-17','2024-04-17',600000,0,600000,0,'lunas','bulanan','cek',1),(5,'G1-B-0002',3,2,'2024-04-17 15:46:46','2024-05-17','2024-04-17',600000,0,600000,0,'lunas','bulanan','cek',4);
/*!40000 ALTER TABLE `tbl_pembayaran` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `tbl_pembayaran` with 4 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET AUTOCOMMIT=@OLD_AUTOCOMMIT */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Wed, 17 Apr 2024 23:02:37 +0700
