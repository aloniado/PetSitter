-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2018 at 09:55 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petsitterdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `helprequests`
--

CREATE TABLE `helprequests` (
  `helpID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `helpLocLat` float(10,6) NOT NULL,
  `helpLocLng` float(10,6) NOT NULL,
  `helpLocCity` varchar(64) NOT NULL,
  `helpLocStreet` varchar(64) NOT NULL,
  `helpLocNum` varchar(64) NOT NULL,
  `helpLocCountry` varchar(64) NOT NULL,
  `helpRegisterTime` datetime NOT NULL,
  `helpStartTime` datetime NOT NULL,
  `helpEndTime` datetime NOT NULL,
  `helpPayment` int(11) NOT NULL,
  `helpAbout` text NOT NULL,
  `helpType` varchar(64) NOT NULL,
  `userPhone` varchar(64) NOT NULL,
  `helpStatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `helprequests`
--

INSERT INTO `helprequests` (`helpID`, `userID`, `helpLocLat`, `helpLocLng`, `helpLocCity`, `helpLocStreet`, `helpLocNum`, `helpLocCountry`, `helpRegisterTime`, `helpStartTime`, `helpEndTime`, `helpPayment`, `helpAbout`, `helpType`, `userPhone`, `helpStatus`) VALUES
(69, 1, 32.785973, 35.016254, 'Haifa', 'Sh. Ben Tsiyon Street', '1', 'Israel', '2018-08-04 14:11:01', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 'Feed my rabbittt', 'Feeding', '0505337779', 2),
(71, 3, 32.782860, 35.013046, 'Haifa', 'A.D. Gordon Street', '4', 'Israel', '2018-08-04 15:21:59', '2018-12-30 00:00:00', '0000-00-00 00:00:00', 0, 'need someone to walk my dog\r\nthanks!', 'Walk', '0546878698', 2),
(73, 4, 32.782291, 35.012699, 'Haifa', 'Derekh Khankin', '6', 'Israel', '2018-08-04 15:29:35', '2018-09-01 00:00:00', '0000-00-00 00:00:00', 0, 'need someone to stay at my house a keep oshi safe', 'House Sitting', '0545253477', 2),
(83, 1, 31.968571, 34.798458, 'Rishon LeTsiyon', 'Sh. Ben Tsiyon Street', '1', 'Israel', '2018-08-13 21:44:09', '2018-12-12 00:00:00', '0000-00-00 00:00:00', 30, 'need help with feeding my animals', 'Feeding', '0505337779', 2),
(89, 2, 32.783840, 35.012997, 'Haifa', 'Berl Katznelson Street', '82', 'Israel', '2018-08-14 14:45:14', '2019-08-14 00:00:00', '0000-00-00 00:00:00', 0, 'an hour with my bunny', 'House Sitting', '0524470630', 2),
(90, 1, 32.780125, 35.022030, 'Haifa', '', '', 'Israel', '2018-08-14 16:04:30', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', 'Feeding', '0505337779', 2),
(91, 3, 31.751522, 35.168510, 'Jerusalem', '', '', 'Israel', '2018-08-14 16:30:43', '2020-09-09 00:00:00', '0000-00-00 00:00:00', 10, 'jkfsn', 'Feeding', '0546878698', 1),
(99, 5, 32.713036, 35.115070, 'Kiryat Tiv&amp;#39;on', 'Yigal Alon Street', '35', 'Israel', '2018-08-14 17:33:52', '2018-12-12 00:00:00', '0000-00-00 00:00:00', 0, 'please walk my rat', 'Walk', '0502402727', 1),
(107, 1, 32.818104, 35.072060, 'Haifa', 'Ha-Shayara Street', '14', 'Israel', '2018-08-21 12:49:40', '2222-12-12 00:00:00', '0000-00-00 00:00:00', 30, 'heelpppppp', 'Other', '0505337779', 2),
(109, 10, 32.813137, 34.995651, 'Haifa', 'Ha-Nevi&amp;#39;im Street', '', 'Israel', '2018-08-23 11:47:05', '1111-11-11 00:00:00', '0000-00-00 00:00:00', 122, 'walk my hamsterrr2', 'Walk', '0505050505', 2),
(112, 4, 32.819359, 35.072994, 'Haifa', 'Ha-Shayara Street', '30', 'Israel', '2018-08-23 16:45:44', '2019-12-12 00:00:00', '0000-00-00 00:00:00', 50, 'need help with feeding Oshi', 'Feeding', '0505337779', 2),
(113, 2, 0.000000, 0.000000, '', '', '', '', '2018-08-23 17:45:41', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', 'Feeding', '0524470630', 2),
(128, 3, 32.793816, 35.037022, 'Haifa', 'HaHistadrut Avenue', '55', 'Israel', '2018-08-26 16:25:57', '2019-02-07 00:00:00', '0000-00-00 00:00:00', 30, 'half an hour walk with my dog', 'Walk', '0546878698', 1),
(129, 4, 32.791245, 35.014137, 'Haifa', '', '', 'Israel', '2018-08-26 16:27:45', '2019-02-20 00:00:00', '2019-02-21 00:00:00', 0, 'just need someone to stay with my dog for two days. paying with cookies. ', 'House Sitting', '0523247063', 1),
(130, 5, 32.780464, 35.017017, 'Haifa', '', '', 'Israel', '2018-08-26 16:29:12', '2019-01-12 00:00:00', '0000-00-00 00:00:00', 20, 'keep an eye on my rat, feed her and maybe play with her a little. she doesn&amp;#39;t bite.', 'Feeding', '0502402727', 1),
(132, 7, 31.749821, 35.169510, 'Jerusalem', 'Arye Dultsin Street', '', 'Israel', '2018-08-28 10:09:58', '2018-12-01 00:00:00', '0000-00-00 00:00:00', 0, 'one time walking', 'Walk', '0545464378', 1),
(133, 1, 32.785671, 35.016144, 'Haifa', 'Sh. Ben Tsiyon Street', '3', 'Israel', '2018-08-28 12:51:44', '2018-12-12 00:00:00', '0000-00-00 00:00:00', 20, 'please help me with my pets', 'House Sitting', '0505337779', 1);

-- --------------------------------------------------------

--
-- Table structure for table `interested`
--

CREATE TABLE `interested` (
  `helpID` int(11) NOT NULL,
  `interestedUserID` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `interested`
--

INSERT INTO `interested` (`helpID`, `interestedUserID`, `status`) VALUES
(69, 2, 1),
(69, 3, 1),
(71, 1, 1),
(71, 2, 1),
(73, 1, 1),
(73, 2, 1),
(83, 2, 1),
(83, 7, 1),
(89, 1, 1),
(90, 3, 1),
(107, 2, 1),
(109, 1, 1),
(112, 2, 1),
(113, 7, 1),
(129, 1, 1),
(133, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `petID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `petName` varchar(64) NOT NULL,
  `petType` varchar(64) NOT NULL,
  `petBirthday` date NOT NULL,
  `petSex` varchar(64) NOT NULL,
  `petFood` text NOT NULL,
  `petTemper` text NOT NULL,
  `petAbout` text NOT NULL,
  `petStatus` int(11) NOT NULL,
  `petRegisterTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`petID`, `userID`, `petName`, `petType`, `petBirthday`, `petSex`, `petFood`, `petTemper`, `petAbout`, `petStatus`, `petRegisterTime`) VALUES
(44, 1, 'Vlad', 'Rabbit\r\n', '2015-09-16', 'Male', 'Pellets', '', 'grey rabbit.\r\nlikes stuff yes.', 1, '2018-08-14 11:44:25'),
(45, 1, 'huawei', 'Turtle\r\n', '2018-01-01', 'Unknown', 'doesnt eat', '', 'This is a very long text. its goal is to test the card and see if the text will pop out of it', 1, '2018-08-23 09:12:44'),
(47, 3, 'Kika', 'Dog\r\n', '2016-12-12', 'Female', 'Dog pellets', '', 'a', 1, '2018-08-14 16:29:15'),
(48, 4, 'Oshi', 'Dog\r\n', '2018-06-18', 'Male', 'Dog pellets', '', 'a dog in training', 1, '2018-08-04 15:25:21'),
(49, 5, 'Lola', 'Rat\r\n', '2016-09-12', 'Female', 'will eat anything', '', '', 1, '2018-08-06 15:30:32'),
(50, 2, 'vlad2', 'Rabbit\r\n', '1999-09-09', 'Male', '', '', '', 1, '2018-08-12 21:00:24'),
(53, 1, 'fishy', 'Fish\r\n', '2017-12-12', 'Male', 'likes fish food', '', 'normal fish', 1, '2018-08-13 21:41:54'),
(54, 1, 'RandomFerret', 'Ferret\r\n', '2018-11-11', 'Male', 'ferret food', '', 'regular ferret', 1, '2018-08-13 21:45:15'),
(55, 1, 'Pigly', 'Pig\r\n', '0000-00-00', 'Male', '', '', 'yes', 1, '2018-08-24 12:47:43'),
(57, 10, 'newp', 'Hedgehog\r\n', '1111-11-11', 'Male', 'likes food', '', 'a happy hedgehog', 1, '2018-08-23 11:45:30'),
(58, 7, 'Dusty', 'Dog\r\n', '1994-12-01', 'Female', '', '', 'old humble dog. \r\ndeosnt like people', 1, '2018-08-28 10:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `pets_helprequests`
--

CREATE TABLE `pets_helprequests` (
  `petID` int(11) NOT NULL,
  `helpID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `recordRegisterTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pets_helprequests`
--

INSERT INTO `pets_helprequests` (`petID`, `helpID`, `userID`, `recordRegisterTime`) VALUES
(44, 69, 1, '2018-08-04 14:11:01'),
(44, 83, 1, '2018-08-13 21:44:09'),
(44, 107, 1, '2018-08-21 12:49:40'),
(44, 133, 1, '2018-08-28 12:51:44'),
(45, 69, 1, '2018-08-04 14:11:01'),
(45, 83, 1, '2018-08-13 21:44:09'),
(45, 133, 1, '2018-08-28 12:51:44'),
(47, 71, 3, '2018-08-04 15:21:59'),
(47, 91, 3, '2018-08-14 16:30:43'),
(47, 128, 3, '2018-08-26 16:25:57'),
(48, 73, 4, '2018-08-04 15:29:35'),
(48, 112, 4, '2018-08-23 16:45:44'),
(48, 129, 4, '2018-08-26 16:27:45'),
(49, 99, 5, '2018-08-14 17:33:52'),
(49, 130, 5, '2018-08-26 16:29:12'),
(50, 89, 2, '2018-08-14 14:45:14'),
(53, 83, 1, '2018-08-13 21:44:09'),
(53, 90, 1, '2018-08-14 16:04:30'),
(55, 107, 1, '2018-08-21 12:49:40'),
(57, 109, 10, '2018-08-23 11:47:05'),
(58, 132, 7, '2018-08-28 10:09:58');

-- --------------------------------------------------------

--
-- Table structure for table `rankings`
--

CREATE TABLE `rankings` (
  `rankID` int(11) NOT NULL,
  `rankingUserID` int(11) NOT NULL,
  `rankedUserID` int(11) NOT NULL,
  `helpID` int(11) NOT NULL,
  `rankRank` int(11) NOT NULL,
  `rankAbout` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rankings`
--

INSERT INTO `rankings` (`rankID`, `rankingUserID`, `rankedUserID`, `helpID`, `rankRank`, `rankAbout`) VALUES
(2, 1, 2, 69, 10, 'very nice'),
(3, 3, 1, 71, 8, 'was ok im elan'),
(4, 1, 3, 90, 8, 'elan was wow'),
(5, 1, 3, 90, 8, 'elan was wow'),
(6, 1, 2, 107, 2, 'not good. rabbit is dead.'),
(8, 4, 1, 73, 10, 'was great!'),
(9, 2, 1, 89, 8, 'my pet was very happy with this help'),
(10, 10, 1, 109, 10, 'was great! '),
(11, 4, 2, 112, 10, 'Oshi is still alive!'),
(12, 2, 7, 113, 4, 'not good help. not recommended.'),
(13, 1, 7, 83, 10, 'my fish is yes. very yes.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `userEmail` varchar(64) NOT NULL,
  `userPassword` varchar(64) NOT NULL,
  `userFName` varchar(64) NOT NULL,
  `userLName` varchar(64) NOT NULL,
  `userPhone` varchar(64) NOT NULL,
  `userBirthday` date NOT NULL,
  `userAbout` text NOT NULL,
  `userRegisterTime` datetime NOT NULL,
  `userStatus` varchar(64) CHARACTER SET utf16 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userEmail`, `userPassword`, `userFName`, `userLName`, `userPhone`, `userBirthday`, `userAbout`, `userRegisterTime`, `userStatus`) VALUES
(1, 'aloniado@gmail.com', '$2y$10$zrKXexq9gKOl8GYe.ULp7eVazc1qU8VK2U3BXvq4kQ9vIDt6JDeXa', 'Alon', 'Laniado', '0505337779', '1988-09-16', 'Studies at the Technion', '2018-08-04 13:35:22', 'admin'),
(2, 'sussman.net@gmail.com', '$2y$10$e9GyZAFJu3dhqtcypSgTBuwE1dp/1cw4tAepkR71QPmF/DCd46xfy', 'Angel', 'Sussman', '0524470630', '1988-02-16', 'About me?', '2018-08-04 15:15:02', 'user'),
(3, 'elan.weisberg@gmail.com', '$2y$10$N/VeXejBBRhdbDy6nYEl/u4pERc1FbrhpK9r6h7pHrypaHpGIsFyK', 'Elan', 'Weisberg', '0546878698', '1989-01-16', 'Mechanical engineer 2', '2018-08-04 15:17:02', 'user'),
(4, 'michael.shentcis@gmail.com', '$2y$10$Akg02QUlKEPl8PdXKD8AMusrEMhbl6PqmKdAGe3a5Fqyl7bO.599S', 'Michael', 'Shentcis', '', '0000-00-00', '', '2018-08-04 15:23:29', 'user'),
(5, 'lior.h.3112@gmail.com', '$2y$10$V.nH7amFFx1Ch2tDcTB.AeTmebxWnSb2UuS1SeNi6QRdl0AghV6i6', 'Lior', 'Hasid', '0502402727', '1986-03-12', 'lives in tivon', '2018-08-06 15:29:13', 'user'),
(7, 'reemlevi@gmail.com', '$2y$10$HvAuFPh1fLHpgpFsiQCgHuAQtZu0Fsq16ByBFoo3naDPVKG9ulpBG', 'Re&#39;em', 'Levy', '0545464378', '0000-00-00', '', '2018-08-14 17:21:01', 'user'),
(8, 'eyalaniado7@gmail.com', '$2y$10$RT4zXlzRJpD/eDhAKgp29uRmBhrjUfQCOfNqG6FOA84UjnqJaPZdm', 'Eyal', 'Laniado', '0526008928', '1990-03-15', 'yes', '2018-08-14 17:40:20', 'user'),
(9, 'sofiryef@gmail.com', '$2y$10$vTO7RgxCbQdA/7KClM1DkuRth3GJBItMGLAwxUf2JkzJpgxeAeRBC', 'Ofir', 'Yefet', '', '0000-00-00', '', '2018-08-21 11:50:48', 'user'),
(10, 'temp@temp.com', '$2y$10$EmCpMEa1DqsJpM1oQuDWu.m8cXbq5Qhdd4mOwYIQdq7lU5EhEuDc2', 'tem', 'tempp', '0505050505', '1111-11-11', '111', '2018-08-21 12:27:37', 'user'),
(11, 'newuser@gmail.com', '$2y$10$p405kh2Eycphu9IiTkJiDeZ4JFGBqgSyWAeUIRY/s8wKtFhqnIeSq', 'new', 'user', '', '0000-00-00', '', '2018-08-23 11:51:24', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `helprequests`
--
ALTER TABLE `helprequests`
  ADD PRIMARY KEY (`helpID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `interested`
--
ALTER TABLE `interested`
  ADD PRIMARY KEY (`helpID`,`interestedUserID`),
  ADD KEY `interestedUserID` (`interestedUserID`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`petID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `pets_helprequests`
--
ALTER TABLE `pets_helprequests`
  ADD PRIMARY KEY (`petID`,`helpID`),
  ADD KEY `helpID` (`helpID`);

--
-- Indexes for table `rankings`
--
ALTER TABLE `rankings`
  ADD PRIMARY KEY (`rankID`),
  ADD KEY `rankedUserID` (`rankedUserID`),
  ADD KEY `rankingUserID` (`rankingUserID`),
  ADD KEY `helpID` (`helpID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `userID` (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `helprequests`
--
ALTER TABLE `helprequests`
  MODIFY `helpID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `petID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `rankings`
--
ALTER TABLE `rankings`
  MODIFY `rankID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `helprequests`
--
ALTER TABLE `helprequests`
  ADD CONSTRAINT `helprequests_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `interested`
--
ALTER TABLE `interested`
  ADD CONSTRAINT `interested_ibfk_1` FOREIGN KEY (`interestedUserID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `interested_ibfk_2` FOREIGN KEY (`helpID`) REFERENCES `helprequests` (`helpID`);

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `pets_helprequests`
--
ALTER TABLE `pets_helprequests`
  ADD CONSTRAINT `pets_helprequests_ibfk_1` FOREIGN KEY (`helpID`) REFERENCES `helprequests` (`helpID`),
  ADD CONSTRAINT `pets_helprequests_ibfk_2` FOREIGN KEY (`petID`) REFERENCES `pets` (`petID`);

--
-- Constraints for table `rankings`
--
ALTER TABLE `rankings`
  ADD CONSTRAINT `rankings_ibfk_1` FOREIGN KEY (`helpID`) REFERENCES `helprequests` (`helpID`),
  ADD CONSTRAINT `rankings_ibfk_2` FOREIGN KEY (`rankingUserID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `rankings_ibfk_3` FOREIGN KEY (`rankedUserID`) REFERENCES `users` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
