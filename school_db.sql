-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 12:38 AM
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
-- Database: `school_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `parents_contact`
--

CREATE TABLE `parents_contact` (
  `student_id` int(11) UNSIGNED NOT NULL,
  `parent name` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `telephone number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents_contact`
--

INSERT INTO `parents_contact` (`student_id`, `parent name`, `email`, `telephone number`) VALUES
(5001, 'Ashley Bennett', 'a.benett@gmail.com', '876-555-5001'),
(5002, 'Abigail Carter', 'a.carter@yahoo.com', '876-555-5002'),
(5003, 'Will Brooks', 'w.brooks@gmail.com', '876-555-5003'),
(5004, 'Alexander Hayes', 'a.hayes@yahoo.com', '876-555-5004'),
(5005, 'Dana Reed\r\n', 'd.reed@gmail.com', '876-555-5005'),
(5006, 'Marvin Cooper\r\n', 'm.cooper@yahoo.com', '876-555-5006'),
(5007, 'Elizabeth Sullivan\r\n', 'e.sullivan@gmail.com', '876-555-5007'),
(5008, 'Natasha Fisher', 'n.fisher@yahoo.com', '876-555-5008'),
(5009, 'Janet Adams\r\n', 'j.adams@yahoo.com', '876-555-5009'),
(5010, 'Karen Parker\r\n', 'k.parker@gmail.com', '876-555-5010'),
(5011, 'Ben Howard\r\n', 'b.howard@yahoo.com', '876-555-5011'),
(5012, 'Natalie Wright\r\n', 'n.wright@gmail.com', '876-555-5012'),
(5013, 'Andre Morgan\r\n', 'a.morgan@yahoo.com', '876-555-5013'),
(5014, 'Samantha Collins\r\n', 's.collins@gmail.com', '876-555-5014'),
(5015, 'Rick Mitchell', 'r.mitchell@yahoo.com', '876-555-5015');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(11) NOT NULL,
  `name` text NOT NULL,
  `grade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `grade`) VALUES
(5001, 'James Bennet', 5),
(5002, 'Michael Carter', 5),
(5003, 'William Brooks', 5),
(5004, 'Alexander Hayes', 5),
(5005, 'Daniel Reed', 5),
(5006, 'Matthew Cooper', 5),
(5007, 'Ethan Sullivan', 5),
(5008, 'Noah Fisher', 5),
(5009, 'Joshua Adams', 5),
(5010, 'Christopher Parker', 5),
(5011, 'Benjamin Howard', 5),
(5012, 'Nathaniel Wright', 5),
(5013, 'Andrew Morgan', 5),
(5014, 'Samuel Collins', 5),
(5015, 'Ryan Mitchell', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'principal', 'principal123', 'Principal'),
(2, 'dean', 'dean123', 'Dean'),
(3, 'teacher', 'teacher123', 'Teacher'),
(4, '123', '123', 'Prefect'),
(5, '1234', '1234', 'Security');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parents_contact`
--
ALTER TABLE `parents_contact`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_db_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parents_contact`
--
ALTER TABLE `parents_contact`
  MODIFY `student_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5016;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5016;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;