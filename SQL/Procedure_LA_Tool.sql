CREATE PROCEDURE getBridgeInspectionData(
    IN bridge_name VARCHAR(45),
    OUT inspection_id INT,
    OUT bridge_no VARCHAR(45),
    OUT rating INT,
    OUT assign_date timestamp,
    OUT finish_date timestamp,
    OUT eval_firstname VARCHAR(45),
    OUT eval_lastname VARCHAR(45),
    OUT insp_firstname VARCHAR(45),
    OUT insp_lastname VARCHAR(45)
)
BEGIN
SELECT 
@inspection_id = Inspections.InspectionID, 
@bridge_no = Inspections.Bridges_BridgeNo,
@rating = Inspections.OverallRating,
@assign_date = Inspections.AssignedDate,
@finish_date = Inspections.FinishedDate,
@eval_firstname = Users.FirstName,
@eval_lastname = Users.LastName
FROM Users, Inspections WHERE UserNo IN  
(
    SELECT EvaluatorID FROM Inspections WHERE Bridges_BridgeNo IN 
    (
        SELECT BridgeNo FROM Bridges
        WHERE Bridgename = bridge_name
    )
);
SELECT 
@insp_firstname = Users.FirstName, 
@insp_lastname = Users.LastName
FROM Users, Inspections WHERE UserNo IN  
(
    SELECT InspectorID FROM Inspections WHERE Bridges_BridgeNo IN 
    (
        SELECT BridgeNo FROM Bridges
        WHERE BridgeName = bridge_name
    )
);
END

