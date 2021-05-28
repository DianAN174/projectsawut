-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2021 at 01:19 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prsawut`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bagian_nazhir_atas_pengelolaan_dan_pengembangan_wakaf`
--

CREATE TABLE `bagian_nazhir_atas_pengelolaan_dan_pengembangan_wakaf` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bagian_nazhir_atas_pengelolaan_dan_pengembangan_wakaf`
--

INSERT INTO `bagian_nazhir_atas_pengelolaan_dan_pengembangan_wakaf` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `beban_pengelolaan_dan_pengembangan_wakaf`
--

CREATE TABLE `beban_pengelolaan_dan_pengembangan_wakaf` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `beban_pengelolaan_dan_pengembangan_wakaf`
--

INSERT INTO `beban_pengelolaan_dan_pengembangan_wakaf` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 150000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 500000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `daftar_wakif`
--

CREATE TABLE `daftar_wakif` (
  `id` int(11) NOT NULL,
  `wakif_id` int(11) NOT NULL,
  `akun_penerimaan_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `data_pengajuan_biaya`
--

CREATE TABLE `data_pengajuan_biaya` (
  `id` int(11) NOT NULL,
  `nama_pengaju` varchar(100) NOT NULL,
  `jenis_biaya` varchar(100) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `status_persetujuan` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `data_wakif`
--

CREATE TABLE `data_wakif` (
  `id` int(11) NOT NULL,
  `nama_wakif` varchar(100) NOT NULL,
  `NIK` varchar(100) NOT NULL,
  `no_AIW` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `telepon` varchar(100) NOT NULL,
  `jenis_wakaf` varchar(100) NOT NULL,
  `jangka_waktu_temporer` int(11) NOT NULL,
  `metode_pembayaran` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_wakif`
--

INSERT INTO `data_wakif` (`id`, `nama_wakif`, `NIK`, `no_AIW`, `alamat`, `telepon`, `jenis_wakaf`, `jangka_waktu_temporer`, `metode_pembayaran`, `created_at`, `updated_at`) VALUES
(1, 'Sumiati', '3305714990001', '001', 'Gunungkidul', '081311311311', 'Tunai Permanen', 0, 'Tunai', '2021-04-25 03:38:09', '2021-04-25 03:38:09'),
(2, 'Budi Mulya', '3305714990002', '002', 'Sleman', '0812113322444', 'Tunai Temporer', 1, 'Transfer', '2021-04-25 03:39:56', '2021-04-25 03:39:56'),
(3, 'Budiati', '3305714990003', '003', 'Umbulharjo', '081333000999', 'Tunai Permanen', 0, 'Tunai', '2021-04-25 03:40:36', '2021-04-25 03:40:36'),
(4, 'Yuliati', '3305714990004', '004', 'Pogung', '082111000999', 'Tunai Temporer', 1, 'Tunai', '2021-04-25 03:45:22', '2021-04-25 03:45:22');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kas_bagi_hasil`
--

CREATE TABLE `kas_bagi_hasil` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kas_bagi_hasil`
--

INSERT INTO `kas_bagi_hasil` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `kas_bagi_nonbagi_hasil`
--

CREATE TABLE `kas_bagi_nonbagi_hasil` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kas_bagi_nonbagi_hasil`
--

INSERT INTO `kas_bagi_nonbagi_hasil` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `kas_deposito_wakaf`
--

CREATE TABLE `kas_deposito_wakaf` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kas_deposito_wakaf`
--

INSERT INTO `kas_deposito_wakaf` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `kas_tabungan_bagi_hasil`
--

CREATE TABLE `kas_tabungan_bagi_hasil` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kas_tabungan_bagi_hasil`
--

INSERT INTO `kas_tabungan_bagi_hasil` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `kas_tabungan_non_bagi_hasil`
--

CREATE TABLE `kas_tabungan_non_bagi_hasil` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kas_tabungan_non_bagi_hasil`
--

INSERT INTO `kas_tabungan_non_bagi_hasil` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `kas_tabungan_wakaf`
--

CREATE TABLE `kas_tabungan_wakaf` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kas_tabungan_wakaf`
--

INSERT INTO `kas_tabungan_wakaf` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `kas_tunai`
--

CREATE TABLE `kas_tunai` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kas_tunai`
--

INSERT INTO `kas_tunai` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2021_04_07_024106_create_articles_table', 1),
(10, '2021_04_08_011121_update_users_table_to_include_type', 1),
(11, '2021_04_10_232129_create_permission_tables', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('1791fcbb6b858a489c3ce2d12b1d69349fc7f8c96cb28470b00c70ee879dd8abb6d0f8a8f13e9941', 2, 1, 'Laravel Password Grant Client', '[]', 0, '2021-04-10 18:14:46', '2021-04-10 18:14:46', '2022-04-11 01:14:46'),
('24e13231eda2790850e7f20ba2ec20bd62d33b6955d9b16c75f92dc648024426342ce15bcb458f9d', 3, 1, 'Laravel Password Grant Client', '[]', 0, '2021-04-13 20:54:38', '2021-04-13 20:54:38', '2022-04-14 03:54:38'),
('48b8eb5fd75411934e13e0c25a64509e5c9ed4379e8f68c661e94bc4cdf8bc4eb39f47515433895e', 2, 1, 'Laravel Password Grant Client', '[]', 0, '2021-04-13 20:49:53', '2021-04-13 20:49:53', '2022-04-14 03:49:53'),
('4ac1672d651ae5ef513f8411eec75377b5067fea98f5fa1d4f0bb0ca2592177283bee82172310882', 3, 1, 'Laravel Password Grant Client', '[]', 0, '2021-04-13 20:54:03', '2021-04-13 20:54:03', '2022-04-14 03:54:03'),
('75af4401438e96decbb3136c622f6060f628b091acbee36d0f5bb7a19cc8ba4bc36d2f84ed49bfcc', 2, 1, 'Laravel Password Grant Client', '[]', 0, '2021-04-13 20:50:15', '2021-04-13 20:50:15', '2022-04-14 03:50:15'),
('caf6f46d92a1ff53bcecfdef95ee4b733313bd7a82b2fccceca87e0831f4d9b9a70d29e469dd74ef', 2, 1, 'Laravel Password Grant Client', '[]', 0, '2021-04-10 18:14:59', '2021-04-10 18:14:59', '2022-04-11 01:14:59'),
('e6a29996ef38479a18fa9e1dc8d2b3a0bbcf7ea058c47b9f43d2a58c9d6c7f7f7cc01dc2b86f8a68', 2, 1, 'Laravel Password Grant Client', '[]', 0, '2021-04-13 20:50:51', '2021-04-13 20:50:51', '2022-04-14 03:50:51');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'wTfA25Lcc7bwWhaqJdT3pnQh9OxvpMHCrAJs36oH', NULL, 'http://localhost', 1, 0, 0, '2021-04-10 18:14:02', '2021-04-10 18:14:02'),
(2, NULL, 'Laravel Password Grant Client', 'vI10JbC0x0afMgQmH9OISS8ZCWXnOwPxV0SCE2Iy', 'users', 'http://localhost', 0, 1, 0, '2021-04-10 18:14:02', '2021-04-10 18:14:02');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-04-10 18:14:02', '2021-04-10 18:14:02');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelunasan_piutang`
--

CREATE TABLE `pelunasan_piutang` (
  `id` int(11) NOT NULL,
  `tanggal_cicilan` date NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `NIK` varchar(100) NOT NULL,
  `jumlah_cicilan` int(11) NOT NULL,
  `kekurangan` int(11) NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `status_pelunasan` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pelunasan_piutang`
--

INSERT INTO `pelunasan_piutang` (`id`, `tanggal_cicilan`, `nama_peminjam`, `NIK`, `jumlah_cicilan`, `kekurangan`, `tanggal_jatuh_tempo`, `status_pelunasan`, `created_at`, `updated_at`) VALUES
(1, '2021-04-03', 'Budi Mulya', '3305714980018', 500000, 1500000, '2021-04-10', 0, '2021-04-25 04:43:38', '2021-04-25 04:43:38'),
(2, '2021-04-03', 'Budi Mulya', '3305714980018', 500000, 1000000, '2021-04-10', 0, '2021-04-25 04:43:38', '2021-04-25 04:43:38'),
(3, '2021-04-03', 'Budi Mulya', '3305714980018', 1000000, 0, '2021-04-10', 1, '2021-04-25 04:43:38', '2021-04-25 04:43:38');

-- --------------------------------------------------------

--
-- Table structure for table `penerimaan_bagi_hasil_dan_pengelolaan_pengembangan_wakaf`
--

CREATE TABLE `penerimaan_bagi_hasil_dan_pengelolaan_pengembangan_wakaf` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penerimaan_bagi_hasil_dan_pengelolaan_pengembangan_wakaf`
--

INSERT INTO `penerimaan_bagi_hasil_dan_pengelolaan_pengembangan_wakaf` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 150000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 500000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `penerimaan_wakaf_tunai_permanen`
--

CREATE TABLE `penerimaan_wakaf_tunai_permanen` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penerimaan_wakaf_tunai_permanen`
--

INSERT INTO `penerimaan_wakaf_tunai_permanen` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `penerimaan_wakaf_tunai_temporer`
--

CREATE TABLE `penerimaan_wakaf_tunai_temporer` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penerimaan_wakaf_tunai_temporer`
--

INSERT INTO `penerimaan_wakaf_tunai_temporer` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_biaya`
--

CREATE TABLE `pengajuan_biaya` (
  `id` int(11) NOT NULL,
  `nama_pengaju` varchar(100) NOT NULL,
  `kategori_biaya` varchar(100) NOT NULL,
  `jenis_biaya` varchar(100) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `sumber_biaya` varchar(100) NOT NULL,
  `pencairan` tinyint(1) NOT NULL DEFAULT 0,
  `status_persetujuan` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengajuan_biaya`
--

INSERT INTO `pengajuan_biaya` (`id`, `nama_pengaju`, `kategori_biaya`, `jenis_biaya`, `keterangan`, `sumber_biaya`, `pencairan`, `status_persetujuan`, `created_at`, `updated_at`) VALUES
(5, 'Mudirman', 'Beban pengelolaan dan pengembangan wakaf', 'Beban ATK', 'Pembelian ATK', 'Kas Tunai', 0, 0, '2021-04-25 04:45:49', '2021-04-25 04:45:49'),
(6, 'Suwarman', 'Beban pengelolaan dan pengembangan wakaf', 'Beban rapat', 'Konsumsi rapat', 'Kas Tunai', 0, 0, '2021-04-25 04:45:49', '2021-04-25 04:45:49');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_penerimaan_manfaat`
--

CREATE TABLE `pengajuan_penerimaan_manfaat` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `NIK` varchar(100) NOT NULL,
  `institusi_asal` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_telepon` varchar(100) NOT NULL,
  `jenis_usaha` varchar(100) NOT NULL,
  `deskripsi_usaha` varchar(100) NOT NULL,
  `periode_peminjaman` date NOT NULL DEFAULT current_timestamp(),
  `kelayakan` tinyint(1) NOT NULL DEFAULT 0,
  `penyaluran` tinyint(1) NOT NULL DEFAULT 0,
  `status_persetujuan` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengajuan_penerimaan_manfaat`
--

INSERT INTO `pengajuan_penerimaan_manfaat` (`id`, `nama`, `NIK`, `institusi_asal`, `alamat`, `no_telepon`, `jenis_usaha`, `deskripsi_usaha`, `periode_peminjaman`, `kelayakan`, `penyaluran`, `status_persetujuan`, `created_at`, `updated_at`) VALUES
(1, 'Juni', '3305714980012', 'UMKM ABC', 'Sleman', '089111333999', 'Makanan', 'Makanan kering', '2021-04-25', 0, 0, 1, '2021-04-25 04:23:20', '2021-04-25 04:23:20'),
(2, 'Joko', '3305714980013', 'UMKM ABC', 'Bantul', '083999444222', 'Lain-Lain', 'Tukang becak', '2021-04-25', 0, 0, 1, '2021-04-25 04:23:20', '2021-04-25 04:23:20'),
(3, 'Jubaedah', '3305714980005', 'UMKM ABC', 'Sleman', '084333999000', 'Perdagangan', 'Penjual sayur', '2021-04-25', 0, 0, 1, '2021-04-25 04:23:20', '2021-04-25 04:23:20'),
(4, 'Mulyono', '3305714980009', 'UMKM ABC', 'Depok', '084333999001', 'Otomotif', 'Bengkel', '2021-04-25', 0, 0, 1, '2021-04-25 04:23:20', '2021-04-25 04:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `pentasyarufan_manfaat_wakaf`
--

CREATE TABLE `pentasyarufan_manfaat_wakaf` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pentasyarufan_manfaat_wakaf`
--

INSERT INTO `pentasyarufan_manfaat_wakaf` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 100000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 50000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'l', 'web', '2021-04-10 18:15:41', '2021-04-10 18:15:41'),
(2, 'lo', 'web', '2021-04-10 21:39:03', '2021-04-10 21:39:03'),
(3, 'Budi Bud', 'web', '2021-04-11 21:52:40', '2021-04-11 21:52:40');

-- --------------------------------------------------------

--
-- Table structure for table `piutang_protab`
--

CREATE TABLE `piutang_protab` (
  `id` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `saldo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `piutang_protab`
--

INSERT INTO `piutang_protab` (`id`, `tanggal_transaksi`, `keterangan`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 2000000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(2, '2021-04-08', 'Wakaf permanen', 1400000, '2021-04-25 03:42:28', '2021-04-25 03:42:28'),
(3, '2021-04-01', 'Masukkan keterangan wakaf di sini...', 1200000, '2021-04-25 03:41:31', '2021-04-25 03:41:56'),
(4, '2021-04-08', 'Wakaf temporer', 1100000, '2021-04-25 03:42:28', '2021-04-25 03:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Budi Bud', 'budi123@gmail.com', NULL, '$2y$10$dwQJryRhucT.w508kVk/aeU3RxKQeM6rzdxZZK3KZT0TtGe8ZMTfG', NULL, '2021-04-10 18:14:44', '2021-04-10 21:42:02'),
(3, 'Budi Jaya', 'budi1234@gmail.com', NULL, '$2y$10$F/Hpn7S7BCXWxTCosdjA6uYsDTZ1PZukbdWrQIuMP9Hcbh0/01e1C', NULL, '2021-04-13 20:54:03', '2021-04-13 20:54:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bagian_nazhir_atas_pengelolaan_dan_pengembangan_wakaf`
--
ALTER TABLE `bagian_nazhir_atas_pengelolaan_dan_pengembangan_wakaf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beban_pengelolaan_dan_pengembangan_wakaf`
--
ALTER TABLE `beban_pengelolaan_dan_pengembangan_wakaf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daftar_wakif`
--
ALTER TABLE `daftar_wakif`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_pengajuan_biaya`
--
ALTER TABLE `data_pengajuan_biaya`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_wakif`
--
ALTER TABLE `data_wakif`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `kas_bagi_hasil`
--
ALTER TABLE `kas_bagi_hasil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kas_bagi_nonbagi_hasil`
--
ALTER TABLE `kas_bagi_nonbagi_hasil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kas_deposito_wakaf`
--
ALTER TABLE `kas_deposito_wakaf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kas_tabungan_bagi_hasil`
--
ALTER TABLE `kas_tabungan_bagi_hasil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kas_tabungan_non_bagi_hasil`
--
ALTER TABLE `kas_tabungan_non_bagi_hasil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kas_tabungan_wakaf`
--
ALTER TABLE `kas_tabungan_wakaf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kas_tunai`
--
ALTER TABLE `kas_tunai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pelunasan_piutang`
--
ALTER TABLE `pelunasan_piutang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerimaan_bagi_hasil_dan_pengelolaan_pengembangan_wakaf`
--
ALTER TABLE `penerimaan_bagi_hasil_dan_pengelolaan_pengembangan_wakaf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerimaan_wakaf_tunai_permanen`
--
ALTER TABLE `penerimaan_wakaf_tunai_permanen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerimaan_wakaf_tunai_temporer`
--
ALTER TABLE `penerimaan_wakaf_tunai_temporer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan_biaya`
--
ALTER TABLE `pengajuan_biaya`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan_penerimaan_manfaat`
--
ALTER TABLE `pengajuan_penerimaan_manfaat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pentasyarufan_manfaat_wakaf`
--
ALTER TABLE `pentasyarufan_manfaat_wakaf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `piutang_protab`
--
ALTER TABLE `piutang_protab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bagian_nazhir_atas_pengelolaan_dan_pengembangan_wakaf`
--
ALTER TABLE `bagian_nazhir_atas_pengelolaan_dan_pengembangan_wakaf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `beban_pengelolaan_dan_pengembangan_wakaf`
--
ALTER TABLE `beban_pengelolaan_dan_pengembangan_wakaf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `data_wakif`
--
ALTER TABLE `data_wakif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kas_bagi_hasil`
--
ALTER TABLE `kas_bagi_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kas_bagi_nonbagi_hasil`
--
ALTER TABLE `kas_bagi_nonbagi_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kas_deposito_wakaf`
--
ALTER TABLE `kas_deposito_wakaf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kas_tabungan_bagi_hasil`
--
ALTER TABLE `kas_tabungan_bagi_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kas_tabungan_non_bagi_hasil`
--
ALTER TABLE `kas_tabungan_non_bagi_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kas_tabungan_wakaf`
--
ALTER TABLE `kas_tabungan_wakaf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kas_tunai`
--
ALTER TABLE `kas_tunai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pelunasan_piutang`
--
ALTER TABLE `pelunasan_piutang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penerimaan_bagi_hasil_dan_pengelolaan_pengembangan_wakaf`
--
ALTER TABLE `penerimaan_bagi_hasil_dan_pengelolaan_pengembangan_wakaf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penerimaan_wakaf_tunai_permanen`
--
ALTER TABLE `penerimaan_wakaf_tunai_permanen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penerimaan_wakaf_tunai_temporer`
--
ALTER TABLE `penerimaan_wakaf_tunai_temporer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengajuan_biaya`
--
ALTER TABLE `pengajuan_biaya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pengajuan_penerimaan_manfaat`
--
ALTER TABLE `pengajuan_penerimaan_manfaat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pentasyarufan_manfaat_wakaf`
--
ALTER TABLE `pentasyarufan_manfaat_wakaf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `piutang_protab`
--
ALTER TABLE `piutang_protab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
