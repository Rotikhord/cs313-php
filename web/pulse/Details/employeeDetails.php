<?php
  require_once 'database.php';


  if (!isset($record)){
    //Value of zero indicates new record otherwise holds pk value
    $record = intval($_GET["record"]);
  }

    //indicates whether to display empty fields
    $displayEmpty = false;
    $checked = ""; //place holder for checked value
    if(isset($_GET['displayEmpty']) && $_GET['displayEmpty'] == 'true'){
      $displayEmpty = true;
      $checked = "checked";
    }


    //indicates whether this is to be displayed within the details section of a parent record. default is false
    if(isset($isChildDetails) && $isChildDetails == 'true'){
      $childDetails = true;
	  $childDetailStr = 'Child';
    } else {
		$parentType = 'employee';
		$isChildDetails = false;
		$childDetailStr = '';
	}

    //connect to the database and make a query
    $db = new Database();
    $query = $db->getDB()->prepare("SELECT * FROM employee
            WHERE emp_pk = $record");
    if ($query->execute()){
      $result = $query->fetch(PDO::FETCH_ASSOC);
    }

    //Parent info displayed only when not child-details or not new record
    if ($record == 0 ){
      echo "<div class='detailInnerBlock'><h4>Add Employee:</h4> </div>";
    }
    //Using 1 echo over multiple lines to improve readablity
    echo "<div class='detailBlock' id='employeeHeader'>
            <div class='detailInnerBlock' id='detailOptions'>
              <div class='detailInputBlock'>
                <label for='showEmpty'>Display Empty Fields:</label>
                <input id='showEmpty' type='checkbox' name='showEmpty' $checked onclick='getDetails(this)'><br>
              </div>
            </div>
            <div class='detailInnerBlock'>
              <h4> Employee Info:</h4>";

    //Add fields and labels to Description Block
    $blockFields = array('emp_fname', 'emp_lname', 'emp_email', 'emp_permissions');
    $blockLabels = array('First Name', 'Last Name', 'Email Address', 'Permissions');
    //The permissions level at which the field becomes editable.
    $fieldEditable =  array(10, 10, 10, 10);
    if($record == 0){
      $fieldEditable =  array(8, 8, 8, 8);
    }
    //The type & validation for each field
    $fieldType = array('text', 'text', 'email', 'text');//, 'text', 'text');

    include "populateDetail.php";

    if ($_SESSION['permissions'] >= 10 || ($_SESSION['permissions'] >= 8 && $record == 0 )){
      echo "<label for='emp_hash'>Enter New Password:</label><br>
            <input class='editable' id='emp_hash' type='password'><br>";
    }


     echo "</div></div>";
    ?>
    <div id="detailButtons" class="detailBlock">
      <?php
        //Add different buttons depending on whether this is an add or update action
        if($record != 0){
          //javascript to use ID to note which php file to call.
          echo "<button id='emp_pk' class='detailButton' value='" . $result['emp_pk'] . "' onclick='update(this)'>Save Changes</button>";
        } else {
          echo "<button id='emp_pk' class='detailButton' value='0' onclick='update(this)'>Create Record</button>";
        }

		  echo "<button class='detailButton' onclick='getDetails$childDetailStr(this)'>Cancel</button>";
		  ?>
    </div>

	<?php
	//This is the area for child records 
	if(!$isChildDetails && $record != 0){
		echo "<div class='detailBlock' id='detailChildBlock'>";
		$parentKey = $result['emp_pk'];
		include 'Lists/assignmentList.php';
		echo "</div>";
	}
	?>
</div>
