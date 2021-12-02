DELIMITER $$

/*
    Used to check a username and password submitted in login.php
*/
CREATE PROCEDURE logIn(IN user_id VARCHAR(45), IN user_password VARCHAR(45))
BEGIN

	SELECT r.UserRoleName, u.UserId, u.UserPassword
    FROM Users u
    JOIN UserRole r ON u.UserRole = r.UserRole
    WHERE UserId = user_id COLLATE utf8_unicode_ci
	AND UserPassword = user_password COLLATE utf8_unicode_ci; 

END$$

/*
    Get data for the tables of the Yearly Inspection Report tool for one year
*/
CREATE PROCEDURE selectBridgeInspectionData_OneYear(IN inspec_year int)
BEGIN
SELECT *
	FROM (
		SELECT i.FinishedDate, i.DueDate, i.Bridges_BridgeNo, b.BridgeName, i.Status, t.InspectionTypeName ,u.FirstName AS inspector_first, 
		u.LastName AS inspector_last, u2.FirstName AS evaluator_first, u2.LastName AS evaluator_last, i.OverallRating
		FROM Inspections i 
		JOIN Bridges b ON i.Bridges_BridgeNo = b.BridgeNo
		JOIN InspectionTypeCode t ON i.InspectionTypeNo = t.InspectionTypeNo
		JOIN Users u ON i.InspectorID = u.UserNo
		JOIN Users u2 ON i.EvaluatorID = u2.UserNo
		WHERE YEAR(DATE(FinishedDate)) = inspec_year
        OR YEAR(DATE(DueDate)) = inspec_year
	) AS inspection_data;
END$$

/*
	Procedure to select data for the Longitudunal Analysis tool tables.
    Selects inspections for one bridge in the given timeframe. This is called multiple times if user wants inspections on multiple bridges. 
*/
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
		SELECT BridgeNo FROM Bridges WHERE BridgeName=bridge_name COLLATE utf8_unicode_ci
	) 
    AND YEAR(DATE(FinishedDate)) >= begin_year
    AND YEAR(DATE(FinishedDate)) <= end_year
	ORDER BY i.FinishedDate;
END$$

/*
	Procedure to get the earliest inspection from up to 3 bridges.
	Used on first Longitudinal Analyis screen where user selects bridges and timeframe to be used. 
*/
CREATE PROCEDURE getEarliestYear(IN bridge1 VARCHAR(45), IN bridge2 VARCHAR(45), IN bridge3 VARCHAR(45))
BEGIN
SELECT MIN(YEAR(DATE(FinishedDate))) as year
FROM Inspections
WHERE Bridges_BridgeNo IN (
	SELECT BridgeNo FROM Bridges WHERE BridgeName=bridge1 COLLATE utf8_unicode_ci
    OR BridgeName=bridge2 COLLATE utf8_unicode_ci
    OR BridgeName=bridge3 COLLATE utf8_unicode_ci
);
END$$







