<?php   ?>
  <div id="header">
    <div id="logoArea">
      <img src="pulseLogo.png">
    </div>
    <div id="userInfoArea" >
      <?php
      if (isset($_SESSION['displayName'])){
        echo "<h5>Currently signed in as ". $_SESSION['displayName'] . "</h5>\n<a href='endSession.php'>Logout</a>";
      }
      ?>
    </div>
    <div id="menuArea">
      <?php
        if(isset($_SESSION['permissions'])){
          echo "<div class='menuItem' id='menuPunchlist' onclick='selectList(this)'>Punch List</div>\n";
          echo "<div class='menuItem' id='menuJobs' onclick='selectList(this)''>Jobs</div>\n";
          echo "<div class='menuItem' id='menuCustomers' onclick='selectList(this)''>Customers</div>\n";
          if($_SESSION['permissions'] > 8){
            echo "<div class='menuItem' id='menuEmployees' onclick='selectList(this)'> Employees </div>";
          }
        } else {
          echo "<h2>Project Management</h2>";
        }
      ?>
    </div>
  </div>
