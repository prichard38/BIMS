<?php 
    session_start();

    unset($_SESSION['selectedBridgeNames']);
    unset($_SESSION['selectedBridgeNumbers']);
    unset($_SESSION['selectedBridgeCounties']);
    unset($_SESSION['yearBegin']);
    unset($_SESSION['yearEnd']);

    header("Location: user-options-longitudinal-analysis.php");
