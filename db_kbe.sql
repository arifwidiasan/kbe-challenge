-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2024 at 11:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kbe`
--

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` int(11) NOT NULL,
  `ebook_name` varchar(100) NOT NULL,
  `pathfile` varchar(254) DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`id`, `ebook_name`, `pathfile`, `created_at`) VALUES
(3, 'test - Soal Assesment Siswa Kelas XII', 'files/Soal-Assessment-Siswa-Kelas-XII.pdf', '2024-03-01'),
(5, 'Soal Assesment Siswa Kelas X', 'files/Soal-Assesment-Siswa-Kelas-X.pdf', '2024-03-02'),
(6, 'Soal Assessment Siswa Kelas XI', 'files/Soal-Assessment-Siswa-Kelas-XI.pdf', '2024-03-02'),
(7, 'Soal Assesment Siswa Kelas XII', 'files/Soal-Assessment-Siswa-Kelas-XII.pdf', '2024-03-02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
