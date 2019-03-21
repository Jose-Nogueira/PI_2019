-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 16-Mar-2019 às 10:54
-- Versão do servidor: 10.1.37-MariaDB-0+deb9u1
-- PHP Version: 7.0.33-0+deb9u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `gold` int(11) NOT NULL,
  `Setor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `esplist`
--

INSERT INTO `esplist` (`id`, `Nome`, `IP`, `X`, `Y`, `gold`, `Setor_id`) VALUES
(3, 'esp_porta', '1234rg', 12, 12, 600, 1),
(4, 'esp_janela', '1234', 12, 12, 850, 2);

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
(1, 4, 850, 769, 255, '2019-03-16 10:53:58'),
(2, 3, 600, 952, 0, '2019-03-16 10:54:35');

-- --------------------------------------------------------

--
-- Estrutura da tabela `kw_h`
--

CREATE TABLE `kw_h` (
  `id` int(11) NOT NULL,
  `id_setor` int(11) NOT NULL,
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
  `mode` enum('auto','on','off') NOT NULL,
  `id_out_pin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `setor`
--

INSERT INTO `setor` (`id`, `Nome`, `x`, `y`, `l`, `c`, `mode`, `id_out_pin`) VALUES
(1, 'porta', 3, 4, 99, 99, 'auto', 1),
(2, 'janela', 11, 11, 11, 11, 'auto', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `id_setor` int(11) NOT NULL,
  `status` enum('on','off') NOT NULL,
  `mode` enum('auto','on','off') NOT NULL,
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
  ADD KEY `id` (`id`),
  ADD KEY `esp_to_setor` (`Setor_id`);

--
-- Indexes for table `esp_stats`
--
ALTER TABLE `esp_stats`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `esp_index` (`id_esp`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `kw_h`
--
ALTER TABLE `kw_h`
  ADD KEY `id` (`id`),
  ADD KEY `setor_kwh` (`id_setor`);

--
-- Indexes for table `setor`
--
ALTER TABLE `setor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_out_pin` (`id_out_pin`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `esp_stats`
--
ALTER TABLE `esp_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `kw_h`
--
ALTER TABLE `kw_h`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `setor`
--
ALTER TABLE `setor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `esplist`
--
ALTER TABLE `esplist`
  ADD CONSTRAINT `esp_to_setor` FOREIGN KEY (`Setor_id`) REFERENCES `setor` (`id_out_pin`);

--
-- Limitadores para a tabela `esp_stats`
--
ALTER TABLE `esp_stats`
  ADD CONSTRAINT `esp_index` FOREIGN KEY (`id_esp`) REFERENCES `esplist` (`id`);

--
-- Limitadores para a tabela `kw_h`
--
ALTER TABLE `kw_h`
  ADD CONSTRAINT `setor_kwh` FOREIGN KEY (`id_setor`) REFERENCES `setor` (`id_out_pin`);

--
-- Limitadores para a tabela `status`
--
ALTER TABLE `status`
  ADD CONSTRAINT `setor` FOREIGN KEY (`id_setor`) REFERENCES `setor` (`id_out_pin`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
