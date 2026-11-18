-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 27, 2026 at 11:34 AM
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
-- Database: `w_c_issues`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `username`, `action`, `timestamp`) VALUES
(1, 'admin', 'User logged into application lifecycle registry.', '2026-07-11 16:35:20'),
(2, 'admin', 'Posted new news article tracking profile.', '2026-07-11 16:40:48'),
(3, 'demo_user', 'User logged into application lifecycle registry.', '2026-07-11 16:42:18'),
(4, 'admin', 'User logged into application lifecycle registry.', '2026-07-11 16:48:47'),
(5, 'admin', 'Deleted structural news item ID: 1', '2026-07-11 16:50:23'),
(6, 'admin', 'User logged into application lifecycle registry.', '2026-07-11 18:46:51'),
(7, 'admin', 'User logged into application lifecycle registry.', '2026-07-12 07:25:05'),
(8, 'admin', 'Updated dual-language content for key: mission', '2026-07-12 07:26:29'),
(9, 'admin', 'User logged into application lifecycle registry.', '2026-07-12 07:30:42'),
(10, 'admin', 'Updated dual-language content for key: vision', '2026-07-12 07:31:21'),
(11, 'admin', 'Updated dual-language content for key: values', '2026-07-12 07:32:06'),
(12, 'admin', 'Updated dual-language content for key: goals', '2026-07-12 07:34:35'),
(13, 'admin', 'Updated dual-language content for key: goals', '2026-07-12 07:35:24'),
(14, 'admin', 'User logged into application lifecycle registry.', '2026-07-12 07:40:20'),
(15, 'admin', 'Updated dual-language content for key: vision', '2026-07-12 07:40:47'),
(16, 'admin', 'Updated dual-language content for key: values', '2026-07-12 07:42:50'),
(17, 'admin', 'Updated dual-language content for key: goals', '2026-07-12 07:44:22'),
(18, 'admin', 'User logged into application lifecycle registry.', '2026-07-12 07:49:09'),
(19, 'admin', 'Updated dual-language content for key: services', '2026-07-12 07:49:23'),
(20, 'admin', 'Updated dual-language content for key: vision', '2026-07-12 07:51:13'),
(21, 'admin', 'Updated dual-language content for key: mission', '2026-07-12 07:52:01'),
(22, 'admin', 'Updated dual-language content for key: values', '2026-07-12 07:52:39'),
(23, 'admin', 'Updated dual-language content for key: goals', '2026-07-12 07:53:37'),
(24, 'admin', 'Updated dual-language content for key: services', '2026-07-12 07:55:46'),
(25, 'admin', 'Updated dual-language content for key: resources', '2026-07-12 11:14:48'),
(26, 'admin', 'Updated dual-language content for key: history', '2026-07-12 11:16:23'),
(27, 'admin', 'Updated dual-language content for key: values', '2026-07-12 11:19:28'),
(28, 'admin', 'Updated dual-language content for key: mission', '2026-07-12 11:20:25'),
(29, 'admin', 'Updated dual-language content for key: goals', '2026-07-12 11:23:26'),
(30, 'admin', 'Updated dual-language content for key: services', '2026-07-12 12:08:14'),
(31, 'admin', 'Updated dual-language content for key: services', '2026-07-12 12:18:23'),
(32, 'admin', 'Updated dual-language content for key: goals', '2026-07-12 12:18:40'),
(33, 'admin', 'Updated dual-language content for key: services', '2026-07-12 12:25:17'),
(34, 'admin', 'Updated dual-language content for key: services', '2026-07-12 12:30:30'),
(35, 'admin', 'Updated dual-language content for key: mission', '2026-07-12 12:31:04'),
(36, 'admin', 'Updated dual-language content for key: vision', '2026-07-12 12:31:21'),
(37, 'admin', 'Updated dual-language content for key: resources', '2026-07-12 12:33:59'),
(38, 'admin', 'Updated dual-language content for key: values', '2026-07-12 12:35:00'),
(39, 'admin', 'Updated dual-language content for key: goals', '2026-07-12 12:36:39'),
(40, 'admin', 'Updated dual-language content for key: resources', '2026-07-12 12:37:51'),
(41, 'admin', 'Updated dual-language content for key: history', '2026-07-12 12:41:43'),
(42, 'admin', 'Updated dual-language content for key: mission', '2026-07-12 12:42:31'),
(43, 'admin', 'Updated dual-language content for key: vision', '2026-07-12 12:42:54'),
(44, 'admin', 'Updated dual-language content for key: vision', '2026-07-12 12:43:23'),
(45, 'admin', 'Updated dual-language content for key: values', '2026-07-12 12:44:48'),
(49, 'admin', 'User logged into application lifecycle registry.', '2026-07-13 09:50:40'),
(51, 'admin', 'User logged into application lifecycle registry.', '2026-07-13 11:18:29'),
(74, 'demo_user', 'User logged into application lifecycle registry.', '2026-08-17 08:52:49'),
(75, 'admin', 'User logged into application lifecycle registry.', '2026-08-17 10:19:26'),
(78, 'admin', 'User logged into application lifecycle registry.', '2026-08-19 14:13:27'),
(79, 'admin', 'Posted new news article tracking profile.', '2026-08-19 14:14:29'),
(80, 'admin', 'Deleted structural news item ID: 3', '2026-08-19 14:16:32'),
(82, 'admin', 'User logged into application lifecycle registry.', '2026-08-20 09:31:42'),
(83, 'admin', 'User logged into application lifecycle registry.', '2026-08-20 10:38:43'),
(84, 'admin', 'User logged into application lifecycle registry.', '2026-08-20 10:54:53'),
(86, 'admin', 'Updated Office HQ contact details.', '2026-08-20 11:47:53'),
(88, 'admin', 'Updated Office HQ contact details.', '2026-08-20 11:49:23'),
(90, 'admin', 'User logged into application lifecycle registry.', '2026-08-20 14:18:35'),
(91, 'admin', 'User logged into application lifecycle registry.', '2026-08-20 14:37:20'),
(92, 'admin', 'User logged into application lifecycle registry.', '2026-08-20 15:10:32'),
(93, 'admin', 'User logged into application lifecycle registry.', '2026-08-20 15:30:06'),
(94, 'admin', 'Updated Office HQ contact details.', '2026-08-20 15:30:27'),
(95, 'admin', 'Deleted message ID: 1', '2026-08-20 15:30:38'),
(96, 'admin', 'User logged into application lifecycle registry.', '2026-08-20 17:06:57'),
(97, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 10:05:45'),
(98, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 10:36:28'),
(99, 'admin', 'Added a new background photo to the Hero Slider: 1787308700_hero_photo-8.jpg', '2026-08-21 10:38:21'),
(100, 'admin', 'Added a new background photo to the Hero Slider: 1787308781_hero_img-3.avif', '2026-08-21 10:39:42'),
(101, 'admin', 'Added a new background photo to the Hero Slider: 1787308835_hero_img-4.avif', '2026-08-21 10:40:36'),
(102, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 10:52:01'),
(103, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 11:00:10'),
(104, 'admin', 'Added a new background photo to the Hero Slider: 1787310028_hero_1787308781_hero_img-3.avif', '2026-08-21 11:00:28'),
(105, 'admin', 'Deleted hero slider background photo ID: 3', '2026-08-21 11:00:38'),
(106, 'admin', 'Deleted hero slider background photo ID: 2', '2026-08-21 11:00:49'),
(107, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 11:08:56'),
(108, 'admin', 'Deleted hero slider background photo ID: 4', '2026-08-21 11:09:03'),
(109, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 11:16:55'),
(110, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 12:16:36'),
(111, 'admin', 'Submitted contact form message.', '2026-08-21 12:17:04'),
(112, 'admin', 'Submitted contact form message.', '2026-08-21 12:17:36'),
(113, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 14:03:13'),
(114, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 14:03:47'),
(115, 'demo', 'Updated account credentials from \'admin\' to \'demo\'.', '2026-08-21 14:39:58'),
(116, 'admin', 'Updated account credentials from \'demo\' to \'admin\'.', '2026-08-21 14:41:28'),
(117, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 14:41:38'),
(118, 'Demo', 'Updated account credentials from \'admin\' to \'Demo\'.', '2026-08-21 14:42:53'),
(119, 'admin', 'Updated account credentials from \'Demo\' to \'admin\'.', '2026-08-21 14:43:39'),
(120, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 14:43:47'),
(121, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 14:48:58'),
(122, 'Demo', 'Updated account credentials from \'admin\' to \'Demo\'.', '2026-08-21 14:49:24'),
(123, 'Demo', 'User logged into application lifecycle registry.', '2026-08-21 14:49:33'),
(124, 'admin', 'Updated account credentials from \'Demo\' to \'admin\'.', '2026-08-21 14:50:31'),
(126, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 15:36:09'),
(128, 'admin', 'Deleted gallery image ID: 8', '2026-08-21 16:46:03'),
(129, 'admin', 'Uploaded a new image to the gallery: 1787330874_photo-7.jpg', '2026-08-21 16:47:55'),
(132, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 16:50:47'),
(134, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 17:06:18'),
(135, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 17:20:45'),
(136, 'admin', 'Deleted gallery image ID: 3', '2026-08-21 17:20:53'),
(137, 'admin', 'Deleted gallery image ID: 1', '2026-08-21 17:20:58'),
(138, 'admin', 'Deleted gallery image ID: 6', '2026-08-21 17:21:06'),
(139, 'admin', 'Deleted gallery image ID: 7', '2026-08-21 17:21:16'),
(140, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 17:25:19'),
(141, 'admin', 'Deleted gallery image ID: 2', '2026-08-21 17:25:29'),
(142, 'admin', 'Deleted gallery image ID: 4', '2026-08-21 17:25:37'),
(143, 'admin', 'Deleted gallery image ID: 5', '2026-08-21 17:25:47'),
(144, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 17:26:59'),
(145, 'admin', 'Uploaded a new image to the gallery: 1787333273_1787330874_photo-7.jpg', '2026-08-21 17:27:54'),
(146, 'admin', 'Uploaded a new image to the gallery: 1787333337_1787333273_1787330874_photo-7.jpg', '2026-08-21 17:28:57'),
(147, 'admin', 'User logged into application lifecycle registry.', '2026-08-21 17:43:35'),
(148, 'admin', 'Uploaded a new image to the gallery: 1787334268_1787330874_photo-7.jpg', '2026-08-21 17:44:28'),
(149, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 07:38:37'),
(150, 'admin', 'Uploaded a new image to the gallery: 1787387547_lirase.jpg', '2026-08-22 08:32:28'),
(151, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 08:56:21'),
(152, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 09:36:41'),
(153, 'admin', 'Updated dual-language content for key: history', '2026-08-22 09:37:22'),
(154, 'admin', 'Updated dual-language content for key: values', '2026-08-22 09:37:40'),
(155, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 10:23:29'),
(156, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 10:24:42'),
(157, 'admin', 'Added a new background photo to the Hero Slider: 1787394305_hero_1787308781_hero_img-3.avif', '2026-08-22 10:25:05'),
(158, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 10:26:08'),
(159, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 10:31:17'),
(160, 'admin', 'Added a new background photo to the Hero Slider: 1787394696_hero_1787308835_hero_img-4.avif', '2026-08-22 10:31:36'),
(161, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 10:37:10'),
(162, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 10:37:55'),
(163, 'admin', 'Deleted hero slider background photo ID: 6', '2026-08-22 10:38:11'),
(164, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 10:38:55'),
(165, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 13:00:56'),
(166, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 13:11:02'),
(168, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 13:14:16'),
(169, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 13:15:02'),
(170, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 13:21:33'),
(172, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 13:35:16'),
(173, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 13:35:42'),
(174, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 13:42:16'),
(175, 'admin', 'User logged into application lifecycle registry.', '2026-08-22 14:04:05'),
(176, 'admin', 'User logged into application lifecycle registry.', '2026-08-23 12:25:32'),
(177, 'admin', 'User logged into application lifecycle registry.', '2026-08-24 06:52:52'),
(178, 'admin', 'User logged into application lifecycle registry.', '2026-08-24 06:54:42'),
(179, 'admin', 'User logged into application lifecycle registry.', '2026-08-24 06:56:02'),
(180, 'admin', 'User logged into application lifecycle registry.', '2026-08-24 07:58:02'),
(181, 'ls', 'Updated account credentials from \'admin\' to \'ls\'.', '2026-08-24 07:59:36'),
(182, 'ls', 'User logged into application lifecycle registry.', '2026-08-24 08:00:23'),
(183, 'ls', 'User logged into application lifecycle registry.', '2026-08-24 08:01:38'),
(184, 'ls', 'User logged into application lifecycle registry.', '2026-08-24 09:22:58'),
(185, 'ls', 'User logged into application lifecycle registry.', '2026-08-24 10:46:03'),
(186, 'ls', 'User logged into application lifecycle registry.', '2026-08-24 18:27:39'),
(187, 'ls', 'Updated Office HQ contact details.', '2026-08-24 18:29:35'),
(188, 'ls', 'Updated Office HQ contact details.', '2026-08-24 18:31:50'),
(189, 'ls', 'Updated Office HQ contact details.', '2026-08-24 18:32:21'),
(190, 'ls', 'User logged into application lifecycle registry.', '2026-08-25 07:53:39'),
(191, 'ls', 'Updated social media links.', '2026-08-25 07:54:06'),
(192, 'ls', 'Updated social media links.', '2026-08-25 07:55:17'),
(193, 'ls', 'User logged into application lifecycle registry.', '2026-08-26 08:39:52'),
(194, 'ls', 'Updated Office HQ contact details.', '2026-08-26 08:40:23'),
(195, 'ls', 'User logged into application lifecycle registry.', '2026-08-26 09:14:28'),
(196, 'ls', 'Updated social media links.', '2026-08-26 09:14:49'),
(197, 'ls', 'Updated social media links.', '2026-08-26 09:16:25');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(2, 'Democracy Beyene', 'beyenedemocracy@gmail.com', 'Hello', '2026-08-21 12:17:03'),
(3, 'Democracy Beyene', 'beyenedemocracy@gmail.com', 'welcome all-of-you!!', '2026-08-21 12:17:36');

-- --------------------------------------------------------

--
-- Table structure for table `heroes`
--

CREATE TABLE `heroes` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `profession` varchar(150) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `heroes`
--

INSERT INTO `heroes` (`id`, `name`, `profession`, `image_path`, `created_at`) VALUES
(3, 'Dase', 'CEX', 'Photos/hero_1787392197_3380.jpg', '2026-08-22 09:49:57'),
(4, 'hero', 'mmg', 'Photos/hero_1787392244_1122.jpg', '2026-08-22 09:50:44'),
(5, 'hero', 'mg', 'Photos/hero_1787392871_2801.jpg', '2026-08-22 10:01:11'),
(13, 'Ms Lirense Lemma', 'S/Directory', 'Photos/hero_1787563554_7679.jpg', '2026-08-24 09:25:54'),
(14, 'Ms Tereze Alemu', 'Directory', 'Photos/hero_1787563605_9300.jpg', '2026-08-24 09:26:45'),
(15, 'Demo', 'Developer', 'Photos/hero_1787568478_4275.jpg', '2026-08-24 10:47:58');

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `image_path`, `created_at`) VALUES
(1, 'photos/1787308700_hero_photo-8.jpg', '2026-08-21 10:38:20'),
(5, 'photos/1787394305_hero_1787308781_hero_img-3.avif', '2026-08-22 10:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_am` varchar(255) NOT NULL,
  `body_en` text NOT NULL,
  `body_am` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title_en`, `title_am`, `body_en`, `body_am`, `created_at`) VALUES
(4, 'Mission', 'ተልዕኮ', '\r\n\"Ensuring the equal participation and benefit of women in development, good governance, and democratic system building; protecting the rights and safety of children, and establishing gender equality.', '\r\n👉 \"የሴቶችና ሕፃናትን መብትና ተጠቃሚነት ማረጋገጥ:: የሴቶችን በልማት ፣ በመልካም አስተዳደርና በዲሞክራሲ ሥርዓት ግንባታ እኩል ተሳትፎና ተጠቃሚነትን ማረጋገጥ ፤ የህፃናትን መብትና ደህንነት ማስጠበቅና የፆታ እኩልነትን ማስፈን', '2026-08-19 14:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `office_settings`
--

CREATE TABLE `office_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office_settings`
--

INSERT INTO `office_settings` (`setting_key`, `setting_value`) VALUES
('hq_address', 'Soro-Bara, Hossana, Ethiopia'),
('hq_email', 'Hadiyazonewomen&ChildrenAffairs@gmail.com'),
('hq_phone', '0465552325');

-- --------------------------------------------------------

--
-- Table structure for table `site_content`
--

CREATE TABLE `site_content` (
  `content_key` varchar(50) NOT NULL,
  `content_en` text NOT NULL,
  `content_am` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_content`
--

INSERT INTO `site_content` (`content_key`, `content_en`, `content_am`) VALUES
('goals', '\"\r\nGoal 1👉 Protecting the rights and holistic well-being of women.\r\n\r\nGoal 2👉 Ensuring the participation and representation of women.\r\n\r\nGoal 3👉 Ensuring the benefit of women.\r\n\r\nGoal 4👉 Enhancing institutionalization by mainstreaming women and children\'s affairs into the plans of sector offices.\r\n\r\nGoal 5👉 Protecting the rights and holistic well-being of children.\r\n\r\nGoal 6👉 Ensuring the participation and representation of children.\r\n\r\nGoal 7👉  Ensuring the benefit of children.\r\n\r\nGoal 8👉  Increasing customer satisfaction and trust.\"', 'ግብ 1👉  የሴቶች መብትና ሁለንተናዊ ደህንነት ማስጠበቅ።\r\nግብ 2👉  የሴቶች ተሳትፎና ውክልና ማረጋገጥ ።\r\nግብ 3👉  የሴቶች ተጠቃሚነት ማረጋገጥ ።\r\nግብ 4👉  የሴ/ህ/ጉዳይ በሴ/መቤቶች ዕቅድ በማካተት ተቋማዊነት ማሳደግ ።\r\nግብ 5👉  የህፃናት መብትና ሁለንተናዊ ደህንነት ማስጠበቅ ።                                                                \r\nግብ 6👉  የህጻናት ተሳትፎና ውክልና ማረጋገጥ ።\r\nግብ 7👉  የህጻናት ተጠቃሚነትን ማረጋገጥ ።                                                             \r\nግብ 8👉  የተገልጋዮችን እርካታና አመኔታን ማሳደግ ።                                                               '),
('history', '1  Background and How it Began.\r\n\r\n👉 The department\'s existence is tied directly to the structural evolution of the **Ministry of Women and Children Affairs (MoWCA)** at the federal level and the establishment of the Hadiya Zone administration itself.\r\n\r\n👉 The Foundation (Post-1991):- Following the overthrow of the Derg regime in 1991, the Transitional Government of Ethiopia introduced ethnic federalism. \r\n👉 The Hadiya Zone was established as an administrative unit. Initially, women\'s affairs operated as a small \"Women’s Affairs Desk\" under the Chief Administrator’s office to handle basic social adjustments.\r\n👉 Structural Upgrades (2000s–2010s):- As the federal government institutionalized the Ministry of Women\'s Affairs (and later expanded it to include Children and Youth), regional bureaus followed suit. In the Southern Nations, Nationalities, and Peoples\' Region (SNNPR)—and now under the \'Central Ethiopia Regional State\'—the sector was upgraded to a full-fledged \'Bureau/Department\'.\r\n\r\n2  Core Mandates & Milestones.\r\n\r\n👉 The office was set up to transition women from traditional, marginalized roles into active community leaders. Key historical focus areas include:-.\r\n\r\n👉 Eradicating Harmful Traditional Practices (HTPs):-. \r\n👉 Historically, Hadiya Zone faced high rates of Female Genital Mutilation (FGM/C). The bureau, alongside international partners like UNFPA and UNICEF, established the famous **\"Male Engagement Forums\"** and Community Accountability Committees, dramatically lowering cutting rates.\r\n👉 Legal Protections:** Enforcing the revised family law, protecting children\'s rights, and establishing safe spaces or legal frameworks for survivors of Gender-Based Violence (GBV).\r\n\r\n3  Leadership History.\r\n👉 Government offices at the zonal level change leaders based on regional cabinet restructuring, political appointments, and structural merges (such as periods when Youth and Women\'s affairs were combined or separated). \r\n👉 The department is typically led by a **Department Head (Department Chief)** appointed by the Hadiya Zone Administration.\r\n\r\n👉 Note:- For the exact, comprehensive list of every director from the 1990s to 2026, you will need to consult the human resource archive directly at the Zonal Administrative Office in Hosaina.', '፩ የኋላ ታሪክ እና አመሰራረት።\r\n\r\n👉 የ\'ሀድያ ዞን ሴቶችና ህፃናት መምሪያ\' አወቃቀርና ታሪክ በቀጥታ ከፌደራል የሴቶችና ህፃናት ሚኒስቴር መዋቅር እና በ፲፱፻፹፫ (1991) ዓ.ም ማግስት ከተፈጠረው የሀድያ ዞን አስተዳደር እድገት ጋር የተያያዘ ነው።\r\n\r\n👉 ምስረታ (ከ፲፱፻፹፫ ዓ.ም በኋላ):- በሀገሪቱ የፌደራሊዝም መዋቅር ሲዘረጋ የሀድያ ዞን ራሱን ችሎ ተዋቀረ። በወቅቱ የሴቶች ጉዳይ በዋና አስተዳዳሪው ጽሕፈት ቤት ስር በጥቃቅን \"ዴስክ\" ደረጃ የጀመረ ሲሆን፣ ዋና ትኩረቱም አነስተኛ ማህበራዊ ድጋፎችን ማድረግ ነበረ።\r\n👉 የመዋቅር ማደግ (በ፳፻ዎቹ):- የፌደራል መንግስት የሴቶች፣ ህፃናትና ወጣቶች ጉዳይን በባለቤትነት የሚመራ ሚኒስቴር መሥሪያ ቤት ሲያቋቁም፣ በደቡብ ብሔር ብሔረሰቦችና ሕዝቦች ክልል (አሁን ደግሞ በ**ማዕከላዊ ኢትዮጵያ ክልል**) ስር የዞኑ መሥሪያ ቤት ወደ ሙሉ **መምሪያ** ደረጃ አድጎ የሰው ኃይልና በጀት ተመደበለት።\r\n\r\n፪  ዋና ዋና ተግባራት እና ታሪካዊ ስኬቶች።\r\n\r\n👉 መምሪያው የተቋቋመበት ዋና ዓላማ የሴቶችንና የህፃናትን መብት ማስከበርና ተጠቃሚነታቸውን ማረጋገጥ ነው።\r\n\r\n👉 ጎጂ ልማዳዊ ድርጊቶችን መዋጋት፦ በታሪክ በሀድያ ዞን በከፍተኛ ደረጃ ይፈጸም የነበረውን የሴቶች ግርዛት (FGM) ለመቀነስ መምሪያው ከዓለም አቀፍ ተቋማት (እንደ UNFPA) ጋር በመተባበር የወንዶች ተሳትፎ መድረኮችን (Male Engagement Forums) በመፍጠር ትልቅ ለውጥ አምጥቷል።\r\n👉 የህፃናት መብትና ደህንነት፦ በዞኑ ያሉ ህፃናት ከጉልበት ብዝበዛ እንዲጠበቁ እና የህግ ከለላ እንዲያገኙ መስራት።\r\n\r\n፫  የአመራር ታሪክ።\r\n\r\n👉 የመምሪያው መሪዎች (የመምሪያ ኃላፊዎች) በየወቅቱ በሚደረጉ የካቢኔ መዋቅር ለውጦች እና ሽግግሮች መሰረት በዞኑ አስተዳደር የሚሰየሙ ናቸው።\r\n\r\n👉 ማሳሰቢያ፦. ከ፲፱፻፹ዎቹ ጀምሮ እስከ አሁን (፳፻፲፰ ዓ.ም) ድረስ መምሪያውን በኃላፊነት የመሩትን ግለሰቦች ስም ዝርዝር እና የአገልግሎት ዘመን ሙሉ መዝገብ ለማግኘት በሆሳዕና ከተማ የሚገኘውን የዞኑን ዋና መስተዳድር የሰው ኃይል ማህደር (Archive) መመልከት ያስፈልጋል።\r\n'),
('mission', '👉 \"Ensuring the equal participation and benefit of women in development, good governance, and democratic system building; protecting the rights and safety of children, and establishing gender equality.\"', '👉 \"የሴቶችና ሕፃናትን መብትና ተጠቃሚነት ማረጋገጥ::\r\nየሴቶችን በልማት ፣ በመልካም አስተዳደርና በዲሞክራሲ ሥርዓት ግንባታ እኩል ተሳትፎና ተጠቃሚነትን ማረጋገጥ ፤ የህፃናትን መብትና ደህንነት ማስጠበቅና የፆታ እኩልነትን ማስፈን \"'),
('resources', '👉 The Hadiya Zone Bureau of Women and Children Affairs manages a comprehensive network of structural, financial, and educational resources dedicated to community development.\r\n👉 We provide institutional legal aid toolkits and legal management frameworks to support women and children facing vulnerabilities.\r\n👉 The bureau operates community-focused training facilities equipped with modern educational materials to foster economic self-reliance.\r\n👉 We track socio-economic data metrics and maintain documentation centers that provide essential research tools for gender equality initiatives.\r\n👉 Furthermore, our collaboration with regional stakeholders ensures the continuous distribution of emergency support packages and empowerment grants.', '👉 የሀዲያ ዞን ሴቶችና ህጻናት ጉዳይ ቢሮ ለማህበረሰብ ልማት የተመደቡ አጠቃላይ መዋቅራዊ፣ ፋይናንሳዊ እና ትምህርታዊ ግብዓቶችን ያስተዳድራል።\r\n👉 ተጋላጭ የሆኑ ሴቶችን እና ህጻናትን ለመደገፍ ተቋማዊ የህግ እገዛ መጫኛዎችን እና የህግ አስተዳደር ማዕቀፎችን እናቀርባለን።\r\n👉 ቢሮው ኢኮኖሚያዊ እራስን መቻልን ለማሳደግ ዘመናዊ የትምህርት ቁሳቁስ የተሟላላቸው ማህበረሰብ-ተኮር የስልጠና ተቋማትን ያንቀሳቅሳል።\r\n👉 ማህበራዊ-ኢኮኖሚያዊ የመረጃ መለኪያዎችን እንከታተላለን እንዲሁም ለስርዓተ-ፆታ እኩልነት ተነሳሽነት አስፈላጊ የምርምር መሳሪያዎችን የሚያቀርቡ የሰነድ ማዕከላትን እንጠብቃለን።\r\n👉 በተጨማሪም ከክልላዊ ባለድርሻ አካላት ጋር ያለን ትብብር የአደጋ ጊዜ ድጋፍ ፓኬጆችን እና የማጎልበቻ ድጎማዎችን የማቋረጥ ስርጭትን ያረጋግጣል።'),
('services', '1)  Providing assessment survey services to identify gaps regarding the level of economic, social, and political benefits of women and the protection of children\'s rights and safety, promoting study reports, and distributing them to relevant bodies.\r\n\r\n1,1)  Conducting capacity gap assessment and gender analysis studies.\r\n\r\n1,2)  Promoting study findings.\r\n\r\n1,3)  Disseminating study findings.\r\n\r\n2)  Providing information services by compiling data at the institutional level that helps increase the participation and benefit of women and children.\r\n\r\n3)  Providing awareness-raising services to the community and relevant bodies on laws and programs that enable women and children to participate and benefit.\r\n\r\n4)  Providing services to create favorable conditions for women to organize into structures they require to increase their participation and ensure their benefit.\r\n\r\n5) Providing services to prepare, disseminate, and promote/raise awareness about implementation frameworks/documents (manuals, memorandums of understanding, regulations, and directives) that enable stakeholder sector offices to mainstream women\'s affairs into their plans and programs.\r\n\r\n5,1)  Providing services to promote the prepared implementation/mainstreaming manuals and memorandums of understanding.\r\n\r\n6)  Providing services to design projects that benefit women and children, mobilize resources, and make them beneficiaries.\r\n\r\n7) Providing services to facilitate vocational and skill development training to enhance the economic capacity of women.\r\n\r\n7,1) Providing services to facilitate skill training delivered by external experts.\r\n7,2)  Facilitating Training of Trainers (ToT) for vocational skills.\r\n\r\n7,3)  Facilitating vocational skills training.\r\n\r\n8)  Providing capacity-building training/community dialogues, life skills, decision-making, understanding gender concepts, leadership, alternative child care guidelines, and Child Rights and Safety (CRC) services to enhance the capacity of women and children.\r\n\r\n8,1)  Providing services to prepare, sensitize, and distribute awareness-raising training documents, brochures, and booklets.\r\n\r\n8,2)  Providing awareness-raising training services.\r\n\r\n8,3) Providing services to prepare and distribute posters.\r\n\r\n8,4) Providing training services by preparing Training of Trainers (ToT) manuals for community dialogue, leadership, and life skills.\r\n\r\n9)  Providing capacity-building support services for women, children, and their organizational structures.\r\n\r\n10)  Providing services to introduce and distribute appropriate technologies that save women\'s time and labor.\r\n\r\n10,1) Providing services to promote the technology.\r\n10,2) Providing services to distribute the technology in coordination with other relevant stakeholder institutions.\r\n11) Providing legal counseling and support services for women whose rights have been violated.\r\n12) Providing services to relevant bodies by conducting participatory gender audits in sector offices to establish gender equality.\r\n13) Providing feedback to government and non-governmental organizations by identifying gaps from a gender perspective in their plans, projects, and programs.\r\n14)  Providing various training services to enhance the decision-making role of women.\r\n\r\n15) Facilitating conditions for orphaned and vulnerable children to receive support and care services.\r\n\r\n15,1) Services to reintegrate street children with their families/relatives.\r\n\r\n15,2) Facilitating conditions for children who have suffered abuse and need psychological support to access services at designated centers.\r\n\r\n15,3) Services to ensure they receive temporary family care / foster care / support.\r\n\r\n15,4)  Services to ensure they receive community-based support.\r\n\r\n15,5)  Domestic adoption execution services.\r\n\r\n15,6) Monitoring the effectiveness of the support and care program for orphaned and vulnerable children living in difficult conditions, providing professional support, and submitting timely reports/information to the relevant body.\r\n\r\n16) Providing services to prepare and facilitate various participation and mobilization platforms (forums, conferences, celebrations like March 8, White Ribbon Day, African and World Children\'s Day, exhibitions, and festivals) to broaden the scope of participation for women and children.\r\n\r\n17)  Providing services to establish and strengthen coordination and cooperation forums with stakeholders and conduct dialogue/consultation platforms to enhance the participation and benefit of women and children.\r\n18 Providing services to enable children to organize into clubs and parliaments to grow and develop their participation.\r\n19) Providing awareness-raising training for women to break free from harmful traditional practices.\r\n20) Providing services to conduct experience sharing among women and children affairs sectors within and outside the zone, scale up, and duplicate identified best practices to increase the participation and benefit of women and children.\r\n21) Providing monitoring, evaluation, support, and feedback services across sectors and women\'s organizations to increase the participation and benefit of women and children.', '1) የሴቶችን ኢኮኖሚዊ ፣ የማህበራዊና ፖለቲካዊ ተጠቃሚነት ያለበትን ደረጃና የህፃናትን መብትና ደህንነት በማስጠበቅ ዙሪያ የሚታዩ ክፍተቶችን ለመለየት የሚረዱ የዳሰሳ ጥናቶችን በማጥናት የጥናት ሪፖርቶችን ማስተዋወቅና ለሚመለከታቸው አካላት የማሰራጨት አገልግሎት መስጠት ።\r\n1,1)  የአቅም ክፍተት የዳሰሳና የስርዓተ ፆታ ልየታ ጥናት የማካሄድ።\r\n1,2)   የጥናት ውጤቶችን ማስተዋወቅ።\r\n1,3)   የጥናት ውጤቶችን ማሰራጨት።\r\n2)   ሴቶችና ህፃናትን በሚመለከት ተሳታፊነታቸውንና ተጠቃሚነታቸውን ለማሳደግ የሚረዱ መረጃዎችን በተቋሙ ደረጃ በማጠናቀር የመረጃ አገልግሎት መስጠት ።\r\n3)   ሴቶችንና ህፃናትን ተሳታፊና ተጠቃሚ ለማድረግ በሚያስችሉ ህጎች ፣ ፕሮግራሞች ዙሪያ የግንዛቤ ማስጨበጫ አገልግሎት ለህብረተሰቡና ለሚመለከታቸው አካላት መስጠት።\r\n4)   ሴቶች ተሳትፏቸውን ለማሳደግና ተጠቃሚነታቸውን ለማረጋገጥ በሚፈልጓቸው አደረጃጀቶች እንዲደራጁ ምቹ ሁኔታ የመፍጠር አገልግሎት መስጠት ።\r\n5)   የሴቶች ጉዳይ በባለድርሻ አካላት ሴ/መ/ቤቶች በዕቅዶቻቸውና በፕሮግራሞቻቸው እንዲያካትቱ ለማድረግ የሚያስችሉ የማስፈጸሚያ ማዕቀፎች/ሰነዶች (ማንዋሎች ፣ የመግባቢያ ሰነድ ፣ ደንብና መመሪያዎች) የማዘጋጀት ፣ የማሰራጨት እና የማስተዋወቅ/የማስገንዘብ አገልግሎት መስጠት።\r\n5,1)   የተዘጋጁ የማስፈጸሚያ/የማካተቻ ማንዋልና የመግባቢያ ሰነዶችን የማስተዋወቅ አገልግሎት መስጠት ።\r\n6)    ሴቶችንና ህፃናትን ተጠቃሚ የሚያደርግ ፕሮጀክት በመቅረጽ ሀብት በማሰባሰብ ተጠቃሚ እንዲሆኑ የማድረግ አገልግሎት መስጠት ።\r\n7)   የሴቶችን ኢኮኖሚያዊ አቅም ለማሳደግ የሙያና የክህሎት ማበልፀጊያ ስልጠና የማመቻቸት አገልግሎት መስጠት ።\r\n7,1)   በውጭ ባለሙያዎች የክህሎት ስልጠና እንዲሰጡ የማመቻቸት አገልግሎት መስጠት ።\r\n7,2)   የሙያ ክህሎት የአሰልጣኞች ስልጠና እንዲሰጥ ማድረግ ።\r\n7,3)   የሙያ ክህሎት ስልጠና እንዲሰጥ ማድረግ ።\r\n8)   የሴቶችንና የህፃናትን ብቃት ለማሳደግ የተለያዩ የአቅም ማጎልበቻ ስልጠና/የማህበረሰብ ውይይት ፣ የህይወት ክህሎት ፣ የውሳኔ ሰጪነት ፣ የስርዓተ ፆታ ምንነት የመሪነት ፣ በአማራጭ የህፃናት እንክብካቤ መመሪያና በህፃናት መብት ደህንነት (CRC) አገልግሎት መስጠት ።\r\n8,1)   የግንዛቤ ማስጨበጫ የስልጠና ሰነዶችን/ብሮሸሮችንና ቡክሌት በማዘጋጀት የማስገንዘብና የማሰራጨት አገልግሎት መስጠት ።\r\n8,2)   የግንዛቤ ማስጨበጫ ስልጠናዎችን የመስጠት አገልግሎት ።\r\n8,3)   ፖስተሮችን በማዘጋጀት የማሰራጨት አገልግሎት መስጠት ።\r\n8,4)   የአሰልጠኞች ስልጠና ማንዋሎችን/የማህበረሰብ ውይይት ፣ የመሪነት ፣ የህይወት ክህሎት በማዘጋጀት ስልጠና የመስጠት አገልግሎት ።\r\n9)   ለሴቶችና ህፃናት እንዲሁም ለአደረጃጀቶቻቸው አቅም ማጎልበቻ ድጋፍ የመስጠት አገልግሎት ።\r\n10)   የሴቶችን ጊዜና ጉልበት የሚቆጥቡ ተስማሚ ቴክኖሎጂዎችን በማስተዋወቅ የማሰራጨት አገልግሎት መስጠት ።\r\n10,1)   ቴክኖሎጂውን የማስተዋወቅ አገልግሎት መስጠት ።\r\n10,2 ቴክኖሎጂውን ከሚመለከታቸው ሌሎች ባለድርሻ ተቋማት ጋር በቅንጅት የማሰራጨት አገልግሎት መስጠት ።\r\n11)   የመብት ጥሰት ለደረሰባቸው ሴቶች የህግ ምክርና ድጋፍ አገ/ት መስጠት።\r\n12)   የስርዓተ-ፆታ እኩልነትን ለማስፈን በሴ/መ/ቤቶች አሳታፊ የስርዓተ-ፆታ ኦዲት በማድረግ አገልግሎቱን ለሚመለከታቸው አካላት የመስጠት አገልግሎት ።\r\n13)   መንግስታና መንግስታዊ ካልሆኑ ድርጅቶች ከዕቅዶቻቸው ። ከፕሮጀክቶቻቸውና ከፕሮግራሞቻቸው ከስርዓተ-ፆታ አንፃር ክፍተት በመለየት ግብረ-መልስ መስጠት ።\r\n14)   የሴቶች የውሳኔ ሰጪነት ሚና እንድጎለብት የተለያዩ ስልጠናዎችን የመስጠት አገልግሎት ።\r\n15)   ወላጆቻቸውን ያጡና ለችግር የተጋለጡ ህፃናት የድጋፍና እንክብካቤ አገልግሎት እንዲያገኙ ሁኔታዎችን ማመቻቸት ።\r\n15,1)    ወደ ጎዳና የወጡ ህፃናትን ከቤተሰቦቻቸው/ዘመዶቻቸው የማቀላቀል አገልግሎት ።\r\n15,2)   ጥቃት ደርሶባቸው ስነ-ልቦናዊ ድጋፍ ለሚያስፈልጋቸው ህፃናት አገልግሎቱን በማዕከላት የሚያገኙበትን ሁኔታ ማመቻቸት ።\r\n15,3)   ጊዜያዊ ቤተሰብ/ፎስተር ኬር/ድጋፍ እንዲያገኙ የማድረግ አገልግሎት ።\r\n15,4)   የማህበረሰብ አቀፍ ድጋፍ እንዲያገኙ የማድርግ አገልግሎት ።\r\n15,5)   የሀገር ውስጥ ጉድፈቻ የማስፈፀም አገልግሎት ።\r\n15,6)   ወላጆቻቸውን ያጡና በአስቸጋሪ የኑሮ ሁኔታ ውስጥ ላሉ ህፃናት የሚደረገውን ድጋፍና ክብካቤ ፕሮግራም ውጤታማነት መከታተል ፣ ሙያዊ ድጋፍ ማድረግና ወቅታዊ መረጃ ለሚመለከተው አካል ማቅረብ ።\r\n16)   የሴቶችንና የህፃናትን የተሳትፎ አድማስ ለማሳደግ የተለያዩ የተሳትፎና የንቅናቄ መድረኮችን/ፎረም ፣ ኮንፈረንስ ፣ ስነ በዓላት( ማርች 8 ፣ ነጭ ሪቫን ፣ የአፍሪካና የዓለም የህፃናት) ፣ አውደ-ራዕይና ፌስቲቫል/የማዘጋጀትና የማመቻቸት አገልግሎት መስጠት ።\r\n17)   የሴቶችንና የህፃናትን ተሳታፊነታቸውንና ተጠቃሚነታቸውን ለማጎልበት ከባለድርሻ አካላት ጋር የቅንጅትና የትብብር ፎረሞችን የማቋቋም ፣ የማጠናከርና የውይይት/ምክክር መድረክ የማካሄድ አገልግሎት መስጠት ።\r\n18)   ህፃናትን በክበባትና በፓርላማ ተደራጅተው ተሳትፏቸውን እንዲያሳድጉና እንዲያደብሩ የማድረግ አገልግሎት መስጠት ።\r\n19)   ሴቶች ከጎጂ ልማዳዊ ድርጊቶች እንድላቀቁ ግንዛቤ የማስጨበጥ ስልጠና መስጠት ።\r\n20)   የሴቶችንና የህፃናትን ተሳታፊነትንና ተጠቃሚነታትን ለማሳደግ በዞንና ከዞን ውጭ ባሉ የሴቶችና የህፃናት ጉዳይ ሴክተሮች የልምድ ልውውጥ የማድረግ ፣ የተገኙ ምርጥ ተሞክሮዎችን የመቀመርና የማስፋፋት አገልግሎት መስጠት ።\r\n21)   የሴቶችንና የህፃናትን ተሳታፊነትንና ተጠቃሚነታትን ለማሳደግ በሴክተሮችና በሴቶች አደረጃጀቶች የክትትል ፣ የግምገማ ፣ የድጋፍ ፣ የግብረ-መልስ አገልግሎት መስጠት ።'),
('values', '👉 Inclusiveness and Equity.\r\n👉 Transparency and Accountability.\r\n👉 Innovation.\r\n👉 Efficiency and Effectiveness.\r\n👉 Quality of Service.\r\n👉 Teamwork.', '👉 አሳታፊነትና ፍትሃዊነት ።                                                                 \r\n👉 ግልጽነትና ተጠያቂነት ።                                                              \r\n👉 ፈጠራ ።\r\n👉 ቅልጥፍናና ውጤታማነት ።\r\n👉 የአገልግሎት ጥራት ።\r\n👉 የቡድን ስራ ።'),
('vision', '👉 “To see a zone by 2022 where women\'s equal participation and benefit in economic, social, political, and cultural fields are ensured; the rights and safety of children are respected; children\'s good character is developed; and gender equality prevails.”', '👉 “በ2022 ሴቶች በኢኮኖሚ ፣ ማህበራዊ ፣ ፖለቲካዊና በባህል መስኮች እኩል ተሳትፎና ተጠቃሚነት የተረጋገጠባት ፣ የህፃናት መብትና ደህንነት የተከበረባት ፤ የህፃናት መልካም ስብዕና የጎለበተባት እና የስርዓተ- ፆታ እኩልነት የሰፈነባት ዞንን ማየት” ');

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `platform` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `social_links`
--

INSERT INTO `social_links` (`platform`, `url`) VALUES
('facebook', 'https://facebook.com'),
('instagram', 'https://www.instagram.com/p/DcdI0rdjC5H/?igsi=MXVkYzAxbHpjNXozZA=='),
('linkedin', 'https://linkedin.com'),
('telegram', 'https://t.me/+DTVJJJ96gwk3YTg0'),
('twitter', 'https://twitter.com'),
('whatsapp', 'https://wa.me/251118723297'),
('youtube', 'https://www.youtube.com/@HadiyaZonewomenChildrenAffairs');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'ls', '$2y$10$xNRbXiqOtd8BHEz35Bzx6.TAeW8VV/b8CHdQ4rE0JRXxp3cSrFnI2', 'admin'),
(2, 'demo_user', '$2y$10$g1k7n0X.WfB6uOnm.K1B1OnXgXU1K5B/S3p1lYkP01G/X1Admi123', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `heroes`
--
ALTER TABLE `heroes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office_settings`
--
ALTER TABLE `office_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `site_content`
--
ALTER TABLE `site_content`
  ADD PRIMARY KEY (`content_key`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`platform`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `heroes`
--
ALTER TABLE `heroes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
