-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 24, 2022 at 02:01 PM
-- Server version: 10.5.11-MariaDB-1:10.5.11+maria~focal
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_wisata`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_level`
--

CREATE TABLE `tbl_level` (
  `id_level` int(5) NOT NULL,
  `user_level` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_level`
--

INSERT INTO `tbl_level` (`id_level`, `user_level`) VALUES
(1, 'Admin'),
(3, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_news`
--

CREATE TABLE `tbl_news` (
  `id_news` int(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text_content` varchar(4096) NOT NULL,
  `date` date DEFAULT NULL,
  `user` varchar(64) NOT NULL,
  `photo_news` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_news`
--

INSERT INTO `tbl_news` (`id_news`, `title`, `text_content`, `date`, `user`, `photo_news`) VALUES
(2, 'Kolam Renang Wisata Dolli Bungaeja', 'Wisata &#34;Dolli&#34; Bungaeja adalah salah satu desa wisata yang ada di kabupaten Maros Provinsi Sulawesi Selatan, kabupaten maros. Jarak tempuh menuju ke Wisata ‘’Doli’’Bungaeja dari kota maros berjarak kurang lebih 30 menit.\r\n\r\nWisata “Dolli” Bungaeja terdiri dari 2 kolam, yaitu Kolam untuk anak kecil dan kolam untuk orang dewasa. Kola mini dikelilingi oleh hamparan sawah, gunung karst. Selain Wisata Kolam, pengelola juga menawarkan wisata goa, wisata kuliner, wisata pemancingan ikan, wisata kebun dan wisata sawah, dan tempat spot foto.', '2022-05-24', '1', 'news_1653341531.jpg'),
(3, 'Bendungan Pannampu, Wisata Tersembunyi di Desa Tukamasea Maros', 'Bendungan Pannampu yang terletak di Dusun Pajjaiang, Desa Tukamasea, Kecamatan Bantimurung, Kabupaten Maros, Sulawesi Selatan ini bak permata tersembunyi.\r\n\r\nDikelilingi keindahan batuan karst, air yang begitu jernih, dan gua yang tak kalah cantik dengan Gua Mimpi yang ada di Bantimurung.\r\n\r\nBendungan Pannampu juga menjadi cagar budaya yang terletak di sebuah gunung yang dikenal masyarakat dengan nama Bulu Kamase.\r\n\r\nDari kata Bulu Kamase ini pula tercetus nama Desa Tukamasea.', '2022-05-24', '1', 'news_1653342142.jpg'),
(4, 'Tambang Bekas Marmer', 'Bekas tambang marmer di Dusun Pajjaiyang, Desa Tukamasea, Kecamatan Bantimurung, Kabupaten Maros, Sulawesi Selatan. Bekas tambang marmer ini sekarang telah dijadikan destinasi wisata panorama alam dan swafoto.', '2022-05-23', '1', 'news_1653342437.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `id_product` int(5) NOT NULL,
  `category` varchar(128) NOT NULL,
  `product` varchar(128) NOT NULL,
  `sub_product` varchar(128) NOT NULL,
  `description` varchar(4096) NOT NULL,
  `galleries` varchar(4096) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`id_product`, `category`, `product`, `sub_product`, `description`, `galleries`) VALUES
(1, 'Penginapan', 'Homestay', 'Dolli Bungaeja', 'Di tempat wisata alam Desa Tukamasea menyediakan fasilitas penginapan yang terjaga kebersihannya.', 'a:7:{i:0;s:24:\"product_1653367217.0.jpg\";i:1;s:24:\"product_1653367217.1.jpg\";i:2;s:24:\"product_1653367217.2.jpg\";i:3;s:24:\"product_1653367217.3.jpg\";i:4;s:24:\"product_1653367217.4.jpg\";i:5;s:24:\"product_1653367217.5.jpg\";i:6;s:24:\"product_1653367217.6.jpg\";}'),
(2, 'UMKM', 'Produksi Gula Merah', 'Olahan Tangan', 'Manfaat gula merah sebagai pemanis sudah sejak lama dikenal masyarakat. Lebih dari itu, gula merah juga dianggap lebih baik dikonsumsi bagi penderita diabetes daripada gula putih.', 'a:7:{i:0;s:24:\"product_1653367392.0.png\";i:1;s:24:\"product_1653367392.1.jpg\";i:2;s:24:\"product_1653367392.2.jpg\";i:3;s:24:\"product_1653367392.3.png\";i:4;s:24:\"product_1653367392.4.jpg\";i:5;s:24:\"product_1653367392.5.jpg\";i:6;s:24:\"product_1653367392.6.jpg\";}'),
(8, 'Wisata Alam dan Permandian', 'jhg djkg lghjk', 'ihie rotjyerti ', 'oithj oidljhtdi kjhtmdol kjfolj yfyojyj', 'a:3:{i:0;s:24:\"product_1653366877.0.jpg\";i:1;s:24:\"product_1653366877.1.jpg\";i:2;s:24:\"product_1653366877.2.png\";}');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(5) NOT NULL,
  `name` varchar(64) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `level` varchar(64) NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `name`, `username`, `password`, `password_hash`, `level`, `photo`) VALUES
(1, 'Admin Gokil', 'admin', 'admin', '$2y$10$j7tN5/MlPH/abf/Q.co7N.yR5khI3is6YCRj6NMJbHgYn0XwIkkNe', 'Admin', 'admin_pic_1653317390.png'),
(2, 'User', 'user', 'user1234', '$2y$10$iBzbhS0qVV68WYrPfWoCW.Jz7YZkuY1r21y8zZ/eJqA.NeF2hCC/S', 'User', 'user_pic_1653312731.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_level`
--
ALTER TABLE `tbl_level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `tbl_news`
--
ALTER TABLE `tbl_news`
  ADD PRIMARY KEY (`id_news`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`id_product`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_level`
--
ALTER TABLE `tbl_level`
  MODIFY `id_level` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_news`
--
ALTER TABLE `tbl_news`
  MODIFY `id_news` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `id_product` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
