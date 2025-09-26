-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2025 at 05:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beathive_web`
--

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
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cinematic', 'cinematic', 'Orchestral / film scoring', NULL, NULL),
(2, 'Hip-Hop', 'hip-hop', 'Rap, trap, boom bap, urban beats', NULL, NULL),
(3, 'Lo-Fi', 'lo-fi', 'Relaxed, study, chill vibes', NULL, NULL),
(4, 'EDM', 'edm', 'Electronic dance music', NULL, NULL),
(5, 'Acoustic', 'acoustic', 'Guitar, piano, and natural instruments', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('BclAtOoqReum1A5pQ4bSdiiRTXsDL69VU0H7GRUq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZVNONTFiV1V4Y1dUM29GUDQwc0NETkZRN1B3R3l2a1RCSHlkSUR4UyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90cmFja3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1758884629),
('FD0OA4XmgLA5o4VoHm42PtPcIt7n72hqGqEr1UgN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidWwzdjBTV0tITm1XdWc2TlZsNm5EdWFZaTN5WTF6SUpTSUV2cGxvdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758875860),
('O0m5gGfmnWGv8hgCEG22u03iAeQ6lwhR20NHSzU9', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYmlRV3RVbWdvOTIxTVVZM1lSY09HOVhxSUxPeGZPNW0xNHlhR0Y0cyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90cmFja3MvY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1758879663);

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE `tracks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `genre_id` bigint(20) UNSIGNED NOT NULL,
  `bpm` smallint(5) UNSIGNED DEFAULT NULL,
  `musical_key` varchar(8) DEFAULT NULL,
  `mood` varchar(100) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price_idr` int(10) UNSIGNED DEFAULT NULL,
  `duration_seconds` int(10) UNSIGNED DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `cover_path` varchar(255) DEFAULT NULL,
  `preview_path` varchar(255) DEFAULT NULL,
  `bundle_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tracks`
--

INSERT INTO `tracks` (`id`, `title`, `slug`, `artist`, `genre_id`, `bpm`, `musical_key`, `mood`, `tags`, `description`, `price_idr`, `duration_seconds`, `release_date`, `is_published`, `cover_path`, `preview_path`, `bundle_path`, `created_at`, `updated_at`) VALUES
(1, 'Sunset Drive', 'sunset-drive-a1b2c3', 'BeatHive Studio', 3, 80, 'Am', 'Chill', 'lofi, chill, study', 'Relaxed lo-fi beat for study and focus.', 150000, 152, '2025-08-01', 1, 'covers/sunset-drive.jpg', 'previews/sunset-drive.mp3', 'bundles/sunset-drive.zip', '2025-09-25 14:10:41', '2025-09-25 14:10:41'),
(3, 'City Lights', 'city-lights-g7h8i9', 'Loopsmith', 2, 92, 'Fm', 'Groovy', 'hiphop, boom-bap, urban', 'Boom-bap drums and jazzy keys for vlogs.', 175000, 185, '2025-06-20', 1, 'covers/city-lights.jpg', 'previews/city-lights.mp3', 'bundles/city-lights.zip', '2025-09-25 14:10:41', '2025-09-25 14:10:41'),
(4, 'Pulse Reactor', 'pulse-reactor-j1k2l3', 'Neon Flux', 4, 128, 'G#m', 'Energetic', 'edm, festival, drop', 'High-energy EDM with driving bass and big-room drop.', 300000, 204, '2025-05-30', 1, 'covers/pulse-reactor.jpg', 'previews/pulse-reactor.mp3', 'bundles/pulse-reactor.zip', '2025-09-25 14:10:41', '2025-09-25 14:10:41'),
(5, 'Coffee Room', 'coffee-room-m4n5o6', 'Wood & Wire', 5, 72, 'C', 'Warm', 'acoustic, guitar, cozy', 'Warm acoustic guitar, perfect for cafés and lifestyle content.', 120000, 163, '2025-08-10', 0, 'covers/coffee-room.jpg', 'previews/coffee-room.mp3', 'bundles/coffee-room.zip', '2025-09-25 14:10:41', '2025-09-25 14:10:41'),
(6, 'Stormchaser', 'stormchaser-p7q8r9', 'Astra Ensemble', 1, 108, 'Em', 'Tense', 'cinematic, tension, trailer', 'Dark hybrid trailer cues with braams and pulses.', 275000, 146, '2025-09-01', 1, 'covers/stormchaser.jpg', 'previews/stormchaser.mp3', 'bundles/stormchaser.zip', '2025-09-25 14:10:41', '2025-09-25 14:10:41'),
(7, 'Lowkey Moves', 'lowkey-moves-s1t2u3', '808 Collective', 2, 100, 'Bm', 'Laid-back', 'hiphop, trap, mellow', 'Laid-back trap beat with textured pads and 808s.', 160000, 171, '2025-07-28', 0, 'covers/lowkey-moves.jpg', 'previews/lowkey-moves.mp3', 'bundles/lowkey-moves.zip', '2025-09-25 14:10:41', '2025-09-25 14:10:41'),
(8, 'Skyline Run', 'skyline-run-v4w5x6', 'NightDrive', 4, 124, 'F#m', 'Driving', 'edm, synthwave, retro', 'Synthwave/EDM hybrid for fast urban montages.', 220000, 199, '2025-06-05', 1, 'covers/skyline-run.jpg', 'previews/skyline-run.mp3', 'bundles/skyline-run.zip', '2025-09-25 14:10:41', '2025-09-25 14:10:41'),
(9, 'my 1st sound', 'test', 'test', 5, 111, 'C#', 'Chill', 'epic', 'test test upload music', 1000, 30, '2025-09-26', 0, 'covers/qvdzqeoVp4eatqNYl9ECPocS5gk0cZndBqmOb4DB.png', NULL, NULL, '2025-09-26 04:00:13', '2025-09-26 04:03:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `tracks_genre_id_foreign` (`genre_id`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tracks`
--
ALTER TABLE `tracks`
  ADD CONSTRAINT `tracks_genre_id_foreign` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
