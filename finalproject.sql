-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2023 at 04:00 PM
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
(23, 'Leart', 'Ramadani', 1234567890, 'leart.ramadani06@gmail.com', 'palidhej', '');

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
(11, 'Pulmologji'),
(13, 'Dermatologji');

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
  `foto` varchar(200) NOT NULL,
  `telefoni` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor_personal_info`
--

INSERT INTO `doctor_personal_info` (`id`, `fullName`, `departamenti`, `gjinia`, `email`, `foto`, `telefoni`, `username`, `password`) VALUES
(5, 'Adnan Rrustemi', 'Kardiologji', 'Mashkull', 'adnanRrustemi@gmail.com', 'IMG-6485c7efa99974.95759593.jpg', '044753444', 'adnanRrustemi', '$2y$10$5KuTjxOcgXqxf/r1ODNNpud6Tobda21h1mypYfssa7/8.0pB3Y.m.'),
(18, 'Dardane Sejdiu', 'Pediatri', 'Femer', 'dardaneSejdiu@gmail.com', 'IMG-647100d67104a7.43975663.jpg', '045456248', 'dardaneSejdiu', '$2y$10$CafiCGtsrG/Bmblps7O5FOYDmlJX0k5No4lcNoaXqHG4XwVggY5tG'),
(20, 'Liridon Krasniqi', 'Kirurgji', 'Mashkull', 'liridonKrasniqi@gmail.com', 'IMG-64710126a55c05.15128042.webp', '044789456', 'liridonKrasniqi', '$2y$10$hZVzfEMUxY9lLKmJhLHsAeTIXKcZJWee/NgadgkPfSexRXF4.frsK'),
(21, 'Nora Bajrami', 'Pediatri', 'Femer', 'noraBajrami@gmail.com', 'IMG-6471018278f025.56100835.webp', '044456987', 'noraBajrami', '$2y$10$aHDtoihzfrABnQJrbi4/b.4mHUjSL/bGNhK.ugZ9H4MDHoMdyxLIe'),
(23, 'Artan Qorri', 'Kardiologji', 'Mashkull', 'artanQorri@gmail.com', 'IMG-647101de297ad2.40635457.webp', '045123855', 'artanQorri', '$2y$10$jS4FSyNgxJW95NduwLBGK.GSl9FqvIu6uIQjW8pjoadnzWmA5GItm'),
(24, 'Elona Hoxha', 'Pediatri', 'Femer', 'elonaHoxha@gmail.com', 'IMG-64a5b0a710ec84.09108968.webp', '045741597', 'elonaHoxha', '$2y$10$3ssBlkAO1U.9YC39B1gm1OOp3v7oNzr29JYjp6wWb1R/X/ARsMpn.');

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
(7, 'IMG-636166c0775f62.29488908.jpg'),
(9, 'IMG-649f1855042ee3.72310711.jpg'),
(10, 'IMG-649f185bb8be78.88441235.jpg'),
(11, 'IMG-649f1862e29f06.24149677.jpg'),
(12, 'IMG-649f257086f623.73052664.jpg'),
(13, 'IMG-649f2574a97b93.67103756.png'),
(14, 'IMG-649f257967f357.18696129.jpg'),
(15, 'IMG-649f257f7b9688.02940590.jpg');

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
(65, 'Adnan Rrustemi', 'Kardiologji', 'Leart Ramadani', '1466408468', 'leart.ramadani06@gmail.com', '2023-03-09', '08:00:00', 'Ftohje e lehte', 'Paracetamol'),
(67, 'Nora Bajrami', 'Pediatri', 'Leart Ramadani', '1234567890', 'leart.ramadani06@gmail.com', '2023-07-06', '09:00:00', 'Ftohje', 'Paracetamol'),
(68, 'Adnan Rrustemi', 'Kardiologji', 'Guesst Guesst', '1561353106', 'guesst2006@gmail.com', '2023-07-20', '10:00:00', 'lorem ipsum', 'dolor'),
(69, 'Nora Bajrami', 'Pediatri', 'Guesst Guesst', '1561353106', 'guesst2006@gmail.com', '2023-07-06', '09:40:00', 'asdas', 'dasdasd');

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
(78, 'Guesst', 'Guesst', 1561353106, 'guesst2006@gmail.com', '044156566', 'Nora Bajrami', 'Pediatri', '2023-07-06', '08:40:00', 'palidhje');

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
(179, 'Artan Qorri', 'Kardiologji', '2023-07-12', '08:00:00', '16:00:00', 20, '08:20:00'),
(180, 'Liridon Krasniqi', 'Kirurgji', '2023-07-20', '08:00:00', '18:00:00', 20, '08:20:00'),
(181, 'Adnan Rrustemi', 'Kardiologji', '2023-07-20', '10:00:00', '18:00:00', 30, '10:30:00'),
(182, 'Elona Hoxha', 'Pediatri', '2023-07-18', '08:00:00', '16:00:00', 20, '08:20:00'),
(183, 'Dardane Sejdiu', 'Pediatri', '2023-07-27', '12:00:00', '18:00:00', 20, '12:00:00'),
(184, 'Adnan Rrustemi', 'Kardiologji', '2023-07-30', '08:10:00', '14:00:00', 20, '08:10:00'),
(185, 'Dardane Sejdiu', 'Pediatri', '2023-07-20', '10:00:00', '18:00:00', 15, '10:00:00');

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
  `veri_code` int(6) NOT NULL,
  `veri_date` date NOT NULL,
  `veri_time` time NOT NULL,
  `verificated` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_table`
--

INSERT INTO `patient_table` (`id`, `emri`, `mbiemri`, `numri_personal`, `gjinia`, `email`, `telefoni`, `ditlindja`, `adresa`, `username`, `password`, `veri_code`, `veri_date`, `veri_time`, `verificated`) VALUES
(1, 'Leart', 'Ramadani', 1234567890, 'Mashkull', 'leart.ramadani06@gmail.com', '044123789', '2000-05-11', 'lorem ipsum', 'leartRamadani', '$2y$10$8lqeXHPI5kgSPSr36Ue38OcXJT0o35GrahIVJMZkNXaxm5cDUwY8a', 456086, '2023-05-25', '20:13:40', 1),
(76, 'Guesst', 'Guesst', 1561353106, 'Mashkull', 'guesst2006@gmail.com', '044156566', '2000-01-01', 'lorem ipsum', 'guesst', '$2y$10$WrgCkUfgYOvBUZFNamYXBO7pwBVyhD11cbA4Gx15a5MZE53rmhuGC', 612949, '2023-07-03', '21:09:25', 1);

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
(523, 'Nora Bajrami', 'Leart', 'Ramadani', 1234567890, 'leart.ramadani06@gmail.com', '2023-07-06', '09:20:00'),
(525, 'Artan Qorri', 'Guesst', 'Guesst', 1561353106, 'guesst2006@gmail.com', '2023-07-12', '08:00:00'),
(526, 'Elona Hoxha', 'Guesst', 'Guesst', 1561353106, 'guesst2006@gmail.com', '2023-07-18', '08:00:00');

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

--
-- Dumping data for table `terminet_e_dyta`
--

INSERT INTO `terminet_e_dyta` (`id`, `doktori`, `emri_pacientit`, `mbiemri_pacientit`, `numri_personal`, `email_pacientit`, `data`) VALUES
(72, 'Nora Bajrami', 'Guesst', 'Guesst', 1561353106, 'guesst2006@gmail.com', '2023-07-13');

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
(417, 'Leart', 'Ramadani', 1234567890, 'Nora Bajrami', 'Pediatri', '2023-07-06', '09:20:00'),
(419, 'Guesst', 'Guesst', 1561353106, 'Artan Qorri', 'Kardiologji', '2023-07-12', '08:00:00'),
(420, 'Guesst', 'Guesst', 1561353106, 'Elona Hoxha', 'Pediatri', '2023-07-18', '08:00:00');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `departamentet`
--
ALTER TABLE `departamentet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `doctor_personal_info`
--
ALTER TABLE `doctor_personal_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `historia_e_termineve`
--
ALTER TABLE `historia_e_termineve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `kerkesatanulimit`
--
ALTER TABLE `kerkesatanulimit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `orari`
--
ALTER TABLE `orari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `patient_table`
--
ALTER TABLE `patient_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `terminet`
--
ALTER TABLE `terminet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=529;

--
-- AUTO_INCREMENT for table `terminet_e_dyta`
--
ALTER TABLE `terminet_e_dyta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `terminet_e_mia`
--
ALTER TABLE `terminet_e_mia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=423;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
