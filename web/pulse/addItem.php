<?php
  session_start();

  require_once 'database.php';
  $db = new Database();

/*******************************************
 * This handles inserts for punchlog table
 *******************************************/
  if (isset($_POST['pun_pk'])){

    //array stores field names and permission levels required.
    $updateFields = array('pun_job_fk' => 4, 'pun_summary' => 4, 'pun_description'=> 4);
    //This stores the pk for the table in question.
    $pkCol = 'pun_pk';

    //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
    include 'buildInsertParams.php';

    $query = $db->getDB()->prepare("INSERT INTO punchlog ($dbColumnString, pun_date, pun_last_update)
                                    VALUES ($dbValueString, current_timestamp, current_timestamp)
                                    RETURNING $pkCol;");
    if ($query->execute($dbValues)){
     echo "Record Added Successfully.";
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $record = $result[$pkCol];
        include 'punchDetails.php';
    } else {
      echo "There was an error connecting to the database.<br>";
      die();
    }

  /*******************************************
   * This handles insert for job table
   *******************************************/
  } else if (isset($_POST['job_pk'])){

     //array stores field names and permission levels required.
     $updateFields = array('add_street' => 4, 'add_city' => 4, 'add_zip'=> 4);
     //This stores the pk for the table in question.
     $pkCol = 'add_pk';

     //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
     include 'buildInsertParams.php';

     $query = $db->getDB()->prepare("INSERT INTO address ($dbColumnString, add_st)
                                     VALUES ($dbValueString, 'NV')
                                     RETURNING $pkCol;");
     if ($query->execute($dbValues)){
      echo "Record Added Successfully.";
         $result = $query->fetch(PDO::FETCH_ASSOC);
         $job_add_fk = $result[$pkCol];
     } else {
       $job_add_fk = 'null';
     }


      //array stores field names and permission levels required.
      $updateFields = array('job_number' => 4, 'job_date' => 4, 'job_description'=> 4,
                            'job_balance' => 4, 'job_cus_fk' => 4);
      //This stores the pk for the table in question.
      $pkCol = 'job_pk';
      //This stores the table name
      $dbTable = 'job';

      //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
      include 'buildInsertParams.php';
      echo $dbColumnString . '<br>';
      echo $dbValueString . '<br>';
      print_r($dbValues);

      $query = $db->getDB()->prepare("INSERT INTO $dbTable ($dbColumnString, job_add_fk)
                                      VALUES ($dbValueString, $job_add_fk)
                                      RETURNING $pkCol");
      if ($query->execute($dbValues)){
       echo "Record Added Successfully.";
          $result = $query->fetch(PDO::FETCH_ASSOC);
          $record = $result[$pkCol];
          include 'jobDetails.php';
      } else {
        echo "There was an error connecting to the database.<br>";
        die();
      }


    /*******************************************
     * This handles insert for customer table
     *******************************************/
   } else if (isset($_POST['cus_pk'])){

       //array stores field names and permission levels required.
       $updateFields = array('add_street' => 4, 'add_city' => 4, 'add_zip'=> 4);
       //This stores the pk for the table in question.
       $pkCol = 'add_pk';

       //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
       include 'buildInsertParams.php';

       $query = $db->getDB()->prepare("INSERT INTO address ($dbColumnString, add_st)
                                       VALUES ($dbValueString, 'NV')
                                       RETURNING $pkCol;");
       if ($query->execute($dbValues)){
        echo "Record Added Successfully.";
           $result = $query->fetch(PDO::FETCH_ASSOC);
           $job_add_fk = $result[$pkCol];
       } else {
         $job_add_fk = 'null';
       }


        //array stores field names and permission levels required.
        $updateFields = array('cus_company_name' => 4, 'cus_contact_fname' => 4, 'cus_contact_lname'=> 4,
                              'cus_email' => 4, 'cus_phone1' => 4, 'cus_phone2' => 4, 'cus_fax' => 4);
        //This stores the pk for the table in question.
        $pkCol = 'cus_pk';
        //This stores the table name
        $dbTable = 'customer';

        //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
        include 'buildInsertParams.php';

        $query = $db->getDB()->prepare("INSERT INTO $dbTable ($dbColumnString, cus_add_fk, cus_active)
                                        VALUES ($dbValueString, $job_add_fk, 'true')
                                        RETURNING $pkCol");
        if ($query->execute($dbValues)){
         echo "Record Added Successfully.";
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $record = $result[$pkCol];
            include 'customerDetails.php';
        } else {
          echo "There was an error connecting to the database.<br>";
          die();
        }

    /*******************************************
     * This handles updates for employee table
     *******************************************/
   } else if (isset($_POST['emp_pk'])){
        //array stores field names and permission levels required.
        $updateFields = array('emp_fname' => 8, 'emp_lname' => 8, 'emp_email'=> 8, 'emp_permissions'=> 8, 'emp_hash' => 8);
        //This stores the pk for the table in question.
        $pkCol = 'emp_pk';
        //This stores the table name
        $dbTable = 'employee';

        //this file will take the above parameters as well as $_POST and create PDO friendly parameters.
        include 'buildInsertParams.php';

      if (isset($updateFields['emp_hash'])){
          $dbValues[":emp_hash"] = password_hash($_POST['emp_hash'], PASSWORD_DEFAULT);
        }


        $query = $db->getDB()->prepare("INSERT INTO $dbTable ($dbColumnString)
                                        VALUES ($dbValueString)
                                        RETURNING $pkCol;");
        if ($query->execute($dbValues)){
         echo "Record Added Successfully.";
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $record = $result[$pkCol];
            include 'employeeDetails.php';
        } else {
          echo "There was an error connecting to the database.<br>";
          print_r($query->errorInfo());
          die();
        }
      }

 ?>
