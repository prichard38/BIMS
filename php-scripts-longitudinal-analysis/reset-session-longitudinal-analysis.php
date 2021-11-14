<?php 
    session_start();

    unset($_SESSION['selectedBridgeNames']);
    unset($_SESSION['selectedBridgeNumbers']);
    unset($_SESSION['selectedBridgeCounties']);
    unset($_SESSION['yearBegin']);
    unset($_SESSION['yearEnd']);
    unset($_SESSION['hasSavedState']);

    header("Location: ../user-search-params-longitudinal-analysis.php");
