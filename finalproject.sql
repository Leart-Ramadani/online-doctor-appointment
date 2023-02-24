-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2023 at 04:38 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finalproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$hvnrmm3FEJL/SKBnsNByGuRDrhAFLLAOBDCklmDKZlG8Cf4whFmDi');

-- --------------------------------------------------------

--
-- Table structure for table `ankesat`
--

CREATE TABLE `ankesat` (
  `id` int(11) NOT NULL,
  `emri` varchar(100) NOT NULL,
  `mbiemri` varchar(100) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ankesa` varchar(350) NOT NULL,
  `permisimi` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ankesat`
--

INSERT INTO `ankesat` (`id`, `emri`, `mbiemri`, `numri_personal`, `email`, `ankesa`, `permisimi`) VALUES
(14, 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', 'Sjellje joprofesionale nga stafi mjeksor.', '');

-- --------------------------------------------------------

--
-- Table structure for table `departamentet`
--

CREATE TABLE `departamentet` (
  `id` int(11) NOT NULL,
  `departamenti` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departamentet`
--

INSERT INTO `departamentet` (`id`, `departamenti`) VALUES
(1, 'Kirurgji'),
(2, 'Neurologji'),
(3, 'Stomatologji'),
(4, 'Kardiologji'),
(7, 'Infermieri'),
(8, 'Pediatri'),
(11, 'Pulmologji');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_personal_info`
--

CREATE TABLE `doctor_personal_info` (
  `id` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `departamenti` varchar(100) NOT NULL,
  `gjinia` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `biografia` varchar(350) NOT NULL,
  `foto` varchar(200) NOT NULL,
  `telefoni` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor_personal_info`
--

INSERT INTO `doctor_personal_info` (`id`, `fullName`, `departamenti`, `gjinia`, `email`, `biografia`, `foto`, `telefoni`, `username`, `password`) VALUES
(5, 'Adnan Rrustemi', 'Kardiologji', 'Mashkull', 'adnanRrustemi@gmail.com', 'Shkollen e mesme e kreu ne Gjimnazin e shkencave natyrore Sami Frasheri. Studimet e nivelit bachelor i kreu ne Universitetin e Prishtines, ndersa masterin e kreu ne nje universitet perstigjioz ne Zvicer.', 'IMG-63f7ee236f4ac5.56398231.jpg', '044753444', 'adnanRrustemi', '$2y$10$EFDT9DLqJSastV.ji63xvuYmkvW/zpBKpUtjBv796eJVYCwrX9P0W'),
(6, 'Aferdita Gashi', 'Stomatologji', 'Femer', 'aferditaGashi@gmail.com', 'Shkollen e mesme e kreu ne Gjimnazin e shkencave natyrore Sami Frasheri. Studimet e nivelit bachelor i kreu ne Universitetin e Prishtines.', 'IMG-63f7ee8565b874.57395922.jpg', '045789159', 'aferditaGashi', '$2y$10$OCiGEBqH1tbyA7wLyHa8QOfpbUhtyPQaNGnAupWgYu0PGeSq/CiuO'),
(10, 'Fatmir Kelmendi', 'Kirurgji', 'Mashkull', 'fatmirKelmendi@gmail.com', 'Shkollen e mesme e kreu ne SHML Dr.Ali Sokoli. Studimet e nivelit bachelor i kreu ne Universitetin e Prishtines, ndersa masterin e kreu ne nje universitet perstigjioz ne Gjermani.', 'IMG-63f7eebe02b986.70835238.webp', '045895753', 'fatmirKelmendi', '$2y$10$IS4WZGu5hBHgPsZMBSWmpuSwNbbGkpRBVrc.o54FzNmmHLttLzF5a'),
(18, 'Dardane Sejdiu', 'Pediatri', 'Mashkull', 'dardaneSejdiu@gmail.com', 'Shkollen e mesme e kreu ne shkollen e mjeksis Ali Sokoli. Studimet e nivelit bachelor i kreu ne Universitetin e Prishtines.', 'IMG-63f7ef5138d442.62292006.jpg', '045456248', 'dardaneSejdiu', '$2y$10$s8e7tjLmAjq6sJRpX86OG.X8oKvP9k9dt5DVGSamOZRWnQclLGbaS');

-- --------------------------------------------------------

--
-- Table structure for table `galeria`
--

CREATE TABLE `galeria` (
  `id` int(11) NOT NULL,
  `foto_src` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `galeria`
--

INSERT INTO `galeria` (`id`, `foto_src`) VALUES
(2, 'IMG-6354690d693763.22222472.jpg'),
(4, 'IMG-635469156f2f58.96223814.png'),
(5, 'IMG-6361668e607565.79647024.jpg'),
(6, 'IMG-63546a751a8d20.56349852.jpg'),
(7, 'IMG-636166c0775f62.29488908.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `historia_e_termineve`
--

CREATE TABLE `historia_e_termineve` (
  `id` int(11) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `departamenti` varchar(100) NOT NULL,
  `pacienti` varchar(100) NOT NULL,
  `numri_personal` varchar(10) NOT NULL,
  `email_pacientit` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `diagnoza` varchar(250) NOT NULL,
  `recepti` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `historia_e_termineve`
--

INSERT INTO `historia_e_termineve` (`id`, `doktori`, `departamenti`, `pacienti`, `numri_personal`, `email_pacientit`, `data`, `ora`, `diagnoza`, `recepti`) VALUES
(65, 'Adnan Rrustemi', 'Kardiologji', 'Leart Ramadani', '1466408468', 'leart.ramadani06@gmail.com', '2023-03-09', '08:00:00', 'Ftohje e lehte', 'Paracetamol');

-- --------------------------------------------------------

--
-- Table structure for table `kerkesatanulimit`
--

CREATE TABLE `kerkesatanulimit` (
  `id` int(11) NOT NULL,
  `emri_pacientit` varchar(100) NOT NULL,
  `mbiemri_pacientit` varchar(100) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefoni` varchar(100) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `departamenti` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `arsyeja_anulimit` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kerkesatanulimit`
--

INSERT INTO `kerkesatanulimit` (`id`, `emri_pacientit`, `mbiemri_pacientit`, `numri_personal`, `email`, `telefoni`, `doktori`, `departamenti`, `data`, `ora`, `arsyeja_anulimit`) VALUES
(47, 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', '044489949', 'Adnan Rrustemi', 'Kardiologji', '2023-03-09', '08:20:00', 'Arsyje personale');

-- --------------------------------------------------------

--
-- Table structure for table `orari`
--

CREATE TABLE `orari` (
  `id` int(11) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `departamenti` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `nga_ora` time NOT NULL,
  `deri_oren` time NOT NULL,
  `kohezgjatja` int(11) NOT NULL,
  `zene_deri` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orari`
--

INSERT INTO `orari` (`id`, `doktori`, `departamenti`, `data`, `nga_ora`, `deri_oren`, `kohezgjatja`, `zene_deri`) VALUES
(164, 'Adnan Rrustemi', 'Kardiologji', '2023-03-09', '08:00:00', '16:00:00', 20, '09:00:00'),
(165, 'Aferdita Gashi', 'Stomatologji', '2023-03-09', '10:00:00', '18:00:00', 15, '10:15:00'),
(166, 'Fatmir Kelmendi', 'Kirurgji', '2023-03-09', '10:00:00', '18:00:00', 15, '10:15:00'),
(167, 'Dardane Sejdiu', 'Pediatri', '2023-03-08', '08:00:00', '16:00:00', 15, '08:15:00'),
(168, 'Aferdita Gashi', 'Stomatologji', '2023-03-10', '10:00:00', '15:00:00', 20, '10:20:00'),
(169, 'Dardane Sejdiu', 'Pediatri', '2023-03-06', '08:00:00', '16:00:00', 20, '08:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `patient_table`
--

CREATE TABLE `patient_table` (
  `id` int(11) NOT NULL,
  `emri` varchar(100) NOT NULL,
  `mbiemri` varchar(100) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `gjinia` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefoni` varchar(100) NOT NULL,
  `ditlindja` date NOT NULL,
  `adresa` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(225) NOT NULL,
  `verification_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_table`
--

INSERT INTO `patient_table` (`id`, `emri`, `mbiemri`, `numri_personal`, `gjinia`, `email`, `telefoni`, `ditlindja`, `adresa`, `username`, `password`, `verification_status`) VALUES
(21, 'Leart', 'Ramadani', 1466408468, 'Mashkull', 'leart.ramadani06@gmail.com', '045123456', '2006-05-11', 'Hasan Prishtina', 'leartRamadani', '$2y$10$6g5QFQwX8mOZw3Q0T/i3MuJFiyF3NqcEm9T4aTla0YUnufRjtgm1.', 'true'),
(35, 'guesst', 'demo', 563403403, 'Mashkull', 'guesst2006@gmail.com', '044123456', '2000-05-22', 'deffd', 'guesst', '$2y$10$N7CDndUkoaimhVd9i3/VHeGF8lCnOs1qYErj/Yt.nyqEtEH40/Ysq', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `terminet`
--

CREATE TABLE `terminet` (
  `id` int(11) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `emri_pacientit` varchar(100) NOT NULL,
  `mbiemri_pacientit` varchar(100) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `email_pacientit` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `terminet`
--

INSERT INTO `terminet` (`id`, `doktori`, `emri_pacientit`, `mbiemri_pacientit`, `numri_personal`, `email_pacientit`, `data`, `ora`) VALUES
(489, 'Aferdita Gashi', 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', '2023-03-09', '10:00:00'),
(490, 'Adnan Rrustemi', 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', '2023-03-09', '08:20:00'),
(491, 'Fatmir Kelmendi', 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', '2023-03-09', '10:00:00'),
(492, 'Dardane Sejdiu', 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', '2023-03-08', '08:00:00'),
(493, 'Aferdita Gashi', 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', '2023-03-10', '10:00:00'),
(494, 'Dardane Sejdiu', 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', '2023-03-06', '08:00:00'),
(495, 'Adnan Rrustemi', 'guesst', 'demo', 563403403, 'guesst2006@gmail.com', '2023-03-09', '08:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `terminet_e_dyta`
--

CREATE TABLE `terminet_e_dyta` (
  `id` int(11) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `emri_pacientit` varchar(100) NOT NULL,
  `mbiemri_pacientit` varchar(100) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `email_pacientit` varchar(100) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `terminet_e_mia`
--

CREATE TABLE `terminet_e_mia` (
  `id` int(11) NOT NULL,
  `emri_pacientit` varchar(100) NOT NULL,
  `mbiemri_pacientit` varchar(100) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `departamenti` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `terminet_e_mia`
--

INSERT INTO `terminet_e_mia` (`id`, `emri_pacientit`, `mbiemri_pacientit`, `numri_personal`, `doktori`, `departamenti`, `data`, `ora`) VALUES
(383, 'Leart', 'Ramadani', 1466408468, 'Aferdita Gashi', 'Stomatologji', '2023-03-09', '10:00:00'),
(384, 'Leart', 'Ramadani', 1466408468, 'Adnan Rrustemi', 'Kardiologji', '2023-03-09', '08:20:00'),
(385, 'Leart', 'Ramadani', 1466408468, 'Fatmir Kelmendi', 'Kirurgji', '2023-03-09', '10:00:00'),
(386, 'Leart', 'Ramadani', 1466408468, 'Dardane Sejdiu', 'Pediatri', '2023-03-08', '08:00:00'),
(387, 'Leart', 'Ramadani', 1466408468, 'Aferdita Gashi', 'Stomatologji', '2023-03-10', '10:00:00'),
(388, 'Leart', 'Ramadani', 1466408468, 'Dardane Sejdiu', 'Pediatri', '2023-03-06', '08:00:00'),
(389, 'guesst', 'demo', 563403403, 'Adnan Rrustemi', 'Kardiologji', '2023-03-09', '08:40:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ankesat`
--
ALTER TABLE `ankesat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departamentet`
--
ALTER TABLE `departamentet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_personal_info`
--
ALTER TABLE `doctor_personal_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `historia_e_termineve`
--
ALTER TABLE `historia_e_termineve`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kerkesatanulimit`
--
ALTER TABLE `kerkesatanulimit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orari`
--
ALTER TABLE `orari`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_table`
--
ALTER TABLE `patient_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminet`
--
ALTER TABLE `terminet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminet_e_dyta`
--
ALTER TABLE `terminet_e_dyta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminet_e_mia`
--
ALTER TABLE `terminet_e_mia`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ankesat`
--
ALTER TABLE `ankesat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `departamentet`
--
ALTER TABLE `departamentet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `doctor_personal_info`
--
ALTER TABLE `doctor_personal_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `historia_e_termineve`
--
ALTER TABLE `historia_e_termineve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `kerkesatanulimit`
--
ALTER TABLE `kerkesatanulimit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `orari`
--
ALTER TABLE `orari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `patient_table`
--
ALTER TABLE `patient_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `terminet`
--
ALTER TABLE `terminet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=496;

--
-- AUTO_INCREMENT for table `terminet_e_dyta`
--
ALTER TABLE `terminet_e_dyta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `terminet_e_mia`
--
ALTER TABLE `terminet_e_mia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
