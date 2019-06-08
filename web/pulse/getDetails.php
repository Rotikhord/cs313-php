<?php
    session_start();

    if (isset($_GET["record"])){

      if ($_SESSION['displayedList'] == 'Employee'){
        include 'Details/employeeDetails.php';
      } else if ($_SESSION['displayedList'] == 'Customer'){
        include 'Details/customerDetails.php';
      } else if ($_SESSION['displayedList'] == 'Job') {
        include 'Details/jobDetails.php';
      } else if ($_SESSION['displayedList'] == "PunchList"){
        include 'Details/punchDetails.php';
      } else {
        die();
        }

      } else {

      die();
    }

?>
