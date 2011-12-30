-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 29, 2011 at 01:16 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-1ubuntu9.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `vt_content_owner_master`
--

CREATE TABLE IF NOT EXISTS `vt_content_owner_master` (
  `content_owner_id` int(11) NOT NULL,
  `company_name` varchar(30) NOT NULL,
  `dte_orig` datetime NOT NULL,
  `dte_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`content_owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='creates content owner data table';

--
-- Dumping data for table `vt_content_owner_master`
--

INSERT INTO `vt_content_owner_master` (`content_owner_id`, `company_name`, `dte_orig`, `dte_update`) VALUES
(0, 'SPCE', '2011-10-22 01:18:08', '2011-10-13 01:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `vt_format_lookup`
--

CREATE TABLE IF NOT EXISTS `vt_format_lookup` (
  `format_id` int(11) NOT NULL,
  `platform_id` int(11) NOT NULL,
  `quality_id` int(11) NOT NULL,
  `format_query` varchar(500) NOT NULL,
  `dte_orig` int(11) NOT NULL DEFAULT '0',
  `dte_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`format_id`),
  KEY `vt_format_lookup_ibfk_3` (`platform_id`),
  KEY `vt_format_lookup_ibfk_4` (`quality_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='creates content owner data table';

--
-- Dumping data for table `vt_format_lookup`
--

INSERT INTO `vt_format_lookup` (`format_id`, `platform_id`, `quality_id`, `format_query`, `dte_orig`, `dte_update`) VALUES
(1, 0, 0, 'ref=2:bframes=2:subq=6:mixed-refs=0:weightb=0:8x8d ct=0:trellis=0', 0, '2011-10-13 18:37:51'),
(2, 0, 1, 'test', 0, '2011-10-21 01:26:26'),
(3, 0, 2, 'test', 0, '2011-10-21 01:26:26'),
(4, 1, 2, 'test', 0, '2011-10-21 01:26:26'),
(5, 1, 1, '-e x264 -b 2000 -B 192', 0, '2011-10-13 18:37:51'),
(6, 1, 0, 'test', 0, '2011-10-21 01:26:26'),
(7, 0, 2, 'test', 0, '2011-10-21 01:26:26'),
(8, 0, 1, 'test', 0, '2011-10-21 01:26:26'),
(9, 0, 0, 'test', 0, '2011-10-21 01:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `vt_inputdata_master`
--

CREATE TABLE IF NOT EXISTS `vt_inputdata_master` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(100) NOT NULL,
  `file_path` varchar(100) NOT NULL,
  `src_format` varchar(10) NOT NULL,
  `content_owner_id` int(11) NOT NULL DEFAULT '0',
  `releasing_banner_id` int(11) NOT NULL DEFAULT '0',
  `dte_orig` int(11) NOT NULL DEFAULT '0',
  `dte_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`file_id`),
  KEY `vt_inputdata_master_ibfk` (`content_owner_id`),
  KEY `vt_inputdata_master_ibfk_2` (`releasing_banner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='creates input video data table' AUTO_INCREMENT=341 ;

--
-- Dumping data for table `vt_inputdata_master`
--

INSERT INTO `vt_inputdata_master` (`file_id`, `file_name`, `file_path`, `src_format`, `content_owner_id`, `releasing_banner_id`, `dte_orig`, `dte_update`) VALUES
(1, 'John-Butler-Trio-Ocean[www.savevid.com]', '/home/aashish/Desktop/', 'flv', 0, 0, 0, '2011-10-13 18:39:52'),
(338, 'Eminem-Not-Afraid[www.savevid.com]', '/home/aashish/Desktop/', 'flv', 0, 0, 0, '2011-10-28 18:01:34'),
(339, '2010-Awesome-Party-Songs[www.savevid.com]', '/home/aashish/Desktop/', 'flv', 0, 0, 0, '2011-10-28 18:01:35'),
(340, 'Akon-Right-Now-Na-Na-Na[www.savevid.com]', '/home/aashish/Desktop/', 'flv', 0, 0, 0, '2011-12-29 01:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `vt_platform_lookup`
--

CREATE TABLE IF NOT EXISTS `vt_platform_lookup` (
  `platform_id` int(11) NOT NULL,
  `platform_name` varchar(30) NOT NULL,
  PRIMARY KEY (`platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='creates platform lookup table to know what platform_id means';

--
-- Dumping data for table `vt_platform_lookup`
--

INSERT INTO `vt_platform_lookup` (`platform_id`, `platform_name`) VALUES
(0, 'Blackberry'),
(1, 'Andriod'),
(2, 'PC');

-- --------------------------------------------------------

--
-- Table structure for table `vt_process_info`
--

CREATE TABLE IF NOT EXISTS `vt_process_info` (
  `pid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `progress` varchar(30) NOT NULL,
  `eta` varchar(30) NOT NULL,
  `cpu` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vt_process_info`
--

INSERT INTO `vt_process_info` (`pid`, `sid`, `progress`, `eta`, `cpu`) VALUES
(1, 2, '100%', '000000s', ''),
(106, 2, '100%', '000000s', ''),
(115, 1, '82.12', '00h00m35s', ''),
(116, 1, '95.29', '00h00m06s', '');

-- --------------------------------------------------------

--
-- Table structure for table `vt_process_master`
--

CREATE TABLE IF NOT EXISTS `vt_process_master` (
  `process_id` int(11) NOT NULL AUTO_INCREMENT,
  `queued_file_id` int(11) NOT NULL,
  `op_format_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `dte_orig` datetime NOT NULL,
  `dte_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`process_id`),
  KEY `vt_process_master_ibfk_1` (`queued_file_id`),
  KEY `vt_process_master_ibfk_2` (`op_format_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='creates process table' AUTO_INCREMENT=117 ;

--
-- Dumping data for table `vt_process_master`
--

INSERT INTO `vt_process_master` (`process_id`, `queued_file_id`, `op_format_id`, `status_id`, `dte_orig`, `dte_update`) VALUES
(1, 1, 1, 2, '2011-10-05 18:41:54', '2011-10-13 18:42:15'),
(106, 338, 1, 2, '0000-00-00 00:00:00', '2011-10-28 18:01:34'),
(115, 339, 1, 1, '0000-00-00 00:00:00', '2011-10-28 18:01:35'),
(116, 340, 2, 1, '0000-00-00 00:00:00', '2011-10-28 18:01:35');

-- --------------------------------------------------------

--
-- Table structure for table `vt_quality_lookup`
--

CREATE TABLE IF NOT EXISTS `vt_quality_lookup` (
  `quality_id` int(11) NOT NULL,
  `quality_name` varchar(30) NOT NULL,
  PRIMARY KEY (`quality_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='creates quality lookup table to know what quality_id means';

--
-- Dumping data for table `vt_quality_lookup`
--

INSERT INTO `vt_quality_lookup` (`quality_id`, `quality_name`) VALUES
(0, 'High quality'),
(1, 'Medium quality'),
(2, 'Low quality');

-- --------------------------------------------------------

--
-- Table structure for table `vt_releasing_banner_master`
--

CREATE TABLE IF NOT EXISTS `vt_releasing_banner_master` (
  `banner_id` int(11) NOT NULL,
  `content_owner_id` int(11) NOT NULL,
  `banner_name` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `dte_orig` datetime NOT NULL,
  `dte_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`banner_id`),
  KEY `vt_process_master_ibfk_3` (`content_owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='creates content owner data table';

--
-- Dumping data for table `vt_releasing_banner_master`
--

INSERT INTO `vt_releasing_banner_master` (`banner_id`, `content_owner_id`, `banner_name`, `display_name`, `dte_orig`, `dte_update`) VALUES
(0, 0, 'SPCEproject', 'SPCEProject', '2011-10-05 01:18:38', '2011-10-13 01:18:42');

-- --------------------------------------------------------

--
-- Table structure for table `vt_status_lookup`
--

CREATE TABLE IF NOT EXISTS `vt_status_lookup` (
  `status_id` int(11) NOT NULL,
  `status_desc` varchar(30) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='creates status lookup table to know what status_id means';

--
-- Dumping data for table `vt_status_lookup`
--

INSERT INTO `vt_status_lookup` (`status_id`, `status_desc`) VALUES
(0, 'Enqueued'),
(1, 'In-process'),
(2, 'Conversion paused'),
(3, 'Conversion successful'),
(4, 'Failed'),
(5, 'Removed from List after comple'),
(6, 'Paused and removed'),
(7, 'Failed and removed');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vt_format_lookup`
--
ALTER TABLE `vt_format_lookup`
  ADD CONSTRAINT `vt_format_lookup_ibfk_3` FOREIGN KEY (`platform_id`) REFERENCES `vt_platform_lookup` (`platform_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `vt_format_lookup_ibfk_4` FOREIGN KEY (`quality_id`) REFERENCES `vt_quality_lookup` (`quality_id`) ON UPDATE CASCADE;

--
-- Constraints for table `vt_inputdata_master`
--
ALTER TABLE `vt_inputdata_master`
  ADD CONSTRAINT `vt_inputdata_master_ibfk` FOREIGN KEY (`content_owner_id`) REFERENCES `vt_content_owner_master` (`content_owner_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `vt_inputdata_master_ibfk_2` FOREIGN KEY (`releasing_banner_id`) REFERENCES `vt_releasing_banner_master` (`banner_id`) ON UPDATE CASCADE;

--
-- Constraints for table `vt_process_master`
--
ALTER TABLE `vt_process_master`
  ADD CONSTRAINT `vt_process_master_ibfk_1` FOREIGN KEY (`queued_file_id`) REFERENCES `vt_inputdata_master` (`file_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `vt_process_master_ibfk_2` FOREIGN KEY (`op_format_id`) REFERENCES `vt_format_lookup` (`format_id`) ON UPDATE CASCADE;

--
-- Constraints for table `vt_releasing_banner_master`
--
ALTER TABLE `vt_releasing_banner_master`
  ADD CONSTRAINT `vt_process_master_ibfk_3` FOREIGN KEY (`content_owner_id`) REFERENCES `vt_content_owner_master` (`content_owner_id`) ON UPDATE CASCADE;
