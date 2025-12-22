-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 21, 2025 at 12:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desaweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrasi_penduduks`
--

CREATE TABLE `administrasi_penduduks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `nama` varchar(255) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `administrasi_penduduks`
--

INSERT INTO `administrasi_penduduks` (`id`, `kategori`, `jumlah`, `nama`, `nik`, `alamat`, `jenis_kelamin`, `created_at`, `updated_at`) VALUES
(1, 'Jumlah Penduduk', 10000, NULL, NULL, NULL, NULL, '2025-12-19 01:39:22', '2025-12-19 01:39:49'),
(2, 'Laki-laki', 20000, NULL, NULL, NULL, NULL, '2025-12-19 01:39:33', '2025-12-19 01:39:33'),
(3, 'Perempuan', 3000, NULL, NULL, NULL, NULL, '2025-12-19 01:39:38', '2025-12-19 01:39:38'),
(4, 'Kepala Keluarga', 9000, NULL, NULL, NULL, NULL, '2025-12-19 01:39:43', '2025-12-19 01:39:43');

-- --------------------------------------------------------

--
-- Table structure for table `appras`
--

CREATE TABLE `appras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_anak` varchar(255) NOT NULL,
  `umur` int(11) NOT NULL,
  `nama_ortu` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appras`
--

INSERT INTO `appras` (`id`, `nama_anak`, `umur`, `nama_ortu`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'muhamad cindy', 10, 'muh. rexaaa abdul tahir', 'desa lawaluuu', '2025-12-19 03:55:56', '2025-12-19 03:55:56');

-- --------------------------------------------------------

--
-- Table structure for table `balitas`
--

CREATE TABLE `balitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_balita` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `umur` varchar(255) NOT NULL,
  `nama_ibu` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `balitas`
--

INSERT INTO `balitas` (`id`, `nama_balita`, `tanggal_lahir`, `jenis_kelamin`, `umur`, `nama_ibu`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'JOKMO', '2025-12-19', 'L', '3', 'nurul azazara', 'lawallu', '2025-12-19 03:55:16', '2025-12-19 03:55:16');

-- --------------------------------------------------------

--
-- Table structure for table `bayis`
--

CREATE TABLE `bayis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_bayi` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `nama_ibu` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bayis`
--

INSERT INTO `bayis` (`id`, `nama_bayi`, `tanggal_lahir`, `jenis_kelamin`, `nama_ibu`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'gaza', '2025-12-19', 'L', 'komang', 'lawallu', '2025-12-19 03:54:42', '2025-12-19 03:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `beritas`
--

CREATE TABLE `beritas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beritas`
--

INSERT INTO `beritas` (`id`, `judul`, `slug`, `konten`, `kategori`, `author`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Kegiatan Gotong Royong di Desa Lawaluu Baru', 'kegiatan-gotong-royong-di-desa-lawaluu-baru', 'Pada tanggal 19 Desember 2025, seluruh warga Desa Lawaluu Baru secara antusias melaksanakan kegiatan gotong royong membersihkan lingkungan desa.  \r\nKegiatan ini meliputi pembersihan jalan, saluran air, taman desa, serta area umum lainnya yang sering digunakan warga.  \r\nKehadiran semua lapisan masyarakat, mulai dari anak-anak, remaja, hingga orang tua, menunjukkan semangat kebersamaan yang tinggi.  \r\nSelain menjaga kebersihan, kegiatan ini juga menjadi momen untuk mempererat tali persaudaraan antarwarga, saling bertukar cerita, dan menumbuhkan rasa memiliki terhadap lingkungan desa.  \r\nDengan partisipasi aktif warga, Desa Lawaluu Baru semakin terlihat rapi, nyaman, dan harmonis, sekaligus menjadi contoh nyata semangat gotong royong yang masih terjaga hingga saat ini.', 'Kegiatan Desa', 'Admin', 'uploads/berita/1766143742_1750296419.webp', '2025-12-19 03:28:34', '2025-12-19 03:29:02'),
(2, 'Kegiatan Gotong Royong Warga Desa Lawaluu Baru', 'kegiatan-gotong-royong-warga-desa-lawaluu-baru', 'Pada tanggal 19 Desember 2025, warga Desa Lawaluu Baru melaksanakan kegiatan gotong royong membersihkan lingkungan desa.  \r\nKegiatan ini meliputi pembersihan jalan, saluran air, dan area umum lainnya.  \r\nSelain menjaga kebersihan, kegiatan ini menjadi momen untuk mempererat kebersamaan warga dan menumbuhkan rasa memiliki terhadap desa.  \r\nSemua lapisan masyarakat, mulai dari anak-anak hingga orang tua, turut aktif berpartisipasi, sehingga suasana kebersamaan terasa sangat kental.  \r\nDengan partisipasi warga, Desa Lawaluu Baru menjadi lingkungan yang bersih, rapi, dan harmonis.', 'Kegiatan Desa', 'Admin Desa', 'uploads/berita/1766144264_1749777830.webp', '2025-12-19 03:37:44', '2025-12-19 03:37:44'),
(4, 'demo mahasiswa', 'demo-mahasiswa', 'mahsiswamahsiswamahsiswamahsiswamahsiswamahsiswamahsiswamahsiswamahsiswa', 'mahasisaw', 'mahsiswa', 'uploads/berita/1766236832_1749777830.webp', '2025-12-20 05:20:32', '2025-12-20 05:20:32');

-- --------------------------------------------------------

--
-- Table structure for table `bpds`
--

CREATE TABLE `bpds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bpds`
--

INSERT INTO `bpds` (`id`, `nama`, `jabatan`, `foto`, `created_at`, `updated_at`) VALUES
(2, NULL, NULL, 'uploads/struktur/bpd/1766303308_SOTK_LPMD.drawio.png', '2025-12-20 23:47:59', '2025-12-20 23:48:28');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foto_wargas`
--

CREATE TABLE `foto_wargas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foto_wargas`
--

INSERT INTO `foto_wargas` (`id`, `gambar`, `created_at`, `updated_at`) VALUES
(3, 'uploads/foto_warga/1766311097_batuputebersama.webp', '2025-12-21 01:55:10', '2025-12-21 01:58:17');

-- --------------------------------------------------------

--
-- Table structure for table `galeris`
--

CREATE TABLE `galeris` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `desc` text DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galeris`
--

INSERT INTO `galeris` (`id`, `judul`, `desc`, `lokasi`, `kategori`, `file`, `tanggal`, `created_at`, `updated_at`) VALUES
(3, 'Sosialiasi  lawalu', 'Sosialisasi Desa adalah kegiatan menyebarkan informasi atau program kepada warga desa agar mereka memahami, ikut serta, dan mendukung pelaksanaan kegiatan pembangunan atau kebijakan di desa. Tujuannya meningkatkan partisipasi masyarakat dan keterlibatan dalam pembangunan desa.', 'Desa Lawaluu Baru, Kecamatan Barru, Kabupaten Barru', 'sosialiasi', '1766158582_sosialiasi-desa.webp', '2025-12-17 23:36:00', '2025-12-19 07:36:22', '2025-12-19 07:59:15'),
(4, 'Kunjungan Pak prabowo', 'The file field must not be greater than 2048 kilobytes.', 'Desa Lawaluu Baru, Kecamatan Barru, Kabupaten Barru', 'Infrastruktur', '1766160405_kunjungan-pak-prabowo.webp', '2025-12-20 00:06:00', '2025-12-19 08:06:45', '2025-12-19 08:06:45'),
(7, 'Pemandangan Sawah Desa Lawaluu Baruu\"', 'Hamparan sawah yang hijau membentang di desa Lawaluu Baruu, menampilkan keindahan alam desa.\"', 'Desa Lawaluu Baru, Kecamatan Barru, Kabupaten Barru', 'Alam / Pemandangan / Wisata Desa', '1766288997_pemandangan-sawah-desa-lawaluu-baruu.webp', '2025-12-21 11:49:00', '2025-12-20 19:49:57', '2025-12-20 19:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `ibu_hamil`
--

CREATE TABLE `ibu_hamil` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_ibu` varchar(255) NOT NULL,
  `umur_kehamilan` int(11) NOT NULL,
  `nama_suami` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ibu_hamil`
--

INSERT INTO `ibu_hamil` (`id`, `nama_ibu`, `umur_kehamilan`, `nama_suami`, `alamat`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 'Siti Aminah', 28, 'yayatt', 'desa lawaallu', '081347019334', '2025-12-19 03:53:47', '2025-12-19 03:53:52');

-- --------------------------------------------------------

--
-- Table structure for table `ibu_menyusuis`
--

CREATE TABLE `ibu_menyusuis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `umur` int(11) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `dusun` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ibu_menyusuis`
--

INSERT INTO `ibu_menyusuis` (`id`, `nama`, `umur`, `alamat`, `dusun`, `created_at`, `updated_at`) VALUES
(1, 'mukartasir', 30, 'desa lawallu', 'barru', '2025-12-19 03:54:25', '2025-12-19 03:54:25');

-- --------------------------------------------------------

--
-- Table structure for table `karang_tarunas`
--

CREATE TABLE `karang_tarunas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `karang_tarunas`
--

INSERT INTO `karang_tarunas` (`id`, `nama`, `jabatan`, `gambar`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, '1766142122_694530aa54587.png', '2025-12-19 03:02:02', '2025-12-19 03:02:02');

-- --------------------------------------------------------

--
-- Table structure for table `kontak_saran`
--

CREATE TABLE `kontak_saran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kontak_saran`
--

INSERT INTO `kontak_saran` (`id`, `nama`, `email`, `pesan`, `created_at`, `updated_at`) VALUES
(1, 'garaa', 'admin@gmail.com', 'aDSA', '2025-12-19 07:10:01', '2025-12-19 07:10:01'),
(2, 'ahmad', 'ahmad@gmail.com', 'web nyh bagus cuman kurang penjelasan lagi', '2025-12-20 04:59:58', '2025-12-20 04:59:58');

-- --------------------------------------------------------

--
-- Table structure for table `lansia`
--

CREATE TABLE `lansia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `umur` int(11) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lansia`
--

INSERT INTO `lansia` (`id`, `nama`, `umur`, `alamat`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 'Suyanto Widodo', 65, 'Jl. Melati No. 3, Desa Sukamaju', '0812-3456-7890', '2025-12-19 04:02:22', '2025-12-19 04:02:22'),
(2, 'Maimunah', 68, 'Jl. Kenanga No. 8, Desa Harapan', '0813-9876-5432', '2025-12-19 04:02:43', '2025-12-19 04:02:43');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_kegiatan`
--

CREATE TABLE `laporan_kegiatan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `anggaran` bigint(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_kegiatan`
--

INSERT INTO `laporan_kegiatan` (`id`, `judul`, `lokasi`, `anggaran`, `foto`, `file_path`, `deskripsi`, `tanggal`, `file`, `created_at`, `updated_at`) VALUES
(3, 'Kegiatan Gotong Royong Pembersihan Desa', 'Desa Lawaluu Baru, Kecamatan Barru, Kabupaten Barru', 19000000, '1766290684_1750298813.webp', '1766290684_1759381447_07.REALISASI_POKOK.SESUAI_PAJAK.pdf', 'kegiatan gotong royong dilaksanakan di Desa Lawaluu Baruu pada tanggal 21 Desember 2025. Warga desa bersama perangkat desa membersihkan jalan, saluran air, dan area umum untuk meningkatkan kebersihan dan kenyamanan lingkungan. Kegiatan ini juga bertujuan mempererat kerja sama antarwarga dan menumbuhkan rasa kepedulian terhadap lingkungan desa.', '2025-12-21', NULL, '2025-12-20 20:09:39', '2025-12-20 20:26:42');

-- --------------------------------------------------------

--
-- Table structure for table `lpms`
--

CREATE TABLE `lpms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lpms`
--

INSERT INTO `lpms` (`id`, `nama`, `jabatan`, `gambar`, `created_at`, `updated_at`) VALUES
(3, NULL, NULL, 'uploads/lpm/1766308514_SOTK LPMD.drawio.png', '2025-12-21 01:15:14', '2025-12-21 01:15:14');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_26_083937_create_transparansi_bumdes_table', 1),
(2, '2025_11_26_084100_create_transparansi_dokumen_table', 1),
(3, '2025_11_26_084123_create_transparansi_laporan_table', 1),
(4, '2025_12_07_152937_create_appras_table', 1),
(5, '2025_12_07_171818_create_ibu_hamil_table', 1),
(6, '2025_12_07_184604_create_ibu_menyusuis_table', 1),
(7, '2025_12_07_190331_create_bayis_table', 1),
(8, '2025_12_07_205401_create_balitas_table', 1),
(9, '2025_12_07_213409_create_puses_table', 1),
(10, '2025_12_07_222134_create_wuses_table', 1),
(11, '2025_12_08_034956_create_pra_lansias_table', 1),
(12, '2025_12_08_035306_create_lansia_table', 1),
(13, '2025_12_08_152018_create_foto_wargas_table', 1),
(14, '2025_12_18_105432_create_umkms_table', 1),
(15, '2025_12_19_045748_create_umkm_produks_table', 1),
(16, '2025_12_19_090759_create_transparansi_anggaran_table', 1),
(17, '2025_12_19_091158_create_users_table', 1),
(18, '2025_12_19_091215_create_profil_desa_table', 1),
(19, '2025_12_19_091230_create_struktur_desa_table', 1),
(20, '2025_12_19_092012_create_sessions_table', 1),
(21, '2025_12_19_092110_create_administrasi_penduduks_table', 1),
(22, '2025_12_19_092742_add_pemasukan_pengeluaran_to_transparansi_anggaran_table', 1),
(23, '2025_12_19_092817_create_galeris_table', 1),
(24, '2025_12_19_092927_create_laporan_kegiatan_table', 1),
(25, '2025_12_19_093055_create_bpds_table', 1),
(26, '2025_12_19_093154_create_pkk_table', 1),
(27, '2025_12_19_093329_create_lpms_table', 1),
(28, '2025_12_19_093406_create_karang_tarunas_table', 1),
(29, '2025_12_19_093435_create_posyandus_table', 1),
(30, '2025_12_19_093500_create_beritas_table', 1),
(31, '2025_12_19_093741_add_kategori_jumlah_to_administrasi_penduduks_table', 1),
(32, '2025_12_19_094000_make_nama_nullable_in_struktur_desa_table', 1),
(33, '2025_12_19_093859_make_nama_nullable_in_administrasi_penduduks_table', 2),
(34, '2025_12_19_100856_create_galeris_table', 3),
(35, '2025_12_19_102226_add_missing_columns_to_laporan_kegiatan_table', 4),
(36, '2025_12_19_112609_add_author_to_beritas_table', 5),
(37, '2025_12_19_112756_rename_gambar_to_image_in_beritas_table', 6),
(38, '2025_12_19_113946_create_pengaduans_table', 7),
(39, '2025_12_19_114047_create_kontak_saran_table', 8),
(40, '2025_12_19_122328_create_cache_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `pengaduans`
--

CREATE TABLE `pengaduans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengaduans`
--

INSERT INTO `pengaduans` (`id`, `nama`, `no_hp`, `kategori`, `pesan`, `foto`, `status`, `created_at`, `updated_at`) VALUES
(1, 'akbal', '081347018611', 'Kependudukan', 'ini bnyk masalah di web nyh ada bnyak. bug cuman suda bagus wkwkwkw', 'uploads/pengaduan/1766144582_1751194574.webp', 'pending', '2025-12-19 03:43:02', '2025-12-19 03:43:02'),
(2, 'user', '081347018611', 'Kependudukan', 'tolongggg', 'uploads/pengaduan/1766158421_8f88eb837c7fd91bca84556dc04c4b50.jpeg', 'pending', '2025-12-19 07:33:41', '2025-12-19 07:33:41'),
(3, 'user', '081347019334', 'Kesehatan', 'web desa kurang stabil', 'uploads/pengaduan/1766236157_1749777830.webp', 'pending', '2025-12-20 05:09:17', '2025-12-20 05:09:17');

-- --------------------------------------------------------

--
-- Table structure for table `pkk`
--

CREATE TABLE `pkk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pkk`
--

INSERT INTO `pkk` (`id`, `nama`, `jabatan`, `gambar`, `created_at`, `updated_at`) VALUES
(5, NULL, NULL, '1766142081_69453081a96bc.png', '2025-12-19 03:01:21', '2025-12-19 03:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `posyandus`
--

CREATE TABLE `posyandus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posyandus`
--

INSERT INTO `posyandus` (`id`, `nama`, `jabatan`, `gambar`, `created_at`, `updated_at`) VALUES
(4, NULL, NULL, '1766310045_POSYANDU MENIRAN DUSUN LAWALLU.drawio.png', '2025-12-21 01:40:45', '2025-12-21 01:40:45');

-- --------------------------------------------------------

--
-- Table structure for table `pra_lansias`
--

CREATE TABLE `pra_lansias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `umur` int(11) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pra_lansias`
--

INSERT INTO `pra_lansias` (`id`, `nama`, `umur`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'Bambang Sutanto', 52, 'Jl. Melati No. 3, Desa Sukamaju', '2025-12-19 04:00:17', '2025-12-19 04:00:17'),
(2, 'Sari Wulandari', 55, 'Jl. Kenanga No. 8, Desa Harapan', '2025-12-19 04:00:30', '2025-12-19 04:00:30'),
(3, 'Agus Santoso', 50, 'Jl. Mawar No. 12, Desa Makmur', '2025-12-19 04:00:48', '2025-12-19 04:00:48'),
(4, 'Ratna Lestari', 57, 'Jl. Anggrek No. 5, Desa Sejahtera', '2025-12-19 04:01:02', '2025-12-19 04:01:02'),
(5, 'Dedi Prasetya', 54, 'Jl. Sakura No. 10, Desa Maju', '2025-12-19 04:01:24', '2025-12-19 04:01:24');

-- --------------------------------------------------------

--
-- Table structure for table `profil_desa`
--

CREATE TABLE `profil_desa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi_singkat` text DEFAULT NULL,
  `gambar_header` varchar(255) DEFAULT NULL,
  `sejarah` text DEFAULT NULL,
  `wilayah_administratif` text DEFAULT NULL,
  `nama_desa` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kabupaten` varchar(255) DEFAULT NULL,
  `batas_utara` varchar(255) DEFAULT NULL,
  `batas_selatan` varchar(255) DEFAULT NULL,
  `batas_timur` varchar(255) DEFAULT NULL,
  `batas_barat` varchar(255) DEFAULT NULL,
  `koordinat` longtext DEFAULT NULL,
  `jarak_kabupaten` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profil_desa`
--

INSERT INTO `profil_desa` (`id`, `judul`, `deskripsi_singkat`, `gambar_header`, `sejarah`, `wilayah_administratif`, `nama_desa`, `kecamatan`, `kabupaten`, `batas_utara`, `batas_selatan`, `batas_timur`, `batas_barat`, `koordinat`, `jarak_kabupaten`, `created_at`, `updated_at`) VALUES
(2, 'Selamat Datang di Desa Lawaluu Baruu', 'Desa Lawaluu Baruu adalah desa yang indah dan ramah, dengan pemandangan alam yang asri dan kehidupan masyarakat yang harmonis.', '[\"1766286436_4126c7LZ.jpeg\"]', 'Desa Lawaluu Baruu berdiri sejak tahun 1975. Nama “Lawaluu” berasal dari bahasa daerah setempat yang berarti “tempat yang sejahtera”. Desa ini berkembang dengan kegiatan pertanian dan kerajinan tangan warga lokal.', 'Lawaluu Baruu', 'Lawallu', 'Barru', 'Barru', 'Desa Mallusetasi', 'Desa Sumpang Binangae', 'Desa Tanete Rilau', 'Desa Balusu', 'https://www.google.com/maps/place/Desa+Lawaluu+Baru,+Barru,+Sulawesi+Selatan', 'Jarak ke Kabupaten: ± 10 km dari pusat Kabupaten Barru, dapat ditempuh sekitar 20–25 menit dengan kendaraan bermotor.', '2025-12-20 18:59:02', '2025-12-20 19:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `pus`
--

CREATE TABLE `pus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pus`
--

INSERT INTO `pus` (`id`, `nama`, `alamat`, `no_hp`, `umur`, `created_at`, `updated_at`) VALUES
(1, 'Fitri Handayani', 'Jl. Kenanga No. 5, Desa Sejahtera, Kec. Makmur', '0812‑9876‑5432', 29, '2025-12-19 03:56:33', '2025-12-19 03:56:33');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0jR4kT9aLQJZuG6uma8HcgVkpx18R7jWfHba92zD', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicG1tU0dXV3loaDR1cGRCbEUzWDh0UXVwcnViWFVvcHJiMlRyTVFEbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjtzOjU6InJvdXRlIjtzOjk6InVzZXIuaG9tZSI7fX0=', 1766315637);

-- --------------------------------------------------------

--
-- Table structure for table `struktur_desa`
--

CREATE TABLE `struktur_desa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `struktur_desa`
--

INSERT INTO `struktur_desa` (`id`, `kategori`, `nama`, `jabatan`, `foto`, `created_at`, `updated_at`) VALUES
(20, 'pemerintahan_desa', 'kaka', 'AKKAS', 'uploads/struktur/1766302480_1750663025.webp', '2025-12-20 23:34:40', '2025-12-20 23:34:40'),
(21, 'pemerintahan_desa_bagan', NULL, NULL, 'uploads/struktur/1766302894_SOTK_KARANG_TARUNA.drawio.png', '2025-12-20 23:41:21', '2025-12-20 23:41:34');

-- --------------------------------------------------------

--
-- Table structure for table `transparansi_anggaran`
--

CREATE TABLE `transparansi_anggaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `pemasukan` bigint(20) DEFAULT NULL,
  `pengeluaran` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transparansi_anggaran`
--

INSERT INTO `transparansi_anggaran` (`id`, `judul`, `jenis`, `tahun`, `pemasukan`, `pengeluaran`, `tanggal`, `file`, `created_at`, `updated_at`) VALUES
(2, 'angaran pembayran pembuatan lapangan bola', 'PERUBAHAN', '2025', 2000000, 1000000, '2025-12-21', '1766289635_angaran-pembayran-pembuatan-lapangan-bola.pdf', '2025-12-20 20:00:35', '2025-12-20 20:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `transparansi_bumdes`
--

CREATE TABLE `transparansi_bumdes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` enum('BUMDES','KOPDES') NOT NULL DEFAULT 'BUMDES',
  `bulan` varchar(255) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transparansi_bumdes`
--

INSERT INTO `transparansi_bumdes` (`id`, `judul`, `kategori`, `bulan`, `tahun`, `tanggal`, `file`, `created_at`, `updated_at`) VALUES
(1, 'Laporan Keuangan BUMDes Lawaluu Baru 2025', 'KOPDES', NULL, NULL, '2025-12-20', '1766292692_1759381447_07.REALISASI_POKOK.SESUAI_PAJAK.pdf', '2025-12-19 02:23:50', '2025-12-20 20:51:32'),
(2, 'Foto Kegiatan Pelatihan UMKM BUMDes', 'BUMDES', NULL, NULL, '2025-12-19', '1766292681_1759381447_07.REALISASI_POKOK.SESUAI_PAJAK.pdf', '2025-12-19 02:24:41', '2025-12-20 20:51:21'),
(4, 'Kegiatan Gotong Royong Pembersihan Desa', 'BUMDES', NULL, NULL, '2025-12-21', '1766292715_1759381447_07.REALISASI_POKOK.SESUAI_PAJAK.pdf', '2025-12-20 20:51:55', '2025-12-20 20:51:55');

-- --------------------------------------------------------

--
-- Table structure for table `transparansi_dokumen`
--

CREATE TABLE `transparansi_dokumen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `jenis` enum('POKOK','PERUBAHAN') NOT NULL DEFAULT 'POKOK',
  `tahun` year(4) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transparansi_dokumen`
--

INSERT INTO `transparansi_dokumen` (`id`, `judul`, `jenis`, `tahun`, `tanggal`, `file`, `created_at`, `updated_at`) VALUES
(2, 'Laporan Keuangan Desa Tahun 2025', 'POKOK', '2025', '2025-12-21', '1766292241_7UIlv2G9jtDgL9riHj7F6MW9ZzrWyPi3BprcFoGH.pdf', '2025-12-20 20:44:01', '2025-12-20 20:44:01'),
(3, 'Laporan Keuangan Desa Tahun 2025', 'PERUBAHAN', '2026', '2026-12-21', '1766292274_7UIlv2G9jtDgL9riHj7F6MW9ZzrWyPi3BprcFoGH.pdf', '2025-12-20 20:44:34', '2025-12-20 20:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `transparansi_laporan`
--

CREATE TABLE `transparansi_laporan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `umkms`
--

CREATE TABLE `umkms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_pengusaha` varchar(255) NOT NULL,
  `nama_usaha` varchar(255) NOT NULL,
  `jenis_usaha` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kontak` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `foto_pengusaha` varchar(255) DEFAULT NULL,
  `kode_umkm` varchar(255) NOT NULL,
  `harga` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `umkms`
--

INSERT INTO `umkms` (`id`, `nama_pengusaha`, `nama_usaha`, `jenis_usaha`, `deskripsi`, `alamat`, `kontak`, `foto`, `foto_pengusaha`, `kode_umkm`, `harga`, `created_at`, `updated_at`) VALUES
(3, 'Andi Saputra', 'Roti Enak', 'Kuliner', 'Usaha roti rumahan dengan varian roti manis dan asin', 'Jl. Melati No.12, Jakarta', '081234567890', 'uploads/umkm/1766312628_WhatsApp Image 2025-12-03 at 22.33.22 (1).jpeg', 'uploads/pengusaha/1766313246_WhatsApp Image 2025-12-03 at 22.33.22 (1).jpeg', '0023 OR1NG', 1000000.00, '2025-12-21 02:19:29', '2025-12-21 02:34:06'),
(4, 'Toko Kreatifku', 'Kerajinan Tangan', 'Toko Kreatifku adalah usaha yang bergerak di bidang kerajinan tangan unik dan kreatif. Setiap produk dibuat dengan teliti menggunakan bahan berkualitas tinggi, sehingga cocok sebagai hadiah, dekorasi rumah, atau koleksi pribadi.', 'Toko Kreatifku adalah usaha yang bergerak di bidang kerajinan tangan unik dan kreatif. Setiap produk dibuat dengan teliti menggunakan bahan berkualitas tinggi, sehingga cocok sebagai hadiah, dekorasi rumah, atau koleksi pribadi. Usaha ini berfokus pada kepuasan pelanggan dan inovasi produk agar selalu menarik dan berbeda dari yang lain.', 'Jl. Melati No. 45, Jakarta Selatan', '081234567890', 'uploads/umkm/1766313529_WhatsApp Image 2025-12-03 at 22.33.22 (2).jpeg', 'uploads/pengusaha/1766313529_WhatsApp Image 2025-12-03 at 22.33.22 (2).jpeg', '0022 OR1NG', 21313123.00, '2025-12-21 02:38:49', '2025-12-21 03:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `umkm_produks`
--

CREATE TABLE `umkm_produks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `umkm_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `harga` decimal(15,2) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'desa', 'desa123', 'desa@gmail.com', '$2y$12$O3OFi8R2z7gBL5iWawNkaO.ol9IOQKJZ8XyE.w9nTvuqO6MW2jrqW', 'admin', NULL, '2025-12-19 01:38:23', '2025-12-19 01:38:23'),
(2, 'pos', 'pos123', 'pos@gmail.com', '$2y$12$KxCvtr0r2vV6vNU5.BNQf.H3/6jfkrhq0L8e1Ej92g7wjTgU0BDge', 'posyandu', NULL, '2025-12-19 03:50:58', '2025-12-19 03:50:58'),
(3, 'posyandu', 'posyandu1', 'admin@gmail.com', '$2y$12$mzKg12C3Sbi6SP7EmovV0.cpvddjw4Wzfwu3QEGikS0z5PNBJOxyq', 'posyandu', NULL, '2025-12-19 08:09:38', '2025-12-19 08:09:38'),
(4, 'pos12', 'pos12', 'posyandu@gnail.com', '$2y$12$vdUbIttxAOzk2MHOqNomV.r70fAiW.jKqeGAWGYAip2LqPmS/PZ1C', 'posyandu', NULL, '2025-12-20 08:17:19', '2025-12-20 08:17:19');

-- --------------------------------------------------------

--
-- Table structure for table `wus`
--

CREATE TABLE `wus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `umur` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wus`
--

INSERT INTO `wus` (`id`, `nama`, `alamat`, `umur`, `created_at`, `updated_at`) VALUES
(1, 'Siti Aulia', 'Jl. Melati No. 12, Desa Sukamaju', 27, '2025-12-19 03:57:08', '2025-12-19 03:57:08'),
(2, 'Rina Kusuma', 'Jl. Mawar No. 7, Desa Harapan', 32, '2025-12-19 03:57:27', '2025-12-19 03:57:27'),
(3, 'Dewi Lestari', 'Jl. Anggrek No. 15, Desa Makmur', 24, '2025-12-19 03:57:42', '2025-12-19 03:57:42'),
(4, 'Fitri Handayani', 'Jl. Kenanga No. 5, Desa Sejahtera', 30, '2025-12-19 03:58:00', '2025-12-19 03:58:00'),
(5, 'Maya Prasetya', 'Jl. Sakura No. 9, Desa Maju', 29, '2025-12-19 03:58:16', '2025-12-19 03:58:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrasi_penduduks`
--
ALTER TABLE `administrasi_penduduks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appras`
--
ALTER TABLE `appras`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `balitas`
--
ALTER TABLE `balitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bayis`
--
ALTER TABLE `bayis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beritas`
--
ALTER TABLE `beritas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `beritas_slug_unique` (`slug`);

--
-- Indexes for table `bpds`
--
ALTER TABLE `bpds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `foto_wargas`
--
ALTER TABLE `foto_wargas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeris`
--
ALTER TABLE `galeris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ibu_menyusuis`
--
ALTER TABLE `ibu_menyusuis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `karang_tarunas`
--
ALTER TABLE `karang_tarunas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kontak_saran`
--
ALTER TABLE `kontak_saran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lansia`
--
ALTER TABLE `lansia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_kegiatan`
--
ALTER TABLE `laporan_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lpms`
--
ALTER TABLE `lpms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengaduans`
--
ALTER TABLE `pengaduans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pkk`
--
ALTER TABLE `pkk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posyandus`
--
ALTER TABLE `posyandus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pra_lansias`
--
ALTER TABLE `pra_lansias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil_desa`
--
ALTER TABLE `profil_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pus`
--
ALTER TABLE `pus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `struktur_desa`
--
ALTER TABLE `struktur_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transparansi_anggaran`
--
ALTER TABLE `transparansi_anggaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transparansi_bumdes`
--
ALTER TABLE `transparansi_bumdes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transparansi_dokumen`
--
ALTER TABLE `transparansi_dokumen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transparansi_laporan`
--
ALTER TABLE `transparansi_laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `umkms`
--
ALTER TABLE `umkms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `umkms_kode_umkm_unique` (`kode_umkm`);

--
-- Indexes for table `umkm_produks`
--
ALTER TABLE `umkm_produks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `umkm_produks_umkm_id_foreign` (`umkm_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wus`
--
ALTER TABLE `wus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrasi_penduduks`
--
ALTER TABLE `administrasi_penduduks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appras`
--
ALTER TABLE `appras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `balitas`
--
ALTER TABLE `balitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bayis`
--
ALTER TABLE `bayis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `beritas`
--
ALTER TABLE `beritas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bpds`
--
ALTER TABLE `bpds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `foto_wargas`
--
ALTER TABLE `foto_wargas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `galeris`
--
ALTER TABLE `galeris`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ibu_menyusuis`
--
ALTER TABLE `ibu_menyusuis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `karang_tarunas`
--
ALTER TABLE `karang_tarunas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kontak_saran`
--
ALTER TABLE `kontak_saran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lansia`
--
ALTER TABLE `lansia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `laporan_kegiatan`
--
ALTER TABLE `laporan_kegiatan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lpms`
--
ALTER TABLE `lpms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `pengaduans`
--
ALTER TABLE `pengaduans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pkk`
--
ALTER TABLE `pkk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posyandus`
--
ALTER TABLE `posyandus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pra_lansias`
--
ALTER TABLE `pra_lansias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `profil_desa`
--
ALTER TABLE `profil_desa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pus`
--
ALTER TABLE `pus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `struktur_desa`
--
ALTER TABLE `struktur_desa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transparansi_anggaran`
--
ALTER TABLE `transparansi_anggaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transparansi_bumdes`
--
ALTER TABLE `transparansi_bumdes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transparansi_dokumen`
--
ALTER TABLE `transparansi_dokumen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transparansi_laporan`
--
ALTER TABLE `transparansi_laporan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `umkms`
--
ALTER TABLE `umkms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `umkm_produks`
--
ALTER TABLE `umkm_produks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wus`
--
ALTER TABLE `wus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `umkm_produks`
--
ALTER TABLE `umkm_produks`
  ADD CONSTRAINT `umkm_produks_umkm_id_foreign` FOREIGN KEY (`umkm_id`) REFERENCES `umkms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
