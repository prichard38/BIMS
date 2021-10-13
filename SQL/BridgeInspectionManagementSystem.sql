-- MySQL Script generated by Hwapyeong Song
-- Wed Sep 15 18:43:29 2021
-- Model: New Model    Version: 1.0

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`County`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`County` (
  `CountyNo` INT NOT NULL,
  `CountyName` VARCHAR(45) NULL,
  PRIMARY KEY (`CountyNo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`BridgeModels`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`BridgeModels` (
  `BridgeModelNo_BridgeModels` INT NOT NULL,
  `BridgeModelPath` LONGTEXT NULL,
  `CreatedDate` TIMESTAMP NULL,
  PRIMARY KEY (`BridgeModelNo_BridgeModels`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Bridges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Bridges` (
  `BridgeNo` VARCHAR(50) NOT NULL,
  `BARsNo` VARCHAR(12) NULL,
  `BridgeName` VARCHAR(45) NULL,
  `FeatureIntersected` VARCHAR(45) NULL,
  `FacilityCarried` VARCHAR(45) NULL,
  `Location` VARCHAR(100) NULL,
  `District` INT NULL,
  `County_CountyNo` INT NOT NULL,
  `BridgePicture` LONGTEXT NULL,
  `BridgeModelNo_Bridges` INT NULL,
  PRIMARY KEY (`BridgeNo`),
  UNIQUE INDEX `BARs_No_UNIQUE` (`BARsNo` ASC) ,
  INDEX `fk_Bridges_County1_idx` (`County_CountyNo` ASC) ,
  INDEX `BridgeModelNo_idx` (`BridgeModelNo_Bridges` ASC) ,
  CONSTRAINT `fk_Bridges_County1`
    FOREIGN KEY (`County_CountyNo`)
    REFERENCES `mydb`.`County` (`CountyNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `BridgeModelNo_BridgeModels_Bridges`
    FOREIGN KEY (`BridgeModelNo_Bridges`)
    REFERENCES `mydb`.`BridgeModels` (`BridgeModelNo_BridgeModels`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Bridge Identification';


-- -----------------------------------------------------
-- Table `mydb`.`Class`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Class` (
  `ClassNo` INT NOT NULL,
  `ClassName` VARCHAR(45) NULL,
  PRIMARY KEY (`ClassNo`))
ENGINE = InnoDB
COMMENT = 'Bridge Structure type and Material_Level1_Class';


-- -----------------------------------------------------
-- Table `mydb`.`Category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Category` (
  `CategoryNo` INT NOT NULL,
  `CategoryName` VARCHAR(45) NULL,
  `Class_ClassNo` INT NOT NULL,
  PRIMARY KEY (`CategoryNo`),
  INDEX `fk_Category_Class_idx` (`Class_ClassNo` ASC) ,
  CONSTRAINT `fk_Category_Class`
    FOREIGN KEY (`Class_ClassNo`)
    REFERENCES `mydb`.`Class` (`ClassNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Bridge Structure type and Material_Level2_Category';


-- -----------------------------------------------------
-- Table `mydb`.`Material`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Material` (
  `MaterialNo` INT NOT NULL,
  `MaterialName` VARCHAR(45) NULL,
  `Category_CategoryNo` INT NOT NULL,
  PRIMARY KEY (`MaterialNo`),
  INDEX `fk_Material_Category1_idx` (`Category_CategoryNo` ASC) ,
  CONSTRAINT `fk_Material_Category1`
    FOREIGN KEY (`Category_CategoryNo`)
    REFERENCES `mydb`.`Category` (`CategoryNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Bridge Structure type and Material_Level3_Material';


-- -----------------------------------------------------
-- Table `mydb`.`DetailElements`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`DetailElements` (
  `DetailElementNo` INT NOT NULL,
  `DetailElementName` VARCHAR(45) NULL,
  `DetailElementNum` INT NULL,
  `Material_MaterialNo` INT NOT NULL,
  PRIMARY KEY (`DetailElementNo`),
  INDEX `fk_DetailElements_Material1_idx` (`Material_MaterialNo` ASC) ,
  CONSTRAINT `fk_DetailElements_Material1`
    FOREIGN KEY (`Material_MaterialNo`)
    REFERENCES `mydb`.`Material` (`MaterialNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Bridge Structure type and Material_Level4_DetailElements';


-- -----------------------------------------------------
-- Table `mydb`.`Classification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Classification` (
  `Bridges_BridgeNo` VARCHAR(50) NOT NULL,
  `NBISBridgeLength` TINYINT NULL,
  `NHS` TINYINT NULL,
  PRIMARY KEY (`Bridges_BridgeNo`),
  INDEX `fk_Classification_Bridges1_idx` (`Bridges_BridgeNo` ASC) ,
  CONSTRAINT `fk_Classification_Bridges1`
    FOREIGN KEY (`Bridges_BridgeNo`)
    REFERENCES `mydb`.`Bridges` (`BridgeNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`InspectionTypeCode`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`InspectionTypeCode` (
  `InspectionTypeNo` INT NOT NULL,
  `InspectionTypeName` VARCHAR(45) NULL,
  PRIMARY KEY (`InspectionTypeNo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`UserRole`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`UserRole` (
  `UserRole` INT NOT NULL,
  `UserRoleName` VARCHAR(45) NULL,
  PRIMARY KEY (`UserRole`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Users` (
  `UserNo` INT NOT NULL,
  `UserID` VARCHAR(45) NULL,
  `UserPassword` VARCHAR(45) NULL,
  `UserRole` INT NULL,
  `UserPicture` LONGTEXT NULL,
  `FirstName` VARCHAR(45) NULL,
  `MiddleName` VARCHAR(45) NULL,
  `LastName` VARCHAR(45) NULL,
  `Phone` VARCHAR(45) NULL,
  `Address` VARCHAR(45) NULL,
  `Email` VARCHAR(45) NULL,
  `DateOfBirth` DATE NULL,
  `UserCreated` TIMESTAMP NULL,
  `UserModified` TIMESTAMP NULL,
  `EmailVerification` TINYINT NULL,
  `ActiveStatus` TINYINT NULL,
  PRIMARY KEY (`UserNo`),
  INDEX `UserRole_idx` (`UserRole` ASC) ,
  CONSTRAINT `UserRole`
    FOREIGN KEY (`UserRole`)
    REFERENCES `mydb`.`UserRole` (`UserRole`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Inspections`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Inspections` (
  `InspectionID` INT NOT NULL,
  `Bridges_BridgeNo` VARCHAR(50) NOT NULL,
  `InspectionTypeNo` INT NULL,
  `AssignedDate` TIMESTAMP NULL,
  `DueDate` TIMESTAMP NULL,
  `FinishedDate` TIMESTAMP NULL,
  `Status` VARCHAR(45) NULL,
  `Report` LONGTEXT NULL,
  `OverallRating` DOUBLE NULL,
  `AdminID` INT NULL,
  `InspectorID` INT NULL,
  `EvaluatorID` INT NULL,
  PRIMARY KEY (`InspectionID`),
  INDEX `fk_Inspection_Bridges1_idx` (`Bridges_BridgeNo` ASC) ,
  INDEX `InspectionTypeNo_idx` (`InspectionTypeNo` ASC) ,
  INDEX `UserNo_idx` (`AdminID` ASC) ,
  INDEX `UserNo_idx1` (`InspectorID` ASC) ,
  INDEX `UserNo_idx2` (`EvaluatorID` ASC) ,
  CONSTRAINT `fk_Inspection_Bridges1`
    FOREIGN KEY (`Bridges_BridgeNo`)
    REFERENCES `mydb`.`Bridges` (`BridgeNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `InspectionTypeNo`
    FOREIGN KEY (`InspectionTypeNo`)
    REFERENCES `mydb`.`InspectionTypeCode` (`InspectionTypeNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `UserNoAdmin`
    FOREIGN KEY (`AdminID`)
    REFERENCES `mydb`.`Users` (`UserNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `UserNoInspector`
    FOREIGN KEY (`InspectorID`)
    REFERENCES `mydb`.`Users` (`UserNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `UserNoEvaluator`
    FOREIGN KEY (`EvaluatorID`)
    REFERENCES `mydb`.`Users` (`UserNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`BridgeInspectionInfo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`BridgeInspectionInfo` (
  `Bridges_BridgeNo` VARCHAR(50) NOT NULL,
  `LatestInspectionDate` DATE NULL,
  `Inspections_InspectionID` INT NULL,
  `Rating` DOUBLE NULL,
  PRIMARY KEY (`Bridges_BridgeNo`),
  INDEX `InspectionID_idx` (`Inspections_InspectionID` ASC) ,
  CONSTRAINT `fk_BridgeInspectionInfo_Bridges1`
    FOREIGN KEY (`Bridges_BridgeNo`)
    REFERENCES `mydb`.`Bridges` (`BridgeNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `InspectionID_Inspections_BridgeInspectionInfo`
    FOREIGN KEY (`Inspections_InspectionID`)
    REFERENCES `mydb`.`Inspections` (`InspectionID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`AgeAndService`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`AgeAndService` (
  `Bridges_BridgeNo` VARCHAR(50) NOT NULL,
  `YearBuilt` YEAR(4) NULL,
  `ADTyear` YEAR(4) NULL,
  `InventoryRouteADT` DOUBLE NULL,
  PRIMARY KEY (`Bridges_BridgeNo`),
  CONSTRAINT `fk_AgeAndService_Bridges1`
    FOREIGN KEY (`Bridges_BridgeNo`)
    REFERENCES `mydb`.`Bridges` (`BridgeNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`BridgeElements`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`BridgeElements` (
  `ElementID` INT NOT NULL,
  `Bridges_BridgeNo` VARCHAR(50) NOT NULL,
  `BridgeModelNo_BridgeElements` INT NULL,
  `InspectionTypeCode_InspectionTypeNo` INT NOT NULL,
  `Class_ClassNo` INT NOT NULL,
  `Category_CategoryNo` INT NOT NULL,
  `Material_MaterialNo` INT NOT NULL,
  `DetailElements_DetailElementNo` INT NOT NULL,
  `ElementX` DOUBLE NULL,
  `ElementY` DOUBLE NULL,
  `ElementZ` DOUBLE NULL,
  `AddedDate` TIMESTAMP NULL,
  `DeletedDate` TIMESTAMP NULL,
  `ModifiedBy` VARCHAR(45) NULL,
  INDEX `fk_InspectionType_InspectionTypeCode1_idx` (`InspectionTypeCode_InspectionTypeNo` ASC) ,
  INDEX `fk_InspectionType_Bridges1_idx` (`Bridges_BridgeNo` ASC) ,
  PRIMARY KEY (`ElementID`),
  INDEX `fk_InspectionTypeElements_Class1_idx` (`Class_ClassNo` ASC) ,
  INDEX `fk_InspectionTypeElements_Category1_idx` (`Category_CategoryNo` ASC) ,
  INDEX `fk_InspectionTypeElements_Material1_idx` (`Material_MaterialNo` ASC) ,
  INDEX `fk_InspectionTypeElements_DetailElements1_idx` (`DetailElements_DetailElementNo` ASC) ,
  INDEX `BridgeModelNo_idx` (`BridgeModelNo_BridgeElements` ASC) ,
  CONSTRAINT `fk_InspectionType_InspectionTypeCode1`
    FOREIGN KEY (`InspectionTypeCode_InspectionTypeNo`)
    REFERENCES `mydb`.`InspectionTypeCode` (`InspectionTypeNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_InspectionType_Bridges1`
    FOREIGN KEY (`Bridges_BridgeNo`)
    REFERENCES `mydb`.`Bridges` (`BridgeNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_InspectionTypeElements_Class1`
    FOREIGN KEY (`Class_ClassNo`)
    REFERENCES `mydb`.`Class` (`ClassNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_InspectionTypeElements_Category1`
    FOREIGN KEY (`Category_CategoryNo`)
    REFERENCES `mydb`.`Category` (`CategoryNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_InspectionTypeElements_Material1`
    FOREIGN KEY (`Material_MaterialNo`)
    REFERENCES `mydb`.`Material` (`MaterialNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_InspectionTypeElements_DetailElements1`
    FOREIGN KEY (`DetailElements_DetailElementNo`)
    REFERENCES `mydb`.`DetailElements` (`DetailElementNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `BridgeModelNo_BridgeModels_BridgeElements`
    FOREIGN KEY (`BridgeModelNo_BridgeElements`)
    REFERENCES `mydb`.`BridgeModels` (`BridgeModelNo_BridgeModels`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`DroneImageSet`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`DroneImageSet` (
  `ImageSetID` INT NOT NULL,
  `Inspections_InspectionID` INT NULL,
  `DateTime` TIMESTAMP NULL,
  PRIMARY KEY (`ImageSetID`),
  INDEX `InspectionID_idx` (`Inspections_InspectionID` ASC) ,
  CONSTRAINT `InspectionID_Inspections_DroneImageSet`
    FOREIGN KEY (`Inspections_InspectionID`)
    REFERENCES `mydb`.`Inspections` (`InspectionID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`BridgeElementInspections`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`BridgeElementInspections` (
  `BEInspectionID` INT NOT NULL,
  `ElementID` INT NULL,
  `Inspections_InspectionID` INT NULL,
  `Rating` DOUBLE NULL,
  `Description` LONGTEXT NULL,
  `UpdatedDate` TIMESTAMP NULL,
  PRIMARY KEY (`BEInspectionID`),
  INDEX `ElementID_idx` (`ElementID` ASC) ,
  INDEX `InspectionID_idx` (`Inspections_InspectionID` ASC) ,
  CONSTRAINT `ElementID`
    FOREIGN KEY (`ElementID`)
    REFERENCES `mydb`.`BridgeElements` (`ElementID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `InspectionID_Inspections_BridgeElementInspections`
    FOREIGN KEY (`Inspections_InspectionID`)
    REFERENCES `mydb`.`Inspections` (`InspectionID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`DroneImages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`DroneImages` (
  `DroneImageID` INT NOT NULL,
  `DroneImageSet_ImageSetID` INT NOT NULL,
  `Picture` LONGTEXT NULL,
  `Name` VARCHAR(100) NULL,
  `Comments` LONGTEXT NULL,
  `ElementX` DOUBLE NULL,
  `ElementY` DOUBLE NULL,
  `ElementZ` DOUBLE NULL,
  `AddedImage` TINYINT NULL,
  `BEInspections_BEInspectionID` INT NULL,
  PRIMARY KEY (`DroneImageID`),
  INDEX `fk_DroneImages_DroneImageSet1_idx` (`DroneImageSet_ImageSetID` ASC) ,
  INDEX `BEInspectionID_idx` (`BEInspections_BEInspectionID` ASC) ,
  CONSTRAINT `fk_DroneImages_DroneImageSet1`
    FOREIGN KEY (`DroneImageSet_ImageSetID`)
    REFERENCES `mydb`.`DroneImageSet` (`ImageSetID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `BEInspectionID_BEInspections_DroneImages`
    FOREIGN KEY (`BEInspections_BEInspectionID`)
    REFERENCES `mydb`.`BridgeElementInspections` (`BEInspectionID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`NarrativeElements`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`NarrativeElements` (
  `NarrativeElementNo` INT NOT NULL,
  `NarrativeElementName` VARCHAR(45) NULL,
  PRIMARY KEY (`NarrativeElementNo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`NarrativeReports`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`NarrativeReports` (
  `NarrativeElementID` INT NOT NULL,
  `NarrativeElements_NarrativeElementNo` INT NOT NULL,
  `NarrativeElementDescription` LONGTEXT NULL,
  `Inspections_InspectionID` INT NOT NULL,
  PRIMARY KEY (`NarrativeElementID`),
  INDEX `fk_NarrativeReport_Inspection1_idx` (`Inspections_InspectionID` ASC) ,
  INDEX `fk_NarrativeReport_NarrativeElements1_idx` (`NarrativeElements_NarrativeElementNo` ASC) ,
  CONSTRAINT `fk_NarrativeReport_Inspection1`
    FOREIGN KEY (`Inspections_InspectionID`)
    REFERENCES `mydb`.`Inspections` (`InspectionID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_NarrativeReport_NarrativeElements1`
    FOREIGN KEY (`NarrativeElements_NarrativeElementNo`)
    REFERENCES `mydb`.`NarrativeElements` (`NarrativeElementNo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`NarrativeReportPhotos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`NarrativeReportPhotos` (
  `PhotoID` INT NOT NULL,
  `NarrativeReport_NarrativeElementID` INT NOT NULL,
  `Photo` LONGTEXT NULL,
  `PhotoName` VARCHAR(100) NULL,
  `Description` LONGTEXT NULL,
  PRIMARY KEY (`PhotoID`),
  INDEX `fk_NarrativeReportPhoto_NarrativeReport1_idx` (`NarrativeReport_NarrativeElementID` ASC),
  CONSTRAINT `fk_NarrativeReportPhoto_NarrativeReport1`
    FOREIGN KEY (`NarrativeReport_NarrativeElementID`)
    REFERENCES `mydb`.`NarrativeReports` (`NarrativeElementID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`AdditionalElements`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`AdditionalElements` (
  `AdditionalElementID` INT NOT NULL,
  `AdditionalElement` LONGTEXT NULL,
  `AdditionalElementName` VARCHAR(100) NULL,
  `AdditionalElementDescription` LONGTEXT NULL,
  `Inspections_InspectionID` INT NOT NULL,
  PRIMARY KEY (`AdditionalElementID`),
  INDEX `fk_AdditionalElements_Inspection1_idx` (`Inspections_InspectionID` ASC),
  CONSTRAINT `fk_AdditionalElements_Inspection1`
    FOREIGN KEY (`Inspections_InspectionID`)
    REFERENCES `mydb`.`Inspections` (`InspectionID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`BridgeElementInspectionPhotos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`BridgeElementInspectionPhotos` (
  `PhotoID` INT NOT NULL,
  `BEInspections_BEInspectionID` INT NULL,
  `Photo` LONGTEXT NULL,
  `Name` VARCHAR(100) NULL,
  `Comments` LONGTEXT NULL,
  PRIMARY KEY (`PhotoID`),
  INDEX `BEInspectionID_idx` (`BEInspections_BEInspectionID` ASC),
  CONSTRAINT `BEInspectionID_BEInspections_BEInspectionPhotos`
    FOREIGN KEY (`BEInspections_BEInspectionID`)
    REFERENCES `mydb`.`BridgeElementInspections` (`BEInspectionID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Comments` (
  `CommentID` INT NOT NULL,
  `Inspections_InspectionID` INT NULL,
  `AdminComments` VARCHAR(300) NULL,
  `InspectorComments` VARCHAR(300) NULL,
  `EvaluatorComments` VARCHAR(300) NULL,
  `Date` TIMESTAMP NULL,
  PRIMARY KEY (`CommentID`),
  INDEX `InspectionID_idx` (`Inspections_InspectionID` ASC),
  CONSTRAINT `InspectionID_Inspections_Comments`
    FOREIGN KEY (`Inspections_InspectionID`)
    REFERENCES `mydb`.`Inspections` (`InspectionID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;