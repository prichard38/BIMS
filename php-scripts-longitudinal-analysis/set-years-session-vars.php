<?php
session_start();


if(isset($_POST['yearBegin']) && isset($_POST['yearEnd'])){

    $_SESSION['yearBegin'] = json_decode($_POST['yearBegin']);
    $_SESSION['yearEnd'] = json_decode($_POST['yearEnd']);

    echo true;
} else{
    echo false;
}