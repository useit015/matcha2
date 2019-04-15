-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Mar 20, 2019 at 11:10 PM
-- Server version: 5.7.25
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slim`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `profile` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `user_id`, `name`, `profile`, `created_at`) VALUES
(33, 1, '1-5c886835a45377.34228584.png', 0, '2019-03-13 02:17:25'),
(34, 1, '1-5c8868c123fea7.53920803.png', 0, '2019-03-13 02:19:45'),
(35, 1, '1-5c886980252260.36654088.png', 0, '2019-03-13 02:22:56'),
(36, 1, '1-5c8869ea273847.94626052.png', 0, '2019-03-13 02:24:42'),
(37, 1, '1-5c886a2a03e307.41286933.png', 0, '2019-03-13 02:25:46'),
(38, 1, '1-5c886aa3a48165.00626085.png', 0, '2019-03-13 02:27:47'),
(39, 1, '1-5c886aee5a7507.01540888.png', 0, '2019-03-13 02:29:02'),
(40, 1, '1-5c8963a23ea111.64712177.png', 0, '2019-03-13 20:10:10'),
(41, 1, '1-5c896e064f46a9.35968567.png', 0, '2019-03-13 20:54:30'),
(42, 1, '1-5c896e1476ebe6.89062925.png', 0, '2019-03-13 20:54:44'),
(43, 1, '1-5c89715b4873b8.41360143.', 0, '2019-03-13 21:08:43'),
(44, 1, '1-5c8971832ac491.73942191.png', 0, '2019-03-13 21:09:23'),
(45, 1, '1-5c8981a2249695.69569482.png', 0, '2019-03-13 22:18:10'),
(46, 1, '1-5c8981fd4238d5.35769270.png', 0, '2019-03-13 22:19:41'),
(47, 1, '1-5c89c2832caf87.79982277.png', 0, '2019-03-14 02:54:59'),
(48, 1, '1-5c89d5e2d7f051.47146160.png', 0, '2019-03-14 04:17:38'),
(49, 1, '1-5c8abe05c9e7b2.70428198.png', 0, '2019-03-14 20:48:05'),
(50, 1, '1-5c8abe6ef029f0.29981087.png', 0, '2019-03-14 20:49:51'),
(51, 1, '1-5c8abf5ce300e4.05558981.png', 0, '2019-03-14 20:53:48'),
(52, 1, '1-5c8abf61624f85.87026054.png', 0, '2019-03-14 20:53:53'),
(53, 1, '1-5c929364610db7.07189571.png', 0, '2019-03-20 19:24:20'),
(54, 1, '1-5c92c5050400d8.41625380.png', 1, '2019-03-20 22:56:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gender` varchar(20) DEFAULT NULL,
  `looking` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `biography` text,
  `tags` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `vkey` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(255) DEFAULT NULL,
  `tokenExpiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `created_at`, `gender`, `looking`, `birthdate`, `biography`, `tags`, `address`, `city`, `country`, `postal_code`, `phone`, `status`, `rating`, `vkey`, `verified`, `token`, `tokenExpiration`) VALUES
(1, 'oussama', 'nahiz', 'onahiz', 'onahiz@student.1337.ma', '$2y$10$Yvj4gBe79SdW2/BhmeGXWudQ5aAodUsTk9syDQ4Nx/4pROYB5ZXMy', '2019-03-06 23:32:53', 'male', 'female', '1993-01-17', 'dignissimos rerum sed ipsam quasi quod\n', '1337,42', '96, rue des jardins', 'khouribga', 'maroc', '25000', '0653095683', NULL, NULL, 'e55b72f4d42a6e9c12f8', 1, '87009a07189c5c8a', '2019-03-21 00:55:37'),
(2, 'john', 'snow', 'jsnow', 'magavoka@weave.email', '$2y$10$2Q5/NBBLCYsk4ILeDF2NZ.jb8wPWHGzUR2HKFKIh3PluBwBpe1Koi', '2019-03-07 20:55:47', NULL, NULL, NULL, 'pecial char Password must contain at least one letter, one number and one special charLorempecial char Password must contain at least one letter, one number and one special charLorem trrr', 'stark,winter is coming,ghost,nights watch', '96, wintertown', 'winterfell', 'westeros', '99906', '0653095683', NULL, NULL, '382c669ff4288a229b68', 1, '151fe4b12eb008ce', '2019-03-12 22:55:21'),
(3, 'denis', 'ritchie', 'dritch', 'refib@yourweb.email', '$2y$10$HKJaBkO9xiFU3V81tp65xeJrGuk3DawNUwo7gCEkOF66s0DqBQCmS', '2019-03-11 01:57:21', NULL, NULL, '1993-01-17', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3b4aa48bdb277562f180', 1, 'db6440b880a6eb58', '2019-03-12 22:53:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
