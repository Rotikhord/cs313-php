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
      <h2 style="text-align:center;">My Family and Me</h2>
      <img class="image" src="myFamily.jpg" alt="Image of Barlow Family" onmouseenter="enlarge(this)" onmouseleave="enlarge(this)"/>
      <p class="imgText">Barlow Family</p>
      <img class="image" src="davey.jpg" alt="Image of David Barlow"  onmouseenter="enlarge(this)" onmouseleave="enlarge(this)"/>
      <p class="imgText">David </p>
      <img class="image" src="michelle.jpg" alt="Image of Michelle Barlow"  onmouseenter="enlarge(this)" onmouseleave="enlarge(this)"/>
      <p class="imgText">Michelle </p>
      <img class="image" src="me.jpg" alt="Image of Doug Barlow"  onmouseenter="enlarge(this)" onmouseleave="enlarge(this)"/></a>
      <p class="imgText">Doug </p>
    </div>
    <div class="rightDiv">
      <h2 id="aboutMe">About Me</h2>
      <p>Hi, my name is Doug Barlow. I've been a member of the Church for about 11 years now.
        I joined the church when I was 17, and ended up serving a mission in the Washington
        Kennewick area. I loved my mission, but I feel most of my blessings have come since
        returning. When I returned I tried to go to school unsuccessfully. I attended BYU-I for
        a few semesters but everything seemed to get in my way.
        I realize now why that was. The Lord needed me back in my home town of Las Vegas so
        I could meet and woo the lovely yound woman that would become my wife. My wife and
        I were married in June of 2017 and we've added an absolutely adorable little boy
        to our family. </p>
      <hr>
      <p> The Lord has blessed our little family with great opportunities here in Las Vegas.
        My wife is currently attending graduate school at UNLV and I have the wonderful opportunity
        to work towards my online Software Engineering degree. I've also been blessed to find\
        work and even an internship here so that our little family and stay together while both my
        wife and I go to school. It's also given us the blessing of being close to both of
        our families, something we are grateful for during finals weeks as they provide
        desperately needed babysitting services. (And you know, we like them too) </p>
      <table>
        <tr class="isOdd">
          <th colspan="5">My Class Schedule</th>
        </tr>
        <tr>
          <th>Course Number</th>
	        <th>Course Description</th>
          <th>Summary</th>
          <th>Time</th>
          <th>Location</th>
	      </tr>
        <tr class="isOdd" onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>CS 398</td>
	        <td>Computer Science Internship</td>
	        <td>I'm currently interning with a small custom software company in Las Vegas.</td>
	        <td>Daily 7:30AM - 4PM</td>
	        <td>Online</td>
	      </tr>
	      <tr onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>REL 250</td>
	        <td>Jesus Christ Everlasting Gospel</td>
	        <td>In this class we get to learn about Jesus Christ and the Plan of Salvation</td>
	        <td>N/A</td>
	        <td>Online</td>
	      </tr>
	      <tr class="isOdd" onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>CS 313</td>
	        <td>Web Engineering II</td>
	        <td>Definitely my favorite class of the semester, and I'm only pandering a little.</td>
	        <td>N/A</td>
	        <td>Online</td>
	      </tr>
	      <tr onmouseenter="highlight(this)" onmouseleave="highlight(this)">
	        <td>CIT  261</td>
	        <td>Mobile Application Development</td>
	        <td>A more in-depth look at front end development topics.</td>
	        <td>N/A</td>
	        <td>Online</td>
	      </tr>
      </table>
    </div>
  </div>
</body>
</html>
