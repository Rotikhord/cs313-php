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
		$parentType = 'punchlog';
	}

    //connect to the database and make a query
    $db = new Database();
    $query = $db->getDB()->prepare("SELECT * FROM punchlog
            INNER JOIN job ON pun_job_fk = job_pk
            INNER JOIN customer ON job_cus_fk = cus_pk
            LEFT JOIN address ON job_add_fk = add_pk
            WHERE pun_pk = $record");
    if ($query->execute()){
      $result = $query->fetch(PDO::FETCH_ASSOC);
    }

	if ($isChildDetails){
		  echo "<div class='detailBlock' id='childHeader'>
              <div class='detailInnerBlock' id='detailOptions'>
                  <button class='childHeaderButton' onclick='getChildList(\"Punchlist\")'>Return to List</button>
                </div>
              </div>";
	}

    //Parent info displayed only when not child-details or not new record
    if (!$isChildDetails && $record != 0 ){
		echo "<div class='detailBlock' id='detailHeader'>
                <div class='detailInputBlock'>
                  <label for='showEmpty'>Display Empty Fields:</label>
                  <input id='showEmpty' type='checkbox' name='showEmpty' $checked onclick='getDetails(this)'><br>
                </div>
              </div>";
      //Using 1 echo over multiple lines to improve readablity
      echo "<div class='detailBlock' id='customerHeader'>
              <div class='detailInnerBlock'>
                <h4> Customer Info:</h4>";

      //Add fields and labels to Description Block
      $blockFields = array('cus_company_name', 'cus_contact_fname', 'cus_contact_lname');
      $blockLabels = array('Company Name', 'Contact First Name', 'Contact Last Name');
      //The permissions level at which the field becomes editable.
      $fieldEditable =  array(8, 8, 8);
      //The type & validation for each field
      $fieldType = array('text', 'text', 'text');

      include "populateDetail.php";

       echo "</div>
             <div class='detailInnerBlock'>
                <h4> Contact Info:</h4>";

      $blockFields = array('cus_phone1', 'cus_phone2', 'cus_fax', 'cus_email');
      $blockLabels = array('Primary Phone', 'Secondary Phone', 'Fax Number', 'Email Address');
      $fieldEditable =  array(8, 8, 8, 8);
      $fieldType = array('tel', 'tel', 'tel', 'email');

      include "populateDetail.php";

      echo "</div>
            </div>
            <div class='detailBlock' id='jobHeader'>
              <div class='detailInnerBlock'>
                <h4> Job Info:</h4>";

      $blockFields = array('job_number', 'job_date', 'add_street', 'add_city', 'add_zip');
      $blockLabels = array('Job Number', 'Start Date', 'Address', 'City', 'ZIP');
      $fieldEditable =  array(8, 8, 8, 8, 8);
      $fieldType = array('text', 'date', 'text', 'text', 'text');

      include "populateDetail.php";

       echo "</div>
              <div class='detailInnerBlock'>
                <h4> Job Details:</h4>";

      $blockFields = array('job_balance', 'job_description');
      $blockLabels = array('Remaining Balance:', 'Description:');
      $fieldEditable =  array(8, 8);
      $fieldType = array('number','textArea');

      include "populateDetail.php";

      echo "</div></div>";
      }
      ?>
  <div class="detailBlock" id="punchHeader">
    <?php
      if ($record == 0 && !$isChildDetails){

        //Get all active jobs
        $jobQuery = $db->getDB()->prepare("SELECT job_pk, COALESCE(cus_company_name, (SELECT cus_contact_lname || ', ' || cus_contact_fname)) AS cus_name,
                job_number FROM job INNER JOIN customer on job_cus_fk = cus_pk
                WHERE job_complete_date IS NULL ORDER BY job_number ASC");
        $jobQuery->execute();
        $jobResult = $jobQuery->fetchAll();
        echo "<div class='detailInnerBlock'>
                <h4>Add PunchList Item:</h4>
                <label for='pun_job_fk'>Select Customer, Job</label>
                  <select class='editable' id='pun_job_fk'>";
        for ($i = 0; $i < sizeof($jobResult); $i++){
          echo "<option value=" . $jobResult[$i]['job_pk'] . ">" . $jobResult[$i]['cus_name'] . " - #" . $jobResult[$i]['job_number'] . "</option>";
        }
        echo "</select>
            </div>";
        }
      ?>
    <div class="detailInnerBlock">
      <h4>PunchList Item Details:</h4>
        <?php
          $blockFields = array('pun_summary');
          $blockLabels = array('Item Summary');
          $fieldEditable =  array(4);
          $fieldType = array('text');

          //Add extra values if not new record (Otherwise value are filled in by default);
          if ($record != 0){
            array_push($blockFields, 'pun_date', 'pun_last_update', 'pun_complete_date');
            array_push($blockLabels, 'Escalation Date', 'Last Updated', 'Date Completed');
            $fieldEditable =  array(8, 8, 8, 8);
            array_push($fieldType, 'date', 'date', 'date');
          }

          include "populateDetail.php";

        ?>
      </div>
      <div class="detailInnerBlock">
        <?php
          $blockFields = array('pun_description');
          $blockLabels = array('Description:');
          $fieldType = array('textArea');
          if($record == 0){
            $fieldEditable =  array(4);
          } else {
            $fieldEditable = array(8, 6);
            array_push($blockFields, 'pun_complete_date');
            array_push($blockLabels, 'Completion Date:');
            array_push($fieldType, 'date');
          }

          include "populateDetail.php";
        ?>
      </div></div>
      <div id="detailButtons" class="detailBlock">
        <?php
          //Add different buttons depending on whether this is an add or update action
          if($record != 0){
            //javascript to use ID to note which php file to call.
            echo "<button id='pun_pk' class='detailButton' value='" . $result['pun_pk'] . "' onclick='update$childDetailStr(this)'>Save Changes</button>";
          } else {
            echo "<button id='pun_pk' class='detailButton' value='0' onclick='update$childDetailStr(this)'>Create Record</button>";
          }

		  echo "<button class='detailButton' onclick='getDetails$childDetailStr(this)'>Cancel</button>";
		  ?>
      </div>

	<?php
	//This is the area for child records 
	if(!$isChildDetails  && $record != 0){
		echo "<div class='detailBlock' id='detailChildBlock'>";
		$parentKey = $result['pun_pk'];
		include 'Lists/updateList.php';
		echo "</div>";
	}
	?>
  </div>
