<?php 
    include 'dbConfig.inc.php';

      
    $sql = "SELECT BridgeName FROM Bridges";
    
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "SQL statement failed";
    } else{
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $bridgeName = $row["BridgeName"];

                $bridgeNames[] = array('bridgeName' => $bridgeName);
            }
            $jsonBridgeNames = "{\"data\":";
            $jsonBridgeNames .= json_encode($bridgeNames);
            $jsonBridgeNames .= "}";
            echo $jsonBridgeNames;
        } 
        else {
            echo "TEST";
        }
    }









    
?>
