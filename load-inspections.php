<?php 
    include 'dbConfig.inc.php';

    $name = mysqli_real_escape_string($conn, $_POST['selectedBridgeName']);

   
    $sql = "SELECT FinishedDate, Bridges.BridgeName, Bridges.BridgeNo, InspectionTypeName, OverallRating
        FROM Bridges, Inspections, InspectionTypeCode 
        WHERE Bridges.BridgeName LIKE ? AND Inspections.Bridges_BridgeNo = Bridges.BridgeNo AND Inspections.InspectionTypeNo = InspectionTypeCode.InspectionTypeNo;";
    
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
                $bridgeNo = $row["BridgeNo"];
                $bridgeName = $row["BridgeName"];
                $inspectionTypeName = $row["InspectionTypeName"];
                $assignedTo = 'Liam Davis';
                $assignedBy = 'Irene Song';
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
            $filename = 'bridgeData.json';
    
            if (!($file = fopen($filename, 'w+'))) {
                echo "Cannot open file ($filename)";
                exit;
           }

           if (fwrite($file, $jsonBridges) === FALSE) {
            echo "Cannot write to file ($filename)";
            exit;
            }

            fclose($file);

        } 
        else {
            echo "No Inspections Found";
        }
    }









    
?>