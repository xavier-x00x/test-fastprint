/*
Navicat MySQL Data Transfer

Source Server         : MySqlLocal
Source Server Version : 80012
Source Host           : localhost:3306
Source Database       : test_fastprint

Target Server Type    : MYSQL
Target Server Version : 80012
File Encoding         : 65001

Date: 2023-06-13 18:19:28
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `produk`
-- ----------------------------
DROP TABLE IF EXISTS `produk`;
CREATE TABLE `produk` (
  `id_produk` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) NOT NULL DEFAULT '',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kategori` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of produk
-- ----------------------------
INSERT INTO produk VALUES ('6', 'ALCOHOL GEL POLISH CLEANSER GP-CLN01', '12500.00', 'L QUEENLY', 'bisa dijual');
INSERT INTO produk VALUES ('9', 'ALUMUNIUM FOIL ALL IN ONE BULAT 23mm IM', '1000.00', 'L MTH AKSESORIS (IM)', 'bisa dijual');
INSERT INTO produk VALUES ('11', 'ALUMUNIUM FOIL ALL IN ONE BULAT 30mm IM', '1000.00', 'L MTH AKSESORIS (IM)', 'bisa dijual');
INSERT INTO produk VALUES ('12', 'ALUMUNIUM FOIL ALL IN ONE SHEET 250mm IM', '12500.00', 'L MTH AKSESORIS (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('15', 'ALUMUNIUM FOIL HDPE/PE BULAT 23mm IM', '12500.00', 'L MTH AKSESORIS (IM)', 'bisa dijual');
INSERT INTO produk VALUES ('17', 'ALUMUNIUM FOIL HDPE/PE BULAT 30mm IM', '1000.00', 'L MTH AKSESORIS (IM)', 'bisa dijual');
INSERT INTO produk VALUES ('18', 'ALUMUNIUM FOIL HDPE/PE SHEET 250mm IM', '13000.00', 'L MTH AKSESORIS (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('19', 'ALUMUNIUM FOIL PET SHEET 250mm IM', '1000.00', 'L MTH AKSESORIS (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('22', 'ARM PENDEK MODEL U', '13000.00', 'L MTH AKSESORIS (IM)', 'bisa dijual');
INSERT INTO produk VALUES ('23', 'ARM SUPPORT KECIL', '13000.00', 'L MTH TABUNG (LK)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('24', 'ARM SUPPORT KOTAK PUTIH', '13000.00', 'L MTH AKSESORIS (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('26', 'ARM SUPPORT PENDEK POLOS', '13000.00', 'L MTH TABUNG (LK)', 'bisa dijual');
INSERT INTO produk VALUES ('27', 'ARM SUPPORT S IM', '1000.00', 'L MTH AKSESORIS (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('28', 'ARM SUPPORT T (IMPORT)', '13000.00', 'L MTH AKSESORIS (IM)', 'bisa dijual');
INSERT INTO produk VALUES ('29', 'ARM SUPPORT T - MODEL 1 ( LOKAL )', '10000.00', 'L MTH TABUNG (LK)', 'bisa dijual');
INSERT INTO produk VALUES ('50', 'BLACK LASER TONER FP-T3 (100gr)', '13000.00', 'L MTH AKSESORIS (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('56', 'BODY PRINTER CANON IP2770', '500.00', 'SP MTH SPAREPART (LK)', 'bisa dijual');
INSERT INTO produk VALUES ('58', 'BODY PRINTER T13X', '15000.00', 'SP MTH SPAREPART (LK)', 'bisa dijual');
INSERT INTO produk VALUES ('59', 'BOTOL 1000ML BLUE KHUSUS UNTUK EPSON R1800/R800 - 4180 IM (T054920)', '10000.00', 'CI MTH TINTA LAIN (IM)', 'bisa dijual');
INSERT INTO produk VALUES ('60', 'BOTOL 1000ML CYAN KHUSUS UNTUK EPSON R1800/R800/R1900/R2000 - 4120 IM (T054220)', '10000.00', 'CI MTH TINTA LAIN (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('62', 'BOTOL 1000ML L.LIGHT BLACK KHUSUS UNTUK EPSON 2400 - 0599 IM', '1500.00', 'CI MTH TINTA LAIN (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('63', 'BOTOL 1000ML LIGHT BLACK KHUSUS UNTUK EPSON 2400 - 0597 IM', '1500.00', 'CI MTH TINTA LAIN (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('65', 'BOTOL 1000ML MATTE BLACK KHUSUS UNTUK EPSON R1800/R800/R1900/R2000 - 3503 IM (T054820)', '1500.00', 'CI MTH TINTA LAIN (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('67', 'BOTOL 1000ML RED KHUSUS UNTUK EPSON R1800/R800/R1900/R2000 - 4170 IM (T054720)', '1000.00', 'CI MTH TINTA LAIN (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('68', 'BOTOL 1000ML YELLOW KHUSUS UNTUK EPSON R1800/R800/R1900/R2000 - 4160 IM (T054420)', '1500.00', 'CI MTH TINTA LAIN (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('72', 'BOTOL 10ML IM', '1000.00', 'S MTH STEMPEL (IM)', 'tidak bisa dijual');
INSERT INTO produk VALUES ('76', 'AAA1111a', '1000.00', 'QQQQ', 'bisa dijual');
