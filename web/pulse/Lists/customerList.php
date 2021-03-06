<?php
  //Remember this is the currently displayed list
  $_SESSION['displayedList'] = 'Customer';

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

  <div class="listArea">
    <div class='listHeader'>
      <div class="listSearchArea">
        <div class='listInputBlock'>
          <label for="listSearch">Search:</label>
          <input id="listSearch" type="text" oninput="searchList(this)"><br>
        </div>
      </div>
      <div class="listFilterArea" >
        <div class='listInputBlock'>
          <label for="showInactive">Display Inactive:</label>
          <input id="showInactive" type="checkbox" name="showInactive"><br>
        </div>
      </div>
    </div>
    <div id="listTableArea">
      <table>
        <tr id="titleRow">
          <th colspan="6"><div id='tableTitleDiv'><span id="addNew" onclick='addNew()'>New Customer</span><span id="tableTitle">Customers</span></div></th>
        </tr>
        <tr id="headerRow">
          <th class="sortable" onclick="getSort(1)">Name</th>
          <th class="sortable" onclick="getSort(2)">Address</th>
          <th class="sortable" onclick="getSort(3)">City</th>
          <th class="sortable" onclick="getSort(4)">ZIP</th>
          <th class="sortable" onclick="getSort(5)">Phone</th>
          <th class="sortable" onclick="getSort(6)">Email</th>
        </tr>
      <?php
        //connect to the database and make a query
        $db = new Database();

        //Get the order by string for the SQL query
        $orderBy;
        switch($sortColumn){
          case 2:
            $orderBy = "add_street";
            break;
          case 3:
            $orderBy = "add_city";
            break;
          case 4:
            $orderBy = "add_zip";
            break;
          case 5:
            $orderBy = "cus_phone1";
            break;
          case 6:
            $orderBy = "cus_email";
            break;
          default: //We want to order by age of punchlist items by default
            $orderBy = "cus_name";
          }
          $query = $db->getDB()->prepare("SELECT cus_pk, COALESCE(cus_company_name, (SELECT cus_contact_lname || ', ' || cus_contact_fname)) AS cus_name,
                  add_street, add_city, add_zip, cus_phone1, cus_email FROM customer
                  LEFT JOIN address ON cus_add_fk = add_pk
                  ORDER BY $orderBy $sortDirection");
          if ($query->execute()){
            $result = $query->fetchAll();
            for ($i = 0; $i < sizeof($result); $i++){
              echo "\t<tr onclick='getDetails(this)'  onmouseenter='highlightRow(this)' onmouseleave='unhighlightRow(this)' class='listRecord' id='record" . $result[$i]['cus_pk'] . "'>\n";
              echo "\t\t<td class='col1'>" . $result[$i]['cus_name'] . "</td>\n";
              echo "\t\t<td class='col2'>" . $result[$i]['add_street'] . "</td>\n";
              echo "\t\t<td class='col3'>" . $result[$i]['add_city'] . "</td>\n";
              echo "\t\t<td class='col4'>" . $result[$i]['add_zip'] . "</td>\n";
              echo "\t\t<td class='col5'>" . $result[$i]['cus_phone1'] . "</td>\n";
              echo "\t\t<td class='col6'>" . $result[$i]['cus_email'] . "</td>\n";
              echo "\t</tr>";
            }
          } else {
            echo "<tr><th colspan='6'> An error occured while connecting to the database... </th></tr>";
          }

      ?>
    </table>
    </div>
  </div>
