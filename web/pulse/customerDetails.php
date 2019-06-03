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

    //indicates whether this is called within the details section default is false
    $subDetails = false;
    if(isset($_GET['subDetails']) && $_GET['subDetails'] == 'true'){
      $subDetails = true;
    }

    //connect to the database and make a query
    $db = new Database();
    $query = $db->getDB()->prepare("SELECT * FROM customer
            LEFT JOIN address ON cus_add_fk = add_pk
            WHERE cus_pk = $record");
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    //Parent info displayed only when not sub-details or not new record
    if ($record == 0 ){
      echo "<div class='detailInnerBlock'><h4>Add Customer:</h4> </div>";
    }
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

    echo "</div></div>";
    ?>
    <div id="detailButtons" class="detailInnerBlock">
      <?php
        //Add different buttons depending on whether this is an add or update action
        if($record != 0){
          //javascript to use ID to note which php file to call.
          echo "<button id='cus_pk' class='detailButton' value='" . $result['cus_pk'] . "' onclick='update(this)'>Save Changes</button>";
        } else {
          echo "<button id='cus_pk' class='detailButton' value='0' onclick='update(this)'>Create Record</button>";
        }
        ?>
        <button class='detailButton' onclick='getDetails(this)'>Cancel</button>
    </div>
</div>
