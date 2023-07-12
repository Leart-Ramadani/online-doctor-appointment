-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2023 at 03:45 PM
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
-- Database: `online-appointment_v.2.0`
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
  `pacienti` varchar(100) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ankesa` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ankesat`
--

INSERT INTO `ankesat` (`id`, `pacienti`, `numri_personal`, `email`, `ankesa`) VALUES
(27, 'testte', 1234125678, 'guesst2006@gmail.com', 'Sjellje joprofesionale');

-- --------------------------------------------------------

--
-- Table structure for table `departamentet`
--

CREATE TABLE `departamentet` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departamentet`
--

INSERT INTO `departamentet` (`id`, `name`) VALUES
(1, 'Kirurgji'),
(2, 'Neurologji'),
(3, 'Stomatologji'),
(4, 'Kardiologji'),
(7, 'Infermieri'),
(8, 'Pediatri'),
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
  `departamenti` int(11) NOT NULL,
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
(70, 'Olivia Michaels', 1, 'testte', '1234125678', 'guesst2006@gmail.com', '2023-07-24', '08:00:00', 'asd', 'asd');

-- --------------------------------------------------------

--
-- Table structure for table `kerkesatanulimit`
--

CREATE TABLE `kerkesatanulimit` (
  `id` int(11) NOT NULL,
  `pacienti` varchar(100) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefoni` varchar(100) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `departamenti` int(11) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `arsyeja_anulimit` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kerkesatanulimit`
--

INSERT INTO `kerkesatanulimit` (`id`, `pacienti`, `numri_personal`, `email`, `telefoni`, `doktori`, `departamenti`, `data`, `ora`, `arsyeja_anulimit`) VALUES
(94, 'testte', 1234125678, 'guesst2006@gmail.com', '045122558', 'Jonathan Reed', 4, '2023-07-24', '10:00:00', 'Arsyeje personale!');

-- --------------------------------------------------------

--
-- Table structure for table `orari`
--

CREATE TABLE `orari` (
  `id` int(11) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `departamenti` int(11) NOT NULL,
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
(191, 'Olivia Michaels', 1, '2023-07-24', '08:00:00', '16:00:00', 20, '08:20:00'),
(192, 'Benjamin Sullivan', 2, '2023-07-24', '10:00:00', '18:00:00', 20, '10:20:00'),
(193, 'Maya Patel', 3, '2023-07-24', '08:00:00', '16:00:00', 30, '08:30:00'),
(194, 'Jonathan Reed', 4, '2023-07-24', '10:00:00', '16:00:00', 20, '10:20:00'),
(195, 'Grace Anderson', 7, '2023-07-24', '08:00:00', '16:00:00', 15, '08:15:00'),
(196, 'Liam Smith', 8, '2023-07-24', '12:00:00', '18:00:00', 30, '12:30:00'),
(197, 'Sophia Ramirez', 13, '2023-07-24', '08:00:00', '16:00:00', 20, '08:20:00');

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
(1, 'Leart', 'Ramadani', 1234567890, 'Mashkull', 'leart.ramadani06@gmail.com', '044123789', '2000-05-11', 'lorem ipsum', 'leartRamadani', '$2y$10$8lqeXHPI5kgSPSr36Ue38OcXJT0o35GrahIVJMZkNXaxm5cDUwY8a', 456086, '2023-05-25', '20:13:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `terminet`
--

CREATE TABLE `terminet` (
  `id` int(11) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `departamenti` int(11) NOT NULL,
  `pacienti` varchar(150) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `email_pacientit` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `terminet`
--

INSERT INTO `terminet` (`id`, `doktori`, `departamenti`, `pacienti`, `numri_personal`, `email_pacientit`, `data`, `ora`) VALUES
(547, 'Benjamin Sullivan', 2, 'testte', 1234125678, 'guesst2006@gmail.com', '2023-07-24', '10:00:00'),
(548, 'Maya Patel', 3, 'testte', 1234125678, 'guesst2006@gmail.com', '2023-07-24', '08:00:00'),
(549, 'Jonathan Reed', 4, 'testte', 1234125678, 'guesst2006@gmail.com', '2023-07-24', '10:00:00'),
(550, 'Grace Anderson', 7, 'testte', 1234125678, 'guesst2006@gmail.com', '2023-07-24', '08:00:00'),
(552, 'Sophia Ramirez', 13, 'testte', 1234125678, 'guesst2006@gmail.com', '2023-07-24', '08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `terminet_e_dyta`
--

CREATE TABLE `terminet_e_dyta` (
  `id` int(11) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `departament` int(11) NOT NULL,
  `pacienti` varchar(100) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `email_pacientit` varchar(100) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `terminet_e_dyta`
--

INSERT INTO `terminet_e_dyta` (`id`, `doktori`, `departament`, `pacienti`, `numri_personal`, `email_pacientit`, `data`) VALUES
(72, 'Nora Bajrami', 1, 'Guesst', 1561353106, 'guesst2006@gmail.com', '2023-07-13'),
(73, 'Liam Smith', 8, 'testte', 1234125678, 'guesst2006@gmail.com', '2023-07-31');

-- --------------------------------------------------------

--
-- Table structure for table `terminet_e_mia`
--

CREATE TABLE `terminet_e_mia` (
  `id` int(11) NOT NULL,
  `pacienti` varchar(150) NOT NULL,
  `numri_personal` int(10) NOT NULL,
  `doktori` varchar(100) NOT NULL,
  `departamenti` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `terminet_e_mia`
--

INSERT INTO `terminet_e_mia` (`id`, `pacienti`, `numri_personal`, `doktori`, `departamenti`, `data`, `ora`) VALUES
(425, 'testte', 1234125678, 'test test', '1', '2222-01-01', '11:52:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userType` int(3) NOT NULL,
  `fullName` varchar(150) NOT NULL,
  `personal_id` int(10) NOT NULL,
  `departament` int(11) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `birthday` date NOT NULL,
  `adress` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `veri_code` int(6) NOT NULL,
  `veri_date` date NOT NULL,
  `veri_time` time NOT NULL,
  `verificated` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userType`, `fullName`, `personal_id`, `departament`, `gender`, `email`, `phone`, `photo`, `birthday`, `adress`, `username`, `password`, `veri_code`, `veri_date`, `veri_time`, `verificated`) VALUES
(3, 1, 'testte', 1234125678, 1, 'Mashkull', 'guesst2006@gmail.com', '045122558', '', '2000-01-01', 'dasdasad', 'test', '$2y$10$oZSDRhxxmNstWMLhOGxXT.cyW67fJz0SLuKyKuStZ/cd3FFUfBzPa', 315334, '2023-07-10', '15:46:57', 1),
(6, 3, '', 0, 1, '', '', '', '', '0000-00-00', '', 'admin', '$2y$10$QRDfMM8OZY3Vf.crq2KVP.L3lJrh7Zq5xMXLJEwOCXCsEzwyIes6a', 0, '0000-00-00', '00:00:00', 0),
(9, 2, 'Olivia Michaels', 0, 1, 'Femer', 'oliviaMichaels@gmail.com', '045123789', 'IMG-64ae8d9b44aaa4.82457204.webp', '0000-00-00', '', 'oliviaMichaels', '$2y$10$dADfq4upOFT0v2OYr8FtNuvVuwMfI/j.OaajOILF/fPHQFTTpVAWG', 0, '0000-00-00', '00:00:00', 0),
(10, 2, 'Benjamin Sullivan', 0, 2, 'Mashkull', 'benjaminSullivan@gmail.com', '045151121', 'IMG-64ae8df6434678.89671794.jpg', '0000-00-00', '', 'benjaminSullivan', '$2y$10$Jle0I6zoWHobuKPZScOkoOnKE/T198T1VStbiX5nXCTN76Iz7j3BS', 0, '0000-00-00', '00:00:00', 0),
(11, 2, 'Maya Patel', 0, 3, 'Femer', 'mayaPatel@gmail.com', '049125111', 'IMG-64ae8e26a0ca90.09146637.webp', '0000-00-00', '', 'mayaPatel', '$2y$10$TBJEDpwJ6Yo5uaDTWL1.p.H3lzTbT90iWjQbJsBa1rNVCEahP4Fhu', 0, '0000-00-00', '00:00:00', 0),
(12, 2, 'Jonathan Reed', 0, 4, 'Mashkull', 'jonathanReed@gmail.com', '045675142', 'IMG-64ae8e4bbcd757.78182653.webp', '0000-00-00', '', 'jonathanReed', '$2y$10$q.m9dFLjIa89Yw9nl8ckQ.vOtDhB7WcjS2/pDK89n8NE..mUcL6pm', 0, '0000-00-00', '00:00:00', 0),
(13, 2, 'Grace Anderson', 0, 7, 'Femer', 'graceAnderson@gmail.com', '045125111', 'IMG-64ae8e991c5651.34480751.png', '0000-00-00', '', 'graceAnderson', '$2y$10$AAt0aB./4dYqDTlNAqatYOEoxSc9JGuVF5P/7Ktk79.g2Gp5wRtTy', 0, '0000-00-00', '00:00:00', 0),
(14, 2, 'Liam Smith', 0, 8, 'Mashkull', 'liamSmith@gmail.com', '045788555', 'IMG-64ae8ee66e0ff2.23642687.webp', '0000-00-00', '', 'liamSmith', '$2y$10$G16goJdJ5MBPQUNSNWJT5.M6VXY2tYjrlFPNyC8nCFoB4Rvm8bOJG', 0, '0000-00-00', '00:00:00', 0),
(15, 2, 'Sophia Ramirez', 0, 13, 'Femer', 'sophiaRamirez@gmail.com', '045148654', 'IMG-64ae8f113690c2.79129875.jpg', '0000-00-00', '', 'sophiaRamirez', '$2y$10$WWuB3Qg6JZTDfzx.ahuWtecQUutD7lm/EV7TNtbsomRbNpkkZo5vG', 0, '0000-00-00', '00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `usertypes`
--

CREATE TABLE `usertypes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usertypes`
--

INSERT INTO `usertypes` (`id`, `name`) VALUES
(1, 'pacient'),
(2, 'doctor'),
(3, 'admin');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `departamenti` (`departamenti`);

--
-- Indexes for table `kerkesatanulimit`
--
ALTER TABLE `kerkesatanulimit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departamenti` (`departamenti`);

--
-- Indexes for table `orari`
--
ALTER TABLE `orari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departamenti` (`departamenti`);

--
-- Indexes for table `patient_table`
--
ALTER TABLE `patient_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminet`
--
ALTER TABLE `terminet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departamenti` (`departamenti`);

--
-- Indexes for table `terminet_e_dyta`
--
ALTER TABLE `terminet_e_dyta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departament` (`departament`);

--
-- Indexes for table `terminet_e_mia`
--
ALTER TABLE `terminet_e_mia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userType` (`userType`),
  ADD KEY `departament` (`departament`);

--
-- Indexes for table `usertypes`
--
ALTER TABLE `usertypes`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `departamentet`
--
ALTER TABLE `departamentet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `kerkesatanulimit`
--
ALTER TABLE `kerkesatanulimit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `orari`
--
ALTER TABLE `orari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `patient_table`
--
ALTER TABLE `patient_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `terminet`
--
ALTER TABLE `terminet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=553;

--
-- AUTO_INCREMENT for table `terminet_e_dyta`
--
ALTER TABLE `terminet_e_dyta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `terminet_e_mia`
--
ALTER TABLE `terminet_e_mia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=431;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `usertypes`
--
ALTER TABLE `usertypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `historia_e_termineve`
--
ALTER TABLE `historia_e_termineve`
  ADD CONSTRAINT `historia_e_termineve_ibfk_1` FOREIGN KEY (`departamenti`) REFERENCES `departamentet` (`id`);

--
-- Constraints for table `kerkesatanulimit`
--
ALTER TABLE `kerkesatanulimit`
  ADD CONSTRAINT `kerkesatanulimit_ibfk_1` FOREIGN KEY (`departamenti`) REFERENCES `departamentet` (`id`);

--
-- Constraints for table `orari`
--
ALTER TABLE `orari`
  ADD CONSTRAINT `orari_ibfk_1` FOREIGN KEY (`departamenti`) REFERENCES `departamentet` (`id`);

--
-- Constraints for table `terminet`
--
ALTER TABLE `terminet`
  ADD CONSTRAINT `terminet_ibfk_1` FOREIGN KEY (`departamenti`) REFERENCES `departamentet` (`id`);

--
-- Constraints for table `terminet_e_dyta`
--
ALTER TABLE `terminet_e_dyta`
  ADD CONSTRAINT `terminet_e_dyta_ibfk_1` FOREIGN KEY (`departament`) REFERENCES `departamentet` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`userType`) REFERENCES `usertypes` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`departament`) REFERENCES `departamentet` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
