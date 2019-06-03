<?php
  //Remember this is the currently displayed list
  $_SESSION['displayedList'] = "Job";

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
  <div id="listArea">
    <div id='listHeader'>
      <div id="listSearchArea">
        <div class='listInputBlock'>
          <label for="listSearch">Search:</label>
          <input id="listSearch" type="text" oninput="searchList(this)"><br>
        </div>
      </div>
      <div id="listFilterArea" >
        <div class='listInputBlock'>
          <label for="showInactive">Display Inactive:</label>
          <input id="showInactive" type="checkbox" name="showInactive"><br>
        </div>
      </div>
    </div>
    <div id="listTableArea">
      <table>
        <tr id="titleRow">
          <th colspan="6"><div id='tableTitleDiv'><span id="addNew" onclick='addNew()'>Add New Job</span><span id="tableTitle">Job List</span></div></th>
        </tr>
        <tr id="headerRow">
          <th class="sortable" onclick="getSort(1)">Customer</th>
          <th class="sortable" onclick="getSort(2)">Job Number</th>
          <th>Description</th>
          <th class="sortable" onclick="getSort(4)">Balance</th>
          <th class="sortable" onclick="getSort(5)">Job Date</th>
        </tr>
      <?php
        //connect to the database and make a query
        $db = new Database();

        //Get the order by string for the SQL query
        $orderBy;
        switch($sortColumn){
          case 1:
            $orderBy = "cus_name";
            break;
          case 2:
            $orderBy = "job_number";
            break;
          case 4: //It doesn't make sense to order by summary field
            $orderBy = "job_balance";
            break;
          default: //We want to order by age of punchlist items by default
            $orderBy = "job_date";
          }
          $query = $db->getDB()->prepare("SELECT job_pk, COALESCE(cus_company_name, (SELECT cus_contact_lname || ', ' || cus_contact_fname)) AS cus_name,
                  job_number, job_description, job_balance, job_date FROM job
                  INNER JOIN customer on job_cus_fk = cus_pk
                  ORDER BY $orderBy $sortDirection");
          if ($query->execute()){
            $result = $query->fetchAll();
            for ($i = 0; $i < sizeof($result); $i++){
              echo "\t<tr  onclick='getDetails(this)' onmouseenter='highlightRow(this)' onmouseleave='unhighlightRow(this)' class='listRecord' id='record" . $result[$i]['job_pk'] . "'>\n";
              echo "\t\t<td class='col1'>" . $result[$i]['cus_name'] . "</td>\n";
              echo "\t\t<td class='col2'>" . $result[$i]['job_number'] . "</td>\n";
              echo "\t\t<td class='col3'>" . $result[$i]['job_description'] . "</td>\n";
              echo "\t\t<td class='col4'>" . $result[$i]['job_balance'] . "</td>\n";
              echo "\t\t<td class='col5'>" . $result[$i]['job_date'] . "</td>\n";
              echo "\t</tr>";
            }
          } else {
            echo "<tr><th colspan='6'> An error occured while connecting to the database... </th></tr>";
          }
      ?>
    </table>
    </div>
  </div>
