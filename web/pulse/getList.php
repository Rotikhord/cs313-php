<?php
    session_start();
    if (isset($_GET["list"])){
      if($_GET["list"] == "Punchlist"){
        include "punchList.php";
      } else if($_GET["list"] == "Employees"){
        include "employeeList.php";
      } else if($_GET["list"] == "Jobs"){
        include "jobList.php";
      } else if($_GET["list"] == "Customers"){
        include "customerList.php";
      } else if($_GET["list"] == "same") {

        if ($_SESSION['displayedList'] == 'Employee'){
          include 'employeeList.php';
        } else if ($_SESSION['displayedList'] == 'Customer'){
          include 'customerList.php';
        } else if ($_SESSION['displayedList'] == 'Job'){
          include 'jobList.php';
        } else if ($_SESSION['displayedList'] == "PunchList"){
          include 'punchList.php';
        } else {
          die();
        }
      } else {
        die();
      }
    } else {
      die();
    }

?>
