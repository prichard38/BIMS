<?php
    session_start();
    
    if (isset($_POST['Username']) && isset($_POST['Password'])){ //if user has entered a username and password

        $username = $_POST['Username'];
        $password = $_POST['Password'];

        include 'dbConfig.inc.php';

        //get the role of the user. If user is not in database, then the role is NULL
        $result = $conn->query("CALL logIn('$username','$password');");
        $row = $result->fetch_assoc();

        //if user is admin, start session and redirect
        if ($row['UserRoleName'] == "admin"){
            session_destroy();
            session_start();
            session_unset();
            $_SESSION["loggedAs"] = "Admin";
            header("Location:admin/admin_report_management.html");
            exit('redirecting...');
        }
        else if ($row['UserRoleName'] == "supervisor"){  
            session_destroy();
            session_start();
            session_unset();
            $_SESSION["loggedAs"] = "Supervisor";
            header("Location:supervisor/supervisor-yearly-inspection-report.php",true,301);
            exit('redirecting...');

        }
        else if ($row['UserRoleName'] == "inspector"){
            session_destroy();
            session_start();
            session_unset();                
            $_SESSION["loggedAs"] = "Inspector";
            header("Location:inspector/inspector_inspection_management.html");
            exit('redirecting...');
        } 
        // if user is NULL, display error
        else {
            header("Location:login.html?error=invalidcredentials");
            exit('redirecting...');
        }
    }
    else{
        header("Location:login.html?error=invalidcredentials");
        exit('redirecting...');
    }
?>