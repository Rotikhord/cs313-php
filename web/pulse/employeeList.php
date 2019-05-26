<?php
  //Remember this is the currently displayed list
  $_SESSION['displayedList'] = 'Employee';

  require_once 'database.php';

  $sortColumn = 0;
  $sortDirection = "ASC"; //Sort oldest to newest by default

  //Check to see if this is a sort request & get the column to be sorted by
  if (isset($_GET['sortColumn'])){
    $sortColumn = intval($_GET['sortColumn']);
  }
  //Check to see if sort direction was set and if it's set to "true"
  if(isset($_GET['sortAscending']) && $_GET['sortAscending'] == 'false'){
    $sortDirection = "DESC";
  }
  ?>

  <div id="listHeaderArea">
    <div id="listSearchArea">
      <label for="listSearch">Search For:</label>
      <input id="listSearch" type="text" oninput="searchList(this)">
    </div>
    <div id="listFilterArea" >
    </div>
    <div id="listTableArea">
      <table>
        <tr id="titleRow">
          <th colspan="6"> Employees </th>
        </tr>
        <tr id="headerRow">
          <th class="sortable" onclick="getSort(1)">Last Name</th>
          <th class="sortable" onclick="getSort(2)">First Name</th>
          <th class="sortable" onclick="getSort(3)">Email</th>
        </tr>
      <?php
        //connect to the database and make a query
        $db = new Database();

        //Get the order by string for the SQL query
        $orderBy;
        switch($sortColumn){
          case 2:
            $orderBy = "emp_fname";
            break;
          case 3:
            $orderBy = "emp_email";
            break;
          default: //We want to order by age of punchlist items by default
            $orderBy = "emp_lname";
          }
          $query = $db->getDB()->prepare("SELECT * FROM employee
                  ORDER BY $orderBy $sortDirection");
          if ($query->execute()){
            $result = $query->fetchAll();
            for ($i = 0; $i < sizeof($result); $i++){
              echo "\t<tr onclick='getDetails(this)'  onmouseenter='highlightRow(this)' onmouseleave='unhighlightRow(this)' class='listRecord' id='record" . $result[$i]['emp_pk'] . "'>\n";
              echo "\t\t<td class='col1'>" . $result[$i]['emp_lname'] . "</td>\n";
              echo "\t\t<td class='col2'>" . $result[$i]['emp_fname'] . "</td>\n";
              echo "\t\t<td class='col3'>" . $result[$i]['emp_email'] . "</td>\n";
              echo "\t</tr>";
            }
          } else {
            echo "<tr><th colspan='6'> An error occured while connecting to the database... </th></tr>";
          }

      ?>
    </table>
    </div>
  </div>
