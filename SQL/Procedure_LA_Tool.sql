
DELIMITER //
CREATE PROCEDURE selectBridgeInspectionData(IN bridge_name VARCHAR(45))
BEGIN
	SELECT i.FinishedDate, i.Bridges_BridgeNo, b.BridgeName, t.InspectionTypeName ,u.FirstName AS inspector_first, 
	u.LastName AS inspector_last, u2.FirstName AS evaluator_first, u2.LastName AS evaluator_last, i.OverallRating
	FROM Inspections i 
	JOIN Bridges b ON i.Bridges_BridgeNo = b.BridgeNo
	JOIN InspectionTypeCode t ON i.InspectionTypeNo = t.InspectionTypeNo
	JOIN Users u ON i.InspectorID = u.UserNo
	JOIN Users u2 ON i.EvaluatorID = u2.UserNo
	WHERE Bridges_BridgeNo IN (
		SELECT BridgeNo FROM Bridges WHERE BridgeName=bridge_name
	);
END 

DELIMITER //
CREATE PROCEDURE selectBridgeInspectionData_OneYear(IN bridge_name VARCHAR(45), IN report_year int)
BEGIN
	SELECT i.FinishedDate, i.Bridges_BridgeNo, b.BridgeName, t.InspectionTypeName ,u.FirstName AS inspector_first, 
	u.LastName AS inspector_last, u2.FirstName AS evaluator_first, u2.LastName AS evaluator_last, i.OverallRating
	FROM Inspections i 
	JOIN Bridges b ON i.Bridges_BridgeNo = b.BridgeNo
	JOIN InspectionTypeCode t ON i.InspectionTypeNo = t.InspectionTypeNo
	JOIN Users u ON i.InspectorID = u.UserNo
	JOIN Users u2 ON i.EvaluatorID = u2.UserNo
	WHERE Bridges_BridgeNo IN (
		SELECT BridgeNo FROM Bridges WHERE BridgeName=bridge_name
	) AND
    YEAR(DATE(FinishedDate)) = report_year;
END 





 
