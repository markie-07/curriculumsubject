-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 09, 2025 at 11:11 PM
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
-- Database: `subjectcurriculum`
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
-- Table structure for table `curriculums`
--

CREATE TABLE `curriculums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curriculum` varchar(255) NOT NULL,
  `program_code` varchar(255) NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `year_level` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `curriculums`
--

INSERT INTO `curriculums` (`id`, `curriculum`, `program_code`, `academic_year`, `year_level`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor of Information Technology', 'BSIT', '2025-2026', 'College', '2025-10-09 10:38:26', '2025-10-09 10:38:26');

-- --------------------------------------------------------

--
-- Table structure for table `curriculum_subject`
--

CREATE TABLE `curriculum_subject` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curriculum_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `year` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `curriculum_subject`
--

INSERT INTO `curriculum_subject` (`id`, `curriculum_id`, `subject_id`, `year`, `semester`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 1, 1, '2025-10-09 11:43:28', '2025-10-09 11:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `employee_activity_logs`
--

CREATE TABLE `employee_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_activity_logs`
--

INSERT INTO `employee_activity_logs` (`id`, `user_id`, `activity_type`, `description`, `metadata`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 9, 'login', 'User logged in', '{\"login_time\":\"2025-10-09T20:09:33.070707Z\",\"browser\":{\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/138.0.0.0 Safari\\/537.36 OPR\\/122.0.0.0\",\"platform\":\"Windows\",\"browser\":\"Chrome\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0', '2025-10-09 12:09:33', '2025-10-09 12:09:33'),
(2, 9, 'login', 'User logged in', '{\"login_time\":\"2025-10-09T20:26:11.026674Z\",\"browser\":{\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/138.0.0.0 Safari\\/537.36 OPR\\/122.0.0.0\",\"platform\":\"Windows\",\"browser\":\"Chrome\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0', '2025-10-09 12:26:11', '2025-10-09 12:26:11'),
(3, 9, 'view', 'Viewed Curriculum Export Tool', '{\"page\":\"Curriculum Export Tool\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/curriculum_export_tool\",\"method\":\"GET\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0', '2025-10-09 12:26:11', '2025-10-09 12:26:11'),
(4, 9, 'export', 'Exported curriculum_export as Bachelor of Information Technology (BSIT) (Major, Minor, Elective, General Education).pdf', '{\"export_type\":\"curriculum_export\",\"file_name\":\"Bachelor of Information Technology (BSIT) (Major, Minor, Elective, General Education).pdf\",\"timestamp\":\"2025-10-09T20:26:28.237520Z\",\"curriculum_id\":\"1\",\"curriculum_name\":\"Bachelor of Information Technology\",\"format\":\"PDF\",\"export_history_id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0', '2025-10-09 12:26:28', '2025-10-09 12:26:28'),
(5, 9, 'logout', 'User logged out', '{\"logout_time\":\"2025-10-09T20:27:34.859359Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0', '2025-10-09 12:27:34', '2025-10-09 12:27:34');

-- --------------------------------------------------------

--
-- Table structure for table `equivalencies`
--

CREATE TABLE `equivalencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_subject_name` varchar(255) NOT NULL,
  `equivalent_subject_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `export_histories`
--

CREATE TABLE `export_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curriculum_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `format` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `export_histories`
--

INSERT INTO `export_histories` (`id`, `curriculum_id`, `file_name`, `format`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bachelor of Information Technology (BSIT).pdf', 'PDF', '2025-10-09 11:28:57', '2025-10-09 11:28:57'),
(2, 1, 'Bachelor of Information Technology (BSIT).pdf', 'PDF', '2025-10-09 11:32:01', '2025-10-09 11:32:01'),
(3, 1, 'Bachelor of Information Technology (BSIT) (Major, Minor, Elective, General Education).pdf', 'PDF', '2025-10-09 12:26:28', '2025-10-09 12:26:28');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `components` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`components`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `subject_id`, `components`, `created_at`, `updated_at`) VALUES
(1, 1, '{\"Prelim\":{\"weight\":100,\"components\":[{\"name\":\"Quiz\",\"weight\":100,\"sub_components\":[]}]}}', '2025-10-09 11:12:14', '2025-10-09 11:12:14');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 1,
  `lockout_count` int(11) NOT NULL DEFAULT 0,
  `last_attempt_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `locked_until` timestamp NULL DEFAULT NULL,
  `first_lockout_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `email`, `ip_address`, `attempts`, `lockout_count`, `last_attempt_at`, `locked_until`, `first_lockout_at`, `created_at`, `updated_at`) VALUES
(1, 'lhandelpamisa0@gmail.com', '127.0.0.1', 0, 0, '2025-10-09 12:31:06', NULL, NULL, '2025-10-09 10:37:53', '2025-10-09 12:31:06'),
(2, 'olausersms3@gmail.com', '127.0.0.1', 0, 0, '2025-10-09 12:06:20', NULL, NULL, '2025-10-09 12:06:20', '2025-10-09 12:06:20'),
(3, 'olaoreo28@gmail.com', '127.0.0.1', 0, 0, '2025-10-09 12:25:57', NULL, NULL, '2025-10-09 12:07:04', '2025-10-09 12:25:57');

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
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_09_154819_create_employee_activity_logs_table', 1),
(5, '2025_01_09_154820_add_status_to_users_table', 1),
(6, '2025_08_26_060835_create_curriculums_table', 1),
(7, '2025_08_26_060913_create_subjects_table', 1),
(8, '2025_09_05_000000_create_prerequisites_table', 1),
(9, '2025_09_09_182042_create_curriculum_subject_table', 1),
(10, '2025_09_10_145255_create_subject_histories_table', 1),
(11, '2025_09_12_173548_create_grades_table', 1),
(12, '2025_09_14_065418_create_equivalencies_table', 1),
(13, '2025_09_17_073916_add_pdfs_to_subjects_table', 1),
(14, '2025_09_21_071853_create_export_histories_table', 1),
(15, '2025_10_08_165419_fix_username_column_in_users_table', 1),
(16, '2025_10_09_075358_create_otps_table', 1),
(17, '2025_10_09_121613_create_login_attempts_table', 1),
(18, '2025_10_09_123413_create_terms_acceptances_table', 1),
(19, '2025_10_09_174800_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`id`, `email`, `otp_code`, `expires_at`, `is_used`, `created_at`, `updated_at`) VALUES
(1, 'lhandelpamisa0@gmail.com', '317961', '2025-10-09 18:38:09', 1, '2025-10-09 10:37:54', '2025-10-09 10:38:09'),
(2, 'olausersms3@gmail.com', '211961', '2025-10-09 20:06:31', 1, '2025-10-09 12:06:20', '2025-10-09 12:06:31'),
(3, 'lhandelpamisa0@gmail.com', '371165', '2025-10-09 20:08:04', 1, '2025-10-09 12:07:52', '2025-10-09 12:08:04'),
(4, 'olaoreo28@gmail.com', '877396', '2025-10-09 20:09:33', 1, '2025-10-09 12:09:17', '2025-10-09 12:09:33'),
(5, 'lhandelpamisa0@gmail.com', '133276', '2025-10-09 20:15:03', 1, '2025-10-09 12:14:49', '2025-10-09 12:15:03'),
(6, 'lhandelpamisa0@gmail.com', '513402', '2025-10-09 20:15:54', 1, '2025-10-09 12:15:38', '2025-10-09 12:15:54'),
(7, 'olaoreo28@gmail.com', '254686', '2025-10-09 20:26:11', 1, '2025-10-09 12:25:57', '2025-10-09 12:26:11'),
(8, 'lhandelpamisa0@gmail.com', '389971', '2025-10-09 20:31:16', 1, '2025-10-09 12:31:06', '2025-10-09 12:31:16');

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
-- Table structure for table `prerequisites`
--

CREATE TABLE `prerequisites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curriculum_id` bigint(20) UNSIGNED NOT NULL,
  `subject_code` varchar(255) NOT NULL,
  `prerequisite_subject_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
('4TeCMrrP58oClc1Lmm7YIX81dp2B8NnN9mabvUoh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMTFQbWp3SEhVWmxGUDJSZ1pTRDRjdEd1ZVZ3ek5rbEZJSFB6d3hwcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760040544),
('LZ4Fi7rUCwDwPhGYSuy8FbfdIhMUQYlDbEn8mlVS', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN3hSSXVUVnAwcEF3engyVkFpbkFmZVR3UnB6TmRGSzFLOEtaSWp3ayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvb3RwLXZlcmlmeSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjk7fQ==', 1760040573),
('uoqGV0KalAtyajmz5HXzRpZlBKtgPcilQhIGKfOM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFhWODdHSllPYXpEUHF4R3hzS2NRdDV5cGM2OWJBYXE3RVFaSEhaZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1760043725);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_code` varchar(255) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_type` varchar(255) NOT NULL,
  `subject_unit` int(11) NOT NULL,
  `lessons` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`lessons`)),
  `pdfs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pdfs`)),
  `contact_hours` int(11) DEFAULT NULL,
  `prerequisites` varchar(255) DEFAULT NULL,
  `pre_requisite_to` varchar(255) DEFAULT NULL,
  `course_description` text DEFAULT NULL,
  `program_mapping_grid` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`program_mapping_grid`)),
  `course_mapping_grid` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`course_mapping_grid`)),
  `pilo_outcomes` text DEFAULT NULL,
  `cilo_outcomes` text DEFAULT NULL,
  `learning_outcomes` text DEFAULT NULL,
  `basic_readings` text DEFAULT NULL,
  `extended_readings` text DEFAULT NULL,
  `course_assessment` text DEFAULT NULL,
  `committee_members` text DEFAULT NULL,
  `consultation_schedule` text DEFAULT NULL,
  `prepared_by` varchar(255) DEFAULT NULL,
  `reviewed_by` varchar(255) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `subject_type`, `subject_unit`, `lessons`, `pdfs`, `contact_hours`, `prerequisites`, `pre_requisite_to`, `course_description`, `program_mapping_grid`, `course_mapping_grid`, `pilo_outcomes`, `cilo_outcomes`, `learning_outcomes`, `basic_readings`, `extended_readings`, `course_assessment`, `committee_members`, `consultation_schedule`, `prepared_by`, `reviewed_by`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 'MTH', 'MATH', 'Major', 3, NULL, NULL, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 11:00:03', '2025-10-09 11:00:03');

-- --------------------------------------------------------

--
-- Table structure for table `subject_histories`
--

CREATE TABLE `subject_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curriculum_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_code` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `action` varchar(255) NOT NULL DEFAULT 'removed',
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
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_activity` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `status`, `last_activity`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrator', 'superadmin', 'lhandelpamisa0@gmail.com', 'super_admin', 'active', NULL, NULL, '$2y$12$YpqKY.odjNhiak/R1/oB..KFicxXyZJzeaUGoXeD4vAnuYekaLSem', NULL, '2025-10-09 10:37:42', '2025-10-09 10:37:42'),
(2, 'Administrator', 'admin', 'olausersms3@gmail.com', 'admin', 'active', NULL, NULL, '$2y$12$4AAU0etVfucu9UzHC.BmzusgHGhhxYelNMtW.Sg4HLD.1maSyTXFe', NULL, '2025-10-09 10:37:42', '2025-10-09 10:37:42'),
(3, 'Test User', 'testuser', 'test@sms.edu', 'user', 'active', NULL, NULL, '$2y$12$MlW5uEYRdZbHTUOrwlpQv.SuAiC/9qngR2znUYqtWxZLEaG2STi6q', NULL, '2025-10-09 10:37:43', '2025-10-09 10:37:43'),
(4, 'John Admin', 'johnadmin', 'john@sms.edu', 'admin', 'active', NULL, NULL, '$2y$12$myWsQQ44dHl9ovBFuXb5Ce72VPn4SHNZoBObXly7wFHhbh/Xbelg6', NULL, '2025-10-09 10:37:43', '2025-10-09 10:37:43'),
(5, 'Secondary Super Admin', 'superadmin2', 'admin@yourdomain.com', 'super_admin', 'active', NULL, NULL, '$2y$12$6YCBmq/AA9pW0gAzzE/souoL1qDO9iIbrk.202sC5NPl4o037RbRy', NULL, '2025-10-09 10:37:43', '2025-10-09 10:37:43'),
(6, 'Third Super Admin', 'superadmin3', 'manager@yourdomain.com', 'super_admin', 'active', NULL, NULL, '$2y$12$TD5gt69VcHJ6SCIjmnaa3uX4pVkNYZolmV5kefkb38ww8Z/Ea41py', NULL, '2025-10-09 10:37:43', '2025-10-09 10:37:43'),
(9, 'Lhandel V. Pamisa', 'Employee', 'olaoreo28@gmail.com', 'employee', 'active', NULL, NULL, '$2y$12$bhu55xt8q1nm0m2vAzKHT.aoWTFgz67X3IQAqIlGQKkpdCwxrKHIG', NULL, '2025-10-09 12:08:57', '2025-10-09 12:08:57');

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
-- Indexes for table `curriculums`
--
ALTER TABLE `curriculums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curriculums_program_code_unique` (`program_code`);

--
-- Indexes for table `curriculum_subject`
--
ALTER TABLE `curriculum_subject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curriculum_subject_curriculum_id_foreign` (`curriculum_id`),
  ADD KEY `curriculum_subject_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `employee_activity_logs`
--
ALTER TABLE `employee_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `employee_activity_logs_activity_type_index` (`activity_type`);

--
-- Indexes for table `equivalencies`
--
ALTER TABLE `equivalencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equivalencies_equivalent_subject_id_foreign` (`equivalent_subject_id`);

--
-- Indexes for table `export_histories`
--
ALTER TABLE `export_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `export_histories_curriculum_id_foreign` (`curriculum_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grades_subject_id_unique` (`subject_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_attempts_email_ip_address_index` (`email`,`ip_address`),
  ADD KEY `login_attempts_email_index` (`email`),
  ADD KEY `login_attempts_ip_address_index` (`ip_address`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  ADD KEY `notifications_created_at_index` (`created_at`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otps_email_otp_code_index` (`email`,`otp_code`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prerequisites_curriculum_id_foreign` (`curriculum_id`),
  ADD KEY `prerequisites_subject_code_foreign` (`subject_code`),
  ADD KEY `prerequisites_prerequisite_subject_code_foreign` (`prerequisite_subject_code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subjects_subject_code_unique` (`subject_code`);

--
-- Indexes for table `subject_histories`
--
ALTER TABLE `subject_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_histories_curriculum_id_foreign` (`curriculum_id`),
  ADD KEY `subject_histories_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `curriculums`
--
ALTER TABLE `curriculums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `curriculum_subject`
--
ALTER TABLE `curriculum_subject`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee_activity_logs`
--
ALTER TABLE `employee_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `equivalencies`
--
ALTER TABLE `equivalencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `export_histories`
--
ALTER TABLE `export_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prerequisites`
--
ALTER TABLE `prerequisites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subject_histories`
--
ALTER TABLE `subject_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `curriculum_subject`
--
ALTER TABLE `curriculum_subject`
  ADD CONSTRAINT `curriculum_subject_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curriculum_subject_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_activity_logs`
--
ALTER TABLE `employee_activity_logs`
  ADD CONSTRAINT `employee_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equivalencies`
--
ALTER TABLE `equivalencies`
  ADD CONSTRAINT `equivalencies_equivalent_subject_id_foreign` FOREIGN KEY (`equivalent_subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `export_histories`
--
ALTER TABLE `export_histories`
  ADD CONSTRAINT `export_histories_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD CONSTRAINT `prerequisites_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prerequisites_prerequisite_subject_code_foreign` FOREIGN KEY (`prerequisite_subject_code`) REFERENCES `subjects` (`subject_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `prerequisites_subject_code_foreign` FOREIGN KEY (`subject_code`) REFERENCES `subjects` (`subject_code`) ON DELETE CASCADE;

--
-- Constraints for table `subject_histories`
--
ALTER TABLE `subject_histories`
  ADD CONSTRAINT `subject_histories_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subject_histories_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
