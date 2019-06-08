<?php
    session_start();
    if (isset($_GET["list"])){
      if($_GET["list"] == "Punchlist"){
        include "Lists/punchList.php";
      } else if($_GET["list"] == "Employees"){
        include "Lists/employeeList.php";
      } else if($_GET["list"] == "Jobs"){
        include "Lists/jobList.php";
      } else if($_GET["list"] == "Customers"){
        include "Lists/customerList.php";
      } else if($_GET["list"] == "same") {

        if ($_SESSION['displayedList'] == 'Employee'){
          include 'Lists/employeeList.php';
        } else if ($_SESSION['displayedList'] == 'Customer'){
          include 'Lists/customerList.php';
        } else if ($_SESSION['displayedList'] == 'Job'){
          include 'Lists/jobList.php';
        } else if ($_SESSION['displayedList'] == "PunchList"){
          include 'Lists/punchList.php';
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
