-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2021 at 03:05 PM
-- Server version: 10.3.15-MariaDB
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `covidtmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(11) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `AdminuserName` varchar(20) NOT NULL,
  `MobileNumber` int(10) NOT NULL,
  `Email` varchar(120) NOT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `AdminuserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(2, 'Admin', 'admin', 9876543222, 'admin@globalhealthcorp.com', 'f925916e2754e5e03f75dd58a5733251', '2021-04-19 18:30:00'),
(3, 'John Doe', 'johndoe', 9876543210, 'john.doe@globalhealthcorp.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2025-04-01 08:00:00'),
(4, 'Jane Smith', 'jane.smith', '2345678901', 'jane.smith@globalhealthcorp.com', 'c146b9e8f3aa02c9eb49e9a3e831a5c3', '2018-03-01'),
(5, 'Michael Johnson', 'michael.johnson', '3456789012', 'michael.johnson@globalhealthcorp.com', '5fd4c8603d3ec0134c7a0e23a3f17f94', '2019-08-23'),
(6, 'Emily Carter', 'emily.carter', '4567890123', 'emily.carter@globalhealthcorp.com', '74e09a87e237c54b4cfabd0c4f9fb9a7', '2021-01-10'),
(7, 'David Lee', 'david.lee', '5678901234', 'david.lee@globalhealthcorp.com', '7f066bd0d5f5b76f8864c8ad107ac2ff', '2022-11-05'),
(8, 'Sophia Brown', 'sophia.brown', '6789012345', 'sophia.brown@globalhealthcorp.com', '14a1b3f6fb1f847c0137feebf7fd8f22', '2020-09-25'),
(9, 'James Williams', 'james.williams', '7890123456', 'james.williams@globalhealthcorp.com', '0a709e44f3c239dd5c2f99b3f36823ce', '2023-04-02');


-- --------------------------------------------------------

--
-- Table structure for table `tblpatients`
--

CREATE TABLE `tblpatients` (
  `id` int(11) NOT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(12) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `GovtIssuedId` varchar(150) DEFAULT NULL,
  `GovtIssuedIdNo` varchar(150) DEFAULT NULL,
  `FullAddress` varchar(255) DEFAULT NULL,
  `State` varchar(200) DEFAULT NULL,
  `RegistrationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblpatients`
--

INSERT INTO `tblpatients` (`id`, `FullName`, `MobileNumber`, `DateOfBirth`, `GovtIssuedId`, `GovtIssuedIdNo`, `FullAddress`, `State`, `RegistrationDate`) VALUES
(1, 'Anuj kumar', 1234567890, '1999-02-01', 'Driving License', '342545445345', 'A83748 New Delhi India', 'Delhi', '2021-04-27 17:31:22'),
(2, 'Sarita', 6547893210, '1990-05-09', 'Driving License', 'DL1234567890', 'H 826273 Noida', 'Uttar Pradesh', '2021-04-27 18:04:57'),
(4, 'Garima Singh', 4598520125, '2005-01-08', 'Pancard', 'DDDKJKJ454545H', 'A-1234 Patna', 'Bihar', '2021-05-08 05:49:44'),
(5, 'Amit Singh', 2536987410, '2007-06-01', 'ID Card', 'ID987654321', 'H 37334 New Delhi', 'Delhi', '2021-05-08 09:25:50'),
(6, 'Rahul Yadav', 1234567899, '2003-06-05', 'Driving License', '5435345', 'ABC 123 XYZ Street Noida', 'Uttar Pradesh', '2021-05-08 09:29:22'),
(7, 'Emily Carter', 9876501234, '1985-07-14', 'Passport', 'XH1234567', '123 Main St, Springfield', 'Illinois', '2025-04-02 09:30:00'),
(8, 'Michael Smith', 9876505678, '1992-03-22', 'Driver License', 'D123456789', '456 Elm St, Metropolis', 'New York', '2025-04-02 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblphlebotomist`
--

CREATE TABLE `tblphlebotomist` (
  `id` int(11) NOT NULL,
  `EmpID` varchar(100) DEFAULT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(12) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblphlebotomist`
--

INSERT INTO `tblphlebotomist` (`id`, `EmpID`, `FullName`, `MobileNumber`, `RegDate`) VALUES
(3, 'GH001', 'Amit Singh', 9876543212, '2021-05-03 04:51:44'),
(4, 'GH002', 'Rahul', 8529631470, '2021-05-03 04:52:06'),
(5, 'GH003', 'Sanjeev Tomar', 1234567890, '2021-05-08 09:34:11'),
(6, 'GH004', 'Sophia Johnson', 9876509876, '2025-04-02 11:00:00'),
(7, 'GH005', 'James Williams', 9876512345, '2025-04-02 11:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblreporttracking`
--

CREATE TABLE `tblreporttracking` (
  `id` int(11) NOT NULL,
  `OrderNumber` bigint(40) DEFAULT NULL,
  `Remark` varchar(255) DEFAULT NULL,
  `Status` varchar(120) DEFAULT NULL,
  `PostingTime` timestamp NULL DEFAULT current_timestamp(),
  `RemarkBy` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblreporttracking`
--

INSERT INTO `tblreporttracking` (`id`, `OrderNumber`, `Remark`, `Status`, `PostingTime`, `RemarkBy`) VALUES
(1, 450040675, 'The Phlebotomist is on the way for collection.', 'On the Way for Collection', '2021-05-06 04:36:22', 2),
(6, 450040675, 'Sample collection.', 'Sample Collected', '2021-05-06 19:15:25', 2),
(7, 450040675, 'Sample sent to the lab.', 'Sent to Lab', '2021-05-06 19:15:48', 2),
(9, 450040675, 'Report uploaded.', 'Delivered', '2021-05-06 20:01:48', 2),
(10, 617325549, 'The phlebotomist is on the way to sample collection.', 'On the Way for Collection', '2021-05-07 04:44:38', 2),
(11, 617325549, 'Sample collected successfully.', 'Sample Collected', '2021-05-07 04:46:46', 2),
(12, 617325549, 'Sample sent to the lab.', 'Sent to Lab', '2021-05-07 04:51:25', 2),
(13, 617325549, 'Report uploaded.', 'Delivered', '2021-05-07 04:57:20', 2),
(14, 250482553, 'On the way for sample collection.', 'On the Way for Collection', '2021-05-08 09:31:42', 2),
(15, 250482553, 'Sample collected successfully', 'Sample Collected', '2021-05-08 09:32:06', 2),
(16, 250482553, 'Sample sent to lab', 'Sent to Lab', '2021-05-08 09:32:26', 2),
(17, 250482553, 'Report Uploaded', 'Delivered', '2021-05-08 09:32:51', 2),
(18, 888123456789, 'Phlebotomist assigned for collection.', 'Assigned', '2025-04-02 12:05:00', 3),
(19, 888987654321, 'Phlebotomist assigned for collection.', 'Assigned', '2025-04-02 12:35:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbltestrecord`
--

CREATE TABLE `tbltestrecord` (
  `id` int(11) NOT NULL,
  `OrderNumber` bigint(14) DEFAULT NULL,
  `PatientMobileNumber` bigint(14) DEFAULT NULL,
  `TestType` varchar(100) DEFAULT NULL,
  `TestTimeSlot` varchar(120) DEFAULT NULL,
  `ReportStatus` varchar(100) DEFAULT NULL,
  `FinalReport` varchar(150) DEFAULT NULL,
  `ReportUploadTime` varchar(200) DEFAULT NULL,
  `RegistrationDate` timestamp NULL DEFAULT current_timestamp(),
  `AssignedtoEmpId` varchar(150) DEFAULT NULL,
  `AssigntoName` varchar(180) DEFAULT NULL,
  `AssignedTime` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbltestrecord`
--

INSERT INTO `tbltestrecord` (`id`, `OrderNumber`, `PatientMobileNumber`, `TestType`, `TestTimeSlot`, `ReportStatus`, `FinalReport`, `ReportUploadTime`, `RegistrationDate`, `AssignedtoEmpId`, `AssigntoName`, `AssignedTime`) VALUES
(1, 450040675, 1234567890, 'Antigen', '2021-05-01T04:05', 'Delivered', '2c86e2aa7eb4cb4db70379e28fab9b521620331308.pdf', '07-05-2021 01:31:48 AM', '2021-04-27 17:31:23', '12587493', 'Amit Singh', '06-05-2021 10:05:22 AM'),
(2, 617325549, 6547893210, 'RT-PCR', '2021-05-01T05:10', 'Delivered', '2c86e2aa7eb4cb4db70379e28fab9b521620363440.pdf', '07-05-2021 10:27:20 AM', '2021-04-27 18:04:58', '105202365', 'Rahul', '07-05-2021 10:13:41 AM'),
(4, 740138296, 1234567890, 'RT-PCR', '2021-05-05T14:40', 'Assigned', NULL, NULL, '2021-04-27 19:10:30', '105202365', 'Rahul', '07-05-2021 03:52:05 PM'),
(5, 716060226, 4598520125, 'CB-NAAT', '2021-05-15T14:22', NULL, NULL, NULL, '2021-05-08 05:49:46', NULL, NULL, NULL),
(6, 599452326, 2536987410, 'CB-NAAT', '2021-05-20T19:00', NULL, NULL, NULL, '2021-05-08 09:25:50', NULL, NULL, NULL),
(7, 250482553, 1234567899, 'Antigen', '2021-05-11T15:00', 'Delivered', '2c86e2aa7eb4cb4db70379e28fab9b521620466371.pdf', '08-05-2021 03:02:51 PM', '2021-05-08 09:29:22', '12587493', 'Amit Singh', '08-05-2021 03:00:47 PM'),
(18, 888123456789, NULL, NULL, NULL, 'Assigned', NULL, NULL, '2025-04-02 12:05:00', 3, 'Phlebotomist assigned for collection.', NULL),
(19, 888987654321, NULL, NULL, NULL, 'Assigned', NULL, NULL, '2025-04-02 12:35:00', 3, 'Phlebotomist assigned for collection.', NULL);

-- Tạo bảng `tblemployees` để mô phỏng thông tin nhân viên
CREATE TABLE `tblemployees` (
  `EmployeeID` int(11) NOT NULL,
  `FullName` varchar(150) NOT NULL,
  `Position` varchar(100) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `Salary` decimal(10, 2) NOT NULL,
  `JoinDate` date NOT NULL,
  `Email` varchar(120) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `Status` varchar(50) DEFAULT 'Active',
  PRIMARY KEY (`EmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Thêm dữ liệu giả lập vào bảng `tblemployees`
INSERT INTO `tblemployees` (`EmployeeID`, `FullName`, `Position`, `Department`, `Salary`, `JoinDate`, `Email`, `PhoneNumber`, `Status`) VALUES
(1, 'John Doe', 'Software Engineer', 'IT', 85000.00, '2020-06-15', 'john.doe@globalhealthcorp.com', '123-456-7890', 'Active'),
(2, 'Jane Smith', 'Project Manager', 'IT', 95000.00, '2018-03-01', 'jane.smith@globalhealthcorp.com', '234-567-8901', 'Active'),
(3, 'Michael Johnson', 'HR Specialist', 'HR', 60000.00, '2019-08-23', 'michael.johnson@globalhealthcorp.com', '345-678-9012', 'Active'),
(4, 'Emily Carter', 'Accountant', 'Finance', 70000.00, '2021-01-10', 'emily.carter@globalhealthcorp.com', '456-789-0123', 'Active'),
(5, 'David Lee', 'Security Officer', 'Security', 55000.00, '2022-11-05', 'david.lee@globalhealthcorp.com', '567-890-1234', 'Active'),
(6, 'Sophia Brown', 'Marketing Manager', 'Marketing', 80000.00, '2020-09-25', 'sophia.brown@globalhealthcorp.com', '678-901-2345', 'Inactive'),
(7, 'James Williams', 'IT Support', 'IT', 55000.00, '2023-04-02', 'james.williams@globalhealthcorp.com', '789-012-3456', 'Active'),
(8, 'CTF{globalhealthcorp_flag_2025}', 'CTF Flag', 'CTF', 0.00, '2025-04-28', 'ctf.flag@globalhealthcorp.com', '000-000-0000', 'Inactive');

-- Tạo index cho bảng `tblemployees` để tối ưu hóa tìm kiếm
ALTER TABLE `tblemployees`
  ADD INDEX `idx_department` (`Department`);
ALTER TABLE `tblemployees`
  ADD INDEX `idx_status` (`Status`);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpatients`
--
ALTER TABLE `tblpatients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblphlebotomist`
--
ALTER TABLE `tblphlebotomist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblreporttracking`
--
ALTER TABLE `tblreporttracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbltestrecord`
--
ALTER TABLE `tbltestrecord`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblpatients`
--
ALTER TABLE `tblpatients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblphlebotomist`
--
ALTER TABLE `tblphlebotomist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblreporttracking`
--
ALTER TABLE `tblreporttracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbltestrecord`
--
ALTER TABLE `tbltestrecord`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
