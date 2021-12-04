<?php 
    include '../../dbConfig.inc.php';

      
    $sql = "SELECT BridgeName, BridgeNo, CountyName FROM Bridges JOIN County ON County.CountyNo = Bridges.County_CountyNo";
    
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "SQL statement failed";
    } else{
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $bridgeName = $row["BridgeName"];
                $bridgeNo = $row["BridgeNo"];
                $countyName = $row["CountyName"];
                $bridgeData[] = array('bridgeName' => $bridgeName,
                                        'bridgeNo' => $bridgeNo,
                                        'countyName' => $countyName);
            }
            $jsonBridgeData = "{\"data\":";
            $jsonBridgeData .= json_encode($bridgeData);
            $jsonBridgeData .= "}";
            echo $jsonBridgeData;
        } 
        else {
            echo "TEST";
        }
    }

?>