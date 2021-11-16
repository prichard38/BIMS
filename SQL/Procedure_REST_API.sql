/*
  This procedure creates a new ImageSet and returns its id so that photos can be uploaded using the set id
*/
DELIMITER $$
CREATE PROCEDURE createImageSet(IN inspection_id int)
BEGIN
	INSERT INTO DroneImageSet (Inspections_InspectionID,DateTime) VALUES (inspection_id,now()); 
	SELECT LAST_INSERT_ID();
END$$

DELIMITER;




DELIMITER $$

/** This procedure grabs information about an inspection for the mobile application to use. 
*/
CREATE PROCEDURE selectInspectionData_ById(IN inspection_id int)
BEGIN
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

DELIMITER;

DELIMITER $$

CREATE PROCEDURE selectLargestImageSetId()
BEGIN
    SELECT MAX(ImageSetID) as set_id FROM DroneImageSet;
END$$

DELIMITER;
