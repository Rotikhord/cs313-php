<?php

	//Check if this table is being called as a child table
	if(isset($parentKey) && $parentKey != 0){
		$isChildTable = true;
		//Add a modifier to active HTML elements for Javascript handling.
		$modifier = 'Child';
		$whereClause = " WHERE pun_job_fk = $parentKey ";
	} else {
		$isChildTable = false;
		$modifier = '';		
		$whereClause = '';
		//If not, Remember this table as the current table the currently displayed list
		$_SESSION['displayedList'] = "PunchList";
	}


  require_once 'database.php';


  $sortColumn = 0;
  $sortDirection = "ASC"; //Sort oldest to newest by default

  //Check to see if this is a sort request & get the column to be sorted by
  if (isset($_GET['sortColumn'])){
    $sortColumn = intval($_GET['sortColumn']);
  }
  //Check to see if sort direction was set and if it's set to "true"
  if(isset($_GET['sortAscending']) && $_GET['sortAscending'] == 'false'){
    $sortDirection = "DESC";
  }

  ?>
  <div class="listArea">
    <div class='listHeader'>
      <div class="listSearchArea">
        <div class='listInputBlock'>
		  <?php 
		  echo "<label for='listSearch$modifier'>Search:</label>";
		  echo "<input id='listSearch$modifier' type='text' oninput='searchList(this)'><br>";
		  ?>
        </div>       
      </div>
      <div class="listFilterArea" >
        <div class='listInputBlock'>
          <label for="showInactive">Display Inactive:</label>
          <input id="showInactive" type="checkbox" name="showInactive"><br>
        </div>
        <div class='listInputBlock'>
          <label for="showAssigned">Only Show Assigned:</label>
          <input id="showAssigned" type="checkbox" name="showAssigned">
        </div>
      </div>
    </div>
    <div id="listTableArea">
      <table>
		<?php
		//Child tables don't need to display as much data
		if (!$isChildTable){
			$colSpan = 6;
		} else {
			$colSpan = 4;
		}
		echo "<tr id='titleRow'><th colspan='$colSpan'><div id='tableTitleDiv'><span id='addNew' onclick='addNew$modifier()'>Add New Item</span><span id='tableTitle'>Escalated Items</span></div></th>
			 </tr><tr id='headerRow'><th class='sortable' onclick='getSort$modifier(1)'>Customer</th>";
		
		if(!$isChildTable){
			echo	"<th class='sortable' onclick='getSort(2)'>Job Number</th>
					 <th>Summary</th>
					 <th class='sortable' onclick='getSort(4)'>Balance</th>";
		} else {
			echo "<th>Summary</th>";
		}
		
		echo	"<th class='sortable' onclick='getSort$modifier(5)'>Escalated</th><th class='sortable' onclick='getSort$modifier(6)'>Last Update</th></tr>";


        //connect to the database and make a query
        $db = new Database();

        //Get the order by string for the SQL query
        $orderBy;
        switch($sortColumn){
          case 1:
            $orderBy = "cus_name";
            break;
          case 2:
            $orderBy = "job_number";
            break;
          case 4: //It doesn't make sense to order by summary field
            $orderBy = "job_balance";
            break;
          case 6:
            $orderBy = "pun_last_update";
            break;
          default: //We want to order by age of punchlist items by default
            $orderBy = "pun_date";
          }
          $query = $db->getDB()->prepare("SELECT pun_pk, COALESCE(cus_company_name, (SELECT cus_contact_lname || ', ' || cus_contact_fname)) AS cus_name,
                  job_number, pun_summary, job_balance, pun_date, pun_last_update FROM punchlog
                  INNER JOIN job ON pun_job_fk = job_pk
                  INNER JOIN customer on job_cus_fk = cus_pk
				  $whereClause
                  ORDER BY $orderBy $sortDirection");
          if ($query->execute()){
            $result = $query->fetchAll();
            for ($i = 0; $i < sizeof($result); $i++){
				echo "\t<tr onclick='getDetails$modifier(this)'  onmouseenter='highlightRow(this)' onmouseleave='unhighlightRow(this)' class='listRecord$modifier' id='record" . $result[$i]['pun_pk'] . "'>\n";
				if (!$isChildTable){
					echo "\t\t<td class='col1'>" . $result[$i]['cus_name'] . "</td>\n";
					echo "\t\t<td class='col2'>" . $result[$i]['job_number'] . "</td>\n";
					echo "\t\t<td class='col3'>" . $result[$i]['pun_summary'] . "</td>\n";
					echo "\t\t<td class='col4'>" . $result[$i]['job_balance'] . "</td>\n";
				} else {					
					echo "\t\t<td class='col1'>" . $result[$i]['cus_name'] . "</td>\n";
					echo "\t\t<td class='col3'>" . $result[$i]['pun_summary'] . "</td>\n";
				}
              echo "\t\t<td class='col5'>" . $result[$i]['pun_date'] . "</td>\n";
              echo "\t\t<td class='col6'>" . $result[$i]['pun_last_update'] . "</td>\n";
              echo "\t</tr>";
            }
          } else {
            echo "<tr><th colspan='$colSpan'> An error occured while connecting to the database... </th></tr>";
          }
		  if (sizeof($result) == 0 && $isChildTable){
			echo "<tr><th colspan='$colSpan'> There are no punchlist items for this job. </th></tr>";		  
		  } else if (sizeof($result) == 0){
		  	echo "<tr><th colspan='$colSpan'> No punchlist items exist in the database. </th></tr>";
		  }

      ?>
    </table>
    </div>
  </div>
