-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14-Out-2025 às 09:50
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `2025_2p_futebolada`
--
CREATE DATABASE IF NOT EXISTS `2025_2p_futebolada` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `2025_2p_futebolada`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `classificado_em`
--

CREATE TABLE `classificado_em` (
  `idclube` int(11) NOT NULL,
  `idtabela` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `lugares` int(11) NOT NULL DEFAULT 1,
  `pontos` int(11) NOT NULL DEFAULT 0,
  `vitorias` int(11) NOT NULL DEFAULT 0,
  `empates` int(11) NOT NULL DEFAULT 0,
  `derrotas` int(11) NOT NULL DEFAULT 0,
  `golosmarcados` int(11) NOT NULL DEFAULT 0,
  `golossofridos` int(11) NOT NULL DEFAULT 0,
  `saldogolo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `clube`
--

CREATE TABLE `clube` (
  `idclube` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `hino` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `inscritos`
--

CREATE TABLE `inscritos` (
  `idclube` int(11) NOT NULL,
  `numerojogador` int(11) NOT NULL,
  `anoinscricao` int(11) NOT NULL,
  `anosaida` int(11) DEFAULT NULL,
  `capitao` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `joga`
--

CREATE TABLE `joga` (
  `id_joga` int(11) NOT NULL,
  `idjogo` int(11) NOT NULL,
  `idclube_casa` int(11) NOT NULL,
  `idclube_fora` int(11) NOT NULL,
  `casa_clube` varchar(100) NOT NULL,
  `fora_clube` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jogador`
--

CREATE TABLE `jogador` (
  `numero` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `golos` int(11) NOT NULL,
  `assistencias` int(11) NOT NULL,
  `cartoes_amarelos` int(11) NOT NULL,
  `cartoes_vermelhos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jogo`
--

CREATE TABLE `jogo` (
  `idjogo` int(11) NOT NULL,
  `resultado` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jogou_em`
--

CREATE TABLE `jogou_em` (
  `numerojogador` int(11) NOT NULL,
  `id_joga` int(11) NOT NULL,
  `marcadores` text NOT NULL,
  `amarelos` text NOT NULL,
  `vermelhos` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tabela`
--

CREATE TABLE `tabela` (
  `idtabela` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `classificado_em`
--
ALTER TABLE `classificado_em`
  ADD PRIMARY KEY (`idclube`,`idtabela`);

--
-- Índices para tabela `clube`
--
ALTER TABLE `clube`
  ADD PRIMARY KEY (`idclube`);

--
-- Índices para tabela `inscritos`
--
ALTER TABLE `inscritos`
  ADD KEY `idclube` (`idclube`,`numerojogador`);

--
-- Índices para tabela `joga`
--
ALTER TABLE `joga`
  ADD PRIMARY KEY (`id_joga`);

--
-- Índices para tabela `jogador`
--
ALTER TABLE `jogador`
  ADD PRIMARY KEY (`numero`);

--
-- Índices para tabela `jogo`
--
ALTER TABLE `jogo`
  ADD PRIMARY KEY (`idjogo`);

--
-- Índices para tabela `jogou_em`
--
ALTER TABLE `jogou_em`
  ADD PRIMARY KEY (`id_joga`,`numerojogador`);

--
-- Índices para tabela `tabela`
--
ALTER TABLE `tabela`
  ADD PRIMARY KEY (`idtabela`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clube`
--
ALTER TABLE `clube`
  MODIFY `idclube` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `joga`
--
ALTER TABLE `joga`
  MODIFY `id_joga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `jogo`
--
ALTER TABLE `jogo`
  MODIFY `idjogo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tabela`
--
ALTER TABLE `tabela`
  MODIFY `idtabela` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `inscritos`
--
ALTER TABLE `inscritos`
  ADD CONSTRAINT `inscritos_ibfk_1` FOREIGN KEY (`idclube`) REFERENCES `clube` (`idclube`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
