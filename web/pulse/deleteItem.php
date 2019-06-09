<?php

session_start();
require_once 'database.php';
$db = new Database();

if (isset($_POST['asi_pk']) && $_SESSION['permissions'] >= 8){
	$query = $db->getDB()->prepare("SELECT * FROM assignment WHERE asi_pk = " . intval($_POST['asi_pk']));
	$query->execute();
	$result = $query->fetch(PDO::FETCH_ASSOC);

	$asi_pun_fk = $result['asi_pun_fk'];
	$asi_emp_fk = $result['asi_emp_fk'];

	$query = $db->getDB()->prepare("DELETE FROM assignment WHERE asi_pk = " . intval($_POST['asi_pk']));
    if ($query->execute()){
		echo "Assignment Deleted Successfully.";
		if ($_SESSION['displayedList'] == 'Employee'){
			$parentKey = $asi_emp_fk;
			$parentType = 'employee';
		} else {
			$parentKey = $asi_pun_fk;
			$parentType = 'punchlog';
		}
        include 'Lists/assignmentList.php';
    } else {
      echo "There was an error connecting to the database.<br>";
      die();
    }
  }

 ?>
