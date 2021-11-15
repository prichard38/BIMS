SET foreign_key_checks = 0;
ALTER TABLE `DroneImageSet` CHANGE `ImageSetID` `ImageSetID` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `DroneImages` CHANGE `DroneImageID` `DroneImageID` INT(11) NOT NULL AUTO_INCREMENT;
SET foreign_key_checks = 1;
