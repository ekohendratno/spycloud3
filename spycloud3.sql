-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2021 at 11:04 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spycloud3`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

DROP TABLE IF EXISTS `calendar_events`;
CREATE TABLE `calendar_events` (
  `calendar_events_id` int(11) NOT NULL,
  `event_timezone` text NOT NULL,
  `event_title` text NOT NULL,
  `event_id` text NOT NULL,
  `event_description` text NOT NULL,
  `event_location` text NOT NULL,
  `event_calendar_account` text NOT NULL,
  `event_calendar_account_name` text NOT NULL,
  `event_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `call_logs`
--

DROP TABLE IF EXISTS `call_logs`;
CREATE TABLE `call_logs` (
  `call_logs_id` int(11) NOT NULL,
  `phone_number` text NOT NULL,
  `call_date` text NOT NULL,
  `call_type` text NOT NULL,
  `call_duration` text NOT NULL,
  `call_logs_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uid` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `camera`
--

DROP TABLE IF EXISTS `camera`;
CREATE TABLE `camera` (
  `camera_id` int(30) NOT NULL,
  `image` longtext NOT NULL,
  `for` text NOT NULL,
  `camera_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `collect_installed_apps`
--

DROP TABLE IF EXISTS `collect_installed_apps`;
CREATE TABLE `collect_installed_apps` (
  `collect_installed_apps_id` int(11) NOT NULL,
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL,
  `app_name` text NOT NULL,
  `app_package` text NOT NULL,
  `app_uid` text NOT NULL,
  `app_vname` text NOT NULL,
  `app_vcode` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `collect_phone_details`
--

DROP TABLE IF EXISTS `collect_phone_details`;
CREATE TABLE `collect_phone_details` (
  `collect_phone_details_id` int(11) NOT NULL,
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL,
  `k` text DEFAULT NULL,
  `v` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `commands`
--

DROP TABLE IF EXISTS `commands`;
CREATE TABLE `commands` (
  `commands_id` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `id` int(11) NOT NULL,
  `param1` text NOT NULL,
  `param2` text NOT NULL,
  `param3` text NOT NULL,
  `param4` text NOT NULL,
  `panding` int(1) NOT NULL DEFAULT 1,
  `uid` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `commands_prompt`
--

DROP TABLE IF EXISTS `commands_prompt`;
CREATE TABLE `commands_prompt` (
  `commands_prompt_id` int(11) NOT NULL,
  `commands_prompt_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commands_prompt`
--

INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(1, 'Phone call');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(2, 'SMS');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(3, 'GPS output');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(4, 'CallLog output');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(5, 'SMS messages output');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(6, 'Contacts output');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(7, 'Camera capture');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(8, 'Dictionary Bookmark Search');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(9, 'Callendar Event');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(10, 'Take Screenshot');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(11, 'Audio Record');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(12, 'Video Record');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(13, 'Gallery List');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(14, 'Vibration');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(15, 'Ring Phone Notification');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(16, 'Lock Screen');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(17, 'Reset Phone');
INSERT INTO `commands_prompt` (`commands_prompt_id`, `commands_prompt_name`) VALUES(18, 'Invisible App');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `contacts_id` int(11) NOT NULL,
  `contact_name` text NOT NULL,
  `contact_phone` text NOT NULL,
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gps`
--

DROP TABLE IF EXISTS `gps`;
CREATE TABLE `gps` (
  `gps_id` int(30) NOT NULL,
  `coordinat` text NOT NULL,
  `tanggal` datetime NOT NULL,
  `uid` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE `history` (
  `history_id` int(255) NOT NULL,
  `history_title` text NOT NULL,
  `history_date` datetime NOT NULL,
  `location_long` text NOT NULL,
  `location_lat` text NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history_bookmark`
--

DROP TABLE IF EXISTS `history_bookmark`;
CREATE TABLE `history_bookmark` (
  `history_bookmark_id` int(11) NOT NULL,
  `bookmark_title` text NOT NULL,
  `bookmark_url` text NOT NULL,
  `bookmark_date` text NOT NULL,
  `bookmark_visits` text NOT NULL,
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history_search`
--

DROP TABLE IF EXISTS `history_search`;
CREATE TABLE `history_search` (
  `history_search_id` int(11) NOT NULL,
  `search_title` text NOT NULL,
  `search_date` text NOT NULL,
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history_word`
--

DROP TABLE IF EXISTS `history_word`;
CREATE TABLE `history_word` (
  `history_word_id` int(11) NOT NULL,
  `locale` text NOT NULL,
  `dictionary_word` text NOT NULL,
  `dictionary_id` text NOT NULL,
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `phone`
--

DROP TABLE IF EXISTS `phone`;
CREATE TABLE `phone` (
  `phone_id` int(11) NOT NULL,
  `phone_name` text NOT NULL,
  `phone_imei` text NOT NULL,
  `phone_serial` text NOT NULL,
  `phone_model` text NOT NULL,
  `phone_status` text NOT NULL,
  `phone_last_active` timestamp NOT NULL DEFAULT current_timestamp(),
  `versicode` text NOT NULL,
  `versiname` text NOT NULL,
  `uid` int(11) NOT NULL,
  `ForceUpload` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

DROP TABLE IF EXISTS `pictures`;
CREATE TABLE `pictures` (
  `pictures_id` int(11) NOT NULL,
  `pictures_file` text NOT NULL,
  `pictures_folder` text NOT NULL,
  `pictures_type` enum('gallery','screenshoot') NOT NULL,
  `pictures_date` date NOT NULL,
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `screenshot`
--

DROP TABLE IF EXISTS `screenshot`;
CREATE TABLE `screenshot` (
  `screenshot_id` int(30) NOT NULL,
  `screenshot` text NOT NULL,
  `screenshot_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL,
  `settings_name` text NOT NULL,
  `settings_value` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

DROP TABLE IF EXISTS `sms`;
CREATE TABLE `sms` (
  `sms_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `message` text NOT NULL,
  `tanggal` text NOT NULL,
  `reader` text NOT NULL,
  `id` text NOT NULL,
  `type` text NOT NULL,
  `user_id` text NOT NULL,
  `phone_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `level` enum('admin','superadmin') NOT NULL DEFAULT 'admin',
  `token` varchar(255) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `level`, `token`, `last_active`) VALUES(1, 'superadmin', 'superadmin', '', 'superadmin', '', '2020-01-03 14:28:07');
INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `level`, `token`, `last_active`) VALUES(2, 'admin', 'admin', '', 'admin', 'abcd', '2021-10-07 16:24:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`calendar_events_id`);

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`call_logs_id`);

--
-- Indexes for table `camera`
--
ALTER TABLE `camera`
  ADD PRIMARY KEY (`camera_id`);

--
-- Indexes for table `collect_installed_apps`
--
ALTER TABLE `collect_installed_apps`
  ADD PRIMARY KEY (`collect_installed_apps_id`);

--
-- Indexes for table `collect_phone_details`
--
ALTER TABLE `collect_phone_details`
  ADD PRIMARY KEY (`collect_phone_details_id`);

--
-- Indexes for table `commands`
--
ALTER TABLE `commands`
  ADD PRIMARY KEY (`commands_id`);

--
-- Indexes for table `commands_prompt`
--
ALTER TABLE `commands_prompt`
  ADD PRIMARY KEY (`commands_prompt_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contacts_id`);

--
-- Indexes for table `gps`
--
ALTER TABLE `gps`
  ADD PRIMARY KEY (`gps_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `history_bookmark`
--
ALTER TABLE `history_bookmark`
  ADD PRIMARY KEY (`history_bookmark_id`);

--
-- Indexes for table `history_search`
--
ALTER TABLE `history_search`
  ADD PRIMARY KEY (`history_search_id`);

--
-- Indexes for table `history_word`
--
ALTER TABLE `history_word`
  ADD PRIMARY KEY (`history_word_id`);

--
-- Indexes for table `phone`
--
ALTER TABLE `phone`
  ADD PRIMARY KEY (`phone_id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`pictures_id`);

--
-- Indexes for table `screenshot`
--
ALTER TABLE `screenshot`
  ADD PRIMARY KEY (`screenshot_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`sms_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `calendar_events_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `call_logs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `camera`
--
ALTER TABLE `camera`
  MODIFY `camera_id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collect_installed_apps`
--
ALTER TABLE `collect_installed_apps`
  MODIFY `collect_installed_apps_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collect_phone_details`
--
ALTER TABLE `collect_phone_details`
  MODIFY `collect_phone_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commands`
--
ALTER TABLE `commands`
  MODIFY `commands_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commands_prompt`
--
ALTER TABLE `commands_prompt`
  MODIFY `commands_prompt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contacts_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gps`
--
ALTER TABLE `gps`
  MODIFY `gps_id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_bookmark`
--
ALTER TABLE `history_bookmark`
  MODIFY `history_bookmark_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_search`
--
ALTER TABLE `history_search`
  MODIFY `history_search_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_word`
--
ALTER TABLE `history_word`
  MODIFY `history_word_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone`
--
ALTER TABLE `phone`
  MODIFY `phone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `pictures_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `screenshot`
--
ALTER TABLE `screenshot`
  MODIFY `screenshot_id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `sms_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
