<?php
session_start();

if(isset($_POST['selectedBridgeNames']) && isset($_POST['selectedBridgeNumbers']) && isset($_POST['selectedBridgeCounties'])){

    $_SESSION['selectedBridgeNames'] = json_decode($_POST['selectedBridgeNames']);
    $_SESSION['selectedBridgeNumbers'] = json_decode($_POST['selectedBridgeNumbers']);
    $_SESSION['selectedBridgeCounties'] = json_decode($_POST['selectedBridgeCounties']);
    $_SESSION['hasSavedState'] = true;

    echo true;
} else{
    echo false;
}