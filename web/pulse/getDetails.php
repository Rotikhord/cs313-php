<?php
    session_start();

    if (isset($_GET["record"])){
		
		if(isset($_GET['childDetails']) && $_GET['childDetails'] == 'true'){
			$isChildDetails = 'true';
			if ($_SESSION['displayedList'] == 'Employee'){
		       include 'Lists/assignmentList.php';
			} else if ($_SESSION['displayedList'] == 'Customer'){
				include 'Details/jobDetails.php';
			} else if ($_SESSION['displayedList'] == 'Job') {
				include 'Details/punchDetails.php';
			} else if ($_SESSION['displayedList'] == "PunchList"){
				include 'Lists/updateList.php';
			} else {
				die();
			}
		} else {
			$isChildDetails = 'false';
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
		}
	} else {
		die();
    }

?>
