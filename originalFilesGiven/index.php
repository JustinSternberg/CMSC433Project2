<?php

session_start();

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


<head>
<title>Advising Sign Up</title>
<!-- ============================================================== -->
<meta name="resource-type" content="document" />
<meta name="distribution" content="global" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="description" content="CMSC Graduation Path" />
<meta name="keywords" content="CMSC Graduation Path" />
<!-- ============================================================== -->

<base target="_top" />
<link rel="stylesheet" type="text/css" href="styler.css" />
<link rel="icon" type="image/png" href="icon.png" />
</head>

<body id="login">

<!-- Styling - Same on Every Page -->
<div class="topContainer">
  <div class="leftTopContainer">
    
  	<img src="umbcLogo.png" width="261" height="72" alt="umbcLogo" />
  	<b>CMSC Graduation Path</b>
  
  	</div>
    
  <div class="rightTopContainer">
  		<div class="rightTopContent">
        <a href="index.php">Logout</a>	
        </div>
  
    </div>
</div>

<div class="indexContainer">
<div class="inner-container">


<!-- Form to enter student first name, lastname, ID, and email
    This information is posted to the classSelection.php page-->
<p>
Please enter your name, student ID number, and major.<br>
<form action="classSelection.php" method="post" id="studentform">
First Name <br>
	<input type="text" size="25" maxlength="50" name="fname"><br> <br />
Last Name <br>
	<input type="text" size="25" maxlength="50" name="lname"><br> <br />
Student ID <br>
	<input type="text" size="25" maxlength="7" name="studentID"><br> <br />
Email <br>
	<input type="text" size="25" maxlength="50" name="email"><br> <br />
<input type='submit' value='Submit' id='submit'>

</form>
<br /><br /> 

</p>

</div>
</div>

</body>
</html>
