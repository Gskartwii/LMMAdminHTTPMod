-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2014 at 11:29 AM
-- Server version: 5.1.73-1-log
-- PHP Version: 5.3.3-7+squeeze19

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `3591_other`
--

-- --------------------------------------------------------

--
-- Table structure for table `roblox_adminlist_template`
--

CREATE TABLE IF NOT EXISTS `roblox_adminlist_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `roblox_lmm_regs`
--

CREATE TABLE IF NOT EXISTS `roblox_lmm_regs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `un` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `em` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `roblox_log_sid_template`
--

CREATE TABLE IF NOT EXISTS `roblox_log_sid_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `roblox_log_template`
--

CREATE TABLE IF NOT EXISTS `roblox_log_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `msg` varchar(7000) NOT NULL,
  `time` int(255) NOT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=532 ;

-- --------------------------------------------------------

--
-- Table structure for table `roblox_placeids_template`
--

CREATE TABLE IF NOT EXISTS `roblox_placeids_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `placeid` int(11) NOT NULL,
  `placename` varchar(255) NOT NULL,
  `placedesc` text NOT NULL,
  `placecreator` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
