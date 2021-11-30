DELIMITER$$

CREATE PROCEDURE selectBridgeInspectionData_BetweenYears(IN bridge_name VARCHAR(45), IN begin_year int, IN end_year int)
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
	) 
    AND YEAR(DATE(FinishedDate)) >= begin_year
    AND YEAR(DATE(FinishedDate)) <= end_year
	ORDER BY i.FinishedDate;
END$$

DELIMITER;

DELIMITER$$

CREATE PROCEDURE getEarliestYear(IN bridge1 VARCHAR(45), IN bridge2 VARCHAR(45), IN bridge3 VARCHAR(45))
BEGIN
SELECT MIN(YEAR(DATE(FinishedDate))) as year
FROM Inspections
WHERE Bridges_BridgeNo IN (
	SELECT BridgeNo FROM Bridges WHERE BridgeName=bridge1
    OR BridgeName=bridge2
    OR BridgeName=bridge3
);
END$$

DELIMITER;








 
