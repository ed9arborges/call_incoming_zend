-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 23-Nov-2012 às 13:02
-- Versão do servidor: 5.1.65
-- versão do PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `callincoming_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `follow_up_action`
--

CREATE TABLE IF NOT EXISTS `follow_up_action` (
  `followupaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`followupaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `follow_up_action`
--

INSERT INTO `follow_up_action` (`followupaction_id`, `name`) VALUES
(1, 'Call Back'),
(2, 'Wait for new telephone'),
(3, 'Take Action'),
(4, 'Other...');

-- --------------------------------------------------------

--
-- Estrutura da tabela `Incoming_telephone`
--

CREATE TABLE IF NOT EXISTS `Incoming_telephone` (
  `incomingtelephone_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `who_called_name` varchar(200) NOT NULL,
  `who_called_company` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `priority` varchar(50) NOT NULL,
  `follow_up_action` int(11) NOT NULL COMMENT 'rel followupaction_id',
  `follow_up_action_text` text NOT NULL,
  `action_for_user` int(11) NOT NULL COMMENT 'rel user',
  `done` tinyint(1) NOT NULL,
  PRIMARY KEY (`incomingtelephone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Extraindo dados da tabela `Incoming_telephone`
--

INSERT INTO `Incoming_telephone` (`incomingtelephone_id`, `date`, `time`, `who_called_name`, `who_called_company`, `description`, `priority`, `follow_up_action`, `follow_up_action_text`, `action_for_user`, `done`) VALUES
(1, '2011-05-14', '10:23:00', 'Edgar Borges', 'His self.', 'Job information.', 'Urgent', 3, 'Give him a chance.', 2, 0),
(3, '2012-11-10', '13:09:20', 'Barack Obama', 'US Governament', 'Need a new website for America', '*', 2, 'Ask for Michelle', 2, 0),
(5, '2012-11-21', '14:54:55', 'Santa Claus', 'North Pole Inc.', 'New naughty list website.', '**', 1, 'Willing to pay up front', 1, 1),
(13, '2012-07-03', '22:52:48', 'Homer Simpson', 'Nuclear Power Plant', 'Power plant has no internet conectivity.', '**', 3, 'Need technical assistance', 3, 1),
(57, '2012-11-21', '23:18:03', 'The testing subject', 'Testing SA', 'Hello this is the final testing, from incoming telephone by Edgar Borges', '**', 1, 'Sending the code.', 2, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`userid`, `name`, `username`, `password`) VALUES
(1, 'Susana Santos', 'susana@factorc.net', 'goodjob'),
(2, 'Testing VP', 'Stuart.Day@quantica-technology.co.uk', 'test'),
(3, 'Edgar Borges', 'ed9arborges@gmail.com', '12345');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
