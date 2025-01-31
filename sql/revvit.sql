-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 08:19 PM
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
-- Database: `revvit`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`id`, `name`, `description`, `icon`, `points`, `created_at`) VALUES
(1, 'First Steps', 'Complete your first quiz', 'first-steps.png', 10, '2024-12-04 04:25:47'),
(2, 'Quiz Master', 'Score 100% on any quiz', 'quiz-master.png', 50, '2024-12-04 04:25:47'),
(3, 'Course Explorer', 'Enroll in your first course', 'explorer.png', 20, '2024-12-04 04:25:47'),
(4, 'Community Contributor', 'Make your first community post', 'contributor.png', 30, '2024-12-04 04:25:47'),
(5, 'Profile Pioneer', 'Complete your profile information', 'pioneer.png', 15, '2024-12-04 04:25:47'),
(6, 'Knowledge Seeker', 'Complete 5 different quizzes', 'seeker.png', 40, '2024-12-04 04:25:47'),
(7, 'Course Champion', 'Complete 3 courses', 'champion.png', 100, '2024-12-04 04:25:47'),
(8, 'Active Learner', 'Log in for 7 consecutive days', 'active.png', 25, '2024-12-04 04:25:47');

-- --------------------------------------------------------

--
-- Table structure for table `community_comments`
--

CREATE TABLE `community_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_comments`
--

INSERT INTO `community_comments` (`id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(5, 19, 5, 'qqqqqqqqqqqqqqqqq', '2024-12-04 07:37:22'),
(6, 19, 6, 'sddfsfdsdf', '2024-12-04 07:39:31'),
(8, 20, 5, 'KRazy', '2024-12-09 02:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` enum('active','hidden','deleted') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`id`, `user_id`, `subject_id`, `title`, `content`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Help with PHP Arrays', 'Can someone explain how associative arrays work in PHP?', 'active', '2024-12-04 04:25:47', '2024-12-04 04:25:47'),
(2, 3, 1, 'PHP Best Practices', 'What are some PHP coding best practices you follow?', 'active', '2024-12-04 04:25:47', '2024-12-04 04:25:47'),
(3, 2, 2, 'MySQL Query Optimization', 'Tips for optimizing MySQL queries for better performance', 'active', '2024-12-04 04:25:47', '2024-12-04 04:25:47'),
(4, 3, 3, 'Web Development Tools', 'What are your favorite web development tools?', 'active', '2024-12-04 04:25:47', '2024-12-04 04:25:47'),
(5, 1, 9, '', 'nbmvnbm', 'active', '2024-12-04 04:44:33', '2024-12-04 04:44:33'),
(6, 1, 9, '', 'asdasdasdasd', 'active', '2024-12-04 04:44:38', '2024-12-04 04:44:38'),
(7, 1, 1, '', 'asdsad', 'active', '2024-12-04 04:46:24', '2024-12-04 04:46:24'),
(8, 1, 10, '', 'asdasd', 'active', '2024-12-04 04:46:33', '2024-12-04 04:46:33'),
(9, 1, 11, '', 'sDvsdf', 'active', '2024-12-04 04:49:34', '2024-12-04 04:49:34'),
(10, 1, 1, '', 'asdasd', 'active', '2024-12-04 04:49:37', '2024-12-04 04:49:37'),
(11, 1, 1, '', 'asdasd', 'active', '2024-12-04 04:49:48', '2024-12-04 04:49:48'),
(12, 1, 1, '', 'asdasd', 'active', '2024-12-04 04:49:50', '2024-12-04 04:49:50'),
(13, 1, 8, '', 'hehe', 'active', '2024-12-04 05:07:09', '2024-12-04 05:07:09'),
(14, 1, 10, '', 'asdasd', 'active', '2024-12-04 05:07:14', '2024-12-04 05:07:14'),
(19, 5, 9, '', 'qqqqqqqqqqqqqqqqqqqqqqqqqqqq', 'active', '2024-12-04 07:37:18', '2024-12-04 07:37:18'),
(20, 5, 953, '', 'asasd', 'active', '2024-12-09 02:23:16', '2024-12-09 02:23:16');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_likes`
--

CREATE TABLE `community_post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_post_likes`
--

INSERT INTO `community_post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(5, 19, 6, '2024-12-04 07:39:33');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `difficulty_level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `subject_id`, `name`, `description`, `difficulty_level`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(2, 1, 'PHP Control Structures', 'Master PHP control structures and loops', 'beginner', 'published', '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(3, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(4, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(5, 4, 'JavaScript Essentials', 'Core JavaScript concepts and syntax', 'beginner', 'published', '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(6, 5, 'HTML5 and CSS3', 'Modern web design with HTML5 and CSS3', 'beginner', 'published', '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(7, 6, 'Object-Oriented PHP', 'Learn OOP concepts in PHP', 'intermediate', 'published', '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(8, 7, 'Database Normalization', 'Understanding database normalization', 'intermediate', 'published', '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(9, 8, 'Security Best Practices', 'Web security fundamentals and best practices', 'intermediate', 'published', '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(10, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:39:19', '2024-12-04 04:39:19'),
(11, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:39:19', '2024-12-04 04:39:19'),
(12, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:39:19', '2024-12-04 04:39:19'),
(13, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:45:48', '2024-12-04 04:45:48'),
(14, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:45:48', '2024-12-04 04:45:48'),
(15, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:45:49', '2024-12-04 04:45:49'),
(16, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:46:01', '2024-12-04 04:46:01'),
(17, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:46:02', '2024-12-04 04:46:02'),
(18, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:46:02', '2024-12-04 04:46:02'),
(19, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:46:03', '2024-12-04 04:46:03'),
(20, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:46:03', '2024-12-04 04:46:03'),
(21, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:46:03', '2024-12-04 04:46:03'),
(22, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:46:11', '2024-12-04 04:46:11'),
(23, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:46:11', '2024-12-04 04:46:11'),
(24, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:46:11', '2024-12-04 04:46:11'),
(25, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:49:58', '2024-12-04 04:49:58'),
(26, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:49:58', '2024-12-04 04:49:58'),
(27, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:49:58', '2024-12-04 04:49:58'),
(28, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:50:17', '2024-12-04 04:50:17'),
(29, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:50:17', '2024-12-04 04:50:17'),
(30, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:50:17', '2024-12-04 04:50:17'),
(31, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:50:43', '2024-12-04 04:50:43'),
(32, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:50:43', '2024-12-04 04:50:43'),
(33, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:50:43', '2024-12-04 04:50:43'),
(34, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:50:44', '2024-12-04 04:50:44'),
(35, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:50:44', '2024-12-04 04:50:44'),
(36, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:50:44', '2024-12-04 04:50:44'),
(37, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:50:46', '2024-12-04 04:50:46'),
(38, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:50:46', '2024-12-04 04:50:46'),
(39, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:50:46', '2024-12-04 04:50:46'),
(40, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:50:54', '2024-12-04 04:50:54'),
(41, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:50:54', '2024-12-04 04:50:54'),
(42, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:50:54', '2024-12-04 04:50:54'),
(43, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:51:38', '2024-12-04 04:51:38'),
(44, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:51:38', '2024-12-04 04:51:38'),
(45, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:51:38', '2024-12-04 04:51:38'),
(46, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:51:49', '2024-12-04 04:51:49'),
(47, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:51:49', '2024-12-04 04:51:49'),
(48, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:51:49', '2024-12-04 04:51:49'),
(49, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:52:33', '2024-12-04 04:52:33'),
(50, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:52:33', '2024-12-04 04:52:33'),
(51, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:52:34', '2024-12-04 04:52:34'),
(52, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:52:35', '2024-12-04 04:52:35'),
(53, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:52:35', '2024-12-04 04:52:35'),
(54, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:52:35', '2024-12-04 04:52:35'),
(55, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:53:45', '2024-12-04 04:53:45'),
(56, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:53:46', '2024-12-04 04:53:46'),
(57, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:53:46', '2024-12-04 04:53:46'),
(58, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:53:47', '2024-12-04 04:53:47'),
(59, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:53:47', '2024-12-04 04:53:47'),
(60, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:53:47', '2024-12-04 04:53:47'),
(61, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:53:51', '2024-12-04 04:53:51'),
(62, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:53:51', '2024-12-04 04:53:51'),
(63, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:53:51', '2024-12-04 04:53:51'),
(64, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:53:54', '2024-12-04 04:53:54'),
(65, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:53:54', '2024-12-04 04:53:54'),
(66, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:53:54', '2024-12-04 04:53:54'),
(67, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:17', '2024-12-04 04:54:17'),
(68, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:17', '2024-12-04 04:54:17'),
(69, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:17', '2024-12-04 04:54:17'),
(70, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:21', '2024-12-04 04:54:21'),
(71, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:21', '2024-12-04 04:54:21'),
(72, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:21', '2024-12-04 04:54:21'),
(73, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:23', '2024-12-04 04:54:23'),
(74, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:23', '2024-12-04 04:54:23'),
(75, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:23', '2024-12-04 04:54:23'),
(76, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:25', '2024-12-04 04:54:25'),
(77, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:25', '2024-12-04 04:54:25'),
(78, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:25', '2024-12-04 04:54:25'),
(79, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:27', '2024-12-04 04:54:27'),
(80, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:27', '2024-12-04 04:54:27'),
(81, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:27', '2024-12-04 04:54:27'),
(82, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:29', '2024-12-04 04:54:29'),
(83, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:29', '2024-12-04 04:54:29'),
(84, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:29', '2024-12-04 04:54:29'),
(85, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:31', '2024-12-04 04:54:31'),
(86, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:31', '2024-12-04 04:54:31'),
(87, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:31', '2024-12-04 04:54:31'),
(88, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:33', '2024-12-04 04:54:33'),
(89, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:33', '2024-12-04 04:54:33'),
(90, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:33', '2024-12-04 04:54:33'),
(91, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:35', '2024-12-04 04:54:35'),
(92, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:36', '2024-12-04 04:54:36'),
(93, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:36', '2024-12-04 04:54:36'),
(94, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:38', '2024-12-04 04:54:38'),
(95, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:38', '2024-12-04 04:54:38'),
(96, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:38', '2024-12-04 04:54:38'),
(97, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:41', '2024-12-04 04:54:41'),
(98, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:41', '2024-12-04 04:54:41'),
(99, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:41', '2024-12-04 04:54:41'),
(100, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:54:43', '2024-12-04 04:54:43'),
(101, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:54:43', '2024-12-04 04:54:43'),
(102, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:54:43', '2024-12-04 04:54:43'),
(103, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:55:59', '2024-12-04 04:55:59'),
(104, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:55:59', '2024-12-04 04:55:59'),
(105, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:55:59', '2024-12-04 04:55:59'),
(106, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:56:03', '2024-12-04 04:56:03'),
(107, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:56:03', '2024-12-04 04:56:03'),
(108, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:56:03', '2024-12-04 04:56:03'),
(109, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:57:26', '2024-12-04 04:57:26'),
(110, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:57:26', '2024-12-04 04:57:26'),
(111, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:57:27', '2024-12-04 04:57:27'),
(112, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:57:29', '2024-12-04 04:57:29'),
(113, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:57:29', '2024-12-04 04:57:29'),
(114, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:57:29', '2024-12-04 04:57:29'),
(115, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:57:34', '2024-12-04 04:57:34'),
(116, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:57:34', '2024-12-04 04:57:34'),
(117, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:57:35', '2024-12-04 04:57:35'),
(118, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:58:59', '2024-12-04 04:58:59'),
(119, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:58:59', '2024-12-04 04:58:59'),
(120, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:58:59', '2024-12-04 04:58:59'),
(121, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:59:00', '2024-12-04 04:59:00'),
(122, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:59:00', '2024-12-04 04:59:00'),
(123, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:59:00', '2024-12-04 04:59:00'),
(124, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:59:03', '2024-12-04 04:59:03'),
(125, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:59:03', '2024-12-04 04:59:03'),
(126, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:59:03', '2024-12-04 04:59:03'),
(127, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 04:59:07', '2024-12-04 04:59:07'),
(128, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 04:59:07', '2024-12-04 04:59:07'),
(129, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 04:59:07', '2024-12-04 04:59:07'),
(130, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:01:35', '2024-12-04 05:01:35'),
(131, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:01:35', '2024-12-04 05:01:35'),
(132, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:01:35', '2024-12-04 05:01:35'),
(133, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:01:38', '2024-12-04 05:01:38'),
(134, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:01:38', '2024-12-04 05:01:38'),
(135, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:01:38', '2024-12-04 05:01:38'),
(136, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:01:42', '2024-12-04 05:01:42'),
(137, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:01:42', '2024-12-04 05:01:42'),
(138, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:01:42', '2024-12-04 05:01:42'),
(139, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:01:52', '2024-12-04 05:01:52'),
(140, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:01:52', '2024-12-04 05:01:52'),
(141, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:01:53', '2024-12-04 05:01:53'),
(142, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:01:56', '2024-12-04 05:01:56'),
(143, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:01:56', '2024-12-04 05:01:56'),
(144, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:01:56', '2024-12-04 05:01:56'),
(145, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:01:58', '2024-12-04 05:01:58'),
(146, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:01:58', '2024-12-04 05:01:58'),
(147, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:01:58', '2024-12-04 05:01:58'),
(148, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:02:20', '2024-12-04 05:02:20'),
(149, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:02:20', '2024-12-04 05:02:20'),
(150, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:02:20', '2024-12-04 05:02:20'),
(151, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:03:33', '2024-12-04 05:03:33'),
(152, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:03:33', '2024-12-04 05:03:33'),
(153, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:03:33', '2024-12-04 05:03:33'),
(154, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:03:35', '2024-12-04 05:03:35'),
(155, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:03:35', '2024-12-04 05:03:35'),
(156, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:03:35', '2024-12-04 05:03:35'),
(157, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:03:37', '2024-12-04 05:03:37'),
(158, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:03:37', '2024-12-04 05:03:37'),
(159, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:03:38', '2024-12-04 05:03:38'),
(160, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:05:03', '2024-12-04 05:05:03'),
(161, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:05:03', '2024-12-04 05:05:03'),
(162, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:05:03', '2024-12-04 05:05:03'),
(163, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:05:05', '2024-12-04 05:05:05'),
(164, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:05:05', '2024-12-04 05:05:05'),
(165, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:05:05', '2024-12-04 05:05:05'),
(166, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:05:08', '2024-12-04 05:05:08'),
(167, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:05:08', '2024-12-04 05:05:08'),
(168, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:05:08', '2024-12-04 05:05:08'),
(169, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:05:12', '2024-12-04 05:05:12'),
(170, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:05:12', '2024-12-04 05:05:12'),
(171, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:05:12', '2024-12-04 05:05:12'),
(172, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:05:54', '2024-12-04 05:05:54'),
(173, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:05:54', '2024-12-04 05:05:54'),
(174, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:05:54', '2024-12-04 05:05:54'),
(175, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:05:58', '2024-12-04 05:05:58'),
(176, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:05:58', '2024-12-04 05:05:58'),
(177, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:05:58', '2024-12-04 05:05:58'),
(178, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:06:04', '2024-12-04 05:06:04'),
(179, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:06:04', '2024-12-04 05:06:04'),
(180, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:06:04', '2024-12-04 05:06:04'),
(181, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:06:09', '2024-12-04 05:06:09'),
(182, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:06:09', '2024-12-04 05:06:09'),
(183, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:06:09', '2024-12-04 05:06:09'),
(184, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:06:12', '2024-12-04 05:06:12'),
(185, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:06:12', '2024-12-04 05:06:12'),
(186, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:06:12', '2024-12-04 05:06:12'),
(187, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:06:57', '2024-12-04 05:06:57'),
(188, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:06:57', '2024-12-04 05:06:57'),
(189, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:06:57', '2024-12-04 05:06:57'),
(190, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:07:00', '2024-12-04 05:07:00'),
(191, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:07:00', '2024-12-04 05:07:00'),
(192, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:07:00', '2024-12-04 05:07:00'),
(193, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:10:19', '2024-12-04 05:10:19'),
(194, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:10:19', '2024-12-04 05:10:19'),
(195, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:10:19', '2024-12-04 05:10:19'),
(196, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:10:22', '2024-12-04 05:10:22'),
(197, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:10:22', '2024-12-04 05:10:22'),
(198, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:10:22', '2024-12-04 05:10:22'),
(199, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:12:28', '2024-12-04 05:12:28'),
(200, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:12:28', '2024-12-04 05:12:28'),
(201, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:12:28', '2024-12-04 05:12:28'),
(202, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:14:19', '2024-12-04 05:14:19'),
(203, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:14:19', '2024-12-04 05:14:19'),
(204, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:14:19', '2024-12-04 05:14:19'),
(205, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:17:50', '2024-12-04 05:17:50'),
(206, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:17:50', '2024-12-04 05:17:50'),
(207, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:17:50', '2024-12-04 05:17:50'),
(208, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:17:52', '2024-12-04 05:17:52'),
(209, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:17:52', '2024-12-04 05:17:52'),
(210, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:17:52', '2024-12-04 05:17:52'),
(211, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:17:55', '2024-12-04 05:17:55'),
(212, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:17:55', '2024-12-04 05:17:55'),
(213, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:17:55', '2024-12-04 05:17:55'),
(214, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:18:39', '2024-12-04 05:18:39'),
(215, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:18:39', '2024-12-04 05:18:39'),
(216, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:18:39', '2024-12-04 05:18:39'),
(217, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:21:17', '2024-12-04 05:21:17'),
(218, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:21:18', '2024-12-04 05:21:18'),
(219, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:21:18', '2024-12-04 05:21:18'),
(220, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:21:36', '2024-12-04 05:21:36'),
(221, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:21:36', '2024-12-04 05:21:36'),
(222, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:21:36', '2024-12-04 05:21:36'),
(223, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:22:58', '2024-12-04 05:22:58'),
(224, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:22:58', '2024-12-04 05:22:58'),
(225, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:22:58', '2024-12-04 05:22:58'),
(226, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:23:00', '2024-12-04 05:23:00'),
(227, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:23:00', '2024-12-04 05:23:00'),
(228, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:23:00', '2024-12-04 05:23:00'),
(229, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:23:13', '2024-12-04 05:23:13'),
(230, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:23:13', '2024-12-04 05:23:13'),
(231, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:23:14', '2024-12-04 05:23:14'),
(232, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:25:10', '2024-12-04 05:25:10'),
(233, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:25:10', '2024-12-04 05:25:10'),
(234, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:25:10', '2024-12-04 05:25:10'),
(235, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:27:05', '2024-12-04 05:27:05'),
(236, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:27:05', '2024-12-04 05:27:05'),
(237, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:27:05', '2024-12-04 05:27:05'),
(238, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:27:10', '2024-12-04 05:27:10'),
(239, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:27:10', '2024-12-04 05:27:10'),
(240, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:27:10', '2024-12-04 05:27:10'),
(241, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:28:52', '2024-12-04 05:28:52'),
(242, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:28:52', '2024-12-04 05:28:52'),
(243, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:28:52', '2024-12-04 05:28:52'),
(244, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:29:27', '2024-12-04 05:29:27'),
(245, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:29:27', '2024-12-04 05:29:27'),
(246, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:29:27', '2024-12-04 05:29:27'),
(247, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:29:33', '2024-12-04 05:29:33'),
(248, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:29:33', '2024-12-04 05:29:33'),
(249, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:29:33', '2024-12-04 05:29:33'),
(250, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:29:35', '2024-12-04 05:29:35'),
(251, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:29:35', '2024-12-04 05:29:35'),
(252, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:29:35', '2024-12-04 05:29:35'),
(253, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:29:38', '2024-12-04 05:29:38'),
(254, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:29:38', '2024-12-04 05:29:38'),
(255, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:29:38', '2024-12-04 05:29:38'),
(256, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:29:39', '2024-12-04 05:29:39'),
(257, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:29:39', '2024-12-04 05:29:39'),
(258, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:29:39', '2024-12-04 05:29:39'),
(259, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:31:45', '2024-12-04 05:31:45'),
(260, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:31:45', '2024-12-04 05:31:45'),
(261, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:31:45', '2024-12-04 05:31:45'),
(262, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:31:50', '2024-12-04 05:31:50'),
(263, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:31:50', '2024-12-04 05:31:50'),
(264, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:31:50', '2024-12-04 05:31:50'),
(265, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:31:54', '2024-12-04 05:31:54'),
(266, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:31:54', '2024-12-04 05:31:54'),
(267, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:31:54', '2024-12-04 05:31:54'),
(268, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:44:47', '2024-12-04 05:44:47'),
(269, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:44:47', '2024-12-04 05:44:47'),
(270, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:44:47', '2024-12-04 05:44:47'),
(271, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:44:56', '2024-12-04 05:44:56'),
(272, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:44:56', '2024-12-04 05:44:56'),
(273, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:44:56', '2024-12-04 05:44:56'),
(274, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:44:59', '2024-12-04 05:44:59'),
(275, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:44:59', '2024-12-04 05:44:59'),
(276, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:44:59', '2024-12-04 05:44:59'),
(277, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:47:12', '2024-12-04 05:47:12'),
(278, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:47:12', '2024-12-04 05:47:12'),
(279, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:47:12', '2024-12-04 05:47:12'),
(280, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:48:32', '2024-12-04 05:48:32'),
(281, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:48:32', '2024-12-04 05:48:32'),
(282, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:48:32', '2024-12-04 05:48:32'),
(283, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:48:34', '2024-12-04 05:48:34'),
(284, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:48:35', '2024-12-04 05:48:35'),
(285, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:48:35', '2024-12-04 05:48:35'),
(286, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:56:28', '2024-12-04 05:56:28'),
(287, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:56:28', '2024-12-04 05:56:28'),
(288, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:56:28', '2024-12-04 05:56:28'),
(289, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:59:56', '2024-12-04 05:59:56'),
(290, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:59:56', '2024-12-04 05:59:56'),
(291, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:59:56', '2024-12-04 05:59:56'),
(292, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 05:59:58', '2024-12-04 05:59:58'),
(293, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 05:59:59', '2024-12-04 05:59:59'),
(294, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 05:59:59', '2024-12-04 05:59:59'),
(295, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 06:02:03', '2024-12-04 06:02:03'),
(296, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 06:02:03', '2024-12-04 06:02:03'),
(297, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 06:02:03', '2024-12-04 06:02:03'),
(298, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 06:02:05', '2024-12-04 06:02:05'),
(299, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 06:02:05', '2024-12-04 06:02:05'),
(300, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 06:02:06', '2024-12-04 06:02:06'),
(301, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 06:03:09', '2024-12-04 06:03:09'),
(302, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 06:03:09', '2024-12-04 06:03:09'),
(303, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 06:03:09', '2024-12-04 06:03:09'),
(304, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 06:03:12', '2024-12-04 06:03:12'),
(305, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 06:03:12', '2024-12-04 06:03:12'),
(306, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 06:03:12', '2024-12-04 06:03:12'),
(307, 1, 'Introduction to PHP', 'Learn the basics of PHP programming language', 'beginner', 'published', '2024-12-04 06:03:14', '2024-12-04 06:03:14'),
(308, 2, 'MySQL Fundamentals', 'Introduction to MySQL database management', 'beginner', 'published', '2024-12-04 06:03:14', '2024-12-04 06:03:14'),
(309, 3, 'Web Development Basics', 'Learn the basics of web development', 'beginner', 'published', '2024-12-04 06:03:14', '2024-12-04 06:03:14'),
(310, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:15:13', '2024-12-04 06:15:13'),
(311, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:15:13', '2024-12-04 06:15:13'),
(312, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:15:13', '2024-12-04 06:15:13'),
(313, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:15:13', '2024-12-04 06:15:13'),
(314, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:15:13', '2024-12-04 06:15:13'),
(315, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:16:06', '2024-12-04 06:16:06'),
(316, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:16:06', '2024-12-04 06:16:06'),
(317, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:16:06', '2024-12-04 06:16:06'),
(318, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:16:06', '2024-12-04 06:16:06'),
(319, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:16:06', '2024-12-04 06:16:06'),
(320, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:16:48', '2024-12-04 06:16:48'),
(321, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:16:48', '2024-12-04 06:16:48'),
(322, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:16:48', '2024-12-04 06:16:48'),
(323, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:16:48', '2024-12-04 06:16:48'),
(324, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:16:48', '2024-12-04 06:16:48'),
(325, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:17:45', '2024-12-04 06:17:45'),
(326, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:17:45', '2024-12-04 06:17:45'),
(327, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:17:45', '2024-12-04 06:17:45'),
(328, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:17:45', '2024-12-04 06:17:45'),
(329, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:17:45', '2024-12-04 06:17:45'),
(330, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:17:52', '2024-12-04 06:17:52'),
(331, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:17:52', '2024-12-04 06:17:52'),
(332, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:17:52', '2024-12-04 06:17:52'),
(333, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:17:52', '2024-12-04 06:17:52'),
(334, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:17:52', '2024-12-04 06:17:52'),
(335, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:17:53', '2024-12-04 06:17:53'),
(336, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:17:53', '2024-12-04 06:17:53'),
(337, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:17:53', '2024-12-04 06:17:53'),
(338, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:17:53', '2024-12-04 06:17:53'),
(339, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:17:53', '2024-12-04 06:17:53'),
(340, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:19:15', '2024-12-04 06:19:15'),
(341, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:19:15', '2024-12-04 06:19:15'),
(342, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:19:15', '2024-12-04 06:19:15'),
(343, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:19:15', '2024-12-04 06:19:15'),
(344, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:19:15', '2024-12-04 06:19:15'),
(345, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:19:16', '2024-12-04 06:19:16'),
(346, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:19:16', '2024-12-04 06:19:16'),
(347, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:19:16', '2024-12-04 06:19:16'),
(348, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:19:17', '2024-12-04 06:19:17'),
(349, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:19:17', '2024-12-04 06:19:17'),
(350, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:19:58', '2024-12-04 06:19:58');
INSERT INTO `courses` (`id`, `subject_id`, `name`, `description`, `difficulty_level`, `status`, `created_at`, `updated_at`) VALUES
(351, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:19:58', '2024-12-04 06:19:58'),
(352, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:19:58', '2024-12-04 06:19:58'),
(353, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:19:58', '2024-12-04 06:19:58'),
(354, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:19:58', '2024-12-04 06:19:58'),
(355, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:19:59', '2024-12-04 06:19:59'),
(356, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:19:59', '2024-12-04 06:19:59'),
(357, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:19:59', '2024-12-04 06:19:59'),
(358, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:19:59', '2024-12-04 06:19:59'),
(359, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:19:59', '2024-12-04 06:19:59'),
(360, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:20:11', '2024-12-04 06:20:11'),
(361, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:20:11', '2024-12-04 06:20:11'),
(362, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:20:11', '2024-12-04 06:20:11'),
(363, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:20:11', '2024-12-04 06:20:11'),
(364, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:20:11', '2024-12-04 06:20:11'),
(365, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:20:12', '2024-12-04 06:20:12'),
(366, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:20:12', '2024-12-04 06:20:12'),
(367, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:20:12', '2024-12-04 06:20:12'),
(368, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:20:12', '2024-12-04 06:20:12'),
(369, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:20:12', '2024-12-04 06:20:12'),
(370, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:20:14', '2024-12-04 06:20:14'),
(371, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:20:14', '2024-12-04 06:20:14'),
(372, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:20:14', '2024-12-04 06:20:14'),
(373, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:20:14', '2024-12-04 06:20:14'),
(374, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:20:14', '2024-12-04 06:20:14'),
(375, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:23:52', '2024-12-04 06:23:52'),
(376, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:23:52', '2024-12-04 06:23:52'),
(377, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:23:52', '2024-12-04 06:23:52'),
(378, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:23:53', '2024-12-04 06:23:53'),
(379, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:23:53', '2024-12-04 06:23:53'),
(380, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:23:53', '2024-12-04 06:23:53'),
(381, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:23:53', '2024-12-04 06:23:53'),
(382, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:23:53', '2024-12-04 06:23:53'),
(383, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:23:53', '2024-12-04 06:23:53'),
(384, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:23:53', '2024-12-04 06:23:53'),
(385, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:24:15', '2024-12-04 06:24:15'),
(386, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:24:15', '2024-12-04 06:24:15'),
(387, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:24:16', '2024-12-04 06:24:16'),
(388, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:24:16', '2024-12-04 06:24:16'),
(389, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:24:16', '2024-12-04 06:24:16'),
(390, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:24:16', '2024-12-04 06:24:16'),
(391, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:24:16', '2024-12-04 06:24:16'),
(392, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:24:16', '2024-12-04 06:24:16'),
(393, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:24:16', '2024-12-04 06:24:16'),
(394, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:24:16', '2024-12-04 06:24:16'),
(395, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:42:48', '2024-12-04 06:42:48'),
(396, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:42:49', '2024-12-04 06:42:49'),
(397, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:42:49', '2024-12-04 06:42:49'),
(398, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:42:49', '2024-12-04 06:42:49'),
(399, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:42:49', '2024-12-04 06:42:49'),
(400, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:42:49', '2024-12-04 06:42:49'),
(401, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:42:49', '2024-12-04 06:42:49'),
(402, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:42:49', '2024-12-04 06:42:49'),
(403, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:42:49', '2024-12-04 06:42:49'),
(404, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:42:49', '2024-12-04 06:42:49'),
(405, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:42:58', '2024-12-04 06:42:58'),
(406, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:42:58', '2024-12-04 06:42:58'),
(407, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:42:58', '2024-12-04 06:42:58'),
(408, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:42:58', '2024-12-04 06:42:58'),
(409, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:42:58', '2024-12-04 06:42:58'),
(410, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:42:59', '2024-12-04 06:42:59'),
(411, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:42:59', '2024-12-04 06:42:59'),
(412, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:42:59', '2024-12-04 06:42:59'),
(413, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:42:59', '2024-12-04 06:42:59'),
(414, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:42:59', '2024-12-04 06:42:59'),
(415, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(416, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(417, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(418, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(419, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(420, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(421, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(422, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(423, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(424, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:45:24', '2024-12-04 06:45:24'),
(425, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:45:31', '2024-12-04 06:45:31'),
(426, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:45:31', '2024-12-04 06:45:31'),
(427, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:45:31', '2024-12-04 06:45:31'),
(428, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:45:31', '2024-12-04 06:45:31'),
(429, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:45:31', '2024-12-04 06:45:31'),
(430, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:45:32', '2024-12-04 06:45:32'),
(431, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:45:32', '2024-12-04 06:45:32'),
(432, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:45:32', '2024-12-04 06:45:32'),
(433, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:45:32', '2024-12-04 06:45:32'),
(434, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:45:32', '2024-12-04 06:45:32'),
(435, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(436, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(437, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(438, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(439, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(440, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(441, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(442, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(443, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(444, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:46:33', '2024-12-04 06:46:33'),
(445, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:48:07', '2024-12-04 06:48:07'),
(446, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:48:07', '2024-12-04 06:48:07'),
(447, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:48:07', '2024-12-04 06:48:07'),
(448, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:48:07', '2024-12-04 06:48:07'),
(449, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:48:07', '2024-12-04 06:48:07'),
(450, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:48:08', '2024-12-04 06:48:08'),
(451, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:48:08', '2024-12-04 06:48:08'),
(452, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:48:08', '2024-12-04 06:48:08'),
(453, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:48:08', '2024-12-04 06:48:08'),
(454, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:48:08', '2024-12-04 06:48:08'),
(455, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:49:37', '2024-12-04 06:49:37'),
(456, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:49:37', '2024-12-04 06:49:37'),
(457, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:49:38', '2024-12-04 06:49:38'),
(458, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:49:38', '2024-12-04 06:49:38'),
(459, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:49:38', '2024-12-04 06:49:38'),
(460, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:49:38', '2024-12-04 06:49:38'),
(461, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:49:38', '2024-12-04 06:49:38'),
(462, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:49:38', '2024-12-04 06:49:38'),
(463, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:49:38', '2024-12-04 06:49:38'),
(464, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:49:38', '2024-12-04 06:49:38'),
(465, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:49:52', '2024-12-04 06:49:52'),
(466, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:49:52', '2024-12-04 06:49:52'),
(467, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:49:52', '2024-12-04 06:49:52'),
(468, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:49:53', '2024-12-04 06:49:53'),
(469, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:49:53', '2024-12-04 06:49:53'),
(470, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:49:53', '2024-12-04 06:49:53'),
(471, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:49:53', '2024-12-04 06:49:53'),
(472, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:49:53', '2024-12-04 06:49:53'),
(473, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:49:53', '2024-12-04 06:49:53'),
(474, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:49:53', '2024-12-04 06:49:53'),
(475, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:51:55', '2024-12-04 06:51:55'),
(476, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:51:55', '2024-12-04 06:51:55'),
(477, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:51:55', '2024-12-04 06:51:55'),
(478, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:51:55', '2024-12-04 06:51:55'),
(479, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:51:55', '2024-12-04 06:51:55'),
(480, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:51:55', '2024-12-04 06:51:55'),
(481, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:51:56', '2024-12-04 06:51:56'),
(482, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:51:56', '2024-12-04 06:51:56'),
(483, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:51:56', '2024-12-04 06:51:56'),
(484, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:51:56', '2024-12-04 06:51:56'),
(485, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:54:32', '2024-12-04 06:54:32'),
(486, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:54:32', '2024-12-04 06:54:32'),
(487, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:54:32', '2024-12-04 06:54:32'),
(488, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:54:32', '2024-12-04 06:54:32'),
(489, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:54:32', '2024-12-04 06:54:32'),
(490, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 06:54:33', '2024-12-04 06:54:33'),
(491, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 06:54:33', '2024-12-04 06:54:33'),
(492, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 06:54:33', '2024-12-04 06:54:33'),
(493, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 06:54:33', '2024-12-04 06:54:33'),
(494, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 06:54:33', '2024-12-04 06:54:33'),
(495, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:03:58', '2024-12-04 07:03:58'),
(496, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:03:58', '2024-12-04 07:03:58'),
(497, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:03:58', '2024-12-04 07:03:58'),
(498, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:03:58', '2024-12-04 07:03:58'),
(499, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:03:58', '2024-12-04 07:03:58'),
(500, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:03:58', '2024-12-04 07:03:58'),
(501, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:03:58', '2024-12-04 07:03:58'),
(502, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:03:58', '2024-12-04 07:03:58'),
(503, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:03:58', '2024-12-04 07:03:58'),
(504, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:03:59', '2024-12-04 07:03:59'),
(505, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:06:16', '2024-12-04 07:06:16'),
(506, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:06:17', '2024-12-04 07:06:17'),
(507, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:06:17', '2024-12-04 07:06:17'),
(508, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:06:17', '2024-12-04 07:06:17'),
(509, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:06:17', '2024-12-04 07:06:17'),
(510, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:06:17', '2024-12-04 07:06:17'),
(511, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:06:17', '2024-12-04 07:06:17'),
(512, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:06:17', '2024-12-04 07:06:17'),
(513, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:06:17', '2024-12-04 07:06:17'),
(514, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:06:18', '2024-12-04 07:06:18'),
(515, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(516, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(517, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(518, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(519, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(520, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(521, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(522, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(523, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(524, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:09:23', '2024-12-04 07:09:23'),
(525, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:10:47', '2024-12-04 07:10:47'),
(526, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:10:47', '2024-12-04 07:10:47'),
(527, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:10:47', '2024-12-04 07:10:47'),
(528, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:10:47', '2024-12-04 07:10:47'),
(529, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:10:47', '2024-12-04 07:10:47'),
(530, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:15:27', '2024-12-04 07:15:27'),
(531, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:15:27', '2024-12-04 07:15:27'),
(532, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:15:27', '2024-12-04 07:15:27'),
(533, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:15:27', '2024-12-04 07:15:27'),
(534, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:15:27', '2024-12-04 07:15:27'),
(535, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:15:27', '2024-12-04 07:15:27'),
(536, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:15:27', '2024-12-04 07:15:27'),
(537, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:15:27', '2024-12-04 07:15:27'),
(538, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:15:27', '2024-12-04 07:15:27'),
(539, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:15:28', '2024-12-04 07:15:28'),
(540, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:15:41', '2024-12-04 07:15:41'),
(541, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:15:41', '2024-12-04 07:15:41'),
(542, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:15:41', '2024-12-04 07:15:41'),
(543, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:15:41', '2024-12-04 07:15:41'),
(544, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:15:41', '2024-12-04 07:15:41'),
(545, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:15:41', '2024-12-04 07:15:41'),
(546, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:15:41', '2024-12-04 07:15:41'),
(547, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:15:41', '2024-12-04 07:15:41'),
(548, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:15:41', '2024-12-04 07:15:41'),
(549, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:15:42', '2024-12-04 07:15:42'),
(550, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:03', '2024-12-04 07:16:03'),
(551, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:03', '2024-12-04 07:16:03'),
(552, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:03', '2024-12-04 07:16:03'),
(553, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:03', '2024-12-04 07:16:03'),
(554, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:03', '2024-12-04 07:16:03'),
(555, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:04', '2024-12-04 07:16:04'),
(556, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:04', '2024-12-04 07:16:04'),
(557, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:04', '2024-12-04 07:16:04'),
(558, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:04', '2024-12-04 07:16:04'),
(559, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:04', '2024-12-04 07:16:04'),
(560, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:09', '2024-12-04 07:16:09'),
(561, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:09', '2024-12-04 07:16:09'),
(562, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:09', '2024-12-04 07:16:09'),
(563, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:09', '2024-12-04 07:16:09'),
(564, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:09', '2024-12-04 07:16:09'),
(565, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:10', '2024-12-04 07:16:10'),
(566, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:10', '2024-12-04 07:16:10'),
(567, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:10', '2024-12-04 07:16:10'),
(568, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:10', '2024-12-04 07:16:10'),
(569, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:10', '2024-12-04 07:16:10'),
(570, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:13', '2024-12-04 07:16:13'),
(571, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:13', '2024-12-04 07:16:13'),
(572, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:13', '2024-12-04 07:16:13'),
(573, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:13', '2024-12-04 07:16:13'),
(574, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:13', '2024-12-04 07:16:13'),
(575, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:13', '2024-12-04 07:16:13'),
(576, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:13', '2024-12-04 07:16:13'),
(577, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:14', '2024-12-04 07:16:14'),
(578, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:14', '2024-12-04 07:16:14'),
(579, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:14', '2024-12-04 07:16:14'),
(580, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(581, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(582, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(583, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(584, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(585, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(586, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(587, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(588, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(589, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:18', '2024-12-04 07:16:18'),
(590, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:21', '2024-12-04 07:16:21'),
(591, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:21', '2024-12-04 07:16:21'),
(592, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:21', '2024-12-04 07:16:21'),
(593, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:21', '2024-12-04 07:16:21'),
(594, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:22', '2024-12-04 07:16:22'),
(595, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:22', '2024-12-04 07:16:22'),
(596, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:22', '2024-12-04 07:16:22'),
(597, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:22', '2024-12-04 07:16:22'),
(598, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:22', '2024-12-04 07:16:22'),
(599, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:22', '2024-12-04 07:16:22'),
(600, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:25', '2024-12-04 07:16:25'),
(601, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:25', '2024-12-04 07:16:25'),
(602, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:25', '2024-12-04 07:16:25'),
(603, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:25', '2024-12-04 07:16:25'),
(604, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:26', '2024-12-04 07:16:26'),
(605, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:26', '2024-12-04 07:16:26'),
(606, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:26', '2024-12-04 07:16:26'),
(607, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:26', '2024-12-04 07:16:26'),
(608, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:26', '2024-12-04 07:16:26'),
(609, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:26', '2024-12-04 07:16:26'),
(610, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:28', '2024-12-04 07:16:28'),
(611, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:28', '2024-12-04 07:16:28'),
(612, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:28', '2024-12-04 07:16:28'),
(613, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:28', '2024-12-04 07:16:28'),
(614, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:28', '2024-12-04 07:16:28'),
(615, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:29', '2024-12-04 07:16:29'),
(616, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:29', '2024-12-04 07:16:29'),
(617, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:29', '2024-12-04 07:16:29'),
(618, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:29', '2024-12-04 07:16:29'),
(619, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:29', '2024-12-04 07:16:29'),
(620, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:31', '2024-12-04 07:16:31'),
(621, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:31', '2024-12-04 07:16:31'),
(622, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:31', '2024-12-04 07:16:31'),
(623, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:31', '2024-12-04 07:16:31'),
(624, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:31', '2024-12-04 07:16:31'),
(625, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:32', '2024-12-04 07:16:32'),
(626, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:32', '2024-12-04 07:16:32'),
(627, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:32', '2024-12-04 07:16:32'),
(628, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:32', '2024-12-04 07:16:32'),
(629, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:32', '2024-12-04 07:16:32'),
(630, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:33', '2024-12-04 07:16:33'),
(631, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:33', '2024-12-04 07:16:33'),
(632, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:33', '2024-12-04 07:16:33'),
(633, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:33', '2024-12-04 07:16:33'),
(634, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:34', '2024-12-04 07:16:34'),
(635, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:34', '2024-12-04 07:16:34'),
(636, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:34', '2024-12-04 07:16:34'),
(637, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:34', '2024-12-04 07:16:34'),
(638, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:34', '2024-12-04 07:16:34'),
(639, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:34', '2024-12-04 07:16:34'),
(640, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:36', '2024-12-04 07:16:36'),
(641, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:36', '2024-12-04 07:16:36'),
(642, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:36', '2024-12-04 07:16:36'),
(643, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:36', '2024-12-04 07:16:36'),
(644, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:36', '2024-12-04 07:16:36'),
(645, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:37', '2024-12-04 07:16:37'),
(646, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:37', '2024-12-04 07:16:37'),
(647, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:37', '2024-12-04 07:16:37'),
(648, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:37', '2024-12-04 07:16:37'),
(649, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:37', '2024-12-04 07:16:37'),
(650, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(651, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(652, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(653, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(654, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(655, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(656, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(657, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(658, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(659, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:16:47', '2024-12-04 07:16:47'),
(660, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:01', '2024-12-04 07:17:01'),
(661, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:01', '2024-12-04 07:17:01'),
(662, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:01', '2024-12-04 07:17:01'),
(663, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:01', '2024-12-04 07:17:01'),
(664, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:01', '2024-12-04 07:17:01'),
(665, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:02', '2024-12-04 07:17:02'),
(666, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:02', '2024-12-04 07:17:02'),
(667, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:02', '2024-12-04 07:17:02'),
(668, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:02', '2024-12-04 07:17:02'),
(669, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:02', '2024-12-04 07:17:02'),
(670, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:07', '2024-12-04 07:17:07'),
(671, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:07', '2024-12-04 07:17:07'),
(672, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:07', '2024-12-04 07:17:07'),
(673, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:07', '2024-12-04 07:17:07'),
(674, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:07', '2024-12-04 07:17:07'),
(675, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:08', '2024-12-04 07:17:08'),
(676, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:08', '2024-12-04 07:17:08'),
(677, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:08', '2024-12-04 07:17:08'),
(678, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:08', '2024-12-04 07:17:08'),
(679, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:08', '2024-12-04 07:17:08'),
(680, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:11', '2024-12-04 07:17:11'),
(681, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:11', '2024-12-04 07:17:11'),
(682, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:11', '2024-12-04 07:17:11'),
(683, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:11', '2024-12-04 07:17:11'),
(684, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:11', '2024-12-04 07:17:11'),
(685, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:12', '2024-12-04 07:17:12'),
(686, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:12', '2024-12-04 07:17:12'),
(687, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:12', '2024-12-04 07:17:12'),
(688, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:12', '2024-12-04 07:17:12'),
(689, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:12', '2024-12-04 07:17:12'),
(690, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:18', '2024-12-04 07:17:18'),
(691, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:18', '2024-12-04 07:17:18'),
(692, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:18', '2024-12-04 07:17:18'),
(693, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:18', '2024-12-04 07:17:18'),
(694, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:18', '2024-12-04 07:17:18'),
(695, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:18', '2024-12-04 07:17:18'),
(696, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:18', '2024-12-04 07:17:18'),
(697, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:18', '2024-12-04 07:17:18'),
(698, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:18', '2024-12-04 07:17:18'),
(699, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:19', '2024-12-04 07:17:19'),
(700, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(701, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(702, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(703, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(704, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(705, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(706, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(707, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(708, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(709, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:47', '2024-12-04 07:17:47'),
(710, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:49', '2024-12-04 07:17:49'),
(711, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:49', '2024-12-04 07:17:49'),
(712, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:49', '2024-12-04 07:17:49'),
(713, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:49', '2024-12-04 07:17:49'),
(714, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:49', '2024-12-04 07:17:49'),
(715, 9, '', 'Learn the basics of PHP', 'beginner', 'draft', '2024-12-04 07:17:50', '2024-12-04 07:17:50'),
(716, 10, '', 'Learn the basics of Chemistry', 'beginner', 'draft', '2024-12-04 07:17:50', '2024-12-04 07:17:50'),
(717, 11, '', 'Learn the basics of Physics', 'beginner', 'draft', '2024-12-04 07:17:50', '2024-12-04 07:17:50'),
(718, 12, '', 'Learn the basics of Mathematics', 'beginner', 'draft', '2024-12-04 07:17:50', '2024-12-04 07:17:50'),
(719, 13, '', 'Learn the basics of Biology', 'beginner', 'draft', '2024-12-04 07:17:50', '2024-12-04 07:17:50'),
(720, 950, '', 'Learn the basics of Python', 'beginner', 'draft', '2024-12-04 07:35:40', '2024-12-04 07:35:40'),
(721, 951, '', 'Learn the basics of Computer Science', 'beginner', 'draft', '2024-12-04 07:35:40', '2024-12-04 07:35:40'),
(722, 952, '', 'Learn the basics of Cybersecurity', 'beginner', 'draft', '2024-12-04 07:35:40', '2024-12-04 07:35:40'),
(723, 953, '', 'Learn the basics of English', 'beginner', 'draft', '2024-12-04 07:35:40', '2024-12-04 07:35:40'),
(724, 954, '', 'Learn the basics of French', 'beginner', 'draft', '2024-12-04 07:35:40', '2024-12-04 07:35:40'),
(725, 955, '', 'Learn the basics of Spanish', 'beginner', 'draft', '2024-12-04 07:35:40', '2024-12-04 07:35:40'),
(726, 956, '', 'Learn the basics of Networking', 'beginner', 'draft', '2024-12-04 07:35:40', '2024-12-04 07:35:40');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `uploaded_at` datetime NOT NULL,
  `file_size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` enum('active','hidden','deleted') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_comments`
--

INSERT INTO `post_comments` (`id`, `post_id`, `user_id`, `content`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Associative arrays in PHP use key-value pairs. Here\'s an example...', 'active', '2024-12-04 04:25:47', '2024-12-04 04:25:47'),
(2, 1, 2, 'Thanks for the explanation!', 'active', '2024-12-04 04:25:47', '2024-12-04 04:25:47'),
(3, 2, 1, 'Always use prepared statements for database queries', 'active', '2024-12-04 04:25:47', '2024-12-04 04:25:47'),
(4, 3, 2, 'Index optimization is key for better query performance', 'active', '2024-12-04 04:25:47', '2024-12-04 04:25:47'),
(5, 1, 1, 'asda', 'active', '2024-12-04 04:26:59', '2024-12-04 04:26:59'),
(6, 2, 1, 'ZXZX', 'active', '2024-12-04 04:39:02', '2024-12-04 04:39:02'),
(7, 1, 1, 'ZXzX', 'active', '2024-12-04 04:39:09', '2024-12-04 04:39:09'),
(8, 2, 1, 'asdasdasd', 'active', '2024-12-04 04:41:43', '2024-12-04 04:41:43'),
(9, 1, 1, 'aadasdadasdas', 'active', '2024-12-04 04:41:46', '2024-12-04 04:41:46'),
(10, 1, 1, 'aaaaaa', 'active', '2024-12-04 04:41:53', '2024-12-04 04:41:53'),
(11, 1, 1, 'aaaaaa', 'active', '2024-12-04 04:44:23', '2024-12-04 04:44:23'),
(12, 6, 1, 'asdasdas', 'active', '2024-12-04 04:44:41', '2024-12-04 04:44:41'),
(13, 12, 1, 'asdad', 'active', '2024-12-04 04:51:20', '2024-12-04 04:51:20'),
(14, 12, 1, 'asdasd', 'active', '2024-12-04 04:51:23', '2024-12-04 04:51:23');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(1, 1, 3, '2024-12-04 04:25:47'),
(2, 1, 2, '2024-12-04 04:25:47'),
(3, 2, 1, '2024-12-04 04:25:47'),
(4, 2, 3, '2024-12-04 04:25:47'),
(5, 3, 1, '2024-12-04 04:25:47'),
(6, 4, 2, '2024-12-04 04:25:47'),
(7, 1, 1, '2024-12-04 04:39:11');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `difficulty` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `correct_answers` int(11) NOT NULL,
  `score_percentage` decimal(5,2) NOT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `started_at` timestamp NULL DEFAULT current_timestamp(),
  `quiz_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `user_id`, `subject_id`, `total_questions`, `correct_answers`, `score_percentage`, `attempted_at`, `started_at`, `quiz_id`, `course_id`, `completed_at`) VALUES
(1, 1, 1, 10, 3, 30.00, '2024-12-04 04:52:35', '2024-12-04 06:45:32', NULL, NULL, '2024-12-04 06:54:33'),
(2, 1, 1, 10, 3, 30.00, '2024-12-04 04:53:47', '2024-12-04 06:45:32', NULL, NULL, '2024-12-04 06:54:33'),
(3, 1, 5, 3, 1, 33.33, '2024-12-04 04:59:00', '2024-12-04 06:45:32', NULL, NULL, '2024-12-04 06:54:33'),
(4, 1, 5, 3, 0, 0.00, '2024-12-04 05:01:42', '2024-12-04 06:45:32', NULL, NULL, '2024-12-04 06:54:33'),
(5, 1, 5, 3, 0, 0.00, '2024-12-04 05:05:58', '2024-12-04 06:45:32', NULL, NULL, '2024-12-04 06:54:33'),
(9, 5, 9, 3, 3, 100.00, '2024-12-09 03:31:03', '2024-12-09 03:31:03', NULL, NULL, '2024-12-09 03:31:03'),
(10, 5, 950, 3, 2, 66.67, '2024-12-09 03:32:41', '2024-12-09 03:32:41', NULL, NULL, '2024-12-09 03:32:41'),
(11, 5, 951, 3, 2, 66.67, '2024-12-09 03:36:01', '2024-12-09 03:36:01', NULL, NULL, '2024-12-09 03:36:01'),
(12, 5, 13, 3, 3, 100.00, '2024-12-09 03:40:30', '2024-12-09 03:40:30', NULL, NULL, '2024-12-09 03:40:30'),
(13, 5, 956, 3, 2, 66.67, '2024-12-09 03:42:22', '2024-12-09 03:42:22', NULL, NULL, '2024-12-09 03:42:22'),
(14, 5, 952, 3, 2, 66.67, '2024-12-09 03:43:46', '2024-12-09 03:43:46', NULL, NULL, '2024-12-09 03:43:46'),
(15, 5, 954, 3, 1, 33.33, '2024-12-09 03:48:11', '2024-12-09 03:48:11', NULL, NULL, '2024-12-09 03:48:11'),
(16, 5, 955, 3, 2, 66.67, '2024-12-09 03:49:28', '2024-12-09 03:49:28', NULL, NULL, '2024-12-09 03:49:28'),
(17, 5, 953, 3, 1, 33.33, '2024-12-09 03:51:55', '2024-12-09 03:51:55', NULL, NULL, '2024-12-09 03:51:55'),
(18, 5, 12, 3, 2, 66.67, '2024-12-09 03:52:59', '2024-12-09 03:52:59', NULL, NULL, '2024-12-09 03:52:59'),
(19, 5, 12, 3, 1, 33.33, '2024-12-09 03:54:48', '2024-12-09 03:54:48', NULL, NULL, '2024-12-09 03:54:48'),
(20, 5, 12, 3, 1, 33.33, '2024-12-09 03:58:35', '2024-12-09 03:58:35', NULL, NULL, '2024-12-09 03:58:35'),
(21, 5, 12, 3, 1, 33.33, '2024-12-09 04:01:28', '2024-12-09 04:01:28', NULL, NULL, '2024-12-09 04:01:28'),
(22, 5, 10, 10, 4, 40.00, '2024-12-09 04:04:26', '2024-12-09 04:04:26', NULL, NULL, '2024-12-09 04:04:26'),
(23, 5, 13, 3, 2, 66.67, '2024-12-09 04:04:56', '2024-12-09 04:04:56', NULL, NULL, '2024-12-09 04:04:56'),
(24, 5, 11, 10, 3, 30.00, '2024-12-09 04:08:54', '2024-12-09 04:08:54', NULL, NULL, '2024-12-09 04:08:54'),
(25, 5, 4, 3, 2, 66.67, '2024-12-09 04:09:17', '2024-12-09 04:09:17', NULL, NULL, '2024-12-09 04:09:17'),
(26, 5, 950, 3, 1, 33.33, '2024-12-09 04:09:36', '2024-12-09 04:09:36', NULL, NULL, '2024-12-09 04:09:36'),
(27, 5, 11, 10, 3, 30.00, '2024-12-09 04:12:56', '2024-12-09 04:12:56', NULL, NULL, '2024-12-09 04:12:56'),
(28, 5, 9, 3, 3, 100.00, '2024-12-09 04:13:11', '2024-12-09 04:13:11', NULL, NULL, '2024-12-09 04:13:11'),
(29, 5, 950, 3, 2, 66.67, '2024-12-09 04:20:40', '2024-12-09 04:20:40', NULL, NULL, '2024-12-09 04:20:40'),
(30, 5, 6, 3, 3, 100.00, '2024-12-09 04:23:28', '2024-12-09 04:23:28', NULL, NULL, '2024-12-09 04:23:28'),
(31, 5, 6, 3, 3, 100.00, '2024-12-09 04:23:50', '2024-12-09 04:23:50', NULL, NULL, '2024-12-09 04:23:50'),
(32, 5, 6, 3, 3, 100.00, '2024-12-09 04:25:49', '2024-12-09 04:25:49', NULL, NULL, '2024-12-09 04:25:49'),
(33, 5, 951, 3, 2, 66.67, '2024-12-09 04:35:09', '2024-12-09 04:35:09', NULL, NULL, '2024-12-09 04:35:09'),
(34, 5, 951, 3, 3, 100.00, '2024-12-09 04:35:31', '2024-12-09 04:35:31', NULL, NULL, '2024-12-09 04:35:31'),
(35, 5, 6, 3, 3, 100.00, '2024-12-09 04:37:58', '2024-12-09 04:37:58', NULL, NULL, '2024-12-09 04:37:58'),
(36, 5, 6, 3, 3, 100.00, '2024-12-09 04:40:16', '2024-12-09 04:40:16', NULL, NULL, '2024-12-09 04:40:16'),
(37, 5, 952, 3, 3, 100.00, '2024-12-09 04:43:16', '2024-12-09 04:43:16', NULL, NULL, '2024-12-09 04:43:16'),
(38, 5, 952, 3, 3, 100.00, '2024-12-09 04:43:41', '2024-12-09 04:43:41', NULL, NULL, '2024-12-09 04:43:41'),
(39, 7, 6, 3, 3, 100.00, '2024-12-09 05:06:11', '2024-12-09 05:06:11', NULL, NULL, '2024-12-09 05:06:11'),
(40, 7, 950, 3, 0, 0.00, '2024-12-09 05:06:43', '2024-12-09 05:06:43', NULL, NULL, '2024-12-09 05:06:43');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `description`, `icon`, `created_at`) VALUES
(1, 'PHP Basics', 'Learn the fundamentals of PHP programming', 'php-icon.png', '2024-12-04 04:25:46'),
(2, 'MySQL', 'Database management with MySQL', 'mysql-icon.png', '2024-12-04 04:25:46'),
(3, 'Web Development', 'Web development fundamentals', 'web-icon.png', '2024-12-04 04:25:46'),
(4, 'JavaScript', 'Learn JavaScript programming', 'js-icon.png', '2024-12-04 04:25:46'),
(5, 'HTML & CSS', 'Web markup and styling', 'html-icon.png', '2024-12-04 04:25:46'),
(6, 'Advanced PHP', 'Advanced PHP concepts and techniques', 'advanced-php-icon.png', '2024-12-04 04:25:46'),
(7, 'Database Design', 'Learn database design principles', 'db-icon.png', '2024-12-04 04:25:46'),
(8, 'Web Security', 'Web application security fundamentals', 'security-icon.png', '2024-12-04 04:25:46'),
(9, 'PHP', 'Learn PHP programming', NULL, '2024-12-04 04:27:08'),
(10, 'Chemistry', 'Basic chemistry concepts', NULL, '2024-12-04 04:27:08'),
(11, 'Physics', 'Basic physics concepts', NULL, '2024-12-04 04:27:08'),
(12, 'Mathematics', 'Basic mathematics', NULL, '2024-12-04 04:27:09'),
(13, 'Biology', 'Basic biology concepts', NULL, '2024-12-04 04:27:09'),
(950, 'Python', 'Learn Python programming', NULL, '2024-12-04 07:18:37'),
(951, 'Computer Science', 'Introduction to computer science', NULL, '2024-12-04 07:18:37'),
(952, 'Cybersecurity', 'Basic cybersecurity principles', NULL, '2024-12-04 07:18:37'),
(953, 'English', 'English language basics', NULL, '2024-12-04 07:18:37'),
(954, 'French', 'Basic French language', NULL, '2024-12-04 07:18:37'),
(955, 'Spanish', 'Basic Spanish language', NULL, '2024-12-04 07:18:37'),
(956, 'Networking', 'Basic networking concepts', NULL, '2024-12-04 07:18:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'default-profile.png',
  `role` enum('user','admin','moderator') DEFAULT 'user',
  `account_status` enum('active','suspended','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_picture`, `role`, `account_status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@revvit.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1.jpg', 'admin', 'active', NULL, '2024-12-04 04:25:46', '2024-12-04 04:37:56'),
(2, 'John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'default-profile.png', 'user', 'active', NULL, '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(3, 'Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'default-profile.png', 'user', 'active', NULL, '2024-12-04 04:25:46', '2024-12-04 04:25:46'),
(5, 'Rey', 'admin@admin.com', '$2y$10$Irb5AWJg4yAR9I2Fthg2wuO8uOjVYVgWhrsrSN5Yns59YAVYlS8SS', '5.jpg', 'user', 'active', NULL, '2024-12-04 07:10:40', '2024-12-09 01:48:45'),
(6, 'PIO', 'pio@pio.com', '$2y$10$8iqSNCyCNjVmuxOpOyl6deZ7fVwX53zS7Ff/7fIU03hvu54fWYBri', '6.PNG', 'user', 'active', NULL, '2024-12-04 07:38:54', '2024-12-04 07:39:18'),
(7, 'Jempy sa Daan', 'admin@example.com', '$2y$10$vegfNbiBrlQwyv0wu1ZMuuAP9MrqJyF5Lzav4Qp0nyWZy.jgH23Ym', '7.PNG', 'user', 'active', NULL, '2024-12-09 05:05:16', '2024-12-14 15:35:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_achievements`
--

CREATE TABLE `user_achievements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_achievements`
--

INSERT INTO `user_achievements` (`id`, `user_id`, `achievement_id`, `earned_at`) VALUES
(1, 1, 1, '2024-12-04 04:52:36'),
(2, 1, 3, '2024-12-04 04:52:36'),
(9, 5, 5, '2024-12-09 04:58:16'),
(10, 5, 1, '2024-12-09 04:58:16'),
(11, 5, 2, '2024-12-09 04:58:16'),
(12, 5, 3, '2024-12-09 04:58:16'),
(13, 5, 4, '2024-12-09 04:58:16'),
(14, 7, 3, '2024-12-09 05:05:23'),
(15, 7, 1, '2024-12-09 05:06:15');

-- --------------------------------------------------------

--
-- Stand-in structure for view `user_achievement_stats`
-- (See below for the actual view)
--
CREATE TABLE `user_achievement_stats` (
`user_id` int(11)
,`total_achievements` bigint(21)
,`total_points` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `user_courses`
--

CREATE TABLE `user_courses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `status` enum('enrolled','in_progress','completed','dropped') DEFAULT 'enrolled',
  `progress_percentage` decimal(5,2) DEFAULT 0.00,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `last_accessed` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_courses`
--

INSERT INTO `user_courses` (`id`, `user_id`, `course_id`, `status`, `progress_percentage`, `enrolled_at`, `completed_at`, `last_accessed`) VALUES
(1, 5, 312, 'in_progress', 30.00, '2024-12-09 04:12:56', NULL, NULL),
(2, 5, 720, 'in_progress', 66.67, '2024-12-09 04:20:40', NULL, NULL),
(3, 5, 7, 'in_progress', 100.00, '2024-12-09 04:23:28', NULL, NULL),
(4, 5, 721, '', 66.67, '2024-12-09 04:35:09', NULL, NULL),
(5, 5, 722, 'in_progress', 100.00, '2024-12-09 04:43:15', NULL, NULL),
(6, 7, 7, 'in_progress', 100.00, '2024-12-09 05:06:11', NULL, NULL),
(7, 7, 720, 'in_progress', 0.00, '2024-12-09 05:06:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `user_course_stats`
-- (See below for the actual view)
--
CREATE TABLE `user_course_stats` (
`user_id` int(11)
,`enrolled_courses` bigint(21)
,`completed_courses` decimal(22,0)
,`in_progress_courses` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Structure for view `user_achievement_stats`
--
DROP TABLE IF EXISTS `user_achievement_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_achievement_stats`  AS SELECT `ua`.`user_id` AS `user_id`, count(distinct `ua`.`achievement_id`) AS `total_achievements`, sum(`a`.`points`) AS `total_points` FROM (`user_achievements` `ua` join `achievements` `a` on(`ua`.`achievement_id` = `a`.`id`)) GROUP BY `ua`.`user_id` ;

-- --------------------------------------------------------

--
-- Structure for view `user_course_stats`
--
DROP TABLE IF EXISTS `user_course_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_course_stats`  AS SELECT `uc`.`user_id` AS `user_id`, count(distinct `uc`.`course_id`) AS `enrolled_courses`, sum(case when `uc`.`status` = 'completed' then 1 else 0 end) AS `completed_courses`, sum(case when `uc`.`status` = 'in_progress' then 1 else 0 end) AS `in_progress_courses` FROM `user_courses` AS `uc` GROUP BY `uc`.`user_id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_post_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_achievement` (`user_id`,`achievement_id`),
  ADD KEY `achievement_id` (`achievement_id`);

--
-- Indexes for table `user_courses`
--
ALTER TABLE `user_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_course` (`user_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `community_comments`
--
ALTER TABLE `community_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=727;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12020;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_achievements`
--
ALTER TABLE `user_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_courses`
--
ALTER TABLE `user_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD CONSTRAINT `community_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `community_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_posts_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  ADD CONSTRAINT `community_post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD CONSTRAINT `user_achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_achievements_ibfk_2` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_courses`
--
ALTER TABLE `user_courses`
  ADD CONSTRAINT `user_courses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
