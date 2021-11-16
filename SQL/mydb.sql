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

DROP DATABASE BIMSdb IF EXISTS;
CREATE DATABASE BIMSdb;
USE BIMSdb;

DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `LogIn` (IN `user_id` VARCHAR(45), IN `user_password` VARCHAR(45), OUT `user_role` VARCHAR(45))  BEGIN

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
('53-014/00-001.60', '53A018', 'McClung Bridge', 'Reedy Creek', NULL, 'Elizabeth', 083, 53, NULL, NULL),
('54-014/00-013.22', '54A037', 'Fifth Street Bridge', 'Little Kanawha River, CSX', NULL, 'Parkersburg', 107, 54, NULL, NULL),
('32-023/08-001.93', '32A040', 'Indian Creek Bridge', 'Indian Creek', NULL, 'Salt Sulphur Springs', 063, 32, NULL, NULL),
('01-012/03-000.02', '01A022', 'Laurel CK DK GRD 1', 'Laurel Creek', NULL, 'Moatsville', 001, 1, NULL, NULL),
('02-009/00-010.88', '02A025', 'North Martinsburg Interchange', 'I81 North & South Lane', NULL, 'Martinsburg', 003, 2, NULL, NULL),
('03-085/00-007.71', '03A077', 'Pond Fork Bridge No. 1868.1', 'Pond Fork of Little Coal River', NULL, 'Madision', 005, 3, NULL, NULL),
('04-013/02-003.21', '04A015','Hecks Bridge', 'Walnut Fork', NULL, 'Walnut Fork', 007, 4, NULL, NULL),
('05-002/00-010.01', '05A009', 'Cross Creek', 'Cross Creek', NULL, 'Wellsburg', 009, 5, NULL, NULL),
('06-060/00-000.02', '06A098', 'Col Justice M Chambers Bridge', 'Fourpole Creek', NULL, 'Huntington', 011, 6, NULL, NULL),
('07-005/00-016.61', '07A013', 'Henrietta Bridge', 'Laurel Creek', NULL, 'Grantsville', 013, 7, NULL, NULL),
('08-002/00-003.36', '08A008', 'West Porter Creek Bridge', 'Porter Creek', NULL, 'Glen', 015, 8, NULL, NULL),
('09-050/30-013.21', '09A078', 'Lower Buckeye Bridge', 'Buckeye Run', NULL, 'Salem', 017, 9, NULL, NULL),
('10-016/00-002.38', '10A089', 'Dunloup Creek Bridge', 'CR 61/23 Dunloup CR RR', NULL, 'Piney View', 019, 10, NULL, NULL),
('11-001/00-002.38', '11A001', 'Gerstner Bridge', 'Leading Creek', NULL, 'Glenville', 021, 11, NULL, NULL),
('12-005/00-000.13', '12A010', 'Arthur Bridge', 'Lunice Creek', NULL, 'Arthur', 023, 12, NULL, NULL),
('13-014/00-004.18', '13A048', 'Meadow Creek Bridge', 'Meadow Creek', NULL, 'Bluefield', 025, 13, NULL, NULL),
('14-050/00-020.03', '14A051', 'Pleasant Dale Bridge', 'Tearcoat Creek', NULL, 'Pleasantdale', 027, 14, NULL, NULL),
('16-007/00-000.14', '16A018', 'Trumbo Ford Bridge', 'S Fk.S.BR. POT. RIV', NULL, 'Milam', 031, 16, NULL, NULL),
('17-019/33-000.01', '17A093', 'Spelter Bridge', 'West Fork River', NULL, 'Spelter', 033, 17, NULL, NULL),
('18-002/00-002.72', '18A004', 'Millwood Bridge', 'Little Mill Creek', NULL, 'Millwood', 035, 18, NULL, NULL),
('20-021/00-001.39', '20A048', 'Kanawha Twomile Bridge No 1525', 'Kanawha Twomile Creek', NULL, 'Charleston', 039, 20, NULL, NULL),
('21-013/00-004.39', '21A042', 'Berlin Bridge', 'Hackers Creek', NULL, 'Berlin', 041, 21, NULL, NULL),
('23-119/34-000.01', '23A029', 'Prices Bottom Bridge', 'Copperas Mine Fork', NULL, 'Holden', 045, 23, NULL, NULL),
('25-218/00-010.86', '25A1029', 'Basnettville W-Beam', 'Paw Paw Creek', NULL, 'Basnettville', 049, 25, NULL, NULL),
('26-002/00-007.78', '26A011', 'Woodlands Bridge', 'Fish Creek', NULL, 'Captina', 051, 26, NULL, NULL),
('27-062/00-006.58', '27A079', 'Leon Pin & Link Bridge', 'Thirteenmile Creek', NULL, 'Point Pleasant', 053, 27, NULL, NULL),
('24-005/02-004.51', '24A031', 'Avondale Bridge', 'Dry Fork', NULL, 'Avondale', 047, 24, NULL, NULL),
('28-003/00-000.47', '29A006', 'Camp Creek Overpass #2', '1-77 SB', NULL, 'Camp Creek', 055, 28, NULL, NULL),
('29-093/00-003.42', '29A054', 'Claysville Bridge', 'New Creek', NULL, 'Claysville', 057, 29, NULL, NULL),
('30-052/00-022.17', '30A162', 'Williamson 4th AvE Bridge', 'E 4th Ave Hillside', NULL, 'Williamson', 059, 30, NULL, NULL),
('31-071/00-000.91', '31A147', 'Rubble Run I-Beam Bridge', 'Morgan Run', NULL, 'Morgantown', 061, 31, NULL, NULL),
('33-9/00-012.39', '33A018', 'Fishers Ford Bridge', 'Cacapon River', NULL, 'Fisher Ford', 065, 33, NULL, NULL),
('34-039/00-052.51', '34A065', 'Hinkle Bridge', 'North Fork Cherry River', NULL, 'Buckhannon', 067, 34, NULL, NULL),
('35-001/00-002.83', '35A002', 'Mine Bridge', 'Short Creek', NULL, 'Gregsville', 069, 35, NULL, NULL),
('36-033/00-019.21', '36A098', 'Judy Gap Bridge', 'N FK S BR Potomac River', NULL, 'Petersburg', 071, 36, NULL, NULL),
('37-005/00-001.05', '37A012', 'Hebron Bridge', 'McKim Creek', NULL, 'Hebron', 073, 37, NULL, NULL),
('38-028/00-006.71', '38A032', 'Thorny Creek Park Bridge', 'Thorny Creek', NULL, 'Thorny Creek', 075, 38, NULL, NULL),
('39-026/00-009.42', '39A050', 'Douthat Creek Bridge', 'Douthat Creek', NULL, 'Minnehah Springs', 077, 39, NULL, NULL),
('40-034/00-021.21', '40A091', 'Winfield OP Bridge', 'US 35', NULL, 'Winfield', 079, 40, NULL, NULL),
('41-003/00-001.17', '41A018', 'Little Marsh Fork Bridge', 'Little Marsh Fork', NULL, 'Marfork', 081, 41, NULL, NULL),
('42-021/00-001.50', '42A035', 'East Dailey Bridge', 'Tygart Valley River Diversion Dam', NULL, 'Valley Bend', 083, 42, NULL, NULL),
('43-007/12-000.81', '43A020', 'Slab Creek Bridge', 'Slab Creek', NULL, 'Slab Fork', 085, 43, NULL, NULL),
('44-003/00-002.58', '44A005', 'Bay Bridge', 'Statts Run', NULL, 'Stratts', 087, 44, NULL, NULL),
('45-033/00-002.41', '45A054', 'Bradshaw Creek Bridge', 'Bradshaw Creek', NULL, 'Bradshaw', 089, 45, NULL, NULL),
('47-072/00-008.26', '47A048', 'Clover Run Bridge', 'Clover Creek', NULL, 'Union', 093, 47, NULL, NULL),
('48-018/00-010.11', '48A032', 'Centerville Bridge', 'Middle Island Creek', NULL, 'Centerville', 095, 48, NULL, NULL),
('49-020/00-016.02', '49A015', 'French Creek West Bridge', 'French Creek', NULL, 'Buckhannon', 097, 49, NULL, NULL),
('50-052/00-014.56', '50A002', 'Gragston Creek Beam Span', 'Gragston Creek', NULL, 'Prichard', 099, 50, NULL, NULL),
('51-005/01-001.98', '51A008', 'Gaurdian Bridge', 'Right Fork Holly River', NULL, 'Clarksburg', 101, 51, NULL, NULL),
('52-007/00-000.07', '52A008', 'Richard Snyder Memorial Bridge', 'WV Rt 2', NULL, 'New Martinsville', 103, 52, NULL, NULL);

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
(1, '54-014/00-013.22', 1, '2021-04-00 00:00:01', '2021-04-30 23:59:59', '2021-04-21 21:48:17', 'high', 'failing superstructure', 1,  2, 3, 1),
(2, '53-014/00-001.60', 1, '2021-05-00 00:00:01', '2021-05-31 23:59:59', '2021-05-20 21:49:52', 'high', 'failing superstructure', 1, 2, 3, 1),
(3, '53-014/00-001.60', 1, '2021-04-00 00:00:01', '2021-04-30 23:59:59', '2020-04-02 01:21:12', 'middle', 'multiple damaged support beams', 5, 2, 3, 1),
(4, '53-014/00-001.60', 1, '2021-10-00 00:00:01', '2021-10-31 23:59:59', '2021-10-06 01:56:27', 'low', 'superficial damage', 9, 1, 3, 2),
(5, '54-014/00-013.22', 1, '2021-10-00 00:00:01', '2021-10-31 23:59:59', '2021-10-05 07:58:59', 'low', 'superficial damage', 8, 2, 3, 1),
(6, '54-014/00-013.22', 1, '2021-10-00 00:00:01', '2021-10-31 23:59:59', '2021-10-01 08:00:20', 'middle', 'multiple damaged support beams', 5, 2, 3, 1),
(7, '54-014/00-013.22', 1, '2021-09-00 00:00:01', '2021-09-30 23:59:59', '2021-09-06 09:31:01', 'low', 'superficial damage', 9, 1, 3, 2),
(8, '53-014/00-001.60', 1, '2021-08-00 00:00:01', '2021-08-30 23:59:59', '2021-08-02 09:40:20', 'middle', 'support beam damage', 6, 2, 3, 1),
(9, '53-014/00-001.60', 1, '2021-10-00 00:00:01', '2021-10-31 23:59:59', '2021-10-04 09:40:20', 'low', 'superficial damage', 8, 2, 3, 1),
(10, '53-014/00-001.60', 1, '2021-07-00 00:00:01', '2021-07-31 23:59:59', '2021-07-13 09:40:20', 'middle', 'support beam damage', 7, 2, 3, 1),
(11, '32-023/08-001.93', 1, '2021-10-00 00:00:01', '2021-10-31 23:59:59', '2021-10-15 03:14:55', 'low', 'superficial damage', 9, 2, 3, 1),
(12, '32-023/08-001.93', 1, '2021-10-00 00:00:01', '2021-10-31 23:59:59', '2021-10-01 03:14:55', 'low', 'superficial damage', 9, 2, 3, 1),
(13, '32-023/08-001.93', 1, '2021-10-00 00:00:01', '2021-10-31 23:59:59', '2021-10-06 03:14:55', 'middle', 'support beam damage', 7, 2, 3, 1),
(14, '32-023/08-001.93', 1, '2021-09-00 00:00:01', '2021-09-30 23:59:59', '2021-09-20 03:14:55', 'middle', 'support beam damage', 7, 2, 3, 1),
(15, '32-023/08-001.93', 1, '2021-08-00 00:00:01', '2021-08-30 23:59:59', '2021-08-10 03:14:55', 'middle', 'support beam damage', 7, 2, 3, 1),
(16, '32-023/08-001.93', 1, '2021-05-00 00:00:01', '2021-05-31 23:59:59', '2021-05-04 03:14:55', 'middle', 'support beam damage', 7, 2, 3, 1),
(17, '54-014/00-013.22', 1, '2021-04-00 00:00:01', '2021-01-31 23:59:59', '2021-08-09 07:58:59', 'high', 'multiple damaged support beams', 4, 2, 3, 1),
(18, '54-014/00-013.22', 1, '2021-04-00 00:00:01', '2021-01-31 23:59:59', '2020-10-14 21:48:17', 'middle', 'support beam damage', 6, 3, 2, 1),
(19, '01-012/03-000.02', 1, '2001-01-01 00:00:01', '2001-01-31 23:59:59', '2001-01-02 09:18:00', 'high', 'failing superstructure', 2, 1, 1, 1),
(20, '02-009/00-010.88', 1, '2002-01-01 00:00:01', '2002-01-31 23:59:59', '2002-01-02 11:06:07', 'high', 'multiple damaged support beams', 4, 2, 2, 1),
(21, '03-085/00-007.71', 1, '2003-01-01 00:00:01', '2003-01-31 23:59:59', '2003-01-04 10:37:29', 'middle', 'support beam damage', 6, 2, 1, 1),
(22, '04-013/02-003.21', 1, '2004-01-01 00:00:01', '2004-01-31 23:59:59', '2004-01-10 12:28:45', 'middle', 'truss damage', 7, 1, 2, 1),
(23, '05-002/00-010.01', 1, '2005-01-01 00:00:01', '2005-01-31 23:59:59', '2005-01-09 13:40:34', 'low', 'superficial damage', 9, 2, 1, 1),
(24, '06-060/00-000.02', 1, '2006-01-01 00:00:01', '2006-01-31 23:59:59', '2006-01-05 15:29:24', 'middle', 'truss damage', 7, 3, 2, 1),
(25, '07-005/00-016.61', 1, '2007-01-01 00:00:01', '2007-01-31 23:59:59', '2007-01-01 08:25:17', 'high', 'failing superstructure', 1, 3, 1, 1),
(26, '08-002/00-003.36', 1, '2008-01-01 00:00:01', '2008-01-31 23:59:59', '2008-01-14 10:27:15', 'high', 'multiple damaged support beams', 3, 2, 2, 1),
(27, '09-050/30-013.21', 1, '2009-01-01 00:00:01', '2009-01-31 23:59:59', '2009-01-17 07:26:14', 'middle', 'truss damage', 7, 2, 1, 1),
(28, '10-016/00-002.38', 1, '2010-01-01 00:00:01', '2010-01-31 23:59:59', '2010-01-09 16:29:09', 'low', 'superficial damage', 9, 3, 2, 1),
(29, '11-001/00-002.38', 1, '2001-01-01 00:00:01', '2001-01-31 23:59:59', '2001-01-12 20:09:03', 'low', 'no issue', 10, 1, 1, 1),
(30, '12-005/00-000.13', 1, '2002-01-01 00:00:01', '2002-01-31 23:59:59', '2002-01-08 12:11:47', 'high', 'multiple damaged support beams', 4, 1, 1, 1),
(31, '13-014/00-004.18', 1, '2003-01-01 00:00:01', '2003-01-31 23:59:59', '2003-01-03 11:14:59', 'middle', 'support beam damage', 6, 1, 2, 1),
(32, '14-050/00-020.03', 1, '2004-01-01 00:00:01', '2004-01-31 23:59:59', '2004-01-17 14:56:53', 'low', 'superficial damage', 8, 3, 3, 1),
(33, '16-007/00-000.14', 1, '2005-01-01 00:00:01', '2005-01-31 23:59:59', '2005-01-21 08:35:23', 'high', 'multiple damaged support beams', 3, 2, 3, 1),
(34, '17-019/33-000.01', 1, '2006-01-01 00:00:01', '2006-01-31 23:59:59', '2006-01-19 14:47:42', 'low', 'superficial damage', 9, 3, 3, 1),
(35, '18-002/00-002.72', 1, '2007-01-01 00:00:01', '2007-01-31 23:59:59', '2007-01-23 21:58:15', 'low', 'truss damage', 8, 1, 1, 1),
(36, '20-021/00-001.39', 1, '2008-01-01 00:00:01', '2008-01-31 23:59:59', '2008-01-22 09:49:55', 'high', 'failing superstructure', 2, 1, 2, 1),
(37, '21-013/00-004.39', 1, '2009-01-01 00:00:01', '2009-01-31 23:59:59', '2009-01-24 07:37:34', 'high', 'multiple damaged support beams', 4, 3, 1, 1),
(38, '23-119/34-000.01', 1, '2010-01-01 00:00:01', '2010-01-31 23:59:59', '2010-01-25 13:23:24', 'low', 'no issue', 10, 2, 2, 1),
(39, '25-218/00-010.86', 1, '2001-01-01 00:00:01', '2001-02-30 23:59:59', '2001-02-16 10:22:14', 'high', 'failing superstructure', 2, 2, 2, 1),
(40, '26-002/00-007.78', 1, '2002-01-01 00:00:01', '2002-02-30 23:59:59', '2002-02-18 12:26:56', 'high', 'failing superstructure', 1, 2, 3, 1),
(41, '27-062/00-006.58', 1, '2003-01-01 00:00:01', '2003-02-30 23:59:59', '2003-02-13 13:25:49', 'high', 'multiple damaged support beams', 4, 3, 3, 1),
(42, '24-005/02-004.51', 1, '2004-01-01 00:00:01', '2004-02-30 23:59:59', '2004-02-15 11:12:33', 'middle', 'truss damage', 7, 2, 3, 1),
(43, '28-003/00-000.47', 1, '2005-01-01 00:00:01', '2005-02-30 23:59:59', '2005-02-03 10:13:35', 'low', 'superficial damage', 8, 1, 2, 1),
(44, '29-093/00-003.42', 1, '2006-01-01 00:00:01', '2006-02-30 23:59:59', '2006-02-06 08:27:48', 'middle', 'truss damage', 6, 1, 3, 1),
(45, '30-052/00-022.17', 1, '2007-01-01 00:00:01', '2007-02-30 23:59:59', '2007-02-22 10:49:25', 'low', 'no issue', 10, 1, 3, 1),
(46, '31-071/00-000.91', 1, '2008-01-01 00:00:01', '2008-02-30 23:59:59', '2008-02-29 09:52:39', 'low', 'superficial damage', 8, 2, 3, 1),
(47, '33-9/00-012.39', 1, '2009-01-01 00:00:01', '2009-02-30 23:59:59', '2009-02-12 14:26:41', 'low', 'superficial damage', 9, 2, 2, 1),
(48, '34-039/00-052.51', 1, '2010-01-01 00:00:01', '2010-02-30 23:59:59', '2010-02-09 15:37:21', 'high', 'multiple damaged support beams', 4, 3, 1, 1),
(49, '35-001/00-002.83', 1, '2001-01-01 00:00:01', '2001-02-30 23:59:59', '2001-02-07 13:11:54', 'middle', 'support beam damage', 5, 2, 1, 1),
(50, '36-033/00-019.21', 1, '2002-01-01 00:00:01', '2002-02-30 23:59:59', '2002-02-06 07:45:36', 'middle', 'truss damage', 6, 3, 1, 1),
(51, '37-005/00-001.05', 1, '2003-01-01 00:00:01', '2003-02-30 23:59:59', '2003-02-03 10:34:57', 'high', 'failing superstructure', 3, 2, 1, 1),
(52, '38-028/00-006.71', 1, '2004-01-01 00:00:01', '2004-02-30 23:59:59', '2004-02-11 09:12:18', 'low', 'superficial damage', 8, 3, 2, 1),
(53, '39-026/00-009.42', 1, '2005-01-01 00:00:01', '2005-02-30 23:59:59', '2005-02-23 12:23:10', 'low', 'superficial damage', 9, 1, 3, 1),
(54, '40-034/00-021.21', 1, '2006-01-01 00:00:01', '2006-02-30 23:59:59', '2006-02-27 08:21:22', 'middle', 'truss damage', 7, 1, 1, 1),
(55, '41-003/00-001.17', 1, '2007-01-01 00:00:01', '2007-03-31 23:59:59', '2007-03-24 15:15:37', 'high', 'multiple damaged support beams', 3, 1, 1, 1),
(56, '42-021/00-001.50', 1, '2008-01-01 00:00:01', '2008-03-31 23:59:59', '2008-03-23 17:17:18', 'middle', 'multiple damaged support beams', 5, 1, 1, 1),
(57, '43-007/12-000.81', 1, '2009-01-01 00:00:01', '2009-03-31 23:59:59', '2009-03-14 16:18:40', 'middle', 'support beam damage', 6, 3, 2, 1),
(58, '44-003/00-002.58', 1, '2010-01-01 00:00:01', '2010-03-31 23:59:59', '2010-03-17 14:56:52', 'high', 'failing superstructure', 2, 3, 2, 1),
(59, '45-033/00-002.41', 1, '2001-01-01 00:00:01', '2001-03-31 23:59:59', '2001-03-05 20:42:39', 'high', 'failing superstructure', 1, 1, 2, 1),
(60, '47-072/00-008.26', 1, '2002-01-01 00:00:01', '2002-03-31 23:59:59', '2002-03-08 19:40:02', 'high', 'multiple damaged support beams', 3, 1, 3, 1),
(61, '48-018/00-010.11', 1, '2003-01-01 00:00:01', '2003-03-31 23:59:59', '2003-03-15 17:55:07', 'low', 'superficial damage', 8, 1, 1, 1),
(62, '49-020/00-016.02', 1, '2004-01-01 00:00:01', '2004-03-31 23:59:59', '2004-03-17 18:51:11', 'low', 'no issue', 10, 1, 1, 1),
(63, '50-052/00-014.56', 1, '2005-01-01 00:00:01', '2005-03-31 23:59:59', '2005-03-20 09:03:08', 'middle', 'truss damage', 7, 1, 2, 1),
(64, '51-005/01-001.98', 1, '2006-01-01 00:00:01', '2006-03-31 23:59:59', '2006-03-19 08:01:19', 'low', 'superficial damage', 9, 1, 3, 1),
(65, '52-007/00-000.07', 1, '2007-01-01 00:00:01', '2007-03-31 23:59:59', '2007-03-23 11:04:28', 'high', 'multiple damaged support beams', 4, 1, 3, 2),

(66, '01-012/03-000.02', 1, '2011-01-01 00:00:01', '2011-01-31 23:59:59', '2011-01-02 09:20:00', 'middle', 'support beam damage', 6, 1, 1, 1),
(67, '02-009/00-010.88', 1, '2012-01-01 00:00:01', '2012-01-31 23:59:59', '2012-01-02 11:20:07', 'low', 'superficial damage', 8, 2, 2, 1),
(68, '03-085/00-007.71', 1, '2013-01-01 00:00:01', '2013-01-31 23:59:59', '2013-01-04 10:20:29', 'middle', 'support beam damage', 5, 2, 1, 1),
(69, '04-013/02-003.21', 1, '2014-01-01 00:00:01', '2014-01-31 23:59:59', '2014-01-10 12:20:45', 'low', 'superficial damage', 9, 1, 2, 1),
(70, '05-002/00-010.01', 1, '2015-01-01 00:00:01', '2015-01-31 23:59:59', '2015-01-09 13:20:34', 'middle', 'support beam damage', 7, 2, 1, 1),
(71, '06-060/00-000.02', 1, '2016-01-01 00:00:01', '2016-01-31 23:59:59', '2016-01-05 15:20:24', 'high', 'multiple damaged support beams', 3, 3, 2, 1),
(72, '07-005/00-016.61', 1, '2017-01-01 00:00:01', '2017-01-31 23:59:59', '2017-01-01 08:25:17', 'middle', 'support beam damage', 5, 3, 1, 1),
(73, '08-002/00-003.36', 1, '2018-01-01 00:00:01', '2018-01-31 23:59:59', '2018-01-14 10:27:15', 'low', 'superficial damage', 8, 2, 2, 1),
(74, '09-050/30-013.21', 1, '2019-01-01 00:00:01', '2019-01-31 23:59:59', '2019-01-17 07:26:14', 'low', 'no issue', 10, 2, 1, 1),
(75, '10-016/00-002.38', 1, '2020-01-01 00:00:01', '2020-01-31 23:59:59', '2020-01-09 16:29:09', 'high', 'failing superstructure', 2, 3, 2, 1),
(76, '11-001/00-002.38', 1, '2011-01-01 00:00:01', '2011-01-31 23:59:59', '2011-01-12 20:09:03', 'middle', 'support beam damage', 7, 1, 1, 1),
(77, '12-005/00-000.13', 1, '2012-01-01 00:00:01', '2012-01-31 23:59:59', '2012-01-08 12:11:47', 'low', 'superficial damage', 8, 1, 1, 1),
(78, '13-014/00-004.18', 1, '2013-01-01 00:00:01', '2013-01-31 23:59:59', '2013-01-03 11:14:59', 'low', 'superficial damage', 9, 1, 2, 1),
(79, '14-050/00-020.03', 1, '2014-01-01 00:00:01', '2014-01-31 23:59:59', '2014-01-17 14:56:53', 'middle', 'support beam damage', 7, 3, 3, 1),
(80, '16-007/00-000.14', 1, '2015-01-01 00:00:01', '2015-01-31 23:59:59', '2015-01-21 08:35:23', 'middle', 'support beam damage', 5, 2, 3, 1),
(81, '17-019/33-000.01', 1, '2016-01-01 00:00:01', '2016-01-31 23:59:59', '2016-01-19 14:47:42', 'middle', 'support beam damage', 6, 3, 3, 1),
(82, '18-002/00-002.72', 1, '2017-01-01 00:00:01', '2017-01-31 23:59:59', '2017-01-23 21:58:15', 'middle', 'support beam damage', 6, 1, 1, 1),
(83, '20-021/00-001.39', 1, '2018-01-01 00:00:01', '2018-01-31 23:59:59', '2018-01-22 09:49:55', 'middle', 'support beam damage', 7, 1, 2, 1),
(84, '21-013/00-004.39', 1, '2019-01-01 00:00:01', '2019-01-31 23:59:59', '2019-01-24 07:37:34', 'low', 'superficial damage', 8, 3, 1, 1),
(85, '23-119/34-000.01', 1, '2020-01-01 00:00:01', '2020-01-31 23:59:59', '2020-01-25 13:23:24', 'low', 'no issue', 9, 2, 2, 1),
(86, '25-218/00-010.86', 1, '2011-01-01 00:00:01', '2011-02-30 23:59:59', '2011-02-16 10:22:14', 'high', 'multiple damaged support beams', 3, 2, 2, 1),
(87, '26-002/00-007.78', 1, '2012-01-01 00:00:01', '2012-02-30 23:59:59', '2012-02-18 12:26:56', 'high', 'multiple damaged support beams', 4, 2, 3, 1),
(88, '27-062/00-006.58', 1, '2013-01-01 00:00:01', '2013-02-30 23:59:59', '2013-02-13 13:25:49', 'middle', 'support beam damage', 7, 3, 3, 1),
(89, '24-005/02-004.51', 1, '2014-01-01 00:00:01', '2014-02-30 23:59:59', '2014-02-15 11:12:33', 'middle', 'support beam damage', 6, 2, 3, 1),
(90, '28-003/00-000.47', 1, '2015-01-01 00:00:01', '2015-02-30 23:59:59', '2015-02-03 10:13:35', 'low', 'superficial damage', 9, 1, 2, 1),
(91, '29-093/00-003.42', 1, '2016-01-01 00:00:01', '2016-02-30 23:59:59', '2016-02-06 08:27:48', 'low', 'superficial damage', 8, 1, 3, 1),
(92, '30-052/00-022.17', 1, '2017-01-01 00:00:01', '2017-02-30 23:59:59', '2017-02-22 10:49:25', 'low', 'superficial damage', 8, 1, 3, 1),
(93, '31-071/00-000.91', 1, '2018-01-01 00:00:01', '2018-02-30 23:59:59', '2018-02-29 09:52:39', 'low', 'superficial damage', 9, 2, 3, 1),
(94, '33-9/00-012.39', 1, '2019-01-01 00:00:01', '2019-02-30 23:59:59', '2019-02-12 14:26:41', 'middle', 'support beam damage', 6, 2, 2, 1),
(95, '34-039/00-052.51', 1, '2020-01-01 00:00:01', '2020-02-30 23:59:59', '2020-02-09 15:37:21', 'middle', 'support beam damage', 5, 3, 1, 1),
(96, '35-001/00-002.83', 1, '2011-01-01 00:00:01', '2011-02-30 23:59:59', '2011-02-07 13:11:54', 'middle', 'support beam damage', 7, 2, 1, 1),
(97, '36-033/00-019.21', 1, '2012-01-01 00:00:01', '2012-02-30 23:59:59', '2012-02-06 07:45:36', 'middle', 'superficial damage', 9, 3, 1, 1),
(98, '37-005/00-001.05', 1, '2013-01-01 00:00:01', '2013-02-30 23:59:59', '2013-02-03 10:34:57', 'low', 'superficial damage', 8, 2, 1, 1),
(99, '38-028/00-006.71', 1, '2014-01-01 00:00:01', '2014-02-30 23:59:59', '2014-02-11 09:12:18', 'low', 'superficial damage', 9, 3, 2, 1),
(100, '39-026/00-009.42', 1, '2015-01-01 00:00:01', '2015-02-30 23:59:59', '2015-02-23 12:23:10', 'middle', 'support beam damage', 7, 1, 3, 1),
(101, '40-034/00-021.21', 1, '2016-01-01 00:00:01', '2016-02-30 23:59:59', '2016-02-27 08:21:22', 'low', 'no issue', 10, 1, 1, 1),
(102, '41-003/00-001.17', 1, '2017-01-01 00:00:01', '2017-03-31 23:59:59', '2017-03-24 15:15:37', 'middle', 'support beam damage', 5, 1, 1, 1),
(103, '42-021/00-001.50', 1, '2018-01-01 00:00:01', '2018-03-31 23:59:59', '2018-03-23 17:17:18', 'high', 'multiple damaged support beams', 4, 1, 1, 1),
(104, '43-007/12-000.81', 1, '2019-01-01 00:00:01', '2019-03-31 23:59:59', '2019-03-14 16:18:40', 'low', 'superficial damage', 8, 3, 2, 1),
(105, '44-003/00-002.58', 1, '2020-01-01 00:00:01', '2020-03-31 23:59:59', '2020-03-17 14:56:52', 'middle', 'support beam damage', 7, 3, 2, 1),
(106, '45-033/00-002.41', 1, '2011-01-01 00:00:01', '2011-03-31 23:59:59', '2011-03-05 20:42:39', 'middle', 'support beam damage', 6, 1, 2, 1),
(107, '47-072/00-008.26', 1, '2012-01-01 00:00:01', '2012-03-31 23:59:59', '2012-03-08 19:40:02', 'high', 'multiple damaged support beams', 4, 1, 3, 1),
(108, '48-018/00-010.11', 1, '2013-01-01 00:00:01', '2013-03-31 23:59:59', '2013-03-15 17:55:07', 'middle', 'support beam damage', 6, 1, 1, 1),
(109, '49-020/00-016.02', 1, '2014-01-01 00:00:01', '2014-03-31 23:59:59', '2014-03-17 18:51:11', 'low', 'superficial damage', 9, 1, 1, 1),
(110, '50-052/00-014.56', 1, '2015-01-01 00:00:01', '2015-03-31 23:59:59', '2015-03-20 09:03:08', 'low', 'superficial damage', 8, 1, 2, 1),
(111, '51-005/01-001.98', 1, '2016-01-01 00:00:01', '2016-03-31 23:59:59', '2016-03-19 08:01:19', 'middle', 'support beam damage', 6, 1, 3, 1),
(112, '52-007/00-000.07', 1, '2017-01-01 00:00:01', '2017-03-31 23:59:59', '2017-03-23 11:04:28', 'middle', 'support beam damage', 7, 1, 3, 2),

(113, '01-012/03-000.02', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-02 09:50:00', 'middle', 'multiple damaged support beams', 5, 1, 1, 1),
(114, '02-009/00-010.88', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-02 11:25:07', 'middle', 'multiple damaged support beams', 6, 2, 2, 1),
(115, '03-085/00-007.71', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-04 10:37:29', 'middle', 'support beam damage', 7, 2, 1, 1),
(116, '04-013/02-003.21', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-10 12:46:45', 'low', 'superficial damage', 8, 1, 2, 1),
(117, '05-002/00-010.01', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-09 13:14:34', 'low', 'no issue', 10, 2, 1, 1),
(118, '06-060/00-000.02', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-05 15:23:24', 'middle', 'truss damage', 6, 3, 2, 1),
(119, '07-005/00-016.61', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-01 08:25:17', 'high', 'multiple damaged support beams', 3, 3, 1, 1),
(120, '08-002/00-003.36', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-14 10:27:15', 'middle', 'support beam damage', 5, 2, 2, 1),
(121, '09-050/30-013.21', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-17 07:26:14', 'low', 'superficial damage', 9, 2, 1, 1),
(122, '10-016/00-002.38', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-09 16:29:09', 'middle', 'truss damage', 7, 3, 2, 1),
(123, '11-001/00-002.38', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-12 20:09:03', 'low', 'superficial damage', 8, 1, 1, 1),
(124, '12-005/00-000.13', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-08 12:11:47', 'middle', 'support beam damage', 6, 1, 1, 1),
(125, '13-014/00-004.18', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-03 11:14:59', 'middle', 'support beam damage', 7, 1, 2, 1),
(126, '14-050/00-020.03', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-17 14:56:53', 'middle', 'superficial damage', 6, 3, 3, 1),
(127, '16-007/00-000.14', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-21 08:35:23', 'middle', 'support beam damage', 6, 2, 3, 1),
(128, '17-019/33-000.01', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-19 14:47:42', 'middle', 'truss damage', 7, 3, 3, 1),
(129, '18-002/00-002.72', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-23 21:58:15', 'low', 'superficial damage', 9, 1, 1, 1),
(130, '20-021/00-001.39', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-22 09:49:55', 'high', 'multiple damaged support beams', 3, 1, 2, 1),
(131, '21-013/00-004.39', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-24 07:37:34', 'middle', 'truss damage', 7, 3, 1, 1),
(132, '23-119/34-000.01', 1, '2021-01-01 00:00:01', '2021-01-31 23:59:59', '2021-01-25 13:23:24', 'low', 'no issue', 10, 2, 2, 1),
(133, '25-218/00-010.86', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-16 10:22:14', 'middle', 'support beam damage', 6, 2, 2, 1),
(134, '26-002/00-007.78', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-18 12:26:56', 'high', 'multiple damaged support beams', 3, 2, 3, 1),
(135, '27-062/00-006.58', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-13 13:25:49', 'middle', 'support beam damage', 5, 3, 3, 1),
(136, '24-005/02-004.51', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-15 11:12:33', 'low', 'superficial damage', 9, 2, 3, 1),
(137, '28-003/00-000.47', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-03 10:13:35', 'low', 'truss damage', 8, 1, 2, 1),
(138, '29-093/00-003.42', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-06 08:27:48', 'middle', 'support beam damage', 7, 1, 3, 1),
(139, '30-052/00-022.17', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-22 10:49:25', 'low', 'superficial damage', 9, 1, 3, 1),
(140, '31-071/00-000.91', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-29 09:52:39', 'middle', 'support beam damage', 6, 2, 3, 1),
(141, '33-9/00-012.39', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-12 14:26:41', 'low', 'truss damage', 8, 2, 2, 1),
(142, '34-039/00-052.51', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-09 15:37:21', 'middle', 'support beam damage', 6, 3, 1, 1),
(143, '35-001/00-002.83', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-07 13:11:54', 'high', 'multiple damaged support beams', 3, 2, 1, 1),
(144, '36-033/00-019.21', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-06 07:45:36', 'low', 'truss damage', 8, 3, 1, 1),
(145, '37-005/00-001.05', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-03 10:34:57', 'low', 'superficial damage', 9, 2, 1, 1),
(146, '38-028/00-006.71', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-11 09:12:18', 'middle', 'truss damage', 7, 3, 2, 1),
(147, '39-026/00-009.42', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-23 12:23:10', 'low', 'no issue', 10, 1, 3, 1),
(148, '40-034/00-021.21', 1, '2021-01-01 00:00:01', '2021-02-30 23:59:59', '2021-02-27 08:21:22', 'middle', 'support beam damage', 6, 1, 1, 1),
(149, '41-003/00-001.17', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-24 15:15:37', 'low', 'truss damage', 8, 1, 1, 1),
(150, '42-021/00-001.50', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-23 17:17:18', 'high', 'multiple damaged support beams', 3, 1, 1, 1),
(151, '43-007/12-000.81', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-14 16:18:40', 'low', 'support beam damage', 8, 3, 2, 1),
(152, '44-003/00-002.58', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-17 14:56:52', 'middle', 'support beam damage', 5, 3, 2, 1),
(153, '45-033/00-002.41', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-05 20:42:39', 'low', 'failing superstructure', 9, 1, 2, 1),
(154, '47-072/00-008.26', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-08 19:40:02', 'middle', 'support beam damage', 6, 1, 3, 1),
(155, '48-018/00-010.11', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-15 17:55:07', 'middle', 'support beam damage', 5, 1, 1, 1),
(156, '49-020/00-016.02', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-17 18:51:11', 'low', 'superficial damage', 9, 1, 1, 1),
(157, '50-052/00-014.56', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-20 09:03:08', 'low', 'truss damage', 8, 1, 2, 1),
(158, '51-005/01-001.98', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-19 08:01:19', 'low', 'no issue', 10, 1, 3, 1),
(159, '52-007/00-000.07', 1, '2021-01-01 00:00:01', '2021-03-31 23:59:59', '2021-03-23 11:04:28', 'middle', 'truss damage', 7, 1, 3, 2);

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
