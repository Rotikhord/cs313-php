<?php
  session_start();
  //Make sure current session is valid
  if (!isset($_SESSION['user'])){
    $_SESSION['badSession'] = true;
    header("Location: login.php");
    die();
  }
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pulse Workbench</title>
  <meta name="description" content="A project tracking application">
  <meta name="keywords" content="">
  <meta name="author" content="Doug Barlow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="pulse.css">
  <script src="pulseWorkbench.js"></script>
</head>
<body>
  <?php
    include 'header.php';
  ?>
  <div id="contentList">
  <?php
    if (isset($_SESSION['displayedList'])){
      if ($_SESSION['displayedList'] == 'Employee'){
        include 'Lists/employeeList.php';
      } else if ($_SESSION['displayedList'] == 'Customer'){
        include 'Lists/customerList.php';
      } else if ($_SESSION['displayedList'] == 'Job'){
        include 'Lists/jobList.php';
      } else if ($_SESSION['displayedList'] == "PunchList"){
        include 'Lists/punchList.php';
      }
  } else {
    //Default is punchlist
    include 'Lists/punchList.php';
  }
  ?>
  </div>
  <div id="contentDetails">
  </div>
</body>
</html>
