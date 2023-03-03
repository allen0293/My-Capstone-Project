-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2020 at 12:28 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cooperativedb_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `eqp_id` int(11) NOT NULL,
  `serial_no` varchar(255) NOT NULL,
  `eqp_name` varchar(255) NOT NULL,
  `eqp_model` varchar(255) NOT NULL,
  `eqp_desc` text NOT NULL,
  `rent_price` float NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`eqp_id`, `serial_no`, `eqp_name`, `eqp_model`, `eqp_desc`, `rent_price`, `status`) VALUES
(13, 'Q0ZXL3JTRlRRUWpxUVZ2bytnNDBydz09OjpedD/C+hvarVulgtBzwYVE', 'emdjSXR5bFEycTB5ckFidlVpRk1oUT09Ojofw2EB3zeI1t4qrkAAhm6q', 'aUxNL1plbjkvWjFPOTVWcFlxZWdXZz09OjobAi3RKIjTrfKrQ6JiVfZz', 'elZQcm9nQnlXb2pESWN0MEpXa0puUT09OjpQamn/oV3JLTqLMW5BcU85', 300, 'occupied'),
(14, 'cUQwcTJUcktZWjBPOFRGc25PUzA2Zz09OjqKwMhpKUkcEJ9sthInHmX/', 'UVVmUTEyb08ycVhyeWxCemc1U3h4dz09Ojrg4PywV07FiKeLqET2ZBi6', 'ZWNKZ3ovNG5KWlROT0FFUGpmM1FiQT09OjoiIRtEuTp93cvQedWnN84a', 'L0srZlRqZTY3dUYrdTZaQjcxWFllQT09OjoLQ0tkOMJNzadx5oD31fer', 300, 'unoccupied');

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `loan_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `based` float NOT NULL,
  `date_start` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `date_finish` date DEFAULT NULL,
  `interest` float NOT NULL,
  `penalty` float NOT NULL,
  `total` float NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL,
  `TIN` varchar(255) NOT NULL,
  `Lname` varchar(255) NOT NULL,
  `Fname` varchar(255) NOT NULL,
  `Mname` varchar(255) NOT NULL,
  `birthdate` text NOT NULL,
  `spouse_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contactno` varchar(255) NOT NULL,
  `land_size` varchar(255) NOT NULL,
  `land_location` text NOT NULL,
  `crop1` varchar(255) NOT NULL,
  `crop2` varchar(255) NOT NULL,
  `crop3` varchar(255) NOT NULL,
  `capital_build_up` float NOT NULL,
  `paid_up_capital` float NOT NULL,
  `reg_fee` float NOT NULL,
  `registered_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `TIN`, `Lname`, `Fname`, `Mname`, `birthdate`, `spouse_name`, `address`, `contactno`, `land_size`, `land_location`, `crop1`, `crop2`, `crop3`, `capital_build_up`, `paid_up_capital`, `reg_fee`, `registered_date`) VALUES
(60, 'd3FocXdJVDMwNHZZMjdJK1NkdlVuZz09Ojo9zKMahZYOHUUeZ8ZEUPDs', 'dVpZZk9lODRqdWg3RGxoN3d5ZzRiUT09OjqeE9cgh4kkO+kCnsB4DYRm', 'UzVWbUJQSnFzcXRDUnBqUTlGeVhuZz09OjodexQ+XFTA7XJv04kJgitx', 'NTIveURqRnRzajZ5YzFwZGpJT2U3dz09OjqnOBjFi9CKzxX5oA4VMaAn', 'L2pTYlZTU0llVitobWJwMGJXdVlPQT09OjqZ5xlvwaHHKqwb+PI3/ki0', 'dFdDRzg5ZFpUcUJFWEl6dFlYbjNzZz09Ojrn+O34SyIsDBvcgFvTOuuk', 'NHBacDFhMGl1RFMrZjdUbGs0eW9MN3YwOHJTSGUzdm1KNkd0M3ZVYllkTT06OlvWXFAh0/NratSdUJFB1jk=', 'bDFSZnBQNGp2QzZHWHBZRDRoY2lYQT09OjpQE7lzP2myDWT76qRjat2B', 'QzQ0Vkdpb0FpTVExeDVzVFZHQjdzZz09Ojqa3R4i5f4pkqirTpKQDBLZ', 'aDJQbkFlTVVFUk1DaHNIejdWTEc0eXRjeTArMnZERW9aVDZtdEFtZnJGaz06OueDxKEQVr4caPd1CvpX0rY=', 'cWdBRnNkVFhsT25GeEFsdmJRSlYyUT09OjqrW13RFjNymCe9dw3ms74L', 'VFZKYm9GRWc2bnlxbnlkSEtSQ1Exdz09OjrnXyvC9CjP+PPC9hZ78VY3', 'SndmN0IvVUhUQ1BlNkozbjNCbnVnUT09OjrdZmYXXGoxkfuVmbwmGA98', 4000, 4000, 500, '2020-12-04 17:33:48');

-- --------------------------------------------------------

--
-- Table structure for table `puc_transaction`
--

CREATE TABLE `puc_transaction` (
  `puc_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `paid` float NOT NULL,
  `date_pay` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `email` varchar(255) NOT NULL,
  `ans1` varchar(60) DEFAULT NULL,
  `ans2` varchar(60) DEFAULT NULL,
  `ans3` varchar(60) DEFAULT NULL,
  `password` varchar(60) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `user_type` varchar(6) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `code` varchar(10) NOT NULL,
  `time_expired` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`user_id`, `username`, `email`, `ans1`, `ans2`, `ans3`, `password`, `user_type`, `code`, `time_expired`) VALUES
(101, 'Allen', 'allencandela0@gmail.com', '$2y$10$KX8OhmxJyAr8P34kdATKluGk2WThVT/rj5SUAUAlBrWm4UpdW2Yae', '$2y$10$gmlcm35pZnuPEMWmLOb7vODstov0Rf90AzdE2l0QyVVZlRfLFrcSi', '$2y$10$VePcLObKkDEYpxrk6tIVTuc4MbKdYB5.Eo/qj1vOM3YO1c391.DD6', '$2y$10$cZ5D.iEfPRikzztIib0o7eXye3G4M4KSZkNb/7.iQKMT3R02EtxNm', 'admin', '', '2020-12-04 20:23:20'),
(102, 'Admin', 'sinulatan1stcooperative@gmail.com', '$2y$10$Rm/jeLG3oT55tnYQg8ldlOEdQvzKpch7QEdEt22zL2qUwqdOaJnMC', '$2y$10$EMOucxLhUayWqUovCzHFZOPnRM3HM3Mc0mm1YkyF2k.pdSi6GOjy.', '$2y$10$76v9.6P64H9mfmyXSobrfO0wev73Mzq/ICN0fgTf1iCkh3Iajm/Ma', '$2y$10$QB6EYM7ngJ.4ZqWssBGG.OgSExt7OIoX44Au31hfYrSrf92KmyGvO', 'admin', '', '2020-12-04 20:24:55');

-- --------------------------------------------------------

--
-- Table structure for table `rent`
--

CREATE TABLE `rent` (
  `rent_id` int(11) NOT NULL,
  `eqp_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `rent_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `date_returned` datetime DEFAULT NULL,
  `amount` float NOT NULL,
  `pay` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rent`
--

INSERT INTO `rent` (`rent_id`, `eqp_id`, `member_id`, `rent_date`, `due_date`, `date_returned`, `amount`, `pay`) VALUES
(214, 13, 60, '2020-12-05 07:19:00', '2020-12-06 07:19:00', NULL, 300, 0);

-- --------------------------------------------------------

--
-- Table structure for table `rent_payment`
--

CREATE TABLE `rent_payment` (
  `pay_id` int(11) NOT NULL,
  `rent_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `paid` float NOT NULL,
  `date_pay` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

CREATE TABLE `user_activities` (
  `act_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` text COLLATE latin1_general_ci NOT NULL,
  `act_url` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`act_id`, `user_id`, `action`, `act_url`, `date_time`) VALUES
(1121, 101, 'Added new User', '/farmers_Cooperative_V2/code.php', '2020-12-05 04:24:29'),
(1122, 102, 'Processed equipment rental  of member ID 60', '/farmers_Cooperative_V2/frm_rent.php', '2020-12-05 07:19:15');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`log_id`, `user_id`, `login`) VALUES
(50, 101, '2020-12-05 04:23:11'),
(51, 102, '2020-12-05 04:24:44'),
(52, 102, '2020-12-05 07:17:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`eqp_id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `puc_transaction`
--
ALTER TABLE `puc_transaction`
  ADD PRIMARY KEY (`puc_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `rent`
--
ALTER TABLE `rent`
  ADD PRIMARY KEY (`rent_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `eqp_id` (`eqp_id`);

--
-- Indexes for table `rent_payment`
--
ALTER TABLE `rent_payment`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `rent_payment_ibfk_1` (`rent_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`act_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_logs_ibfk_1` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `eqp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `puc_transaction`
--
ALTER TABLE `puc_transaction`
  MODIFY `puc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `rent`
--
ALTER TABLE `rent`
  MODIFY `rent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `rent_payment`
--
ALTER TABLE `rent_payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `act_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1123;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `register` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `puc_transaction`
--
ALTER TABLE `puc_transaction`
  ADD CONSTRAINT `puc_transaction_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `puc_transaction_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `register` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rent`
--
ALTER TABLE `rent`
  ADD CONSTRAINT `rent_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rent_ibfk_2` FOREIGN KEY (`eqp_id`) REFERENCES `equipment` (`eqp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rent_payment`
--
ALTER TABLE `rent_payment`
  ADD CONSTRAINT `rent_payment_ibfk_1` FOREIGN KEY (`rent_id`) REFERENCES `rent` (`rent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rent_payment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `register` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `register` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `register` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
