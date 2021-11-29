<?php

    session_start();
    include 'dbConfig.inc.php';
    $name = mysqli_real_escape_string($conn, $_POST['selectedYear']);
    
    $sql = "CALL selectNewestInspectionData_ByYear(?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "SQL statement failed";
    } else{
        mysqli_stmt_bind_param($stmt, 'i', $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $bridgeNo = $row["Bridges_BridgeNo"];
                $bridgeName = $row["BridgeName"];
                $status = $row["Status"];
                $inspectionTypeName = $row["InspectionTypeName"];
                $assignedTo = $row["inspector_first"]." ".$row["inspector_last"];
                $assignedBy = $row["evaluator_first"]." ".$row["evaluator_last"];
                $finishedDate = $row["FinishedDate"];
                $dueDate = $row["DueDate"];
                $rating = $row["OverallRating"];
                $bridgeElements = null;
                $report = null;
                $bridges[] = array( 'bridgeNo' => $bridgeNo,
                                    'bridgeName' => $bridgeName,
                                    'status' => $status,
                                    'inspectionTypeName' => $inspectionTypeName,
                                    'assignedTo' => $assignedTo,
                                    'assignedBy' => $assignedBy,
                                    'finishedDate' => $finishedDate,
                                    'dueDate' => $dueDate,
                                    'rating' => $rating,
                                    'bridgeElements' => $bridgeElements,
                                    'report' => $report);
            }
            $jsonBridges = "{\"data\":";
            $jsonBridges .= json_encode($bridges);
            $jsonBridges .= "}";
            echo $jsonBridges;
        } 
        else {
            echo false;
        }
    
    }

?>
