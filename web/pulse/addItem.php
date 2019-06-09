<?php
  session_start();

  require_once 'database.php';
  $db = new Database();
  $isChildDetails = false;
  if (isset($_POST['isChild']) && $_POST['isChild'] == 'true'){
  	  $isChildDetails = true;
  }
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
                                    RETURNING $pkCol, pun_job_fk;");
    if ($query->execute($dbValues)){
     echo "Record Added Successfully.";	 
        $result = $query->fetch(PDO::FETCH_ASSOC);
		if(!$isChildDetails){
			$record = $result[$pkCol];
			include 'Details/punchDetails.php';
		} else {
			$parentKey = $result['pun_job_fk'];
			include 'Lists/punchList.php';
		}
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
                                     RETURNING $pkCol");
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

      $query = $db->getDB()->prepare("INSERT INTO $dbTable ($dbColumnString, job_add_fk)
                                      VALUES ($dbValueString, $job_add_fk)
                                      RETURNING $pkCol, job_cus_fk");
      if ($query->execute($dbValues)){
       echo "Record Added Successfully.";
          $result = $query->fetch(PDO::FETCH_ASSOC);
          if(!$isChildDetails){
				$record = $result[$pkCol];						
				include 'Details/jobDetails.php';
		  } else {
				$parentKey = $result['job_cus_fk'];
				include 'Lists/jobList.php';
		  }
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
            include 'Details/customerDetails.php';
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
            include 'Details/employeeDetails.php';
        } else {
          echo "There was an error connecting to the database.<br>";
          print_r($query->errorInfo());
          die();
        }

		//This handles the adding of updates;
      } else if (isset($_POST['upd_pk'])){
			//array stores field names and permission levels required.
			$updateFields = array('upd_description' => 4, 'upd_payment' => 4, 'upd_pun_fk' => 4);
			//This stores the pk for the table in question.
			$pkCol = 'upd_pun_fk';
			
			//this file will take the above parameters as well as $_POST and create PDO friendly parameters.
			include 'buildInsertParams.php';
			
			$query = $db->getDB()->prepare("INSERT INTO update ($dbColumnString, upd_timestamp,  upd_emp_fk)
                                    VALUES ($dbValueString, current_timestamp, " . $_SESSION['user'] . ")
                                    RETURNING $pkCol;");
			if ($query->execute($dbValues)){
				echo "Record Added Successfully.";				
				$result = $query->fetch(PDO::FETCH_ASSOC);
				$parentKey = $result[$pkCol]; 

				//If payment was made job balance needs to be updated.
				if (isset($_POST['upd_payment']) &&  is_numeric($_POST['upd_payment'])){
					$query = $db->getDB()->prepare("SELECT job_pk, job_balance FROM job 
							INNER JOIN punchlog on job_pk = pun_job_fk WHERE pun_pk = $parentKey;");
					$query->execute();
					$result = $query->fetch(PDO::FETCH_ASSOC);
					$job_pk = $result['job_pk'];
					$balance = floatval($result['job_balance']) - floatval($_POST['upd_payment']);

					$query = $db->getDB()->prepare("UPDATE job SET job_balance = $balance WHERE job_pk = $job_pk");
					$query->execute();
				} else {
					echo "There was an error updating job balance. Please update it manually.<br>";
					die();
				}
				include 'Lists/updateList.php';
			} else {
				echo "There was an error connecting to the database.<br>";
				die();
			}
	  } else if (isset($_POST['asi_emp_fk']) && isset($_POST['asi_pun_fk'])){
			//array stores field names and permission levels required.
			$updateFields = array('asi_emp_fk' => 4, 'asi_pun_fk' => 4, 'upd_pun_fk' => 4);
			//This stores the pk for the table in question.			
			//this file will take the above parameters as well as $_POST and create PDO friendly parameters.
			include 'buildInsertParams.php';
			
			$query = $db->getDB()->prepare("INSERT INTO assignment ($dbColumnString)
                                    VALUES ($dbValueString)");
			if ($query->execute($dbValues)){
				echo "Record Added Successfully.";
				if (isset($_POST['assign_emp_fk'])){
					$parentKey = intval($_POST['asi_emp_fk']);
					$parentType = 'employee';
				} else {
					$parentKey = intval($_POST['asi_pun_fk']);
					$parentType = 'punchlog';
				}
				include 'Lists/assignmentList.php';
			} else {
				echo "There was an error connecting to the database.<br>";
				die();
			}
	  }


 ?>
