-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 19, 2021 at 07:11 AM
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
-- Database: `BIMSdb`
--

CREATE DATABASE BIMSdb;
USE BIMSdb;

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `createImageSet` (IN `inspection_id` INT)  BEGIN
	INSERT INTO DroneImageSet (Inspections_InspectionID,DateTime) VALUES (inspection_id,now()); 
	SELECT LAST_INSERT_ID() AS newestImageSetId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEarliestYear` (IN `bridge1` VARCHAR(45), IN `bridge2` VARCHAR(45), IN `bridge3` VARCHAR(45))  BEGIN
SELECT MIN(YEAR(DATE(FinishedDate))) as year
FROM Inspections
WHERE Bridges_BridgeNo IN (
	SELECT BridgeNo FROM Bridges WHERE BridgeName=bridge1
    OR BridgeName=bridge2
    OR BridgeName=bridge3
);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertImageData`(IN `image_set_id` int, IN `filepath` VARCHAR(200), IN `image_name` VARCHAR(200), IN `comment` LONGTEXT, IN `x` double, IN `y` double, IN `z` double) BEGIN
	INSERT INTO DroneImages 
  (DroneImageSet_ImageSetID, Picture, Name, Comments, ElementX, ElementY, ElementZ) 
  VALUES 
  (image_set_id, filepath, image_name, comment, x, y, z);
END$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `selectBridgeInspectionData_BetweenYears` (IN `bridge_name` VARCHAR(45), IN `begin_year` INT, IN `end_year` INT)  BEGIN
	SELECT i.FinishedDate, i.Bridges_BridgeNo, b.BridgeName, t.InspectionTypeName ,u.FirstName AS inspector_first, 
	u.LastName AS inspector_last, u2.FirstName AS evaluator_first, u2.LastName AS evaluator_last, i.OverallRating
	FROM Inspections i 
	JOIN Bridges b ON i.Bridges_BridgeNo = b.BridgeNo
	JOIN InspectionTypeCode t ON i.InspectionTypeNo = t.InspectionTypeNo
	JOIN Users u ON i.InspectorID = u.UserNo
	JOIN Users u2 ON i.EvaluatorID = u2.UserNo
	WHERE Bridges_BridgeNo IN (
		SELECT BridgeNo FROM Bridges WHERE BridgeName=bridge_name
	) 
    AND YEAR(DATE(FinishedDate)) >= begin_year
    AND YEAR(DATE(FinishedDate)) <= end_year
	ORDER BY i.FinishedDate;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `selectInspectionData_ById` (IN `inspection_id` INT)  BEGIN
    SELECT i.InspectionID, i.Bridges_BridgeNo, b.BridgeName, i.AssignedDate, i.DueDate, i.FinishedDate, 
    t.InspectionTypeName,  i.OverallRating, u.FirstName AS inspector_first, 
    u.LastName AS inspector_last, u2.FirstName AS evaluator_first, u2.LastName AS evaluator_last
    FROM Inspections i 
    JOIN Bridges b ON i.Bridges_BridgeNo = b.BridgeNo
    JOIN InspectionTypeCode t ON i.InspectionTypeNo = t.InspectionTypeNo
    JOIN Users u ON i.InspectorID = u.UserNo
    JOIN Users u2 ON i.EvaluatorID = u2.UserNo
    WHERE InspectionID = inspection_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `selectNewestInspectionData_ByYear` (IN `inspec_year` INT)  BEGIN
	SELECT *
	FROM (
		SELECT i.FinishedDate, i.DueDate, i.Bridges_BridgeNo, b.BridgeName, t.InspectionTypeName ,u.FirstName AS inspector_first, 
		u.LastName AS inspector_last, u2.FirstName AS evaluator_first, u2.LastName AS evaluator_last, i.OverallRating
		FROM Inspections i 
		JOIN Bridges b ON i.Bridges_BridgeNo = b.BridgeNo
		JOIN InspectionTypeCode t ON i.InspectionTypeNo = t.InspectionTypeNo
		JOIN Users u ON i.InspectorID = u.UserNo
		JOIN Users u2 ON i.EvaluatorID = u2.UserNo
		WHERE YEAR(DATE(FinishedDate)) = inspec_year
	) AS inspection_data
	WHERE FinishedDate IN (
		SELECT MAX(FinishedDate)
		FROM Inspections 
		WHERE year(FinishedDate)= inspec_year 
		GROUP BY Bridges_BridgeNo
	);
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
  `BridgeElements_ElementID` int(11) DEFAULT NULL,
  `Inspections_InspectionID` int(11) NOT NULL,
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
  `BridgeModels_BridgeModelNo` int(11) DEFAULT NULL,
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
  `BridgeModelNo` int(11) NOT NULL,
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
  `BridgeModels_BridgeModelNo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Bridge Identification';

--
-- Dumping data for table `Bridges`
--

INSERT INTO `Bridges` (`BridgeNo`, `BARsNo`, `BridgeName`, `FeatureIntersected`, `FacilityCarried`, `Location`, `District`, `County_CountyNo`, `BridgePicture`, `BridgeModels_BridgeModelNo`) VALUES
('01-012/03-000.02', '01A022', 'Laurel CK DK GRD 1', 'Laurel Creek', NULL, 'Moatsville', 1, 1, NULL, NULL),
('02-009/00-010.88', '02A025', 'North Martinsburg Interchange', 'I81 North & South Lane', NULL, 'Martinsburg', 3, 2, NULL, NULL),
('03-085/00-007.71', '03A077', 'Pond Fork Bridge No. 1868.1', 'Pond Fork of Little Coal River', NULL, 'Madision', 5, 3, NULL, NULL),
('04-013/02-003.21', '04A015', 'Hecks Bridge', 'Walnut Fork', NULL, 'Walnut Fork', 7, 4, NULL, NULL),
('05-002/00-010.01', '05A009', 'Cross Creek', 'Cross Creek', NULL, 'Wellsburg', 9, 5, NULL, NULL),
('06-060/00-000.02', '06A098', 'Col Justice M Chambers Bridge', 'Fourpole Creek', NULL, 'Huntington', 11, 6, NULL, NULL),
('07-005/00-016.61', '07A013', 'Henrietta Bridge', 'Laurel Creek', NULL, 'Grantsville', 13, 7, NULL, NULL),
('08-002/00-003.36', '08A008', 'West Porter Creek Bridge', 'Porter Creek', NULL, 'Glen', 15, 8, NULL, NULL),
('09-050/30-013.21', '09A078', 'Lower Buckeye Bridge', 'Buckeye Run', NULL, 'Salem', 17, 9, NULL, NULL),
('10-016/00-002.38', '10A089', 'Dunloup Creek Bridge', 'CR 61/23 Dunloup CR RR', NULL, 'Piney View', 19, 10, NULL, NULL),
('11-001/00-002.38', '11A001', 'Gerstner Bridge', 'Leading Creek', NULL, 'Glenville', 21, 11, NULL, NULL),
('12-005/00-000.13', '12A010', 'Arthur Bridge', 'Lunice Creek', NULL, 'Arthur', 23, 12, NULL, NULL),
('13-014/00-004.18', '13A048', 'Meadow Creek Bridge', 'Meadow Creek', NULL, 'Bluefield', 25, 13, NULL, NULL),
('14-050/00-020.03', '14A051', 'Pleasant Dale Bridge', 'Tearcoat Creek', NULL, 'Pleasantdale', 27, 14, NULL, NULL),
('16-007/00-000.14', '16A018', 'Trumbo Ford Bridge', 'S Fk.S.BR. POT. RIV', NULL, 'Milam', 31, 16, NULL, NULL),
('17-019/33-000.01', '17A093', 'Spelter Bridge', 'West Fork River', NULL, 'Spelter', 33, 17, NULL, NULL),
('18-002/00-002.72', '18A004', 'Millwood Bridge', 'Little Mill Creek', NULL, 'Millwood', 35, 18, NULL, NULL),
('20-021/00-001.39', '20A048', 'Kanawha Twomile Bridge No 1525', 'Kanawha Twomile Creek', NULL, 'Charleston', 39, 20, NULL, NULL),
('21-013/00-004.39', '21A042', 'Berlin Bridge', 'Hackers Creek', NULL, 'Berlin', 41, 21, NULL, NULL),
('23-119/34-000.01', '23A029', 'Prices Bottom Bridge', 'Copperas Mine Fork', NULL, 'Holden', 45, 23, NULL, NULL),
('24-005/02-004.51', '24A031', 'Avondale Bridge', 'Dry Fork', NULL, 'Avondale', 47, 24, NULL, NULL),
('25-218/00-010.86', '25A1029', 'Basnettville W-Beam', 'Paw Paw Creek', NULL, 'Basnettville', 49, 25, NULL, NULL),
('26-002/00-007.78', '26A011', 'Woodlands Bridge', 'Fish Creek', NULL, 'Captina', 51, 26, NULL, NULL),
('27-062/00-006.58', '27A079', 'Leon Pin & Link Bridge', 'Thirteenmile Creek', NULL, 'Point Pleasant', 53, 27, NULL, NULL),
('28-003/00-000.47', '29A006', 'Camp Creek Overpass #2', '1-77 SB', NULL, 'Camp Creek', 55, 28, NULL, NULL),
('29-093/00-003.42', '29A054', 'Claysville Bridge', 'New Creek', NULL, 'Claysville', 57, 29, NULL, NULL),
('30-052/00-022.17', '30A162', 'Williamson 4th AvE Bridge', 'E 4th Ave Hillside', NULL, 'Williamson', 59, 30, NULL, NULL),
('31-071/00-000.91', '31A147', 'Rubble Run I-Beam Bridge', 'Morgan Run', NULL, 'Morgantown', 61, 31, NULL, NULL),
('32-023/08-001.93', '32A040', 'Indian Creek Bridge', 'Indian Creek', NULL, 'Salt Sulphur Springs', 63, 32, NULL, NULL),
('33-9/00-012.39', '33A018', 'Fishers Ford Bridge', 'Cacapon River', NULL, 'Fisher Ford', 65, 33, NULL, NULL),
('34-039/00-052.51', '34A065', 'Hinkle Bridge', 'North Fork Cherry River', NULL, 'Buckhannon', 67, 34, NULL, NULL),
('35-001/00-002.83', '35A002', 'Mine Bridge', 'Short Creek', NULL, 'Gregsville', 69, 35, NULL, NULL),
('36-033/00-019.21', '36A098', 'Judy Gap Bridge', 'N FK S BR Potomac River', NULL, 'Petersburg', 71, 36, NULL, NULL),
('37-005/00-001.05', '37A012', 'Hebron Bridge', 'McKim Creek', NULL, 'Hebron', 73, 37, NULL, NULL),
('38-028/00-006.71', '38A032', 'Thorny Creek Park Bridge', 'Thorny Creek', NULL, 'Thorny Creek', 75, 38, NULL, NULL),
('39-026/00-009.42', '39A050', 'Douthat Creek Bridge', 'Douthat Creek', NULL, 'Minnehah Springs', 77, 39, NULL, NULL),
('40-034/00-021.21', '40A091', 'Winfield OP Bridge', 'US 35', NULL, 'Winfield', 79, 40, NULL, NULL),
('41-003/00-001.17', '41A018', 'Little Marsh Fork Bridge', 'Little Marsh Fork', NULL, 'Marfork', 81, 41, NULL, NULL),
('42-021/00-001.50', '42A035', 'East Dailey Bridge', 'Tygart Valley River Diversion Dam', NULL, 'Valley Bend', 83, 42, NULL, NULL),
('43-007/12-000.81', '43A020', 'Slab Creek Bridge', 'Slab Creek', NULL, 'Slab Fork', 85, 43, NULL, NULL),
('44-003/00-002.58', '44A005', 'Bay Bridge', 'Statts Run', NULL, 'Stratts', 87, 44, NULL, NULL),
('45-033/00-002.41', '45A054', 'Bradshaw Creek Bridge', 'Bradshaw Creek', NULL, 'Bradshaw', 89, 45, NULL, NULL),
('47-072/00-008.26', '47A048', 'Clover Run Bridge', 'Clover Creek', NULL, 'Union', 93, 47, NULL, NULL),
('48-018/00-010.11', '48A032', 'Centerville Bridge', 'Middle Island Creek', NULL, 'Centerville', 95, 48, NULL, NULL),
('49-020/00-016.02', '49A015', 'French Creek West Bridge', 'French Creek', NULL, 'Buckhannon', 97, 49, NULL, NULL),
('50-052/00-014.56', '50A002', 'Gragston Creek Beam Span', 'Gragston Creek', NULL, 'Prichard', 99, 50, NULL, NULL),
('51-005/01-001.98', '51A008', 'Gaurdian Bridge', 'Right Fork Holly River', NULL, 'Clarksburg', 101, 51, NULL, NULL),
('52-007/00-000.07', '52A008', 'Richard Snyder Memorial Bridge', 'WV Rt 2', NULL, 'New Martinsville', 103, 52, NULL, NULL),
('53-014/00-001.60', '53A018', 'McClung Bridge', 'Reedy Creek', NULL, 'Elizabeth', 83, 53, NULL, NULL),
('54-014/00-013.22', '54A037', 'Fifth Street Bridge', 'Little Kanawha River, CSX', NULL, 'Parkersburg', 107, 54, NULL, NULL);

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
(1, 'Barbour'),
(2, 'Berkeley'),
(3, 'Boone'),
(4, 'Braxton'),
(5, 'Brooke'),
(6, 'Cabell'),
(7, 'Calhoun'),
(8, 'Clay'),
(9, 'Doddridge'),
(10, 'Fayette'),
(11, 'Gilmer'),
(12, 'Grant'),
(13, 'Greenbrier'),
(14, 'Hampshire'),
(15, 'Hancock'),
(16, 'Hardy'),
(17, 'Harrison'),
(18, 'Jackson'),
(19, 'Jefferson'),
(20, 'Kanawha'),
(21, 'Lewis'),
(22, 'Lincoln'),
(23, 'Logan'),
(24, 'Marion'),
(25, 'Marshall'),
(26, 'Mason'),
(27, 'McDowell'),
(28, 'Mercer'),
(29, 'Mineral'),
(30, 'Mingo'),
(31, 'Monongalia'),
(32, 'Monroe'),
(33, 'Morgan'),
(34, 'Nicholas'),
(35, 'Ohio'),
(36, 'Pendleton'),
(37, 'Pleasants'),
(38, 'Pocahontas'),
(39, 'Preston'),
(40, 'Putnam'),
(41, 'Raleigh'),
(42, 'Randolph'),
(43, 'Ritchie'),
(44, 'Roane'),
(45, 'Summers'),
(46, 'Taylor'),
(47, 'Tucker'),
(48, 'Tyler'),
(49, 'Upshur'),
(50, 'Wayne'),
(51, 'Webster'),
(52, 'Wetzel'),
(53, 'Wirt'),
(54, 'Wood'),
(55, 'Wyoming');

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
  `AssignedDate` date NOT NULL,
  `DueDate` date NOT NULL,
  `FinishedDate` date DEFAULT NULL,
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
(1, '54-014/00-013.22', 1, '2016-01-01', '2016-01-31', '2016-01-15', 'complete', 'failing superstructure', 1, 1, 3, 2),
(2, '53-014/00-001.60', 1, '2016-01-01', '2016-01-31', '2016-01-15', 'complete', 'failing superstructure', 1, 1, 3, 2),
(3, '53-014/00-001.60', 1, '2017-01-01', '2017-01-31', '2017-01-15', 'complete', 'multiple damaged support beams', 5, 1, 3, 2),
(4, '53-014/00-001.60', 1, '2018-01-01', '2018-01-31', '2018-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(5, '54-014/00-013.22', 1, '2017-01-01', '2017-01-31', '2017-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(6, '54-014/00-013.22', 1, '2018-01-01', '2018-01-31', '2018-01-15', 'complete', 'multiple damaged support beams', 5, 1, 3, 2),
(7, '54-014/00-013.22', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(8, '53-014/00-001.60', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(9, '53-014/00-001.60', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(10, '53-014/00-001.60', 1, '2021-01-01', '2021-01-31', '2021-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(11, '32-023/08-001.93', 1, '2021-01-01', '2021-10-31', '2021-10-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(12, '32-023/08-001.93', 1, '2021-01-01', '2021-10-31', '2021-10-01', 'complete', 'superficial damage', 9, 1, 3, 2),
(13, '32-023/08-001.93', 1, '2021-01-01', '2021-10-31', '2021-10-06', 'complete', 'support beam damage', 7, 1, 3, 2),
(14, '32-023/08-001.93', 1, '2021-01-01', '2021-09-30', '2021-09-20', 'complete', 'support beam damage', 7, 1, 3, 2),
(15, '32-023/08-001.93', 1, '2021-01-01', '2021-08-30', '2021-08-10', 'complete', 'support beam damage', 7, 1, 3, 2),
(16, '32-023/08-001.93', 1, '2021-01-01', '2021-05-31', '2021-05-04', 'complete', 'support beam damage', 7, 1, 3, 2),
(17, '54-014/00-013.22', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'multiple damaged support beams', 4, 1, 3, 2),
(18, '54-014/00-013.22', 1, '2021-01-01', '2021-01-31', '2021-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(19, '01-012/03-000.02', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'assets/Report.pdf', 2, 1, 3, 2),
(20, '02-009/00-010.88', 1, '2017-01-01', '2017-01-31', '2017-01-02', 'complete', 'assets/Report.pdf', 7, 1, 3, 2),
(21, '03-085/00-007.71', 1, '2017-01-01', '2017-01-31', '2017-01-04', 'complete', 'assets/Report.pdf', 9, 1, 3, 2),
(22, '04-013/02-003.21', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'truss damage', 7, 1, 3, 2),
(23, '05-002/00-010.01', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(24, '06-060/00-000.02', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'truss damage', 7, 1, 3, 2),
(25, '07-005/00-016.61', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'failing superstructure', 1, 1, 3, 2),
(26, '08-002/00-003.36', 1, '2008-01-01', '2008-01-31', '2008-01-14', 'complete', 'bridge permanently closed', 1, 1, 3, 2),
(27, '09-050/30-013.21', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'truss damage', 7, 1, 3, 2),
(28, '10-016/00-002.38', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(29, '11-001/00-002.38', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'no issue', 9, 1, 3, 2),
(30, '12-005/00-000.13', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'multiple damaged support beams', 4, 1, 3, 2),
(31, '13-014/00-004.18', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(32, '14-050/00-020.03', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(33, '16-007/00-000.14', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'multiple damaged support beams', 3, 1, 3, 2),
(34, '17-019/33-000.01', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(35, '18-002/00-002.72', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'truss damage', 8, 1, 3, 2),
(36, '20-021/00-001.39', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'failing superstructure', 2, 1, 3, 2),
(37, '21-013/00-004.39', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'multiple damaged support beams', 4, 1, 3, 2),
(38, '23-119/34-000.01', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'no issue', 9, 1, 3, 2),
(39, '25-218/00-010.86', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'failing superstructure', 2, 1, 3, 2),
(40, '26-002/00-007.78', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'failing superstructure', 1, 1, 3, 2),
(41, '27-062/00-006.58', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'multiple damaged support beams', 4, 1, 3, 2),
(42, '24-005/02-004.51', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'truss damage', 7, 1, 3, 2),
(43, '28-003/00-000.47', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(44, '29-093/00-003.42', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'truss damage', 6, 1, 3, 2),
(45, '30-052/00-022.17', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'no issue', 9, 1, 3, 2),
(46, '31-071/00-000.91', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(47, '33-9/00-012.39', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(48, '34-039/00-052.51', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'multiple damaged support beams', 4, 1, 3, 2),
(49, '35-001/00-002.83', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'support beam damage', 5, 1, 3, 2),
(50, '36-033/00-019.21', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'truss damage', 6, 1, 3, 2),
(51, '37-005/00-001.05', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'failing superstructure', 3, 1, 3, 2),
(52, '38-028/00-006.71', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(53, '39-026/00-009.42', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(54, '40-034/00-021.21', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'truss damage', 7, 1, 3, 2),
(55, '41-003/00-001.17', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'multiple damaged support beams', 3, 1, 3, 2),
(56, '42-021/00-001.50', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'multiple damaged support beams', 5, 1, 3, 2),
(57, '43-007/12-000.81', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(58, '44-003/00-002.58', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'failing superstructure', 2, 1, 3, 2),
(59, '45-033/00-002.41', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'failing superstructure', 1, 1, 3, 2),
(60, '47-072/00-008.26', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'multiple damaged support beams', 3, 1, 3, 2),
(61, '48-018/00-010.11', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(62, '49-020/00-016.02', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'no issue', 9, 1, 3, 2),
(63, '50-052/00-014.56', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'truss damage', 7, 1, 3, 2),
(64, '51-005/01-001.98', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(65, '52-007/00-000.07', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'multiple damaged support beams', 4, 1, 3, 2),
(66, '01-012/03-000.02', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'assets/Report.pdf', 6, 1, 3, 2),
(67, '02-009/00-010.88', 1, '2018-01-01', '2018-01-31', '2018-01-02', 'complete', 'assets/Report.pdf', 7, 1, 3, 2),
(68, '03-085/00-007.71', 1, '2018-01-01', '2018-01-31', '2018-01-04', 'complete', 'assets/Report.pdf', 5, 1, 3, 2),
(69, '04-013/02-003.21', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(70, '05-002/00-010.01', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(71, '06-060/00-000.02', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'multiple damaged support beams', 3, 1, 3, 2),
(72, '07-005/00-016.61', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 5, 1, 3, 2),
(74, '09-050/30-013.21', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'no issue', 9, 1, 3, 2),
(75, '10-016/00-002.38', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'failing superstructure', 2, 1, 3, 2),
(76, '11-001/00-002.38', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(77, '12-005/00-000.13', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(78, '13-014/00-004.18', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(79, '14-050/00-020.03', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(80, '16-007/00-000.14', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 5, 1, 3, 2),
(81, '17-019/33-000.01', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(82, '18-002/00-002.72', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(83, '20-021/00-001.39', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(84, '21-013/00-004.39', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(85, '23-119/34-000.01', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'no issue', 9, 1, 3, 2),
(86, '25-218/00-010.86', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'multiple damaged support beams', 3, 1, 3, 2),
(87, '26-002/00-007.78', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'multiple damaged support beams', 4, 1, 3, 2),
(88, '27-062/00-006.58', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(89, '24-005/02-004.51', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(90, '28-003/00-000.47', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(91, '29-093/00-003.42', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(92, '30-052/00-022.17', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(93, '31-071/00-000.91', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(94, '33-9/00-012.39', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(95, '34-039/00-052.51', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 5, 1, 3, 2),
(96, '35-001/00-002.83', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(97, '36-033/00-019.21', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(98, '37-005/00-001.05', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(99, '38-028/00-006.71', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(100, '39-026/00-009.42', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(101, '40-034/00-021.21', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'no issue', 9, 1, 3, 2),
(102, '41-003/00-001.17', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 5, 1, 3, 2),
(103, '42-021/00-001.50', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'multiple damaged support beams', 4, 1, 3, 2),
(104, '43-007/12-000.81', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(105, '44-003/00-002.58', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(106, '45-033/00-002.41', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(107, '47-072/00-008.26', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'multiple damaged support beams', 4, 1, 3, 2),
(108, '48-018/00-010.11', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(109, '49-020/00-016.02', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(110, '50-052/00-014.56', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'superficial damage', 8, 1, 3, 2),
(111, '51-005/01-001.98', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(112, '52-007/00-000.07', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'support beam damage', 7, 1, 3, 2),
(113, '01-012/03-000.02', 1, '2021-12-01', '2021-12-31', NULL, 'in progress', NULL, NULL, 1, 3, 2),
(114, '02-009/00-010.88', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'assets/Report.pdf', 6, 1, 3, 2),
(115, '03-085/00-007.71', 1, '2019-01-01', '2019-01-31', '2019-01-15', 'complete', 'assets/Report.pdf', 3, 1, 3, 2),
(116, '04-013/02-003.21', 1, '2021-12-01', '2021-12-31', NULL, 'in progress', NULL, NULL, 1, 3, 2),
(117, '05-002/00-010.01', 1, '2021-12-01', '2021-12-31', NULL, 'in progress', NULL, NULL, 1, 3, 2),
(118, '06-060/00-000.02', 1, '2021-12-01', '2021-12-31', NULL, 'not started', NULL, NULL, 1, 3, 2),
(119, '07-005/00-016.61', 1, '2021-12-01', '2021-12-31', NULL, 'not started', NULL, NULL, 1, 3, 2),
(121, '09-050/30-013.21', 1, '2021-01-01', '2021-01-31', '2021-01-17', 'complete', 'superficial damage', 9, 1, 3, 2),
(122, '10-016/00-002.38', 1, '2021-01-01', '2021-01-31', '2021-01-09', 'complete', 'truss damage', 7, 1, 3, 2),
(123, '11-001/00-002.38', 1, '2021-01-01', '2021-01-31', '2021-01-12', 'complete', 'superficial damage', 8, 1, 3, 2),
(124, '12-005/00-000.13', 1, '2021-01-01', '2021-01-31', '2021-01-08', 'complete', 'support beam damage', 6, 1, 3, 2),
(125, '13-014/00-004.18', 1, '2021-01-01', '2021-01-31', '2021-01-03', 'complete', 'support beam damage', 7, 1, 3, 2),
(126, '14-050/00-020.03', 1, '2021-01-01', '2021-01-31', '2021-01-17', 'complete', 'superficial damage', 6, 1, 3, 2),
(127, '16-007/00-000.14', 1, '2021-01-01', '2021-01-31', '2021-01-21', 'complete', 'support beam damage', 6, 1, 3, 2),
(128, '17-019/33-000.01', 1, '2021-01-01', '2021-01-31', '2021-01-19', 'complete', 'truss damage', 7, 1, 3, 2),
(129, '18-002/00-002.72', 1, '2021-01-01', '2021-01-31', '2021-01-23', 'complete', 'superficial damage', 9, 1, 3, 2),
(130, '20-021/00-001.39', 1, '2021-01-01', '2021-01-31', '2021-01-22', 'complete', 'multiple damaged support beams', 3, 1, 3, 2),
(131, '21-013/00-004.39', 1, '2021-01-01', '2021-01-31', '2021-01-24', 'complete', 'truss damage', 7, 1, 3, 2),
(132, '23-119/34-000.01', 1, '2021-01-01', '2021-01-31', '2021-01-25', 'complete', 'no issue', 9, 1, 3, 2),
(133, '25-218/00-010.86', 1, '2021-01-01', '2021-02-16', '2021-02-16', 'complete', 'support beam damage', 6, 1, 3, 2),
(134, '26-002/00-007.78', 1, '2021-01-01', '2021-02-18', '2021-02-18', 'complete', 'multiple damaged support beams', 3, 1, 3, 2),
(135, '27-062/00-006.58', 1, '2021-01-01', '2021-02-13', '2021-02-13', 'complete', 'support beam damage', 5, 1, 3, 2),
(136, '24-005/02-004.51', 1, '2021-01-01', '2021-02-15', '2021-02-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(137, '28-003/00-000.47', 1, '2021-01-01', '2021-02-03', '2021-02-03', 'complete', 'truss damage', 8, 1, 3, 2),
(138, '29-093/00-003.42', 1, '2021-01-01', '2021-02-06', '2021-02-06', 'complete', 'support beam damage', 7, 1, 3, 2),
(139, '30-052/00-022.17', 1, '2021-01-01', '2021-02-22', '2021-02-22', 'complete', 'superficial damage', 9, 1, 3, 2),
(140, '31-071/00-000.91', 1, '2021-01-01', '2021-01-31', '2021-01-31', 'complete', 'support beam damage', 6, 1, 3, 2),
(141, '33-9/00-012.39', 1, '2021-01-01', '2021-02-12', '2021-02-12', 'complete', 'truss damage', 8, 1, 3, 2),
(142, '34-039/00-052.51', 1, '2021-01-01', '2021-02-09', '2021-02-09', 'complete', 'support beam damage', 6, 1, 3, 2),
(143, '35-001/00-002.83', 1, '2021-01-01', '2021-02-07', '2021-02-07', 'complete', 'multiple damaged support beams', 3, 1, 3, 2),
(144, '36-033/00-019.21', 1, '2021-01-01', '2021-02-06', '2021-02-06', 'complete', 'truss damage', 8, 1, 3, 2),
(145, '37-005/00-001.05', 1, '2021-01-01', '2021-02-03', '2021-02-03', 'complete', 'superficial damage', 9, 1, 3, 2),
(146, '38-028/00-006.71', 1, '2021-01-01', '2021-02-11', '2021-02-11', 'complete', 'truss damage', 7, 1, 3, 2),
(147, '39-026/00-009.42', 1, '2021-01-01', '2021-02-23', '2021-02-23', 'complete', 'no issue', 9, 1, 3, 2),
(148, '40-034/00-021.21', 1, '2021-01-01', '2021-02-27', '2021-02-27', 'complete', 'support beam damage', 6, 1, 3, 2),
(149, '41-003/00-001.17', 1, '2021-01-01', '2021-03-31', '2021-03-24', 'complete', 'truss damage', 8, 1, 3, 2),
(150, '42-021/00-001.50', 1, '2021-01-01', '2021-03-31', '2021-03-23', 'complete', 'multiple damaged support beams', 3, 1, 3, 2),
(151, '43-007/12-000.81', 1, '2021-01-01', '2021-03-31', '2021-03-14', 'complete', 'support beam damage', 8, 1, 3, 2),
(152, '44-003/00-002.58', 1, '2021-01-01', '2021-03-31', '2021-03-17', 'complete', 'support beam damage', 5, 1, 3, 2),
(153, '45-033/00-002.41', 1, '2021-01-01', '2021-03-31', '2021-03-05', 'complete', 'failing superstructure', 9, 1, 3, 2),
(154, '47-072/00-008.26', 1, '2021-01-01', '2021-01-31', '2021-01-15', 'complete', 'support beam damage', 6, 1, 3, 2),
(155, '48-018/00-010.11', 1, '2021-01-01', '2021-01-31', '2021-01-15', 'complete', 'support beam damage', 5, 1, 3, 2),
(156, '49-020/00-016.02', 1, '2021-01-01', '2021-01-31', '2021-01-15', 'complete', 'superficial damage', 9, 1, 3, 2),
(157, '50-052/00-014.56', 1, '2021-01-01', '2021-01-31', '2021-01-15', 'complete', 'truss damage', 8, 1, 3, 2),
(158, '51-005/01-001.98', 1, '2021-01-01', '2021-01-31', '2021-01-15', 'complete', 'no issue', 9, 1, 3, 2),
(159, '52-007/00-000.07', 1, '2021-01-01', '2021-01-31', '2021-01-15', 'complete', 'truss damage', 7, 1, 3, 2),
(162, '01-012/03-000.02', 1, '2017-01-01', '2017-01-31', '2017-01-02', 'complete', 'assets/Report.pdf', 5, 1, 3, 2),
(163, '01-012/03-000.02', 1, '2018-01-01', '2018-01-31', '2018-01-02', 'complete', 'assets/Report.pdf', 5, 1, 3, 2),
(164, '02-009/00-010.88', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'assets/Report.pdf', 5, 1, 3, 2),
(165, '02-009/00-010.88', 1, '2021-12-01', '2021-12-31', NULL, 'not started', NULL, NULL, 1, 3, 2),
(166, '03-085/00-007.71', 1, '2020-01-01', '2020-01-31', '2020-01-15', 'complete', 'assets/Report.pdf', 9, 1, 3, 2),
(167, '03-085/00-007.71', 1, '2021-01-01', '2021-01-31', '2021-01-04', 'complete', 'assets/Report.pdf', 8, 1, 3, 2),
(168, '04-013/02-003.21', 1, '2018-01-01', '2018-01-31', '2018-01-10', 'complete', 'truss damage', 7, 1, 3, 2),
(169, '04-013/02-003.21', 1, '2017-01-01', '2017-01-31', '2017-01-10', 'complete', 'no issues', 9, 1, 3, 2),
(170, '05-002/00-010.01', 1, '2018-01-01', '2018-01-31', '2018-01-09', 'complete', 'no issues', 9, 1, 3, 2),
(171, '05-002/00-010.01', 1, '2017-01-01', '2017-01-31', '2017-01-09', 'complete', 'girder corrosion', 7, 1, 3, 2),
(172, '06-060/00-000.02', 1, '2018-01-01', '2018-01-31', '2018-01-05', 'complete', 'truss damage', 8, 1, 3, 2),
(173, '06-060/00-000.02', 1, '2017-01-01', '2017-01-31', '2017-01-05', 'complete', 'failing superstructure', 2, 1, 3, 2),
(174, '07-005/00-016.61', 1, '2018-01-01', '2018-01-31', '2018-01-01', 'complete', 'failing superstructure', 3, 1, 3, 2),
(175, '07-005/00-016.61', 1, '2017-01-01', '2017-01-31', '2017-01-01', 'complete', 'major support beam damage', 4, 1, 3, 2);

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
  ADD KEY `fk_AdditionalElements_Inspections_idx` (`Inspections_InspectionID`) USING BTREE;

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
  ADD KEY `fk_BEInspectionPhotos_BEInspections_idx` (`BEInspections_BEInspectionID`) USING BTREE;

--
-- Indexes for table `BridgeElementInspections`
--
ALTER TABLE `BridgeElementInspections`
  ADD PRIMARY KEY (`BEInspectionID`),
  ADD KEY `fk_BEInspections_BridgeElements_idx` (`BridgeElements_ElementID`) USING BTREE,
  ADD KEY `fk_BEInspections_Inspections_idx` (`Inspections_InspectionID`) USING BTREE;

--
-- Indexes for table `BridgeElements`
--
ALTER TABLE `BridgeElements`
  ADD PRIMARY KEY (`ElementID`),
  ADD KEY `fk_BridgeElements_Bridges_idx` (`Bridges_BridgeNo`) USING BTREE,
  ADD KEY `fk_BridgeElements_BridgeModels_idx` (`BridgeModels_BridgeModelNo`) USING BTREE,
  ADD KEY `fk_BridgeElements_DetailElements_idx` (`DetailElements_DetailElementNo`) USING BTREE,
  ADD KEY `fk_BridgeElements_Material_idx` (`Material_MaterialNo`) USING BTREE,
  ADD KEY `fk_BridgeElements_Category_idx` (`Category_CategoryNo`) USING BTREE,
  ADD KEY `fk_BridgeElements_Class_idx` (`Class_ClassNo`) USING BTREE,
  ADD KEY `fk_BridgeElements_InspectionTypeCode_idx` (`InspectionTypeCode_InspectionTypeNo`) USING BTREE;

--
-- Indexes for table `BridgeInspectionInfo`
--
ALTER TABLE `BridgeInspectionInfo`
  ADD PRIMARY KEY (`Bridges_BridgeNo`),
  ADD KEY `fk_BridgeInspectionInfo_Inspections_idx` (`Inspections_InspectionID`) USING BTREE,
  ADD KEY `Bridges_BridgeNo` (`Bridges_BridgeNo`);

--
-- Indexes for table `BridgeModels`
--
ALTER TABLE `BridgeModels`
  ADD PRIMARY KEY (`BridgeModelNo`);

--
-- Indexes for table `Bridges`
--
ALTER TABLE `Bridges`
  ADD PRIMARY KEY (`BridgeNo`),
  ADD UNIQUE KEY `BARs_No_UNIQUE` (`BARsNo`),
  ADD KEY `fk_Bridges_County_idx` (`County_CountyNo`) USING BTREE,
  ADD KEY `fk_Bridges_BridgeModels_idx` (`BridgeModels_BridgeModelNo`) USING BTREE;

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
  ADD KEY `fk_Classification_Bridges_idx` (`Bridges_BridgeNo`) USING BTREE;

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
  ADD KEY `fk_DroneImageSet_Inspections_idx` (`Inspections_InspectionID`) USING BTREE;

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
  ADD KEY `fk_Material_Category_idx` (`Category_CategoryNo`) USING BTREE;

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
  ADD KEY `fk_NarrativeReports_Inspections_idx` (`Inspections_InspectionID`) USING BTREE,
  ADD KEY `fk_NarrativeReports_NarrativeElements_idx` (`NarrativeElements_NarrativeElementNo`) USING BTREE;

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `DroneImages`
--
ALTER TABLE `DroneImages`
  MODIFY `DroneImageID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `DroneImageSet`
--
ALTER TABLE `DroneImageSet`
  MODIFY `ImageSetID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Inspections`
--
ALTER TABLE `Inspections`
  MODIFY `InspectionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AdditionalElements`
--
ALTER TABLE `AdditionalElements`
  ADD CONSTRAINT `fk_AdditionalElements_Inspections` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `AgeAndService`
--
ALTER TABLE `AgeAndService`
  ADD CONSTRAINT `fk_AgeAndService_Bridges` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BridgeElementInspectionPhotos`
--
ALTER TABLE `BridgeElementInspectionPhotos`
  ADD CONSTRAINT `fk_BEInspectionPhotos_BEInspections` FOREIGN KEY (`BEInspections_BEInspectionID`) REFERENCES `BridgeElementInspections` (`BEInspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BridgeElementInspections`
--
ALTER TABLE `BridgeElementInspections`
  ADD CONSTRAINT `fk_BEInspections_BridgeElements` FOREIGN KEY (`BridgeElements_ElementID`) REFERENCES `BridgeElements` (`ElementID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BEInspections_Inspections` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BridgeElements`
--
ALTER TABLE `BridgeElements`
  ADD CONSTRAINT `fk_BridgeElements_BridgeModels` FOREIGN KEY (`BridgeModels_BridgeModelNo`) REFERENCES `BridgeModels` (`BridgeModelNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BridgeElements_Bridges` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BridgeElements_Category` FOREIGN KEY (`Category_CategoryNo`) REFERENCES `Category` (`CategoryNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BridgeElements_Class` FOREIGN KEY (`Class_ClassNo`) REFERENCES `Class` (`ClassNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BridgeElements_DetailElements` FOREIGN KEY (`DetailElements_DetailElementNo`) REFERENCES `DetailElements` (`DetailElementNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BridgeElements_InspectionTypeCode` FOREIGN KEY (`InspectionTypeCode_InspectionTypeNo`) REFERENCES `InspectionTypeCode` (`InspectionTypeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BridgeElements_Material` FOREIGN KEY (`Material_MaterialNo`) REFERENCES `Material` (`MaterialNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BridgeInspectionInfo`
--
ALTER TABLE `BridgeInspectionInfo`
  ADD CONSTRAINT `fk_BridgeInspectionInfo_Bridges` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BridgeInspectionInfo_Inspections` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Bridges`
--
ALTER TABLE `Bridges`
  ADD CONSTRAINT `fk_Bridges_BridgeModels` FOREIGN KEY (`BridgeModels_BridgeModelNo`) REFERENCES `BridgeModels` (`BridgeModelNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Bridges_County` FOREIGN KEY (`County_CountyNo`) REFERENCES `County` (`CountyNo`);

--
-- Constraints for table `Category`
--
ALTER TABLE `Category`
  ADD CONSTRAINT `fk_Category_Class` FOREIGN KEY (`Class_ClassNo`) REFERENCES `Class` (`ClassNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Classification`
--
ALTER TABLE `Classification`
  ADD CONSTRAINT `fk_Classification_Bridges` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Comments`
--
ALTER TABLE `Comments`
  ADD CONSTRAINT `fk_Comments_Inspections` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `DetailElements`
--
ALTER TABLE `DetailElements`
  ADD CONSTRAINT `fk_DetailElements_Material1` FOREIGN KEY (`Material_MaterialNo`) REFERENCES `Material` (`MaterialNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `DroneImages`
--
ALTER TABLE `DroneImages`
  ADD CONSTRAINT `fk_DroneImages_BEInspections` FOREIGN KEY (`BEInspections_BEInspectionID`) REFERENCES `BridgeElementInspections` (`BEInspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DroneImages_DroneImageSet` FOREIGN KEY (`DroneImageSet_ImageSetID`) REFERENCES `DroneImageSet` (`ImageSetID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `DroneImageSet`
--
ALTER TABLE `DroneImageSet`
  ADD CONSTRAINT `fk_DroneImageSet_Inspections` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Inspections`
--
ALTER TABLE `Inspections`
  ADD CONSTRAINT `fk_Inspection_Bridges` FOREIGN KEY (`Bridges_BridgeNo`) REFERENCES `Bridges` (`BridgeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Inspections_InspectionTypeCode` FOREIGN KEY (`InspectionTypeNo`) REFERENCES `InspectionTypeCode` (`InspectionTypeNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Inspections_UserNoAdmin` FOREIGN KEY (`AdminID`) REFERENCES `Users` (`UserNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Inspections_UserNoEvaluator` FOREIGN KEY (`EvaluatorID`) REFERENCES `Users` (`UserNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Inspections_UserNoInspector` FOREIGN KEY (`InspectorID`) REFERENCES `Users` (`UserNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Material`
--
ALTER TABLE `Material`
  ADD CONSTRAINT `fk_Material_Category` FOREIGN KEY (`Category_CategoryNo`) REFERENCES `Category` (`CategoryNo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `NarrativeReportPhotos`
--
ALTER TABLE `NarrativeReportPhotos`
  ADD CONSTRAINT `fk_NarrativeReportPhoto_NarrativeReport` FOREIGN KEY (`NarrativeReport_NarrativeElementID`) REFERENCES `NarrativeReports` (`NarrativeElementID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `NarrativeReports`
--
ALTER TABLE `NarrativeReports`
  ADD CONSTRAINT `fk_NarrativeReport_NarrativeElements1` FOREIGN KEY (`NarrativeElements_NarrativeElementNo`) REFERENCES `NarrativeElements` (`NarrativeElementNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_NarrativeReports_Inspections` FOREIGN KEY (`Inspections_InspectionID`) REFERENCES `Inspections` (`InspectionID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `fk_Users_UserRole` FOREIGN KEY (`UserRole`) REFERENCES `UserRole` (`UserRole`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
