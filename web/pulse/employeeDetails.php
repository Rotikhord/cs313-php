<?php
  require_once 'database.php';
    $record = intval($_GET["record"]);

    //connect to the database and make a query
    $db = new Database();
    $query = $db->getDB()->prepare("SELECT * FROM employee
            WHERE emp_pk = $record");
    if ($query->execute()){
      $result = $query->fetch(PDO::FETCH_ASSOC);
    }
  ?>
  <div class="detailBlock" id="employeeHeader">
    <div class="detailInnerBlock">
      <h4> Employee Info:</h4>
        <?php
          $blockFields = array('emp_fname', 'emp_lname', 'emp_email', 'emp_permissions');
          $blockLabels = array('First Name', 'Last Name', 'Email Address', 'Permissions');

          for ($i = 0; $i < sizeof($blockFields); $i++){
            if ($result[$blockFields[$i]] != ""){
              echo "<label for='" . $blockFields[$i] . "'>" . $blockLabels[$i] . ":</label><br>";
              echo "<input class='unlockedInput' id='". $blockFields[$i] . "' type='text' value='" . $result[$blockFields[$i]] . "'><br>";            }
          }
        ?>
        <label for='emp_password'>Enter New Password:</label><br>
        <input class='unlockedInput' id='emp_password' type='password'><br>
    </div>
  </div>
