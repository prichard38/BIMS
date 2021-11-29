/* Current best query but didn't take non-started inspections into account
	Also need to grab the Status row */
/*
DELIMITER $$

CREATE PROCEDURE selectNewestInspectionData_ByYear(IN inspec_year int)
BEGIN
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

DELIMITER;
*/

/*
	Temporary Query for demo today 11-29 
	works fine but need to ensure the group by grabs the newest inspection for final project
*/
DELIMITER $$

CREATE PROCEDURE selectNewestInspectionData_ByYear(IN inspec_year int)
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
	) AS inspection_data
    GROUP BY Bridges_BridgeNo;
END$$

DELIMITER;

/*
Old unfinished query. 
Worked fast to get newest report for each bridge in one given year.
However, I could not get joins to work, so it was replaces with the second, slower method for now. 
*/
/*
CREATE PROCEDURE selectNewestInspectionData_ByYear(IN inspec_year int)
BEGIN
	SELECT Inspections.* FROM Inspections, 
	(SELECT Bridges_BridgeNo, MAX(FinishedDate) AS newest_date FROM Inspections WHERE year(FinishedDate)=inspec_year GROUP BY Bridges_BridgeNo) newest_reports
	WHERE Inspections.Bridges_BridgeNo=newest_reports.Bridges_BridgeNo
	AND Inspections.FinishedDate=newest_reports.newest_date;
END$$
*/


