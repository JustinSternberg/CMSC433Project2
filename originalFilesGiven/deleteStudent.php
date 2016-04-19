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
        <a href="index.php">Home</a>	
        </div>
  
    </div>
</div>

<body>

<div class="container" style="background-color:transparent">
<div class="inner-container" style="background-color:transparent">

<?php

//include methods for easy database access
//a mix of standard database commands and these "easy" methods are used in the code
include('CommonMethods.php');
$debug = false;
$COMMON = new Common($debug);

//grab studentID from session
$studentID = $_SESSION['studentID'];

//delete the student courses from StudentCourses database table
	$sql = "DELETE FROM `StudentCourses` WHERE `studentID` = '$studentID'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

//delete the student from Students table
	$sql = "DELETE FROM `Students` WHERE `studentID` = '$studentID'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

?>

<!-- A message is displayed telling the user their information has been deleted and asks them to navigate home to start over. -->
<p class='centerP' style='background-color:white'>
Your information has been deleted from the database and you have been logged out. Please click on the home button in the upper right corner to go back to the login screen.

</p>

</div>
</div>

</body>
</html>