-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2023 at 04:04 PM
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
(19, 'Leart', 'Ramadani', 1234567890, 'leart.ramadani06@gmail.com', 'Sjelle joprofesionale', ''),
(21, 'test', 'test', 1478523690, 'guesst2006@gmail.com', 'gvjgj', ''),
(22, 'test', 'test', 1478523690, 'guesst2006@gmail.com', 'fgfhfgh', '');

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
(5, 'Adnan Rrustemi', 'Kardiologji', 'Mashkull', 'adnanRrustemi@gmail.com', 'Dr. Adnan Rrustemi është një mjek kardiolog me përvojë. \r\nAi \r\nspecializohet në sëmundjet e zemrës dhe ofron trajtim cilësor për pacientët. \r\nProfesionalizmi dhe pasioni i tij bëjnë ndryshimin.', 'IMG-6485c7efa99974.95759593.jpg', '044753444', 'adnanRrustemi', '$2y$10$5KuTjxOcgXqxf/r1ODNNpud6Tobda21h1mypYfssa7/8.0pB3Y.m.'),
(18, 'Dardane Sejdiu', 'Pediatri', 'Femer', 'dardaneSejdiu@gmail.com', 'Dr. Dardane Sejdiu është një mjek pediatër me përvojë. Ajo specializohet në kujdesin e fëmijëve dhe ofron trajtim cilësor për pacientët e vegjël. Përulësia dhe devotshmëria e saj e bëjnë atë një mjek të shkëlqyeshëm për fëmijët.', 'IMG-647100d67104a7.43975663.jpg', '045456248', 'dardaneSejdiu', '$2y$10$CafiCGtsrG/Bmblps7O5FOYDmlJX0k5No4lcNoaXqHG4XwVggY5tG'),
(20, 'Liridon Krasniqi', 'Kirurgji', 'Mashkull', 'liridonKrasniqi@gmail.com', 'Dr. Liridon Krasniqi është një mjek kardiolog me përvojë të gjerë. Ai specializohet në diagnostikimin dhe trajtimin e sëmundjeve të zemrës. Ekspertiza e tij dhe përkushtimi ndaj pacientëve e bëjnë atë një autoritet në fushën e kardiologjisë.', 'IMG-64710126a55c05.15128042.webp', '044789456', 'liridonKrasniqi', '$2y$10$hZVzfEMUxY9lLKmJhLHsAeTIXKcZJWee/NgadgkPfSexRXF4.frsK'),
(21, 'Nora Bajrami', 'Pediatri', 'Femer', 'noraBajrami@gmail.com', 'Pediatre me përkushtim të veçantë për pacientët e vegjël dhe familjet. Shërben komunitetin shqiptar për më shumë se një dekadë.', 'IMG-6471018278f025.56100835.webp', '044456987', 'noraBajrami', '$2y$10$aHDtoihzfrABnQJrbi4/b.4mHUjSL/bGNhK.ugZ9H4MDHoMdyxLIe'),
(23, 'Artan Qorri', 'Kardiologji', 'Mashkull', 'artanQorri@gmail.com', 'Dr. Artan Qorri është një mjek kardiolog i njohur. Ai ka përvojë të gjerë në diagnostikimin dhe trajtimin e sëmundjeve të zemrës. Devotshmëria dhe ekspertiza e tij e bëjnë atë një profesionist të shquar në fushën e kardiologjisë.', 'IMG-647101de297ad2.40635457.webp', '045123855', 'artanQorri', '$2y$10$jS4FSyNgxJW95NduwLBGK.GSl9FqvIu6uIQjW8pjoadnzWmA5GItm'),
(24, 'Elona Hoxha', 'Dermatologji', 'Femer', 'elonaHoxha@gmail.com', 'Dr. Elona Hoxha është një dermatologe me njohuri të thella. Ajo specializohet në diagnostikimin dhe trajtimin e sëmundjeve të lëkurës. Ekspertiza e saj në dermatologji dhe përkushtimi ndaj pacientëve e bëjnë atë një profesioniste të shquar në fushën', 'IMG-647102e786c4a7.37425444.webp', '045741597', 'elonaHoxha', '$2y$10$3k3/VTg/l.1g/MYhHV3jqeTgZSdgnjsR7bmO.GyJ4jHNczU2GG216');

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
(62, 'Leart', 'Ramadani', 1234567890, 'leart.ramadani06@gmail.com', '044123789', 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:00:00', 'ddfgdf'),
(63, 'Leart', 'Ramadani', 1234567890, 'leart.ramadani06@gmail.com', '044123789', 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:00:00', 'ddfgdf'),
(64, 'Leart', 'Ramadani', 1234567890, 'leart.ramadani06@gmail.com', '044123789', 'Nora Bajrami', 'Pediatri', '2023-05-31', '12:00:00', 'uyvugv'),
(65, 'Test', 'Test', 2147483647, 'guesst2006@gmail.com', '044125875', 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:00:00', 'Personal]'),
(66, 'Leart', 'Ramadani', 1234567890, 'leart.ramadani06@gmail.com', '044123789', 'Dardane Sejdiu', 'Pediatri', '2023-05-31', '08:00:00', 'lll'),
(67, 'Test', 'Test', 2147483647, 'guesst2066@gmail.com', '044123654', 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:00:00', 'jjj'),
(68, 'Test', 'Test', 2147483647, 'guesst2066@gmail.com', '044123654', 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:00:00', 'jujh'),
(69, 'test', 'test', 1513516516, 'guesst2006@gmail.com', '044455666', 'Artan Qorri', 'Kardiologji', '2023-05-31', '09:00:00', 'jashte vendi'),
(70, 'Test', 'Test', 1513516516, 'guesst2006@gmail.com', '044455666', 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:00:00', 'jhbjg'),
(71, 'test', 'test', 1478523690, 'guesst2006@gmail.com', '045123987', 'Artan Qorri', 'Kardiologji', '2023-05-31', '09:15:00', 'dfdf'),
(72, 'test', 'test', 1478523690, 'guesst2006@gmail.com', '045123987', 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:55:00', 'dgdg'),
(73, 'Leart', 'Ramadani', 1234567890, 'leart.ramadani06@gmail.com', '044123789', 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:00:00', 'fvfvfv'),
(74, 'test', 'test', 1478523690, 'guesst2006@gmail.com', '045123987', 'Dardane Sejdiu', 'Pediatri', '2023-05-31', '08:15:00', 'Personlae');

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
(177, 'Nora Bajrami', 'Pediatri', '2023-07-13', '08:00:00', '16:00:00', 15, '09:00:00');

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
(493, 'Aferdita Gashi', 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', '2023-03-10', '10:00:00'),
(496, 'Adnan Rrustemi', 'Leart', 'Ramadani', 1466408468, 'leart.ramadani06@gmail.com', '2023-05-31', '08:00:00'),
(511, 'Adnan Rrustemi', 'test', 'test', 1478523690, 'guesst2006@gmail.com', '2023-05-31', '08:55:00'),
(512, 'Adnan Rrustemi', 'Leart', 'Ramadani', 1234567890, 'leart.ramadani06@gmail.com', '2023-05-31', '09:10:00'),
(513, 'Dardane Sejdiu', 'test', 'test', 1478523690, 'guesst2006@gmail.com', '2023-05-31', '08:15:00'),
(517, 'Nora Bajrami', 'test', 'test', 1478523690, 'guesst2006@gmail.com', '2023-07-13', '08:45:00');

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
(390, 'Leart', 'Ramadani', 1466408468, 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:00:00'),
(405, 'test', 'test', 1478523690, 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '08:55:00'),
(406, 'Leart', 'Ramadani', 1234567890, 'Adnan Rrustemi', 'Kardiologji', '2023-05-31', '09:10:00'),
(407, 'test', 'test', 1478523690, 'Dardane Sejdiu', 'Pediatri', '2023-05-31', '08:15:00'),
(411, 'test', 'test', 1478523690, 'Nora Bajrami', 'Pediatri', '2023-07-13', '08:45:00');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `departamentet`
--
ALTER TABLE `departamentet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `doctor_personal_info`
--
ALTER TABLE `doctor_personal_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `historia_e_termineve`
--
ALTER TABLE `historia_e_termineve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `kerkesatanulimit`
--
ALTER TABLE `kerkesatanulimit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `orari`
--
ALTER TABLE `orari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `patient_table`
--
ALTER TABLE `patient_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `terminet`
--
ALTER TABLE `terminet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=518;

--
-- AUTO_INCREMENT for table `terminet_e_dyta`
--
ALTER TABLE `terminet_e_dyta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `terminet_e_mia`
--
ALTER TABLE `terminet_e_mia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=412;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
