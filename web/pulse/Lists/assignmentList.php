<?php
/***********************************
 * Displays updates for a given punchlist item
 *********************************/
	//Check to make sure parentType is specified
	if(!isset($parentType)){
		die('Error loading assignments. Parent record type was un-specified');
	}

	//Check if this table is being called as a child table
	if(isset($parentKey)){
		$isChildTable = true;
		//Add a modifier to active HTML elements for Javascript handling.
		$modifier = 'Child';
	} else {
		$isChildTable = false;
		$modifier = '';		
		$whereClause = '';
		//This should only be called as a child
		die('No parent record was specified');
	}

	//Set the where clause
	if ($parentType == 'employee'){		
		$whereClause = " WHERE asi_emp_fk = '$parentKey' AND pun_complete_date IS null";
	} else {
		$whereClause = " WHERE asi_pun_fk = '$parentKey'AND pun_complete_date IS null";
	}


  require_once 'database.php';

  ?>
  <div id="listArea">
    <div id="listTableArea">
      <table>
		<?php
		//Different parents require different data
		$colSpan = 3;

		echo "<tr id='titleRow'><th colspan='$colSpan'><div id='tableTitleDiv'>
				<span id='tableTitle'>Assignments</span></div></th>
			 </tr>
			 <tr id='headerRow'>
				<th colspan='2'>Assigned</th>
				<th></th></tr>";


        //connect to the database and make a query
        $db = new Database();

        //Get the order by string for the SQL query
		
          
        $query = $db->getDB()->prepare("SELECT asi_pk, emp_fname, emp_lname, pun_summary, job_number,  COALESCE(cus_company_name, (SELECT cus_contact_lname || ', ' || cus_contact_fname)) AS cus_name FROM assignment
                  INNER JOIN employee on asi_emp_fk = emp_pk
                  INNER JOIN punchlog on asi_pun_fk = pun_pk
                  INNER JOIN job on pun_job_fk = job_pk
                  INNER JOIN customer on job_cus_fk = cus_pk
				  $whereClause");
        if ($query->execute()){
			$result = $query->fetchAll();
            for ($i = 0; $i < sizeof($result); $i++){

				if($parentType == 'employee'){
					$displayData = $result[$i]['cus_name'] . " - " . $result[$i]['pun_summary'];
				} else {
					$displayData = $result[$i]['emp_lname'] . ", " . $result[$i]['emp_fname'];
				}

				echo "\t<tr onmouseenter='highlightRow(this)' onmouseleave='unhighlightRow(this)' class='listRecord$modifier' id='record" . $result[$i]['asi_pk'] . "'>\n";
				echo "\t\t<td colspan='2' class='col1'>$displayData</td>\n";
				echo "\t\t<td class='col2'><button id='asi_pk' class='detailButton' value='". $result[$i]['asi_pk'] ."' onclick='deleteAssign(this)'>Delete Assignment</button>";
                echo "\t</tr>";
            }
        } else {
            echo "<tr><th colspan='$colSpan'> An error occured while connecting to the database... </th></tr>";
        }
		if (sizeof($result) == 0 && $isChildTable){
			echo "<tr><th colspan='$colSpan'> There are no updates for this punchlog item. </th></tr>";		  
		} else if (sizeof($result) == 0){
		  	echo "<tr><th colspan='$colSpan'> No punchlist updates exist in the database. </th></tr>";
		}
		echo "<tr><td colspan='2'>";

		if($parentType == 'employee'){
			echo "<select class='editableChildField' id='asi_pun_fk'><option value='-1' selected='true' disabled='true' hidden='true'>New Assignment</option>";
			$query = $db->getDB()->prepare("SELECT pun_pk, asi_pun_fk, asi_emp_fk, pun_summary, job_number,  COALESCE(cus_company_name, (SELECT cus_contact_lname || ', ' || cus_contact_fname)) AS cus_name FROM punchlog
                  INNER JOIN job on pun_job_fk = job_pk
                  INNER JOIN customer on job_cus_fk = cus_pk
				  LEFT JOIN assignment a on pun_pk = asi_pun_fk
				  WHERE pun_complete_date IS null AND (asi_emp_fk IS NULL OR (asi_emp_fk IS NOT NULL AND asi_emp_fk != $parentKey)) AND NOT EXISTS (SELECT asi_pk from assignment b where a.asi_pun_fk = b.asi_pun_fk and b.asi_emp_fk = $parentKey)");
				  
			if ($query->execute()){
				$result = $query->fetchAll();
				for ($i = 0; $i < sizeof($result); $i++){
					echo "<option value='" . $result[$i]['pun_pk'] . "'>" . $result[$i]['cus_name'] . " - " . $result[$i]['pun_summary'] . "</option>";
				}
			}
			
			echo "</td><td><button id='assign_emp_fk' class='detailButton' value='0' onclick='updateChild(this)'>Add Assignment</button></td></tr>";

		} else {
		   echo "else;";
			echo "<tr><td><select class='editableChildField' id='asi_emp_fk'><option value='-1' selected='true' disabled='true' hidden='true'>New Assignment</option>";
			$query = $db->getDB()->prepare("SELECT emp_pk, emp_fname, emp_lname FROM employee
				  LEFT JOIN assignment on emp_pk = asi_emp_fk
				  WHERE emp_permissions > 0 AND (asi_pun_fk IS NULL OR (asi_pun_fk IS NOT NULL AND asi_pun_fk != $parentKey)) AND NOT EXISTS (SELECT asi_pk from assignment b where a.asi_emp_fk = b.asi_emp_fk and b.asi_pun_fk = $parentKey)");
			if ($query->execute()){
				$result = $query->fetchAll();
				for ($i = 0; $i < sizeof($result); $i++){
					echo "<option value='" . $result[$i]['emp_pk'] . "'>" . $result[$i]['emp_lname'] . ", " . $result[$i]['emp_fname'] . "</option>";
				}
			}
			
			echo "</td><td><button id='assign_pun_fk' class='detailButton' value='0' onclick='updateChild(this)'>Add Assignment</button></td></tr>";

		}



		  ?>
    </table>
    </div>
