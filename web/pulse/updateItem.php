<?php
  session_start();

  require_once 'database.php';
  $db = new Database();

  //Declare these variables so they have wider scope;
  $punPK = 0;
  $jobPK = 0;
  $cusPK = 0;
  $empPK = 0;
  $addPK = 0;
/*******************************************
 * This handles updates for punchlog table
 *******************************************/
  if (isset($_POST['pun_pk'])){

    //array stores field names and permission levels required.
    $updateFields = array('pun_date' => 8, 'pun_summary' => 8, 'pun_description'=> 8,
                          'pun_last_update' => 8, 'pun_complete_date' => 6);
    //This stores the pk for the table in question.
    $pkCol = 'pun_pk';
    if ($punPK == 0){
      $punPK = intval($_POST[$pkCol]);
    }
    //This will store the pdo key and value for the prepared SQL statement.
    $dbValues = array(":$pkCol" => $punPK);
    //This stores the table name
    $dbTable = 'punchlog';

    //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
    include 'buildUpdateParams.php';

    $query = $db->getDB()->prepare("UPDATE $dbTable SET $dbString  WHERE $pkCol = :$pkCol");
    if ($query->execute($dbValues)){
      $jobPK = $db->getDB()->query("SELECT pun_job_fk FROM $dbTable WHERE $pkCol = " . $dbValues[":$pkCol"])->fetch()[0];
    } else {
      echo "There was an error connecting to the database.<br>";
      die();
    }
  }
  /*******************************************
   * This handles updates for job table
   *******************************************/
    if (isset($_POST['job_pk']) || $jobPK != 0){
      //array stores field names and permission levels required.
      $updateFields = array('job_number' => 8, 'job_date' => 8, 'job_description'=> 8,
                            'job_balance' => 8, 'job_complete_date' => 6);
      //This stores the pk for the table in question.
      $pkCol = 'job_pk';
      if ($jobPK == 0){
        $jobPK = intval($_POST[$pkCol]);
      }
      //This will store the pdo key and value for the prepared SQL statement.
      $dbValues = array(":$pkCol" => $jobPK);
      //This stores the table name
      $dbTable = 'job';

      //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
      include 'buildUpdateParams.php';

      if (sizeof($dbColumns) > 0){
        $query = $db->getDB()->prepare("UPDATE $dbTable SET $dbString  WHERE $pkCol = :$pkCol");
        if ($query->execute($dbValues)){
          $getPK = $db->getDB()->query("SELECT job_cus_fk, job_add_fk  FROM $dbTable WHERE $pkCol = " . $dbValues[":$pkCol"])->fetch();
          $cusPK = $getPK['job_cus_fk'];
          $addPK = $getPK['job_add_fk'];
        } else {
          echo "There was an error connecting to the database.<br>";
          die();
        }
      }
    }
/*******************************************
 * This handles updates for customer table
 *******************************************/
  if (isset($_POST['cus_pk']) || $cusPK != 0){
    //array stores field names and permission levels required.
    $updateFields = array('cus_company_name' => 8, 'cus_contact_fname' => 8, 'cus_contact_lname'=> 8,
                          'cus_email' => 6, 'cus_phone1' => 6, 'cus_phone2' => 6, 'cus_fax' => 6, 'cus_active' => 6);
    //This stores the pk for the table in question.
    $pkCol = 'cus_pk';
    if ($cusPK == 0){
      $cusPK = intval($_POST[$pkCol]);
    }
    //This will store the pdo key and value for the prepared SQL statement.
    $dbValues = array(":$pkCol" => $cusPK);
    //This stores the table name
    $dbTable = 'customer';
      //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
    include 'buildUpdateParams.php';
      if (sizeof($dbColumns) > 0){
      $query = $db->getDB()->prepare("UPDATE $dbTable SET $dbString  WHERE $pkCol = :$pkCol");
      if ($query->execute($dbValues)){
        if ($addPK == 0){
          $getPK = $db->getDB()->query("SELECT cus_add_fk  FROM $dbTable WHERE $pkCol = " . $dbValues[":$pkCol"])->fetch();
          $addPK = $getPK['cus_add_fk'];
        }
      } else {
        echo "There was an error connecting to the database.<br>";
        die();
      }
    }
  }
  /*******************************************
   * This handles updates for address table
   *******************************************/
    if (isset($_POST['add_pk']) || $addPK != 0){
      //array stores field names and permission levels required.
      $updateFields = array('add_street' => 8, 'add_city' => 8, 'add_st'=> 8, 'add_zip'=> 8);
      //This stores the pk for the table in question.
      $pkCol = 'add_pk';
      if ($addPK == 0){
        $addPK = intval($_POST[$pkCol]);
      }
      //This will store the pdo key and value for the prepared SQL statement.
      $dbValues = array(":$pkCol" => $addPK);
      //This stores the table name
      $dbTable = 'address';
    //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
      include 'buildUpdateParams.php';
      if (sizeof($dbColumns) > 0){
        $query = $db->getDB()->prepare("UPDATE $dbTable SET $dbString  WHERE $pkCol = :$pkCol");
        if ($query->execute($dbValues)){
          //placeholder
        } else {
          echo "There was an error connecting to the database.<br>";
          die();
        }
      }
    }

    /*******************************************
     * This handles updates for employee table
     *******************************************/
      if (isset($_POST['emp_pk']) || $empPK != 0){
        //array stores field names and permission levels required.
        $updateFields = array('emp_fname' => 10, 'emp_lname' => 10, 'emp_email'=> 10, 'emp_permissions'=> 10);
        if (isset($_POST['emp_hash']) && $_POST['emp_hash'] != ''){
          $updateFields['emp_hash'] = 10;
        }
        //This stores the pk for the table in question.
        $pkCol = 'emp_pk';
        if ($empPK == 0){
          $empPK = intval($_POST[$pkCol]);
        }
        //This will store the pdo key and value for the prepared SQL statement.
        $dbValues = array(":$pkCol" => $empPK);
        //This stores the table name
        $dbTable = 'employee';
        //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
        include 'buildUpdateParams.php';
        if (isset($updateFields['emp_hash'])){
          $dbValues[":emp_hash"] = password_hash($_POST['emp_hash'], PASSWORD_DEFAULT);
        }
        if (sizeof($dbColumns) > 0){
          $query = $db->getDB()->prepare("UPDATE $dbTable SET $dbString  WHERE $pkCol = :$pkCol");
          if ($query->execute($dbValues)){
            //placeholder
          } else {
            echo "There was an error connecting to the database.<br>";
            die();
          }
        }
      }
      echo "<div class='detailMessageBlock'><h4>Record Updated Successfully!</h4></div>";

      if($punPK != 0){
        $record = $punPK;
        include "punchDetails.php";
      } else if($jobPK != 0) {
        $record = $jobPK;
        include "jobDetails.php";
      } else if($cusPK != 0) {
        $record = $cusPK;
        include "customerDetails.php";
      } else if ($empPK != 0) {
        $record = $empPK;
        include "employeeDetails.php";
      }
 ?>
