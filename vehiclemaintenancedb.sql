-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 15, 2025 at 09:21 AM
-- Server version: 5.7.24
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vehiclemaintenancedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `maintenancerecords`
--

CREATE TABLE `maintenancerecords` (
  `RecordID` int(11) NOT NULL,
  `VehicleID` int(11) DEFAULT NULL,
  `Vehicle_Reg_No` varchar(10) NOT NULL,
  `MaintenanceDate` date DEFAULT NULL,
  `OdometerReading` int(11) DEFAULT NULL,
  `ServiceType` text,
  `ServiceDescription` text,
  `Cost` decimal(10,2) DEFAULT NULL,
  `ServiceImage` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `maintenancerecords`
--

INSERT INTO `maintenancerecords` (`RecordID`, `VehicleID`, `Vehicle_Reg_No`, `MaintenanceDate`, `OdometerReading`, `ServiceType`, `ServiceDescription`, `Cost`, `ServiceImage`) VALUES
(19, 21, 'NW-7253', '2025-01-07', 25000, 'Suspension Repair', 'ok', '200.00', 0x75706c6f6164732f342e6a706567),
(20, 20, 'WP-1452233', '2025-01-07', 450000, 'Suspension Repair', 'as', '12.00', 0x75706c6f6164732f363738336435333237366136652e6a7067),
(21, 14, 'WP-PQ-7869', '2025-01-07', 70000, 'Electrical System Repair', 'okkkkkkkk', '250.00', 0x75706c6f6164732f312e6a7067),
(22, 1, 'WP-1452', '2025-01-06', 80000, 'Routine Services', 'service', '4001.00', 0x75706c6f6164732f363738336435363566303431382e6a7067),
(23, 20, 'WP-1452233', '2025-01-06', 780002, 'Fuel System Repair', 'hg', '5000.00', 0x75706c6f6164732f313030303339303738392e6a7067);

-- --------------------------------------------------------

--
-- Table structure for table `partsused`
--

CREATE TABLE `partsused` (
  `PartID` int(11) NOT NULL,
  `RecordID` int(11) DEFAULT NULL,
  `Vehicle_Reg_No` varchar(20) NOT NULL,
  `PartName` varchar(100) DEFAULT NULL,
  `PartNumber` varchar(50) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Cost` decimal(10,2) DEFAULT NULL,
  `type_of_service` enum('Seasonal Maintenance','Preventive Maintenance','Electrical Repair','Engine Repair','Oil/oil filter/air filter changed','Battery replacement','Engine tune-up','Wheels aligned/balanced') NOT NULL,
  `Description` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `partsused`
--

INSERT INTO `partsused` (`PartID`, `RecordID`, `Vehicle_Reg_No`, `PartName`, `PartNumber`, `Quantity`, `Cost`, `type_of_service`, `Description`) VALUES
(1, NULL, 'WP-1452', 'Oil Filter', 'OF123', 1, '10.00', 'Seasonal Maintenance', 'okuj'),
(2, NULL, 'WP-14522', 'air filter', '125', 1, '4500.00', 'Oil/oil filter/air filter changed', 'ok3'),
(7, NULL, 'WP-PQ-7869', 'wew', '1245', 1, '21.00', 'Oil/oil filter/air filter changed', 'df');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `PermissionID` int(11) NOT NULL,
  `PermissionName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rolepermissions`
--

CREATE TABLE `rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `userroles`
--

CREATE TABLE `userroles` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `CreatedAt`) VALUES
(1, 'admin', '$2y$10$VAdXTbyyEQsKUvI2l1RTIePxaEjbBfblDqOxnUK2xZ8xPhJp5VIFq', 'admin@example.com', '2025-01-12 02:49:07'),
(4, 'user', '$2y$10$uNNoaIf75L4SGFh71RjQ4Ok6NivGSKn1BhyyVqp9lAZecy2Bi.qS.', 'user123@gmail.com', '2025-01-12 03:42:25'),
(5, 'rohan', '$2y$10$26m4Vr6muc0gMbWoDTAKC.B7M4IgN5kevq.G5YJZmH8t0kN4jHiFi', 'rohan@gmail.com', '2025-01-12 04:03:42');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `VehicleID` int(11) NOT NULL,
  `Vehicle_Reg_No` varchar(30) NOT NULL,
  `Make` varchar(50) DEFAULT NULL,
  `Model` varchar(50) DEFAULT NULL,
  `Year` year(4) DEFAULT NULL,
  `Engine_No` varchar(50) DEFAULT NULL,
  `Chassis Number` varchar(100) NOT NULL,
  `vehicle_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`VehicleID`, `Vehicle_Reg_No`, `Make`, `Model`, `Year`, `Engine_No`, `Chassis Number`, `vehicle_description`) VALUES
(1, 'WP-1452', 'Toyota', 'Toyota Hilux Vigo Smart Cab ', 2010, 'WP-QR-454222', 'DDKJ-4551222-HJ45622', 'Make Toyota Model Vigo Smart Cab YOM 2010-\r\n\r\n Mileage 155000 km Transmission Manual Fuel Type Diesel Engine 3000 CC Turbo (1KD) Options'),
(14, 'WP-PQ-7869', 'Mitshubishi', 'Pajaro', 2000, 'DD-45556-GGJ5678455', 'DD-455RT56-GGJ5678455', 'Easily access vehicle details through Vehicleinfo. Quickly search vehicle numbers for RC & Challan Search, and '),
(20, 'WP-1452233', 'Toyota', 'insightt', 2011, 'qw344566', 'khh455', 'ol'),
(21, 'NW-7253', 'Suzuki', 'Volty-250cc', 2000, 'AS-145SD-5478', 'BW78455-HSF-7522', 'Bike Type:\\r\\nMotorbikes\\r\\nCondition:\\r\\nUsed\\r\\nBrand:\\r\\nSuzuki\\r\\nModel:\\r\\nVolty\\r\\nTrim / Edition:\\r\\nSticker Model\\r\\nYear of Manufacture:\\r\\n2013\\r\\nEngine capacity:\\r\\n250 cc\\r\\nMileage:\\r\\n60,000 km\\r\\n');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `maintenancerecords`
--
ALTER TABLE `maintenancerecords`
  ADD PRIMARY KEY (`RecordID`),
  ADD KEY `VehicleID` (`VehicleID`),
  ADD KEY `Vehicle_Reg_No` (`Vehicle_Reg_No`);

--
-- Indexes for table `partsused`
--
ALTER TABLE `partsused`
  ADD PRIMARY KEY (`PartID`),
  ADD KEY `RecordID` (`RecordID`),
  ADD KEY `Vehicle_Reg_No` (`Vehicle_Reg_No`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`PermissionID`),
  ADD UNIQUE KEY `PermissionName` (`PermissionName`);

--
-- Indexes for table `rolepermissions`
--
ALTER TABLE `rolepermissions`
  ADD PRIMARY KEY (`RoleID`,`PermissionID`),
  ADD KEY `PermissionID` (`PermissionID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`RoleID`),
  ADD UNIQUE KEY `RoleName` (`RoleName`);

--
-- Indexes for table `userroles`
--
ALTER TABLE `userroles`
  ADD PRIMARY KEY (`UserID`,`RoleID`),
  ADD KEY `RoleID` (`RoleID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`VehicleID`),
  ADD KEY `Vehicle_Reg_No` (`Vehicle_Reg_No`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `maintenancerecords`
--
ALTER TABLE `maintenancerecords`
  MODIFY `RecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `partsused`
--
ALTER TABLE `partsused`
  MODIFY `PartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `PermissionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `VehicleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `maintenancerecords`
--
ALTER TABLE `maintenancerecords`
  ADD CONSTRAINT `MaintenanceRecords_ibfk_1` FOREIGN KEY (`VehicleID`) REFERENCES `vehicles` (`VehicleID`) ON DELETE SET NULL;

--
-- Constraints for table `partsused`
--
ALTER TABLE `partsused`
  ADD CONSTRAINT `PartsUsed_ibfk_1` FOREIGN KEY (`RecordID`) REFERENCES `maintenancerecords` (`RecordID`) ON DELETE SET NULL;

--
-- Constraints for table `rolepermissions`
--
ALTER TABLE `rolepermissions`
  ADD CONSTRAINT `rolepermissions_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `roles` (`RoleID`),
  ADD CONSTRAINT `rolepermissions_ibfk_2` FOREIGN KEY (`PermissionID`) REFERENCES `permissions` (`PermissionID`);

--
-- Constraints for table `userroles`
--
ALTER TABLE `userroles`
  ADD CONSTRAINT `userroles_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `userroles_ibfk_2` FOREIGN KEY (`RoleID`) REFERENCES `roles` (`RoleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
