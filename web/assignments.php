<?php   ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CS 313 Homepage</title>
  <meta name="description" content="A homepage for my CS 313 Web Engineering Class">
  <meta name="keywords" content="">
  <meta name="author" content="Doug Barlow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="cs313style.css">
  <script src="cs313script.js"></script>
</head>
<body>
  <div id="header">
    <?php
      include "header.php"
    ?>
  </div>
  <div id="contentArea">
    <div class="leftDiv">
      <h2>Helpful Links!</h2>
      <p class="imgText">Coming soon!</p>
    </div>
    <div class="rightDiv">
      <h2>My Assignments!</h2>
      <table>
        <tr class="isOdd">
          <th colspan="3">Assignment List</th>
        </tr>
        <tr>
          <th class="smallCol">Week</th>
	        <th class="bigCol">Description</th>
          <th class="bigCol">Link</th>
	      </tr>
        <tr class="isOdd" onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>01</td>
	        <td>A simple "Hello World" webpage. The perfect way to start a software class.</td>
	        <td><a href="hello.html" class="link">Assignment 01</a></td>

	      </tr>
	      <tr onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>02</td>
          <td>A page to call home! Everyone needs somewhere to call their own! Oh and also links to assignments!</td>
	        <td><a href="home.php" class="link">Assignment 02</a></td>
	      </tr>
	      <tr class="isOdd" onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>03</td>
	        <td>A "Shopping Cart" that allows users to browse and select items for purchase, view their cart and confirm their orders! </td>
	        <td><a href="week03/browse.php" class="link">Assignment 03</td>
	      </tr>
	      <tr onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>04</td>
          <td>Week 04 Assignment</td>
	        <td>Coming Soon!</td>
	      </tr>
        <tr class="isOdd" onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>05</td>
	        <td>Week 05 Assignment</td>
	        <td>Coming Soon!</td>
	      </tr>
	      <tr onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>06</td>
          <td>Week 06 Assignment</td>
	        <td>Coming Soon!</td>
	      </tr>
        <tr class="isOdd" onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>07</td>
	        <td>Week 07 Assignment</td>
	        <td>Coming Soon!</td>
	      </tr>
	      <tr onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>08</td>
          <td>Week 08 Assignment</td>
	        <td>Coming Soon!</td>
	      </tr>
        <tr class="isOdd" onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>09</td>
	        <td>Week 09 Assignment</td>
	        <td>Coming Soon!</td>
	      </tr>
	      <tr onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>10</td>
          <td>Week 10 Assignment</td>
	        <td>Coming Soon!</td>
	      </tr>
      </table>
    </div>
  </div>
</body>
</html>
