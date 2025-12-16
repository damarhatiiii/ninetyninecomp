-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Des 2025 pada 14.40
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_komputer`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aktifitas`
--

CREATE TABLE `aktifitas` (
  `id_aktifitas` int(11) NOT NULL,
  `id_karyawan` varchar(255) NOT NULL,
  `jenis_aktifitas` enum('barang_masuk','transaksi') NOT NULL,
  `keterangan` text NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aktifitas`
--

INSERT INTO `aktifitas` (`id_aktifitas`, `id_karyawan`, `jenis_aktifitas`, `keterangan`, `tanggal`) VALUES
(1, '', 'transaksi', 'Melakukan transaksi penjualan dengan total Rp 1.685.000', '2025-11-16 09:45:54'),
(2, 'KRY001', 'transaksi', 'Melakukan transaksi penjualan dengan total Rp 1.685.000', '2025-11-24 03:28:48'),
(3, 'KRY001', 'transaksi', 'Melakukan transaksi penjualan dengan total Rp 2.002.000', '2025-11-24 03:33:01'),
(4, 'KRY001', 'transaksi', 'Melakukan transaksi penjualan dengan total Rp 1.300.000', '2025-11-24 03:34:07'),
(5, 'KRY001', 'barang_masuk', 'Menerima barang masuk: ACER PREDATOR VESTA RGB SILVER DDR4 16GB (2X8GB) sebanyak 4 unit', '2025-11-24 09:42:17'),
(7, 'KRY001', 'transaksi', 'Melakukan transaksi penjualan dengan total Rp 2.002.000', '2025-11-24 03:55:12'),
(8, 'KRY001', 'barang_masuk', 'Menerima barang masuk: ACER PREDATOR VESTA RGB SILVER DDR4 16GB (2X8GB) sebanyak 6 unit', '2025-11-25 01:43:07'),
(10, 'KRY001', 'transaksi', 'Melakukan transaksi penjualan dengan total Rp 785.000', '2025-11-28 21:45:46'),
(11, 'KRY001', 'barang_masuk', 'Menerima barang masuk: ADATA DDR4 XPG GAMMIX D35 WHITE 16GB (2X8GB) sebanyak 4 unit', '2025-11-29 03:48:55'),
(12, 'KRY001', 'transaksi', 'Melakukan transaksi penjualan dengan total Rp 900.000', '2025-12-04 20:36:25'),
(13, 'KRY001', 'barang_masuk', 'Menerima barang masuk: 1STPLAYER Gaming PSU DK60 600W 80+ Bronze sebanyak 6 unit', '2025-12-05 02:44:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id_masuk` varchar(20) NOT NULL,
  `id_produk` varchar(20) NOT NULL,
  `id_supplier` varchar(20) NOT NULL,
  `jumlah_masuk` int(20) NOT NULL,
  `tanggal` date NOT NULL,
  `id_karyawan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang_masuk`
--

INSERT INTO `barang_masuk` (`id_masuk`, `id_produk`, `id_supplier`, `jumlah_masuk`, `tanggal`, `id_karyawan`) VALUES
('BM001', 'RAM005', 'SUP003', 4, '2025-11-24', 'KRY001'),
('BM002', 'RAM005', 'SUP002', 6, '2025-11-24', 'KRY001'),
('BM003', 'RAM002', 'SUP002', 4, '2025-11-28', 'KRY001'),
('BM004', 'PSU002', 'SUP003', 6, '2025-12-04', 'KRY001');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
--

CREATE TABLE `customer` (
  `id_customer` varchar(20) NOT NULL,
  `nama` text NOT NULL,
  `nomor_telepon` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id_customer`, `nama`, `nomor_telepon`) VALUES
('CUS001', 'Deni Setiawan', '0'),
('CUS002', 'Yogi Aprilianto', '0'),
('CUS003', 'Achmad Nazzri', '0');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` varchar(20) NOT NULL,
  `id_transaksi` varchar(20) NOT NULL,
  `id_produk` varchar(20) NOT NULL,
  `jumlah` int(100) NOT NULL,
  `subtotal` bigint(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_produk`, `jumlah`, `subtotal`) VALUES
('DTL001', 'TRX001', 'PSU002', 1, 785000),
('DTL002', 'TRX001', 'RAM005', 1, 900000),
('DTL003', 'TRX002', 'PSU002', 1, 785000),
('DTL004', 'TRX002', 'RAM005', 1, 900000),
('DTL005', 'TRX003', 'PSU002', 1, 785000),
('DTL006', 'TRX003', 'RAM005', 1, 900000),
('DTL007', 'TRX004', 'RAM002', 1, 1300000),
('DTL008', 'TRX004', 'FAN009', 1, 702000),
('DTL009', 'TRX005', 'RAM002', 1, 1300000),
('DTL010', 'TRX006', 'RAM002', 1, 1300000),
('DTL011', 'TRX006', 'FAN009', 1, 702000),
('DTL012', 'TRX007', 'PSU002', 1, 785000),
('DTL013', 'TRX008', 'RAM005', 1, 900000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `tanggal_dibuat` datetime DEFAULT current_timestamp(),
  `role` enum('admin','staf') NOT NULL DEFAULT 'staf',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `foto_profil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama`, `username`, `email`, `password`, `tanggal_dibuat`, `role`, `status`, `foto_profil`) VALUES
('KRY001', 'Muhamad Damar Hati', 'damar', 'damarhati123@gmail.com', 'damarhati123', '2025-11-29 03:55:20', 'admin', 'aktif', 'KRY001_1764363809.jpg'),
('KRY002', 'Ananta Bagas Sasena', 'remon', NULL, '$2y$10$24HPranGAzz5RZ3/3K3kjuzUfNBIURQwsKJmvEM37jwS1HU9KMzkS', '2025-11-29 03:55:20', 'admin', 'aktif', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` varchar(10) NOT NULL,
  `nama_kategori` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
('CAS007', 'CASE'),
('CPU001', 'CPU'),
('FAN008', 'FAN'),
('GPU003', 'GPU'),
('MTB002', 'MOTHERBOARD'),
('PRP009', 'PERIPHERAL'),
('PSU006', 'PSU'),
('RAM004', 'RAM'),
('STR005', 'STORAGE');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` varchar(20) NOT NULL,
  `nama_produk` text NOT NULL,
  `id_kategori` varchar(20) NOT NULL,
  `merk` text NOT NULL,
  `spesifikasi` varchar(100) NOT NULL,
  `stok` int(11) NOT NULL,
  `harga` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `id_kategori`, `merk`, `spesifikasi`, `stok`, `harga`) VALUES
('CAS001', 'Aigo DarkFlash A290 White Include 3 Fan ARGB', '7', 'Aigo', '320 x 190 x 433mm, ATX / M-ATX/ ITX', 6, 384000),
('CAS002', 'Aigo DarkFlash A290 Black Include 3 Fan ARGB', '7', 'Aigo', '320 x 190 x 433mm, ATX / M-ATX/ ITX', 2, 384000),
('CAS003', 'Aigo DarkFlash ARC1 M-ATX Curve Case - White', '7', 'Aigo', '420 x 265 x 410mm, ATX / M-ATX/ ITX', 3, 645000),
('CAS004', 'Aigo DarkFlash ARC1 M-ATX Curve Case - Black', '7', 'Aigo', '420 x 265 x 410mm, ATX / M-ATX/ ITX', 3, 645000),
('CAS005', 'Digital Alliance Gaming Case N30S BTF White ', '7', 'Digital Alliance', '420 x 285 x 350mm, M-ATX/ ITX', 2, 405000),
('CAS006', 'Digital Alliance Gaming Case N30S BTF Black', '7', 'Digital Alliance', '420 x 285 x 350mm, M-ATX/ ITX', 2, 405000),
('CAS007', 'Digital Alliance Gaming Case AMP Black', '7', 'Digital Alliance', '440 x 205 x 352 mm,M-ATX/ ITX, LED DISPLAY', 3, 1275000),
('CAS008', 'Digital Alliance Gaming Case AMP White', '7', 'Digital Alliance', '440 x 205 x 352 mm,M-ATX/ ITX, LED DISPLAY', 3, 1275000),
('CAS009', 'NZXT H6 Flow RGB Matte White', '7', 'NZXT', '435 x 287 x 415mm, ATS/ M-ATX / ITX', 2, 2215000),
('CPU001', 'Intel Core i5-12400F', '1', 'Intel', '6-Core 12-Thread, 25GHz, LGA1700', 20, 2650000),
('CPU002', 'AMD Ryzen 5 5600', '1', 'AMD', '6-Core 12-Thread, 35GHz, AM4', 15, 2350000),
('CPU003', 'Intel Core i7-13700K', '1', 'Intel', '16-Core 24-Thread, 34GHz, LGA1700', 10, 5950000),
('CPU004', 'AMD Ryzen 5 8500G', '1', 'AMD', '6-Core 12-Thread, 35GHz, AM5', 4, 3400000),
('CPU005', 'AMD Ryzen 7 5700G', '1', 'AMD', '8-Core 16-Thread, 46GHz, AM4', 5, 2725000),
('CPU006', 'AMD Ryzen 9 7950X', '1', 'AMD', '16-Core, 32-Thread, 57GHz, AM5', 5, 8300000),
('CPU007', 'Intel Core i9-12900K', '1', 'Intel', '16-Core, 24-Thread, 32GHz, LGA1700', 6, 4100000),
('CPU008', 'Intel Core i7-14700K', '1', 'Intel', '20-Core, 28-Thread, 34GHz, LGA 1700', 2, 5800000),
('FAN001', 'Aerocool Phantom M-3 ARGB Black 12CM PWM Fan', '8', 'AeroCool', '20x120x25 mm, 12v CASE, ARGB', 10, 120000),
('FAN002', 'Aerocool Saturn 12F ARGB 3 Pcs + HUB + Remote', '8', 'AeroCool', '120x120x25mm, 12v CASE, ARGB', 12, 345000),
('FAN003', 'Aigo DarkFlash APC1 PWM Controller', '8', 'Aigo', 'PWM CONTROLLER/ARGB REMOTE', 12, 120000),
('FAN004', 'Aigo DarkFlash DM12 PRO ARGB 12CM PWM', '8', 'Aigo', '120x120x25mm, 12v CASE, ARGB', 8, 396000),
('FAN005', 'DeepCool FL12R 3-IN-1 12CM Unique Addressable RGB Fan', '8', 'DeepCool', '120×120×25mm, 12v CASE, ARGB', 3, 800000),
('FAN006', 'NZXT F120 RGB Core Triple Pack - Matte White', '8', 'NZXT', '120×120×26mm, 12v CASE, ARGB', 8, 995000),
('FAN007', 'NZXT F120 RGB Duo Triple Pack - Matte Black', '8', 'NZXT', '120×120×26mm, 12v CASE, ARGB', 7, 1301000),
('FAN008', 'Aerocool Mirage L360 360MM ARGB Liquid Cooler', '8', 'AeroCool', '394x120x27mm, 12v CPU, ARGB', 3, 1385000),
('FAN009', 'Aigo DarkFlash AquaGlow DG240 Black', '8', 'Aigo', '277x120x27mm, 12v CPU, ARGB', 0, 702000),
('FAN010', 'Aigo DarkFlash DX120 Twister V26 RGB - Black Edition', '8', 'Aigo', ' 75x75x53mm, 12v CPU, ARGB', 5, 526000),
('FAN011', 'Aigo DarkFlash Nebula DN360D ARGB - Black Edition', '8', 'Aigo', '397x120x27mm, 12v CPU, ARGB', 1, 1061000),
('FAN012', 'Asus ROG Ryujin III 360 ARGB Extreme Liquid Cooler', '8', 'ASUS', ' 3995x120x30 mm, 12v CPU, ARGB', 1, 6040000),
('FAN013', 'Asus ROG STRIX LC III 360 ARGB LCD', '8', 'ASUS', '394×121×27 mm, 12v CPU, ARGB', 1, 3550000),
('GPU001', 'Colorful iGame RTX 3060Ti Ultra OC', '3', 'iGame', '8GB GDDR6X, 256-bit, PCIe 40', 3, 6800000),
('GPU002', 'XFX Radeon RX 6600XT', '3', 'XFX', '8GB GDDR6, 128-bit, PCI-e 40', 4, 5300000),
('GPU003', 'Asus GeForce RTX 4060Ti ', '3', 'ASUS', '8GB GDDR6, 128-bit, PCIe 40', 6, 7550000),
('GPU004', 'Gigabyte RTX 4060Ti WINDFORCE OC', '3', 'GIGABYTE', '8GB GDDR6, 128-bit, PCI-e 40', 2, 7000000),
('GPU005', 'ASRock RX 7600 PHANTOM GAMING OC', '3', 'ASRock', '8GB GDDR6, 128-bit, PCI-e 40', 3, 4930000),
('GPU006', 'POWERCOLOR RX 7700XT HELLHOUND', '3', 'POWERCOLOR', '12GB GDDR6, 192-bit, PCI-e 40', 2, 7410000),
('GPU007', 'GIGABYTE GEFORCE RTX 5060Ti AERO OC', '3', 'GIGABYTE', '16GB GDDR7, 128-bit, PCI-e 50', 4, 9550000),
('MBO001', 'MSI B550M PRO-VDH MORTAR WIFI AM4', '2', 'MSI', '4x DDR4, 2x M2, USB32, SATA, WiFi, AM4', 3, 1600000),
('MBO002', 'ASRock B550M Steel Legend AM4', '2', 'ASRock', '4x DDR4, 2x M2, USB32, SATA, AM4', 6, 2200000),
('MBO003', 'GIGABYTE B450 AORUS Elite V2 AM4', '2', 'GIGABYTE', '4x DDR4, 2x M2, USB32, SATA, AM4', 4, 1650000),
('MBO004', 'ASUS B650M-AYW WIFI AM5', '2', 'ASUS', '2x DDR5, 2x M2, USB 32, 4x SATA, WiFi, AM5', 3, 2130000),
('MBO005', 'ASROCK B760M PRO RS WIFI WHITE LGA1700', '2', 'ASRock', '4x DDR5, 2x M2, USB 32, 4x SATA, WiFi, LGA1700', 2, 2580000),
('MBO006', 'ASROCK PHANTOM GAMING Z790 NOVA WIFI LGA 1700', '2', 'ASRock', '4x DDR5, 4x M2, USB 32, 4x SATA, WiFi, LGA1700', 3, 5630000),
('PRP001', 'Royal Kludge RK R75', '9', 'Royal Kludge', 'K Silver Pro Switch, 80 Tombol + Knob 75%', 2, 648999),
('PRP002', 'Royal Kludge RK N80', '9', 'Royal Kludge', 'RK Red Switch, 80 + 1 Knob & Smart Screen', 3, 1160000),
('PRP003', 'AULA F75', '9', 'AULA', 'Reaper Switch + 81 Tombol, Gasket mount', 4, 739000),
('PRP004', 'AULA WIN60/WIN68', '9', 'AULA', 'Magnetic Switch, Gasket mount', 6, 779000),
('PRP005', 'Noir Timeless82 V+B552 Classic Edition 75%', '9', 'Noir', '81 Tombol, Gasket mount, LCD Display Screen', 4, 864000),
('PSU001', '1STPLAYER Gaming PSU DK50 500W  80+ Bronze', '6', '1STPLAYER', '80+ Bronze, FULL MODULAR, 500-600W, ATX', 3, 685000),
('PSU002', '1STPLAYER Gaming PSU DK60 600W 80+ Bronze', '6', '1STPLAYER', '80+ Bronze, FULL MODULAR, 500-600W, ATX', 7, 785000),
('PSU003', 'ADATA XPG PSU Core Reactor II 850w 80+ Gold', '6', 'ADATA', '80+ Gold, FULL MODULAR, 850W, ATX', 5, 1605000),
('PSU004', 'ADATA XPG PSU Core Reactor II 750w 80+ Gold', '6', 'ADATA', '80+ Gold, FULL MODULAR, 750W, ATX', 4, 1450000),
('PSU005', 'ASRock Challenger CL-750G 750W 80+ Gold', '6', 'ASRock', '80+ Gold, NON MODULAR, 750W, ATX', 5, 1285000),
('PSU006', 'Asus Prime 750W White - 80+ Gold', '6', 'ASUS', '80+ Gold, FULL MODULAR, 750W, ATX', 6, 1835000),
('PSU007', 'Asus Prime 850W White - 80+ Gold', '6', 'ASUS', '80+ Gold, FULL MODULAR, 850W, ATX', 4, 2270000),
('PSU008', 'Asus ROG STRIX 1000G AURA GAMING - 80+ Gold', '6', 'ASUS', '80+ Gold, FULL MODULAR, 1000W, ATX', 2, 2875000),
('PSU009', 'Asus TUF Gaming 1000W White Edition - 80+ Gold', '6', 'ASUS', '80+ Gold, FULL MODULAR, 1000W, ATX', 3, 3150000),
('PSU010', 'be quiet! PURE POWER 12 M 550W - 80+ Gold', '6', 'be quiet!', '80+ Gold, FULL MODULAR, 550W, ATX', 7, 1275000),
('PSU011', 'be quiet! PURE POWER 12 M 650W - 80+ Gold', '6', 'be quiet!', '80+ Gold, FULL MODULAR, 650W, ATX', 6, 1685000),
('PSU012', 'be quiet! PURE POWER 12 M 750W - 80+ Gold', '6', 'be quiet!', '80+ Gold, FULL MODULAR, 750W, ATX', 8, 1195000),
('PSU013', 'be quiet! PURE POWER 12 M 850W - 80+ Gold', '6', 'be quiet!', '80+ Gold, FULL MODULAR, 850W, ATX', 5, 2150000),
('RAM001', 'ADATA DDR4 XPG SPECTRIX D35G WHITE 32GB (2X16GB)', '4', 'ADATA', 'DDR4, 3200-3600MHz, RGB', 3, 1570000),
('RAM002', 'ADATA DDR4 XPG GAMMIX D35 WHITE 16GB (2X8GB)', '4', 'ADATA', 'DDR4, 3200-3600MHz', 4, 1300000),
('RAM003', 'COLORFUL BATTLE AX DDR4 16GB', '4', 'COLORFUL', 'DDR4, 3200MHz', 4, 600000),
('RAM004', 'LEXAR DDR4 THOR 16GB (2X8GB)', '4', 'LEXAR', 'DDR4, 3200MHz', 6, 1100000),
('RAM005', 'ACER PREDATOR VESTA RGB SILVER DDR4 16GB (2X8GB)', '4', 'ACER', 'DDR4, 3200-3600-4000MHz, RGB', 9, 900000),
('RAM006', 'GSKILL DDR5 TRIDENT Z5 RGB 32GB (2X16GB)', '4', 'GSKILL', 'DDR5, 5600MHz, RGB', 2, 1540000),
('RAM007', 'Apacer DDR5 NOX 16GB (2x8GB)', '4', 'Apacer', 'DDR5, 5600MHz', 4, 1200000),
('RAM008', 'Crucial Pro OC Black DDR5 32GB (2x16GB)', '4', 'Crucial', 'DDR5, 5600-6000MHz', 2, 2010000),
('RAM009', 'GSKILL DDR5 FLARE X5 32GB (2X16GB)', '4', 'GSKILL', 'DDR5, 5600MHz', 5, 2000000),
('RAM010', 'TEAM T-CREATE EXPERT DDR5 32GB (2X16GB)', '4', 'T-CREATE', 'DDR5, 6000MHz', 3, 1500000),
('RAM011', 'T-FORCE XTREEM PINK DDR5 32GB (2X16GB)', '4', 'T-FORCE', 'DDR5, 7600MHz', 4, 2300000),
('STR001', 'KLEVV SSD CRAS C715 512GB M2 NVMe', '5', 'KLEVV', '512GB, R3200MB/S, NVMe PCIe Gen3x4', 6, 705000),
('STR002', 'Paradox Gaming Ballista Advance SSD 512GB M2 NVMe', '5', 'PARADOX', '512GB, 2800MB/S, NVMe', 5, 525000),
('STR003', 'Samsung SSD 980 M2 500GB M2 NVMe', '5', 'Samsung', '500GB, R5000MB/S, NVMe PCIe Gen3 x4', 4, 795000),
('STR004', 'LEXAR SSD NM610 1TB M2 NVMEe', '5', 'LEXAR', '1TB, 3300MB/S, NVMe PCIe Gen3 x4', 6, 922000),
('STR005', 'Samsung SSD 980 1TB M2 NVMe', '5', 'Samsung', '1TB, 7450MB/S, NVMe PCIe Gen3 x4', 2, 1310000),
('STR006', 'KLEVV SSD CRAS C720 1TB M2 NVMe', '5', 'KLEVV', '1TB, 3400MB/S, NVMe PCIe Gen3 x4', 3, 1800000),
('STR007', 'Samsung SSD 990 EVO PLUS M2 PCIe', '5', 'Samsung', '2TB, 7250MB/S, NVMe PCIe Gen3 x4', 2, 2310000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` varchar(20) NOT NULL,
  `nama` text NOT NULL,
  `alamat` text NOT NULL,
  `nomor_telepon` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama`, `alamat`, `nomor_telepon`) VALUES
('SUP001', 'PT Sumber Teknologi', 'Jl. Merpati No. 12, Jakarta', '02188991234'),
('SUP002', 'CV Mandiri Komponen', 'Jl. Cemara Raya No. 55, Bandung', '02276543210'),
('SUP003', 'PT Digital Nusantara', 'Jl. Diponegoro No. 44, Surabaya', '03199112345'),
('SUP004', 'UD Maju Bersama', 'Jl. Gajah Mada No. 27, Yogyakarta', '0274556677'),
('SUP005', 'PT Prima Hardware', 'Jl. Sudirman No. 88, Semarang', '02481726345');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `total` int(20) NOT NULL,
  `id_customer` varchar(255) DEFAULT NULL,
  `nama_pembeli` varchar(20) DEFAULT NULL,
  `id_karyawan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal`, `total`, `id_customer`, `nama_pembeli`, `id_karyawan`) VALUES
('TRX001', '2025-11-16', 1685000, NULL, NULL, ''),
('TRX002', '2025-11-16', 1685000, NULL, NULL, ''),
('TRX003', '2025-11-24', 1685000, NULL, 'DENI', 'KRY001'),
('TRX004', '2025-11-24', 2002000, 'CUS001', 'Deni Setiawan', 'KRY001'),
('TRX005', '2025-11-24', 1300000, NULL, 'mansurrr', 'KRY001'),
('TRX006', '2025-11-24', 2002000, 'CUS001', 'Deni Setiawan', 'KRY001'),
('TRX007', '2025-11-28', 785000, 'CUS001', 'Deni Setiawan', 'KRY001'),
('TRX008', '2025-12-04', 900000, 'CUS001', 'Deni Setiawan', 'KRY001');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `aktifitas`
--
ALTER TABLE `aktifitas`
  ADD PRIMARY KEY (`id_aktifitas`),
  ADD KEY `idx_id_karyawan` (`id_karyawan`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_jenis_aktifitas` (`jenis_aktifitas`);

--
-- Indeks untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id_masuk`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_karyawan` (`id_karyawan`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_karyawan` (`id_karyawan`),
  ADD KEY `transaksi_ibfk_1` (`id_customer`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aktifitas`
--
ALTER TABLE `aktifitas`
  MODIFY `id_aktifitas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
