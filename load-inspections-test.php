<?php 
    session_start();
    $_SESSION["bridgeNames"] = ['Cane Hill Bridge over Little Red River', 'Robert C. Byrd Bridge over Ohio River', 'East Huntington Bridge over Ohio River'];

    include 'dbConfig.inc.php';

    $bridgeNames = $_SESSION['bridgeNames'];

    $message = '';

   for($i=0; $i<count($bridgeNames); $i++){

       $sql = "SELECT FinishedDate, Bridges.BridgeName, Bridges.BridgeNo, InspectionTypeName, OverallRating
           FROM Bridges, Inspections, InspectionTypeCode 
           WHERE Bridges.BridgeName LIKE ? AND Inspections.Bridges_BridgeNo = Bridges.BridgeNo AND Inspections.InspectionTypeNo = InspectionTypeCode.InspectionTypeNo
           ORDER BY FinishedDate DESC;";
       
       $stmt = mysqli_stmt_init($conn);
       if(!mysqli_stmt_prepare($stmt, $sql)){
           echo "SQL statement failed";
       } else{
           mysqli_stmt_bind_param($stmt, "s", $bridgeNames[$i]);
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
   
                   $inspections[] = array('finishedDate' => $finishedDate,
                                      'bridgeNo' => $bridgeNo,
                                      'bridgeName' => $bridgeName,
                                      'inspectionTypeName' => $inspectionTypeName,
                                      'assignedTo' => $assignedTo,
                                      'assignedBy' => $assignedBy,
                                      'rating' => $rating,
                                      'bridgeElements' => $bridgeElements,
                                      'report' => $report);
               }
               $jsonInspections = "{\"data\":";
               $jsonInspections .= json_encode($inspections);
               $jsonInspections .= "}";
               // reset inspections array for next bridge
               $inspections = array();

               $filename = 'bridge' . ($i+1) . 'Data.json';
       
               if (!($file = fopen($filename, 'w+'))) {
                   echo "Cannot open file ($filename)";
                   exit;
               }
       
               if (fwrite($file, $jsonInspections) === FALSE) {
               echo "Cannot write to file ($filename)";
               exit;
               }
       
               fclose($file);
               
               $_SESSION["hasData_bridge" . $i+1] = 1; 
           } 
           else {
               $_SESSION["hasData_bridge" . $i+1] = 0; 
               $message .= "No inspections found for " . $bridgeNames[$i] . " (bridge" . $i+1 . ")\n";
           }
           echo $message;
   
           
       }
   }
?>