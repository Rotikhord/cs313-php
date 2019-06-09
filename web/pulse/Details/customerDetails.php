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
		$isChildDetails = false;
		$childDetailStr = '';
	}


    //connect to the database and make a query
    $db = new Database();
    $query = $db->getDB()->prepare("SELECT * FROM customer
            LEFT JOIN address ON cus_add_fk = add_pk
            WHERE cus_pk = $record");
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

	if ($result['cus_active'] == 1){
		$active = 'checked';
	} else {
		$active = '';
	}

    //Parent info displayed only when not child-details or not new record
    if ($record == 0 ){
      echo "<div class='detailInnerBlock'><h4>Add Customer:</h4> </div>";
    }
    //Using 1 echo over multiple lines to improve readablity
    echo "<div class='detailBlock' id='customerHeader'>
            <div class='detailInnerBlock' id='detailOptions'>
              <div class='detailInputBlock'>
                <label for='showEmpty'>Display Empty Fields:</label>
                <input id='showEmpty' type='checkbox' name='showEmpty' $checked onclick='getDetails(this)'><br> </div><div class='detailInputBlock'>";

	//Check if user has high enough permissions
	if ($_SESSION['permissions'] >= 8) {
		echo "	<label for='cus_active'>Customer is active?</label>
                <select id='cus_active' class='editable'><br>";
		if ($result['cus_active'] == 1){
			echo "<option value='true' selected='selected'>Active</option>
				  <option value='false'>Inactive</option>;";
		} else {
			echo "<option value='true'>Active</option>
				  <option value='false' selected='selected'>Inactive</option>";
		}
		echo "</select>";
		}
	echo "</div>
            </div>
            <div class='detailInnerBlock'>
              <h4> Customer Info:</h4>";

    //Add fields and labels to Description Block
    $blockFields = array('cus_company_name', 'cus_contact_fname', 'cus_contact_lname', 'add_street', 'add_city', 'add_zip');
    $blockLabels = array('Company Name', 'Contact First Name', 'Contact Last Name', 'Address', 'City', 'ZIP');
    //The permissions level at which the field becomes editable.
    $fieldEditable =  array(8, 8, 8, 8, 8);
    if($record == 0){
      $fieldEditable =  array(4 ,4 ,4 ,4 ,4);
    }
    //The type & validation for each field
    $fieldType = array('text', 'text', 'text', 'text', 'text', 'text');

    include "populateDetail.php";

     echo "</div>
           <div class='detailInnerBlock'>
              <h4> Contact Info:</h4>";

    $blockFields = array('cus_phone1', 'cus_phone2', 'cus_fax', 'cus_email');
    $blockLabels = array('Primary Phone', 'Secondary Phone', 'Fax Number', 'Email Address');
    $fieldEditable =  array(6, 6, 6, 6);
    if($record == 0){
      $fieldEditable =  array(4 ,4 ,4 ,4 ,4);
    }
    $fieldType = array('tel', 'tel', 'tel', 'email');

    include "populateDetail.php";

    echo "</div></div></div>";
    ?>
    <div id="detailButtons" class="detailBlock">
      <?php
        //Add different buttons depending on whether this is an add or update action
        if($record != 0){
          //javascript to use ID to note which php file to call.
          echo "<button id='cus_pk' class='detailButton' value='" . $result['cus_pk'] . "' onclick='update$childDetailStr(this)'>Save Changes</button>";
        } else {
          echo "<button id='cus_pk' class='detailButton' value='0' onclick='update$childDetailStr(this)'>Create Record</button>";
        }

		  echo "<button class='detailButton' onclick='getDetails$childDetailStr(this)'>Cancel</button>";
		  ?>
		</div>


	<?php
	//This is the area for child records 
	if(!$isChildDetails  && $record != 0){
		echo "<div class='detailBlock' id='detailChildBlock'>";
		$parentKey = $result['cus_pk'];
		include 'Lists/jobList.php';
		echo "</div>";
	}
	?>

</div>
