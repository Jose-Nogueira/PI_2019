-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18-Fev-2019 às 01:09
-- Versão do servidor: 10.1.37-MariaDB
-- versão do PHP: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `basepi`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `esplist`
--

CREATE TABLE `esplist` (
  `id` int(11) NOT NULL,
  `Nome` varchar(255) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `X` int(11) NOT NULL,
  `Y` int(11) NOT NULL,
  `gold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `esplist`
--

INSERT INTO `esplist` (`id`, `Nome`, `IP`, `X`, `Y`, `gold`) VALUES
(3, 'asd', '1234rg', 12, 12, 355);

-- --------------------------------------------------------

--
-- Estrutura da tabela `esp_stats`
--

CREATE TABLE `esp_stats` (
  `id` int(11) NOT NULL,
  `id_esp` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  `pid_in` int(11) NOT NULL,
  `pid_out` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `esp_stats`
--

INSERT INTO `esp_stats` (`id`, `id_esp`, `gold`, `pid_in`, `pid_out`, `time`) VALUES
(4, 3, 344, 348, 346, '2019-02-13 20:59:51');

-- --------------------------------------------------------

--
-- Estrutura da tabela `kw_h`
--

CREATE TABLE `kw_h` (
  `id` int(11) NOT NULL,
  `w` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `setor`
--

CREATE TABLE `setor` (
  `id` int(11) NOT NULL,
  `Nome` varchar(255) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `l` int(11) NOT NULL,
  `c` int(11) NOT NULL,
  `mode` enum('auto','on','off') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `setor`
--

INSERT INTO `setor` (`id`, `Nome`, `x`, `y`, `l`, `c`, `mode`) VALUES
(1, 'qwe', 3, 4, 99, 99, 'on');

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `id_setor` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `esplist`
--
ALTER TABLE `esplist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `esp_stats`
--
ALTER TABLE `esp_stats`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `esp_index` (`id_esp`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `setor`
--
ALTER TABLE `setor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setor` (`id_setor`),
  ADD KEY `time` (`time`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `esplist`
--
ALTER TABLE `esplist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `esp_stats`
--
ALTER TABLE `esp_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `setor`
--
ALTER TABLE `setor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `esp_stats`
--
ALTER TABLE `esp_stats`
  ADD CONSTRAINT `esp_index` FOREIGN KEY (`id_esp`) REFERENCES `esplist` (`id`);

--
-- Limitadores para a tabela `status`
--
ALTER TABLE `status`
  ADD CONSTRAINT `setor` FOREIGN KEY (`id_setor`) REFERENCES `setor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
