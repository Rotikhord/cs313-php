<?php

//This holds the names of columns to be updated.
$dbColumns = array();
$dbValues = array();
//Build the $dbValues array for the PDO SQL statement & check permissions
foreach ($updateFields as $field => $permissions){
  if(intval($_SESSION['permissions']) >= $permissions){
    if (isset($_POST[$field])){
      $updateFields[$field] = htmlspecialchars($_POST[$field]);
      if($updateFields[$field] == ''){
        $updateFields[$field] = null;
      }
      array_push($dbColumns, $field);
      $dbValues[':'.$field] = $updateFields[$field];
    }
  } else {
    echo "<div class='detailMessageBlock'><h4>You don't have sufficient permissions to add a record.</h4>
          Please contact your administrator for assistance.</div>";
    die();
  }
}


$dbColumnString = '';
$dbValueString = '';
$count = 0;
foreach($dbColumns as $column){
  if ($count != 0){
    $dbColumnString .= ', ';
    $dbValueString  .= ', ';
  }
  $dbColumnString .= $column;
  $dbValueString .=  ':' . $column;
  $count++;
}

?>
