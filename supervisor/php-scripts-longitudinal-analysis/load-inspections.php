<?php 
    session_start();
    
    include '../../dbConfig.inc.php';

    $name = mysqli_real_escape_string($conn, $_POST['selectedBridgeName']);
    
    $sql = "CALL selectBridgeInspectionData_BetweenYears(?,".(string) json_encode($_SESSION['yearBegin']).",".(string) json_encode($_SESSION['yearEnd']).");";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "SQL statement failed";
    } else{
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $finishedDate = $row["FinishedDate"];
                $bridgeNo = $row["Bridges_BridgeNo"];
                $bridgeName = $row["BridgeName"];
                $inspectionTypeName = $row["InspectionTypeName"];
                $assignedTo = $row["inspector_first"]." ".$row["inspector_last"];
                $assignedBy = $row["evaluator_first"]." ".$row["evaluator_last"];
                $rating = $row["OverallRating"];
                $bridgeElements = null;
                $report = null;
                $bridges[] = array('finishedDate' => $finishedDate,
                                   'bridgeNo' => $bridgeNo,
                                   'bridgeName' => $bridgeName,
                                   'inspectionTypeName' => $inspectionTypeName,
                                   'assignedTo' => $assignedTo,
                                   'assignedBy' => $assignedBy,
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
