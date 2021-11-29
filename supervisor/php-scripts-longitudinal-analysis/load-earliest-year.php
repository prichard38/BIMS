<?php 
    
    include '../../dbConfig.inc.php';

    session_start();
        
    $bridgeNames = json_decode($_POST['bridgeNames']);
    

    $sql = "CALL getEarliestYear(".(string) json_encode($bridgeNames[0]);
   
    for($x = 1; $x < sizeof($bridgeNames); $x++){
        $sql = $sql . "," . (string) json_encode($bridgeNames[$x]);
    }
    if(sizeof($bridgeNames) < 3){
        $diff = 3 - sizeof($bridgeNames);
        for($x = 0; $x < $diff; $x++){
            $sql = $sql . ",\"\"";
        }
    }
    $sql = $sql . ");";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "SQL statement failed";
    } else{
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo $row['year'];
            }
        } else {
            echo false;
        }
    
    }
    
?>
