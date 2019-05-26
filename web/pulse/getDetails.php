<?php
    session_start();

    if (isset($_GET["record"])){

      if ($_SESSION['displayedList'] == 'Employee'){
        include 'employeeDetails.php';
      } else if ($_SESSION['displayedList'] == 'Customer'){
        include 'customerDetails.php';
      } else if ($_SESSION['displayedList'] == 'Job') {
        include 'jobDetails.php';
      } else if ($_SESSION['displayedList'] == "PunchList"){
        include 'punchDetails.php';
      } else {
        die();
        }

      } else {

      die();
    }

?>
