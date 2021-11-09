DELIMITER$$

CREATE PROCEDURE selectNewestInspectionData_ByYear(IN inspec_year int)
BEGIN
	/* Select From 2 tables */
	/* First Table is all Inspections */
	SELECT Inspections.* FROM Inspections, 
	/* Second Table is Newest Date in parameter year for each bridge number */
	(SELECT Bridges_BridgeNo, MAX(FinishedDate) AS newest_date FROM Inspections WHERE year(FinishedDate)=inspec_year GROUP BY Bridges_BridgeNo) newest_reports
	/* Grab inspections data from all inspection table where the inspections are included in the second table */
	WHERE Inspections.Bridges_BridgeNo=newest_reports.Bridges_BridgeNo
	AND Inspections.FinishedDate=newest_reports.newest_date;
END$$

DELIMITER;

