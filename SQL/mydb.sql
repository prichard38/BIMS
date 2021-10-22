-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 23, 2021 at 12:01 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `LogIn` (IN `user_id` VARCHAR(45), IN `user_password` VARCHAR(45), OUT `user_role` VARCHAR(45))  BEGIN

	DECLARE role_num INT;
    	DECLARE role_name VARCHAR(45);

	SELECT UserRole INTO role_num
	FROM Users
	WHERE UserId = user_id AND UserPassword=user_password;

	SELECT UserRoleName INTO role_name
	FROM UserRole
	WHERE UserRole = role_num;
    
    	SET user_role = role_name;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `AdditionalElements`
--

CREATE TABLE `AdditionalElements` (
  `AdditionalElementID` int(11) NOT NULL,
  `AdditionalElement` longtext DEFAULT NULL,
  `AdditionalElementName` varchar(100) DEFAULT NULL,
  `AdditionalElementDescription` longtext DEFAULT NULL,
  `Inspections_InspectionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `AgeAndService`
--

CREATE TABLE `AgeAndService` (
  `Bridges_BridgeNo` varchar(50) NOT NULL,
  `YearBuilt` year(4) DEFAULT NULL,
  `ADTyear` year(4) DEFAULT NULL,
  `InventoryRouteADT` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `BridgeElementInspectionPhotos`
--

CREATE TABLE `BridgeElementInspectionPhotos` (
  `PhotoID` int(11) NOT NULL,
  `BEInspections_BEInspectionID` int(11) DEFAULT NULL,
  `Photo` longtext DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Comments` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `BridgeElementInspections`
--

CREATE TABLE `BridgeElementInspections` (
  `BEInspectionID` int(11) NOT NULL,
  `ElementID` int(11) DEFAULT NULL,
  `Inspections_InspectionID` int(11) DEFAULT NULL,
  `Rating` double DEFAULT NULL,
  `Description` longtext DEFAULT NULL,
  `UpdatedDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `BridgeElements`
--

CREATE TABLE `BridgeElements` (
  `ElementID` int(11) NOT NULL,
  `Bridges_BridgeNo` varchar(50) NOT NULL,
  `BridgeModelNo_BridgeElements` int(11) DEFAULT NULL,
  `InspectionTypeCode_InspectionTypeNo` int(11) NOT NULL,
  `Class_ClassNo` int(11) NOT NULL,
  `Category_CategoryNo` int(11) NOT NULL,
  `Material_MaterialNo` int(11) NOT NULL,
  `DetailElements_DetailElementNo` int(11) NOT NULL,
  `ElementX` double DEFAULT NULL,
  `ElementY` double DEFAULT NULL,
  `ElementZ` double DEFAULT NULL,
  `AddedDate` timestamp NULL DEFAULT NULL,
  `DeletedDate` timestamp NULL DEFAULT NULL,
  `ModifiedBy` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `BridgeInspectionInfo`
--

CREATE TABLE `BridgeInspectionInfo` (
  `Bridges_BridgeNo` varchar(50) NOT NULL,
  `LatestInspectionDate` date DEFAULT NULL,
  `Inspections_InspectionID` int(11) DEFAULT NULL,
  `Rating` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `BridgeModels`
--

CREATE TABLE `BridgeModels` (
  `BridgeModelNo_BridgeModels` int(11) NOT NULL,
  `BridgeModelPath` longtext DEFAULT NULL,
  `CreatedDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Bridges`
--

CREATE TABLE `Bridges` (
  `BridgeNo` varchar(50) NOT NULL,
  `BARsNo` varchar(12) DEFAULT NULL,
  `BridgeName` varchar(45) DEFAULT NULL,
  `FeatureIntersected` varchar(45) DEFAULT NULL,
  `FacilityCarried` varchar(45) DEFAULT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `District` int(11) DEFAULT NULL,
  `County_CountyNo` int(11) NOT NULL,
  `BridgePicture` longtext DEFAULT NULL,
  `BridgeModelNo_Bridges` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Bridge Identification';

--
-- Dumping data for table `Bridges`
--

INSERT INTO `Bridges` (`BridgeNo`, `BARsNo`, `BridgeName`, `FeatureIntersected`, `FacilityCarried`, `Location`, `District`, `County_CountyNo`, `BridgePicture`, `BridgeModelNo_Bridges`) VALUES
('001-4/5-2.95(01810)', NULL, 'Cane Hill Bridge over Little Red River', NULL, NULL, NULL, NULL, 2, NULL, NULL),
('006-3/4-8.65(03148)', NULL, 'Robert C. Byrd Bridge over Ohio River', NULL, NULL, NULL, NULL, 1, NULL, NULL),
('bridge #3', NULL, 'East Huntington Bridge over Ohio River', NULL, NULL, NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
  `CategoryNo` int(11) NOT NULL,
  `CategoryName` varchar(45) DEFAULT NULL,
  `Class_ClassNo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Bridge Structure type and Material_Level2_Category';

-- --------------------------------------------------------

--
-- Table structure for table `Class`
--

CREATE TABLE `Class` (
  `ClassNo` int(11) NOT NULL,
  `ClassName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Bridge Structure type and Material_Level1_Class';

-- --------------------------------------------------------

--
-- Table structure for table `Classification`
--

CREATE TABLE `Classification` (
  `Bridges_BridgeNo` varchar(50) NOT NULL,
  `NBISBridgeLength` tinyint(4) DEFAULT NULL,
  `NHS` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE `Comments` (
  `CommentID` int(11) NOT NULL,
  `Inspections_InspectionID` int(11) DEFAULT NULL,
  `AdminComments` varchar(300) DEFAULT NULL,
  `InspectorComments` varchar(300) DEFAULT NULL,
  `EvaluatorComments` varchar(300) DEFAULT NULL,
  `Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `County`
--

CREATE TABLE `County` (
  `CountyNo` int(11) NOT NULL,
  `CountyName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `County`
--

INSERT INTO `County` (`CountyNo`, `CountyName`) VALUES
(1, 'Cabell'),
(2, 'Wyoming');

-- --------------------------------------------------------

--
-- Table structure for table `DetailElements`
--

CREATE TABLE `DetailElements` (
  `DetailElementNo` int(11) NOT NULL,
  `DetailElementName` varchar(45) DEFAULT NULL,
  `DetailElementNum` int(11) DEFAULT NULL,
  `Material_MaterialNo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Bridge Structure type and Material_Level4_DetailElements';

-- --------------------------------------------------------

--
-- Table structure for table `DroneImages`
--

CREATE TABLE `DroneImages` (
  `DroneImageID` int(11) NOT NULL,
  `DroneImageSet_ImageSetID` int(11) NOT NULL,
  `Picture` longtext DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Comments` longtext DEFAULT NULL,
  `ElementX` double DEFAULT NULL,
  `ElementY` double DEFAULT NULL,
  `ElementZ` double DEFAULT NULL,
  `AddedImage` tinyint(4) DEFAULT NULL,
  `BEInspections_BEInspectionID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `DroneImageSet`
--

CREATE TABLE `DroneImageSet` (
  `ImageSetID` int(11) NOT NULL,
  `Inspections_InspectionID` int(11) DEFAULT NULL,
  `DateTime` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Inspections`
--

CREATE TABLE `Inspections` (
  `InspectionID` int(11) NOT NULL,
  `Bridges_BridgeNo` varchar(50) NOT NULL,
  `InspectionTypeNo` int(11) DEFAULT NULL,
  `AssignedDate` timestamp NULL DEFAULT NULL,
  `DueDate` timestamp NULL DEFAULT NULL,
  `FinishedDate` timestamp NULL DEFAULT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Report` longtext DEFAULT NULL,
  `OverallRating` double DEFAULT NULL,
  `AdminID` int(11) DEFAULT NULL,
  `InspectorID` int(11) DEFAULT NULL,
  `EvaluatorID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Inspections`
--

INSERT INTO `Inspections` (`InspectionID`, `Bridges_BridgeNo`, `InspectionTypeNo`, `AssignedDate`, `DueDate`, `FinishedDate`, `Status`, `Report`, `OverallRating`, `AdminID`, `InspectorID`, `EvaluatorID`) VALUES
(1, '006-3/4-8.65(03148)', 1, NULL, NULL, '2021-04-21 21:48:17', 'high', NULL, 1, 2, 3, 1),
(2, '001-4/5-2.95(01810)', 1, NULL, NULL, '2021-05-20 21:49:52', 'high', NULL, 1, 2, 3, 1),
(3, '001-4/5-2.95(01810)', 1, NULL, NULL, '2020-04-02 01:21:12', 'middle', NULL, 5, 2, 3, 1),
(4, '001-4/5-2.95(01810)', 1, NULL, NULL, '2021-10-06 01:56:27', 'low', NULL, 9, 1, 3, 2),
(5, '006-3/4-8.65(03148)', 1, NULL, NULL, '2021-10-05 07:58:59', NULL, NULL, 8, 2, 3, 1),
(6, '006-3/4-8.65(03148)', 1, NULL, NULL, '2021-10-01 08:00:20', NULL, NULL, 5, 2, 3, 1),
(7, '006-3/4-8.65(03148)', 1, NULL, NULL, '2021-09-06 09:31:01', NULL, NULL, 9, 1, 3, 2),
(8, '001-4/5-2.95(01810)', 1, NULL, NULL, '2021-08-02 09:40:20', NULL, NULL, 6, 2, 3, 1),
(9, '001-4/5-2.95(01810)', 1, NULL, NULL, '2021-10-04 09:40:20', NULL, NULL, 8, 2, 3, 1),
(10, '001-4/5-2.95(01810)', 1, NULL, NULL, '2021-07-13 09:40:20', NULL, NULL, 7, 2, 3, 1),
(11, 'bridge #3', 1, NULL, NULL, '2021-10-15 03:14:55', NULL, NULL, 9, 2, 3, 1),
(12, 'bridge #3', 1, NULL, NULL, '2021-10-01 03:14:55', NULL, NULL, 9, 2, 3, 1),
(13, 'bridge #3', 1, NULL, NULL, '2021-10-06 03:14:55', NULL, NULL, 7, 2, 3, 1),
(14, 'bridge #3', 1, NULL, NULL, '2021-09-20 03:14:55', NULL, NULL, 7, 2, 3, 1),
(15, 'bridge #3', 1, NULL, NULL, '2021-08-10 03:14:55', NULL, NULL, 7, 2, 3, 1),
(16, 'bridge #3', 1, NULL, NULL, '2021-05-04 03:14:55', NULL, NULL, 7, 2, 3, 1),
(17, '006-3/4-8.65(03148)', 1, NULL, NULL, '2021-08-09 07:58:59', NULL, NULL, 4, 2, 3, 1),
(18, '006-3/4-8.65(03148)', 1, NULL, NULL, '2020-10-14 21:48:17', 'high', NULL, 6, 3, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `InspectionTypeCode`
--

CREATE TABLE `InspectionTypeCode` (
  `InspectionTypeNo` int(11) NOT NULL,
  `InspectionTypeName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `InspectionTypeCode`
--

INSERT INTO `InspectionTypeCode` (`InspectionTypeNo`, `InspectionTypeName`) VALUES
(1, 'Annual');

-- --------------------------------------------------------

--
-- Table structure for table `Material`
--

CREATE TABLE `Material` (
  `MaterialNo` int(11) NOT NULL,
  `MaterialName` varchar(45) DEFAULT NULL,
  `Category_CategoryNo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Bridge Structure type and Material_Level3_Material';

-- --------------------------------------------------------

--
-- Table structure for table `NarrativeElements`
--

CREATE TABLE `NarrativeElements` (
  `NarrativeElementNo` int(11) NOT NULL,
  `NarrativeElementName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `NarrativeReportPhotos`
--

CREATE TABLE `NarrativeReportPhotos` (
  `PhotoID` int(11) NOT NULL,
  `NarrativeReport_NarrativeElementID` int(11) NOT NULL,
  `Photo` longtext DEFAULT NULL,
  `PhotoName` varchar(100) DEFAULT NULL,
  `Description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `NarrativeReports`
--

CREATE TABLE `NarrativeReports` (
  `NarrativeElementID` int(11) NOT NULL,
  `NarrativeElements_NarrativeElementNo` int(11) NOT NULL,
  `NarrativeElementDescription` longtext DEFAULT NULL,
  `Inspections_InspectionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `UserRole`
--

CREATE TABLE `UserRole` (
  `UserRole` int(11) NOT NULL,
  `UserRoleName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `UserRole`
--

INSERT INTO `UserRole` (`UserRole`, `UserRoleName`) VALUES
(1, 'supervisor'),
(2, 'admin'),
(3, 'inspector');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `UserNo` int(11) NOT NULL,
  `UserID` varchar(45) DEFAULT NULL,
  `UserPassword` varchar(45) DEFAULT NULL,
  `UserRole` int(11) DEFAULT NULL,
  `UserPicture` longtext DEFAULT NULL,
  `FirstName` varchar(45) DEFAULT NULL,
  `MiddleName` varchar(45) DEFAULT NULL,
  `LastName` varchar(45) DEFAULT NULL,
  `Phone` varchar(45) DEFAULT NULL,
  `Address` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `UserCreated` timestamp NULL DEFAULT NULL,
  `UserModified` timestamp NULL DEFAULT NULL,
  `EmailVerification` tinyint(4) DEFAULT NULL,
  `ActiveStatus` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`UserNo`, `UserID`, `UserPassword`, `UserRole`, `UserPicture`, `FirstName`, `MiddleName`, `LastName`, `Phone`, `Address`, `Email`, `DateOfBirth`, `UserCreated`, `UserModified`, `EmailVerification`, `ActiveStatus`) VALUES
(1, 'frodobaggins', 'frodo', 1, NULL, 'Frodo', NULL, 'Baggins', NULL, NULL, 'bagginsf@email.com', NULL, '2021-10-04 19:03:07', NULL, NULL, 1),
(2, 'willcoleman', 'coleman', 2, NULL, 'Will', NULL, 'Coleman', NULL, NULL, NULL, NULL, '2021-10-06 23:59:47', NULL, NULL, 1),
(3, 'alexprichard', 'Prichard', 3, NULL, 'Alex', NULL, 'Prichard', NULL, NULL, NULL, NULL, '2021-10-06 00:07:30', NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AdditionalElements`
--
ALTER TABLE `AdditionalElements`
  ADD PRIMARY KEY (`AdditionalElementID`),
  ADD KEY `fk_AdditionalElements_Inspection1_idx` (`Inspections_InspectionID`);

--
-- Indexes for table `AgeAndService`
--
ALTER TABLE `AgeAndService`
  ADD PRIMARY KEY (`Bridges_BridgeNo`);

--
-- Indexes for table `BridgeElementInspectionPhotos`
--
ALTER TABLE `BridgeElementInspectionPhotos`
  ADD PRIMARY KEY (`PhotoID`),
  ADD KEY `BEInspectionID_idx` (`BEInspections_BEInspectionID`);

--
-- Indexes for table `BridgeElementInspections`
--
ALTER TABLE `BridgeElementInspections`
  ADD PRIMARY KEY (`BEInspectionID`),
  ADD KEY `ElementID_idx` (`ElementID`),
  ADD KEY `InspectionID_idx` (`Inspections_InspectionID`);

--
-- Indexes for table `BridgeElements`
--
ALTER TABLE `BridgeElements`
  ADD PRIMARY KEY (`ElementID`),
  ADD KEY `fk_InspectionType_InspectionTypeCode1_idx` (`InspectionTypeCode_InspectionTypeNo`),
  ADD KEY `fk_InspectionType_Bridges1_idx` (`Bridges_BridgeNo`),
  ADD KEY `fk_InspectionTypeElements_Class1_idx` (`Class_ClassNo`),
  ADD KEY `fk_InspectionTypeElements_Category1_idx` (`Category_CategoryNo`),
  ADD KEY `fk_InspectionTypeElements_Material1_idx` (`Material_MaterialNo`),
  ADD KEY `fk_InspectionTypeElements_DetailElements1_idx` (`DetailElements_DetailElementNo`),
  ADD KEY `BridgeModelNo_idx` (`BridgeModelNo_BridgeElements`);

--
-- Indexes for table `BridgeInspectionInfo`
--
ALTER TABLE `BridgeInspectionInfo`
  ADD PRIMARY KEY (`Bridges_BridgeNo`),
  ADD KEY `InspectionID_idx` (`Inspections_InspectionID`);

--
-- Indexes for table `BridgeModels`
--
ALTER TABLE `BridgeModels`
  ADD PRIMARY KEY (`BridgeModelNo_BridgeModels`);

--
-- Indexes for table `Bridges`
--
ALTER TABLE `Bridges`
  ADD PRIMARY KEY (`BridgeNo`),
  ADD UNIQUE KEY `BARs_No_UNIQUE` (`BARsNo`),
  ADD KEY `fk_Bridges_County1_idx` (`County_CountyNo`),
  ADD KEY `BridgeModelNo_idx` (`BridgeModelNo_Bridges`);

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`CategoryNo`),
  ADD KEY `fk_Category_Class_idx` (`Class_ClassNo`);

--
-- Indexes for table `Class`
--
ALTER TABLE `Class`
  ADD PRIMARY KEY (`ClassNo`);

--
-- Indexes for table `Classification`
--
ALTER TABLE `Classification`
  ADD PRIMARY KEY (`Bridges_BridgeNo`),
  ADD KEY `fk_Classification_Bridges1_idx` (`Bridges_BridgeNo`);

--
-- Indexes for table `Comments`
--
ALTER TABLE `Comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `InspectionID_idx` (`Inspections_InspectionID`);

--
-- Indexes for table `County`
--
ALTER TABLE `County`
  ADD PRIMARY KEY (`CountyNo`);

--
-- Indexes for table `DetailElements`
--
ALTER TABLE `DetailElements`
  ADD PRIMARY KEY (`DetailElementNo`),
  ADD KEY `fk_DetailElements_Material1_idx` (`Material_MaterialNo`);

--
-- Indexes for table `DroneImages`
--
ALTER TABLE `DroneImages`
  ADD PRIMARY KEY (`DroneImageID`),
  ADD KEY `fk_DroneImages_DroneImageSet1_idx` (`DroneImageSet_ImageSetID`),
  ADD KEY `BEInspectionID_idx` (`BEInspections_BEInspectionID`);

--
-- Indexes for table `DroneImageSet`
--
ALTER TABLE `DroneImageSet`
  ADD PRIMARY KEY (`ImageSetID`),
  ADD KEY `InspectionID_idx` (`Inspections_InspectionID`);

--
-- Indexes for table `Inspections`
--
ALTER TABLE `Inspections`
  ADD PRIMARY KEY (`InspectionID`),
  ADD KEY `fk_Inspection_Bridges1_idx` (`Bridges_BridgeNo`),
  ADD KEY `InspectionTypeNo_idx` (`InspectionTypeNo`),
  ADD KEY `UserNo_idx` (`AdminID`),
  ADD KEY `UserNo_idx1` (`InspectorID`),
  ADD KEY `UserNo_idx2` (`EvaluatorID`);

--
-- Indexes for table `InspectionTypeCode`
--
ALTER TABLE `InspectionTypeCode`
  ADD PRIMARY KEY (`InspectionTypeNo`);

--
-- Indexes for table `Material`
--
ALTER TABLE `Material`
  ADD PRIMARY KEY (`MaterialNo`),
  ADD KEY `fk_Material_Category1_idx` (`Category_CategoryNo`);

--
-- Indexes for table `NarrativeElements`
--
ALTER TABLE `NarrativeElements`
  ADD PRIMARY KEY (`NarrativeElementNo`);

--
-- Indexes for table `NarrativeReportPhotos`
--
ALTER TABLE `NarrativeReportPhotos`
  ADD PRIMARY KEY (`PhotoID`),
  ADD KEY `fk_NarrativeReportPhoto_NarrativeReport1_idx` (`NarrativeReport_NarrativeElementID`);

--
-- Indexes for table `NarrativeReports`
--
ALTER TABLE `NarrativeReports`
  ADD PRIMARY KEY (`NarrativeElementID`),
  ADD KEY `fk_NarrativeReport_Inspection1_idx` (`Inspections_InspectionID`),
  ADD KEY `fk_NarrativeReport_NarrativeElements1_idx` (`NarrativeElements_NarrativeElementNo`);

--
-- Indexes for table `UserRole`
--
ALTER TABLE `UserRole`
  ADD PRIMARY KEY (`UserRole`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserNo`),
  ADD KEY `UserRole_idx` (`UserRole`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AdditionalElements`
--
ALTER TABLE `AdditionalElements`
  ADD CONSTRAINT `fk_AdditionalElements_Inspection1` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `AgeAndService`
--
ALTER TABLE `AgeAndService`
  ADD CONSTRAINT `fk_AgeAndService_Bridges1` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BridgeElementInspectionPhotos`
--
ALTER TABLE `BridgeElementInspectionPhotos`
  ADD CONSTRAINT `BEInspectionID_BEInspections_BEInspectionPhotos` FOREIGN KEY (`BEInspections_BEInspectionID`) REFERENCES `BridgeElementInspections` (`BEInspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BridgeElementInspections`
--
ALTER TABLE `BridgeElementInspections`
  ADD CONSTRAINT `ElementID` FOREIGN KEY (`ElementID`) REFERENCES `BridgeElements` (`ElementID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `InspectionID_Inspections_BridgeElementInspections` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BridgeElements`
--
ALTER TABLE `BridgeElements`
  ADD CONSTRAINT `BridgeModelNo_BridgeModels_BridgeElements` FOREIGN KEY (`BridgeModelNo_BridgeElements`) REFERENCES `BridgeModels` (`BridgeModelNo_BridgeModels`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_InspectionTypeElements_Category1` FOREIGN KEY (`Category_CategoryNo`) REFERENCES `Category` (`CategoryNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_InspectionTypeElements_Class1` FOREIGN KEY (`Class_ClassNo`) REFERENCES `Class` (`ClassNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_InspectionTypeElements_DetailElements1` FOREIGN KEY (`DetailElements_DetailElementNo`) REFERENCES `DetailElements` (`DetailElementNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_InspectionTypeElements_Material1` FOREIGN KEY (`Material_MaterialNo`) REFERENCES `Material` (`MaterialNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_InspectionType_Bridges1` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_InspectionType_InspectionTypeCode1` FOREIGN KEY (`InspectionTypeCode_InspectionTypeNo`) REFERENCES `InspectionTypeCode` (`InspectionTypeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BridgeInspectionInfo`
--
ALTER TABLE `BridgeInspectionInfo`
  ADD CONSTRAINT `InspectionID_Inspections_BridgeInspectionInfo` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BridgeInspectionInfo_Bridges1` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Bridges`
--
ALTER TABLE `Bridges`
  ADD CONSTRAINT `BridgeModelNo_BridgeModels_Bridges` FOREIGN KEY (`BridgeModelNo_Bridges`) REFERENCES `BridgeModels` (`BridgeModelNo_BridgeModels`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Bridges_County1` FOREIGN KEY (`County_CountyNo`) REFERENCES `County` (`CountyNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Category`
--
ALTER TABLE `Category`
  ADD CONSTRAINT `fk_Category_Class` FOREIGN KEY (`Class_ClassNo`) REFERENCES `Class` (`ClassNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Classification`
--
ALTER TABLE `Classification`
  ADD CONSTRAINT `fk_Classification_Bridges1` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Comments`
--
ALTER TABLE `Comments`
  ADD CONSTRAINT `InspectionID_Inspections_Comments` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `DetailElements`
--
ALTER TABLE `DetailElements`
  ADD CONSTRAINT `fk_DetailElements_Material1` FOREIGN KEY (`Material_MaterialNo`) REFERENCES `Material` (`MaterialNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `DroneImages`
--
ALTER TABLE `DroneImages`
  ADD CONSTRAINT `BEInspectionID_BEInspections_DroneImages` FOREIGN KEY (`BEInspections_BEInspectionID`) REFERENCES `BridgeElementInspections` (`BEInspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DroneImages_DroneImageSet1` FOREIGN KEY (`DroneImageSet_ImageSetID`) REFERENCES `DroneImageSet` (`ImageSetID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `DroneImageSet`
--
ALTER TABLE `DroneImageSet`
  ADD CONSTRAINT `InspectionID_Inspections_DroneImageSet` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Inspections`
--
ALTER TABLE `Inspections`
  ADD CONSTRAINT `InspectionTypeNo` FOREIGN KEY (`InspectionTypeNo`) REFERENCES `InspectionTypeCode` (`InspectionTypeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `UserNoAdmin` FOREIGN KEY (`AdminID`) REFERENCES `Users` (`UserNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `UserNoEvaluator` FOREIGN KEY (`EvaluatorID`) REFERENCES `Users` (`UserNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `UserNoInspector` FOREIGN KEY (`InspectorID`) REFERENCES `Users` (`UserNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Inspection_Bridges1` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Material`
--
ALTER TABLE `Material`
  ADD CONSTRAINT `fk_Material_Category1` FOREIGN KEY (`Category_CategoryNo`) REFERENCES `Category` (`CategoryNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `NarrativeReportPhotos`
--
ALTER TABLE `NarrativeReportPhotos`
  ADD CONSTRAINT `fk_NarrativeReportPhoto_NarrativeReport1` FOREIGN KEY (`NarrativeReport_NarrativeElementID`) REFERENCES `NarrativeReports` (`NarrativeElementID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `NarrativeReports`
--
ALTER TABLE `NarrativeReports`
  ADD CONSTRAINT `fk_NarrativeReport_Inspection1` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_NarrativeReport_NarrativeElements1` FOREIGN KEY (`NarrativeElements_NarrativeElementNo`) REFERENCES `NarrativeElements` (`NarrativeElementNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `UserRole` FOREIGN KEY (`UserRole`) REFERENCES `UserRole` (`UserRole`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
