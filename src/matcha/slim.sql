-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: May 08, 2019 at 03:08 AM
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
(55, 1, '1-5cb4dd5221d934.41650153.png', 0, '2019-04-15 19:36:50'),
(56, 1, '1-5cb4df33943218.78775191.png', 0, '2019-04-15 19:44:51'),
(57, 1, '1-5cc7c0510c17a9.17279155.png', 1, '2019-04-30 03:26:09'),
(58, 127, 'https://randomuser.me/api/portraits/women/87.jpg', 1, '2019-05-06 19:34:43'),
(59, 128, 'https://randomuser.me/api/portraits/men/96.jpg', 1, '2019-05-06 20:13:14'),
(60, 129, 'https://randomuser.me/api/portraits/women/85.jpg', 1, '2019-05-06 21:19:20'),
(61, 130, 'https://randomuser.me/api/portraits/men/19.jpg', 1, '2019-05-06 21:19:20'),
(62, 132, 'https://randomuser.me/api/portraits/men/73.jpg', 1, '2019-05-06 21:19:21'),
(63, 134, 'https://randomuser.me/api/portraits/women/24.jpg', 1, '2019-05-06 21:19:21'),
(64, 131, 'https://randomuser.me/api/portraits/men/16.jpg', 1, '2019-05-06 21:19:21'),
(65, 133, 'https://randomuser.me/api/portraits/women/67.jpg', 1, '2019-05-06 21:19:21'),
(66, 135, 'https://randomuser.me/api/portraits/women/13.jpg', 1, '2019-05-06 21:19:21'),
(67, 136, 'https://randomuser.me/api/portraits/women/77.jpg', 1, '2019-05-06 21:19:21'),
(68, 137, 'https://randomuser.me/api/portraits/men/93.jpg', 1, '2019-05-06 21:19:21'),
(69, 138, 'https://randomuser.me/api/portraits/women/20.jpg', 1, '2019-05-06 21:19:21');

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
  `lat` varchar(30) NOT NULL DEFAULT '0',
  `lng` varchar(30) NOT NULL DEFAULT '0',
  `vkey` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(255) DEFAULT NULL,
  `tokenExpiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `created_at`, `gender`, `looking`, `birthdate`, `biography`, `tags`, `address`, `city`, `country`, `postal_code`, `phone`, `status`, `rating`, `lat`, `lng`, `vkey`, `verified`, `token`, `tokenExpiration`) VALUES
(1, 'oussama', 'nahiz', 'ouss', 'onahiz@student.1337.ma', '$2y$10$Yvj4gBe79SdW2/BhmeGXWudQ5aAodUsTk9syDQ4Nx/4pROYB5ZXMy', '2019-03-06 23:32:53', 'male', 'female', '1993-01-17', 'dignissimos rerum sed ipsam quasi quod\n', '1337,42', '96, rue des jardins', 'khouribga', 'maroc', '25000', '0653095683', NULL, NULL, '0', '0', 'e55b72f4d42a6e9c12f8', 1, '426f7de9b68fea1b', '2019-05-06 21:54:14'),
(2, 'john', 'snow', 'jsnow', 'magavoka@weave.email', '$2y$10$2Q5/NBBLCYsk4ILeDF2NZ.jb8wPWHGzUR2HKFKIh3PluBwBpe1Koi', '2019-03-07 20:55:47', NULL, NULL, NULL, 'pecial char Password must contain at least one letter, one number and one special charLorempecial char Password must contain at least one letter, one number and one special charLorem trrr', 'stark,winter is coming,ghost,nights watch', '96, wintertown', 'winterfell', 'westeros', '99906', '0653095683', NULL, NULL, '0', '0', '382c669ff4288a229b68', 1, '151fe4b12eb008ce', '2019-03-12 22:55:21'),
(3, 'denis', 'ritchie', 'dritch', 'refib@yourweb.email', '$2y$10$HKJaBkO9xiFU3V81tp65xeJrGuk3DawNUwo7gCEkOF66s0DqBQCmS', '2019-03-11 01:57:21', NULL, NULL, '1993-01-17', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '3b4aa48bdb277562f180', 1, 'db6440b880a6eb58', '2019-03-12 22:53:02'),
(127, 'nurdan', 'Ã¶zdenak', 'organicdog263', 'nurdan.Ã¶zdenak@example.com', '$2y$10$0KD4AWZQQc8jgZQwOqAKTOw38Xn4sfCxkDi5M7jJO4S54yiY7E3wy', '2019-05-06 19:34:43', 'female', 'male', '1953-11-02', '', '', '5180 mevlana cd', 'giresun', 'turkey', '68694', '(305)-307-5995', NULL, NULL, '0', '0', '3b129ce23221d0b10de7', 1, '8bbee8bd3315486f', '2019-05-06 22:12:52'),
(128, 'tyler', 'chapman', 'angrykoala104', 'tyler.chapman@example.com', '$2y$10$e7kK3AzrTI8ko8M4SHl.N.cqYpChXyJOVXbyPKd8R70PldJ91/Bbu', '2019-05-06 20:13:14', 'male', 'female', '1957-02-08', '', '', '73 w sherman dr', 'roseburg', 'united states', '51697', '(198)-011-7087', NULL, NULL, '0', '0', 'be728be5d6ce8c0f1189', 1, 'b25066516f00ca1c', '2019-05-08 04:05:41'),
(129, 'margherita', 'bourgeois', 'organicfish286', 'margherita.bourgeois@example.com', '$2y$10$xHZeYLIqxm6hXeQZT4dzw.Yq3SxdruKVdfUTMLf3NScrNV8iacejK', '2019-05-06 21:19:20', 'female', 'male', '1965-10-04', '', '', '499 place du 8 novembre 1942', 'safnern', 'switzerland', '3369', '(415)-275-3296', NULL, NULL, '0', '0', 'ccf86f055983cf3c7aec', 1, 'fa44ff2727a40df4', '2019-05-07 23:16:46'),
(130, 'efe', 'saygÄ±ner', 'tinypanda395', 'efe.saygÄ±ner@example.com', '$2y$10$3i.ItbOSnLfZbg.GuI/Wdu7cxKeykqgIrhRPTrl1I.bxAKgbCkeha', '2019-05-06 21:19:20', 'male', 'female', '1983-05-31', '', '', '4641 necatibey cd', 'batman', 'turkey', '11508', '(366)-288-2345', NULL, NULL, '0', '0', '8201a79a10f1d05c5f9a', 1, NULL, NULL),
(131, 'noah', 'singh', 'beautifullion749', 'noah.singh@example.com', '$2y$10$u58eHNXy.TWSB5NXNiZHLuTASp/tIoVodkSzgUWiyKhU5rxOm41hG', '2019-05-06 21:19:20', 'male', 'female', '1957-09-10', '', '', '5314 maple ave', 'westport', 'canada', 'Y5B 0M9', '013-244-4430', NULL, NULL, '0', '0', '554b305067aeb289b570', 1, NULL, NULL),
(132, 'philip', 'fowler', 'blacktiger534', 'philip.fowler@example.com', '$2y$10$N/IQhzmrLh3dow/ZvRZbaeqS7Qf/7FnT4gFXIHmrm9cL6H5dSPrl2', '2019-05-06 21:19:20', 'male', 'female', '1986-09-02', '', '', '8542 walnut hill ln', 'devonport', 'australia', '7620', '0497-133-804', NULL, NULL, '0', '0', '7e084bba164830096cb6', 1, NULL, NULL),
(133, 'priscilla', 'carr', 'ticklishrabbit931', 'priscilla.carr@example.com', '$2y$10$NYCLwPMbFZ3AlOGX1/k0ieDmfMHxdosrzhdhEoBXSX4lR4bZCmeju', '2019-05-06 21:19:20', 'female', 'male', '1992-04-23', '', '', '7005 nowlin rd', 'charlotte', 'united states', '84538', '(814)-107-7240', NULL, NULL, '0', '0', 'e806e6da4de9d102093f', 1, NULL, NULL),
(134, 'adelheid', 'harrer', 'yellowgoose158', 'adelheid.harrer@example.com', '$2y$10$mlsrYRnXYBeIDbUlDRIPfeEVABzFdpuZwPpNDLGLiKBh3amjHFGWe', '2019-05-06 21:19:20', 'female', 'male', '1985-04-25', '', '', 'mittelweg 52', 'polch', 'germany', '58251', '0176-1817049', NULL, NULL, '0', '0', '9a2014e28988ced2d582', 1, NULL, NULL),
(135, 'sabine', 'gÃ¶tte', 'tinyrabbit871', 'sabine.gÃ¶tte@example.com', '$2y$10$qDYeEV215WjIB/MGO2ObRexmoLz5TCBP/lDdsOZGh1ZYusQd94mhe', '2019-05-06 21:19:21', 'female', 'male', '1993-04-01', '', '', 'kirchstraÃŸe 88', 'weimar', 'germany', '68703', '0173-5135170', NULL, NULL, '0', '0', '802e5449d4b0454d701a', 1, NULL, NULL),
(136, 'denise', 'duncan', 'whiteswan196', 'denise.duncan@example.com', '$2y$10$Qa5yqkbhmPTRQoC5zusi8ezGd34c/UzVGikqSUr95c3GPe3Rs95/m', '2019-05-06 21:19:21', 'female', 'male', '1975-07-19', '', '', '4310 dane st', 'sunshine coast', 'australia', '7691', '0482-761-040', NULL, NULL, '0', '0', '75d41ec49b85a96ba926', 1, 'e41a6119fef9e035', '2019-05-08 04:05:41'),
(137, 'lauri', 'lassila', 'goldenbutterfly675', 'lauri.lassila@example.com', '$2y$10$/8btBNIXu50ck5gDkK1n9OdR5JOTNbAd7xkol4rJUx7655x7PRopW', '2019-05-06 21:19:21', 'male', 'female', '1982-05-22', '', '', '3306 tehtaankatu', 'vaala', 'finland', '94472', '040-176-27-26', NULL, NULL, '0', '0', '4c5a46586fda7c486590', 1, NULL, NULL),
(138, 'afet', 'yÄ±ldÄ±zoÄŸlu', 'heavyzebra404', 'afet.yÄ±ldÄ±zoÄŸlu@example.com', '$2y$10$KyUxhkBPHKEohWVX3hOjR.DuI7nV1MIknJAap/u8dm0XxpsjdEqPy', '2019-05-06 21:19:21', 'female', 'male', '1968-12-02', '', '', '567 anafartalar cd', 'mardin', 'turkey', '37432', '(333)-278-8115', NULL, NULL, '0', '0', '0adf7278ce6ea5ea2794', 1, NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
