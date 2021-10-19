SELECT 
inspections.InspectionID,inspections.Bridges_bridgeNo,inspections.OverallRating,inspections.AssignedDate,inspections.FinishedDate,users.firstname AS eval_first, users.lastname as eval_last
FROM users, inspections WHERE UserNo IN  
(
    SELECT EvaluatorID FROM Inspections WHERE Bridges_BridgeNo IN 
    (
        SELECT BridgeNo FROM bridges
        WHERE bridgename LIKE 'Alderson Bridge over Greenbrier River'
    )
);
SELECT users.firstname AS insp_first, users.lastname as insp_last
FROM users, inspections WHERE UserNo IN  
(
    SELECT InspectorID FROM Inspections WHERE Bridges_BridgeNo IN 
    (
        SELECT BridgeNo FROM bridges
        WHERE bridgename LIKE 'Alderson Bridge over Greenbrier River'
    )
);