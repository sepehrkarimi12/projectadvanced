-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 23, 2018 at 02:02 PM
-- Server version: 5.7.24-0ubuntu0.18.04.1
-- PHP Version: 7.2.10-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yii2advanced`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL,
  `file` varchar(100) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `is_deleted` smallint(6) DEFAULT '0',
  `creator_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `deletor_id` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `text`, `file`, `customer_id`, `is_deleted`, `creator_id`, `created_at`, `deletor_id`, `deleted_at`) VALUES
(35, 'q', 'uploads/1545562401.png', 14, 1, 1, 1545562401, 1, 1545562433),
(36, 'w', NULL, 14, 0, 1, 1545562420, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `phone` varchar(11) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `is_deleted` smallint(6) DEFAULT '0',
  `creator_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `deletor_id` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `fname`, `lname`, `address`, `email`, `phone`, `mobile`, `is_deleted`, `creator_id`, `created_at`, `deletor_id`, `deleted_at`) VALUES
(11, 'aaaaaaaaaa', 'aaaaaaa', 'aaaaaaa', 'mkl@gmail.com', '11111111111', '11111111111', 1, 1, 1545544952, 1, 1545544976),
(12, 'bbbbB', 'bbbbbbB', 'BBBBBBB', 'mkl@gmail.com', '11111111111', '11111111111', 1, 1, 1545545025, 1, 1545545826),
(13, 'lmmp;mpopom', 'pompo', 'momo', 'a@gmail.com', '11111111111', '22222222222', 1, 1, 1545545618, 1, 1545545824),
(14, 'sepehr', 'KARimi', 'tehpars', 'a@gmail.com', '11111111111', '22222222222', 0, 1, 1545545985, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1545484744),
('m130524_201442_init', 1545484749),
('m140506_102106_rbac_init', 1545484816),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1545484817),
('m181220_051117_customer', 1545484750),
('m181220_080920_service', 1545484750),
('m181220_083252_servicetype', 1545484750),
('m181220_092631_network', 1545484751),
('m181220_093203_radio', 1545484751),
('m181222_082820_comment', 1545484752),
('m181222_094934_networktype', 1545484752),
('m181222_103320_foreign_keys', 1545484770);

-- --------------------------------------------------------

--
-- Table structure for table `network`
--

CREATE TABLE `network` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `type_id` int(11) NOT NULL,
  `ip_address` varchar(15) DEFAULT NULL,
  `is_deleted` smallint(6) DEFAULT '0',
  `creator_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `deletor_id` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `network`
--

INSERT INTO `network` (`id`, `name`, `address`, `type_id`, `ip_address`, `is_deleted`, `creator_id`, `created_at`, `deletor_id`, `deleted_at`) VALUES
(1, 'asghr', 'as', 3, '', 1, 1, 1545568151, 1, 1545568166),
(2, 'ty', 'ty', 3, '', 1, 1, 1545568421, 1, 1545569475),
(3, 'tttt', 'ttttttttttt', 3, '', 0, 1, 1545570994, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `networktype`
--

CREATE TABLE `networktype` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `need_ip` smallint(6) DEFAULT '0',
  `is_deleted` smallint(6) DEFAULT '0',
  `creator_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `deletor_id` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `networktype`
--

INSERT INTO `networktype` (`id`, `title`, `need_ip`, `is_deleted`, `creator_id`, `created_at`, `deletor_id`, `deleted_at`) VALUES
(1, 'pop-site', 0, 1, 1, 1545563299, 1, 1545563332),
(2, 'biulding', 0, 1, 1, 1545563734, 1, 1545567774),
(3, 'ali', 1, 0, 1, 1545565849, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `radio`
--

CREATE TABLE `radio` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `serial` varchar(100) NOT NULL,
  `network_id` int(11) NOT NULL,
  `is_deleted` smallint(6) DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `deletor_id` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `network_id` int(11) NOT NULL,
  `address` varchar(200) NOT NULL,
  `ppoe_username` varchar(100) NOT NULL,
  `ppoe_password` varchar(100) NOT NULL,
  `is_deleted` smallint(6) DEFAULT '0',
  `creator_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `deletor_id` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `servicetype`
--

CREATE TABLE `servicetype` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `is_deleted` smallint(6) DEFAULT '0',
  `creator_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `deletor_id` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `servicetype`
--

INSERT INTO `servicetype` (`id`, `title`, `is_deleted`, `creator_id`, `created_at`, `deletor_id`, `deleted_at`) VALUES
(1, 'Adsl', 9, 1, 1545554868, 1, 1545561696);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'NqsN9Qqwfp_Sw7Aq-Qy-A9ejFADAn7du', '$2y$13$2JH/enqb2YipI/4Yad9.iO3qgx.a4HOgwKd5jrJRN/Jp27NjsTq8e', NULL, 'admin@gmail.com', 10, 1545484908, 1545484908);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `auth_assignment_user_id_idx` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_customer_1_comment_n_` (`customer_id`),
  ADD KEY `FK_comment creator` (`creator_id`),
  ADD KEY `FK_comment deletor` (`deletor_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_customer creator` (`creator_id`),
  ADD KEY `FK_customer deletor` (`deletor_id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `network`
--
ALTER TABLE `network`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_networktype_1_network_n_` (`type_id`),
  ADD KEY `FK_network creator` (`creator_id`),
  ADD KEY `FK_network deletor` (`deletor_id`);

--
-- Indexes for table `networktype`
--
ALTER TABLE `networktype`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`),
  ADD KEY `FK_networktype creator` (`creator_id`),
  ADD KEY `FK_networktype deletor` (`deletor_id`);

--
-- Indexes for table `radio`
--
ALTER TABLE `radio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_network_1_radio_n_` (`network_id`),
  ADD KEY `FK_radio creator` (`creator_id`),
  ADD KEY `FK_radio deletor` (`deletor_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_customer_1_service_n_` (`customer_id`),
  ADD KEY `FK_servicetype_1_service_n_` (`type_id`),
  ADD KEY `FK_network_1_service_n_` (`network_id`),
  ADD KEY `FK_service creator` (`creator_id`),
  ADD KEY `FK_service deletor` (`deletor_id`);

--
-- Indexes for table `servicetype`
--
ALTER TABLE `servicetype`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`),
  ADD KEY `FK_servicetype creator` (`creator_id`),
  ADD KEY `FK_servicetype deletor` (`deletor_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `network`
--
ALTER TABLE `network`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `networktype`
--
ALTER TABLE `networktype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `radio`
--
ALTER TABLE `radio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `servicetype`
--
ALTER TABLE `servicetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_comment creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_comment deletor` FOREIGN KEY (`deletor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_customer_1_comment_n_` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `FK_customer creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_customer deletor` FOREIGN KEY (`deletor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `network`
--
ALTER TABLE `network`
  ADD CONSTRAINT `FK_network creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_network deletor` FOREIGN KEY (`deletor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_networktype_1_network_n_` FOREIGN KEY (`type_id`) REFERENCES `networktype` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `networktype`
--
ALTER TABLE `networktype`
  ADD CONSTRAINT `FK_networktype creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_networktype deletor` FOREIGN KEY (`deletor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `radio`
--
ALTER TABLE `radio`
  ADD CONSTRAINT `FK_network_1_radio_n_` FOREIGN KEY (`network_id`) REFERENCES `network` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_radio creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_radio deletor` FOREIGN KEY (`deletor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `FK_customer_1_service_n_` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_network_1_service_n_` FOREIGN KEY (`network_id`) REFERENCES `network` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_service creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_service deletor` FOREIGN KEY (`deletor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_servicetype_1_service_n_` FOREIGN KEY (`type_id`) REFERENCES `servicetype` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `servicetype`
--
ALTER TABLE `servicetype`
  ADD CONSTRAINT `FK_servicetype creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_servicetype deletor` FOREIGN KEY (`deletor_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
