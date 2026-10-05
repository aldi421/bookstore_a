-- LENTERA / bookstore_a
-- Clean schema for local development and InfinityFree import.
-- Contains no user accounts, passwords, private messages, or order history.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `detail_pesanan`;
DROP TABLE IF EXISTS `keranjang`;
DROP TABLE IF EXISTS `pesan`;
DROP TABLE IF EXISTS `pesanan`;
DROP TABLE IF EXISTS `buku`;
DROP TABLE IF EXISTS `kategori`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) NOT NULL,
  `judul_buku` varchar(150) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL DEFAULT 0,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  PRIMARY KEY (`id_buku`),
  KEY `idx_buku_kategori` (`id_kategori`),
  CONSTRAINT `fk_buku_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `total` int(11) NOT NULL DEFAULT 0,
  `metode_pembayaran` varchar(50) NOT NULL,
  `status_pembayaran` varchar(50) NOT NULL DEFAULT 'Belum Bayar',
  `status` varchar(50) NOT NULL DEFAULT 'Menunggu Pembayaran',
  PRIMARY KEY (`id_pesanan`),
  KEY `idx_pesanan_user` (`id_user`),
  CONSTRAINT `fk_pesanan_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_pesanan` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `harga` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `idx_detail_pesanan` (`id_pesanan`),
  KEY `idx_detail_buku` (`id_buku`),
  CONSTRAINT `fk_detail_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `keranjang` (
  `id_cart` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_cart`),
  KEY `idx_keranjang_user` (`id_user`),
  KEY `idx_keranjang_buku` (`id_buku`),
  CONSTRAINT `fk_keranjang_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_keranjang_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pesan` (
  `id_pesan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `judul_pesan` varchar(100) NOT NULL,
  `isi_pesan` text NOT NULL,
  `balasan_admin` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Baru',
  PRIMARY KEY (`id_pesan`),
  KEY `idx_pesan_user` (`id_user`),
  CONSTRAINT `fk_pesan_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Novel'),
(2, 'Komik'),
(3, 'Teknologi'),
(4, 'Horor'),
(5, 'Sejarah');

INSERT INTO `buku` (`id_buku`, `id_kategori`, `judul_buku`, `penulis`, `harga`, `stok`, `gambar`, `deskripsi`, `penerbit`, `tahun`) VALUES
(1, 3, 'Pemrograman Web dengan PHP dan MySQL', 'Budi Raharjo', 50000, 14, 'cover buku pemrograman.jpg', 'Buku pengantar pemrograman web menggunakan PHP dan MySQL.', 'PT Yudhistira Ghalia', 2018),
(2, 2, 'One Piece Vol. 1', 'Eiichiro Oda', 45000, 5, 'cover buku one piece.jpg', 'Komik petualangan bajak laut karya Eiichiro Oda.', 'Elex Media Komputindo', 1995),
(3, 1, 'Dilan: Dia Adalah Dilanku Tahun 1990', 'Pidi Baiq', 65000, 22, 'cover buku dilan.jpg', 'Novel remaja karya Pidi Baiq.', 'Pastel Books / Mizan', 2014);

SET FOREIGN_KEY_CHECKS = 1;
