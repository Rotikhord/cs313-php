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
    $query = $db->getDB()->prepare("SELECT * FROM job
            INNER JOIN customer ON job_cus_fk = cus_pk
            LEFT JOIN address ON job_add_fk = add_pk
            WHERE job_pk = $record");
    if ($query->execute()){
      $result = $query->fetch(PDO::FETCH_ASSOC);
    }

    //Parent info displayed only when not childdetails or not new record
    if (!$isChildDetails && $record != 0 ){
      //Using 1 echo over multiple lines to improve readablity
      echo "<div class='detailBlock' id='customerHeader'>
              <div class='detailInnerBlock' id='detailOptions'>
                <div class='detailInputBlock'>
                  <label for='showEmpty'>Display Empty Fields:</label><div class='detailBlock' id='customerHeader'>
                  <input id='showEmpty' type='checkbox' name='showEmpty' $checked onclick='getDetails(this)'><br>
                </div>
              </div>
              <div class='detailInnerBlock'>
                <h4> Customer Info:</h4>";

      //Add fields and labels to Description Block
      $blockFields = array('cus_company_name', 'cus_contact_fname', 'cus_contact_lname');
      $blockLabels = array('Company Name', 'Contact First Name', 'Contact Last Name');
      //The permissions level at which the field becomes editable.
      $fieldEditable =  array(8, 8, 8);
      if($record == 0){
        $fieldEditable =  array(4 ,4 ,4);
      }
      //The type & validation for each field
      $fieldType = array('text', 'text', 'text');

      include "populateDetail.php";

       echo "</div>
             <div class='detailInnerBlock'>
                <h4> Contact Info:</h4>";

      $blockFields = array('cus_phone1', 'cus_phone2', 'cus_fax', 'cus_email');
      $blockLabels = array('Primary Phone', 'Secondary Phone', 'Fax Number', 'Email Address');
      $fieldEditable =  array(8, 8, 8, 8);
      if($record == 0){
        $fieldEditable =  array(4, 4, 4, 4);
      }
      $fieldType = array('tel', 'tel', 'tel', 'email');

      include "populateDetail.php";

      echo "</div></div>";
    }
      if ($record == 0 && !$isChildDetails){
        //Get all active customers
        $customerQuery = $db->getDB()->prepare("SELECT cus_pk, COALESCE(cus_company_name, (SELECT cus_contact_lname || ', ' || cus_contact_fname)) AS cus_name
                   FROM customer WHERE cus_active = 'true' ORDER BY cus_name ASC");
        $customerQuery->execute();
        $customerResult = $customerQuery->fetchAll();
        echo "<div class='detailInnerBlock'>
                <h4>Add New Job:</h4>
                <label for='job_cus_fk'>Select Customer</label>
                  <select class='editable' id='job_cus_fk''>";
        for ($i = 0; $i < sizeof($customerResult); $i++){
          echo "<option value=" . $customerResult[$i]['cus_pk'] . ">" . $customerResult[$i]['cus_name'] . "</option>";
        }
        echo "</select>
            </div>";
        }


      echo "<div class='detailBlock' id='jobHeader'>
              <div class='detailInnerBlock'>
                <h4> Job Info:</h4>";

      $blockFields = array('job_number', 'job_date', 'add_street', 'add_city', 'add_zip');
      $blockLabels = array('Job Number', 'Start Date', 'Address', 'City', 'ZIP');
      $fieldEditable =  array(8, 8, 8, 8, 8);
      if($record == 0){
        $fieldEditable =  array(4, 4, 4, 4, 4);
      }
      $fieldType = array('text', 'date', 'text', 'text', 'text');

      include "populateDetail.php";

       echo "</div>
              <div class='detailInnerBlock'>
                <h4> Job Details:</h4>";

      $blockFields = array('job_balance', 'job_description');
      $blockLabels = array('Remaining Balance:', 'Description:');
      $fieldType = array('number','textArea');
      $fieldEditable =  array(4, 4);
      if($record != 0){
        array_push($blockFields, 'job_complete_date');
        array_push($blockLabels, 'Completion Date:');
        array_push($fieldType, 'date');
        $fieldEditable =  array(8, 8, 6);
      }


      include "populateDetail.php";

      echo "</div></div>";


        ?>
      <div id="detailButtons" class="detailInner Block">
        <?php
          //Add different buttons depending on whether this is an add or update action
          if($record != 0){
            //javascript to use ID to note which php file to call.
            echo "<button id='job_pk' class='detailButton' value='" . $result['job_pk'] . "' onclick='update$childDetailStr(this)'>Save Changes</button>";
          } else {
            echo "<button id='job_pk' class='detailButton' value='0' onclick='update$childDetailStr(this)'>Create Record</button>";
          }
          ?>
          <button class='detailButton' onclick='getDetails(this)'>Cancel</button>
      </div>


	<?php
	//This is the area for child records 
	if(!$isChildDetails  && $record != 0){
		echo "<div class='detailBlock' id='detailChildBlock'>";
		$parentKey = $result['job_pk'];
		include 'punchList.php';
		echo "</div>";
	}
	?>
  </div>
