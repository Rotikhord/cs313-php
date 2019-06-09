<?php
/***********************************
 * Displays updates for a given punchlist item
 *********************************/
	
	//Check if this table is being called as a child table
	if(isset($parentKey)){
		$isChildTable = true;
		//Add a modifier to active HTML elements for Javascript handling.
		$modifier = 'Child';
		$whereClause = " WHERE upd_pun_fk = $parentKey ";
	} else {
		$isChildTable = false;
		$modifier = '';		
		$whereClause = '';
		//This should only be called as a child
		die('No parent record was specified');
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
  <div id="listArea">
    <div class='listHeader'>
      <div class="listSearchArea">
         <div class='listInputBlock'>
		  <?php 
		  echo "<label for='listSearch$modifier'>Search:</label>";
		  echo "<input id='listSearch$modifier' type='text' oninput='searchList(this)'><br>";
		  ?>
        </div>
      </div>
    </div>
    <div id="listTableArea">
      <table>
		<?php
		//Child tables don't need to display as much data
		if (!$isChildTable){
			$colSpan = 4;
		} else {
			$colSpan = 4;
		}
		echo "<tr id='titleRow'><th colspan='$colSpan'><div id='tableTitleDiv'>
				<span id='tableTitle'>Updates</span></div></th>
			 </tr>
			 <tr id='headerRow'>
				<th class='sortable' onclick='getSort$modifier(1)'>Update</th>				
				<th class='sortable' onclick='getSort$modifier(2)'>Description</th>				
				<th class='sortable' onclick='getSort$modifier(3)'>Payment</th>				
				<th class='sortable' onclick='getSort$modifier(4)'>Employee</th>";

		
		echo	"</tr>";


        //connect to the database and make a query
        $db = new Database();

        //Get the order by string for the SQL query
        $orderBy;
        switch($sortColumn){
          case 2:
            $orderBy = "upd_description";
            break;
          case 3:
            $orderBy = "upd_payment";
            break;
          case 4: //It doesn't make sense to order by summary field
            $orderBy = "emp_fname";
            break;
          default: //We want to order by age of punchlist items by default
            $orderBy = "upd_timestamp";
          }
          $query = $db->getDB()->prepare("SELECT upd_pk, upd_timestamp, upd_description, upd_payment, emp_fname FROM update
                  INNER JOIN employee on upd_emp_fk = emp_pk
				  $whereClause
                  ORDER BY $orderBy $sortDirection");
          if ($query->execute()){
            $result = $query->fetchAll();
            for ($i = 0; $i < sizeof($result); $i++){
				echo "\t<tr onclick='getDetails$modifier(this)'  onmouseenter='highlightRow(this)' onmouseleave='unhighlightRow(this)' class='listRecord$modifier' id='record" . $result[$i]['upd_pk'] . "'>\n";
				echo "\t\t<td class='col1'>" . $result[$i]['upd_timestamp'] . "</td>\n";
				echo "\t\t<td class='col2'>" . $result[$i]['upd_description'] . "</td>\n";
				echo "\t\t<td class='col3'>" . $result[$i]['upd_payment'] . "</td>\n";
				echo "\t\t<td class='col4'>" . $result[$i]['emp_fname'] . "</td>\n";
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
		  ?>
    </table>
    </div>
	<div id='listFooter'>
		<div id='addSubRecord' class="detailInnerBlock">
			<h4>Add update:</h4>
			<label for="upd_description">Update Description:</label><br>
			<textarea autocomplete="off" id='upd_description' class='editableChildField' placeholder='Description'></textarea></br>
			<label for="upd_payment">Payment:</label><br>
			<input autocomplete="off" type='number' id='upd_payment' class='editableChildField' placeholder='$0.00'>
		</div>
		<div id="detailButtons" class="detailInnerBlock">
			<button id='upd_pk' class='detailButton' value='0' onclick='updateChild(this)'>Create Update</button>
			<button class='detailButton' onclick='clearChildFields(this)'>Cancel</button>
		</div>
  </div>
