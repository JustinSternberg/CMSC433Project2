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

<body>

<div class="container" style="background-color:transparent">
<div class="inner-container" style="background-color:transparent">

<?php

//include methods for easy database access
//a mix of standard database commands and these "easy" methods are used in the code
include('CommonMethods.php');
$debug = false;
$COMMON = new Common($debug);

//grab posted class data and session studentID data
$studentID = $_SESSION['studentID'];
$classes = $_POST['submitclass'];

//split selected classes into an array
$classList = explode(",", $classes);

//sql query to see if a student already has courses in the database
//this should always return with 0
	$sql = "SELECT * FROM `StudentCourses` WHERE `studentID` = '$studentID'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	$isThere = mysql_fetch_row($rs);


//if there is no student courses created
//format the data from $classes further and submit the student classes into the StudentCourses table
if (empty($isThere)){

	foreach($classList as $class){
		$inx = strpos($class, ':');
		$key = substr($class, 0, $inx);
		$classid = trim($key);

		if(strlen($key) > 0){
			$sql = "INSERT INTO `StudentCourses`(`courseID`, `studentID`) VALUES ('$classid','$studentID')";
			$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		}
	}
}
	
//this is the main logic of the program
//this function displays what classes a student should take based on the 
//$type parameter for a given classes
function classes($type){

		//create new common database object
		$FUNCTIONCOMMON = new Common($debug);

		//query to connect to the database as well, essentially does the same thing as above
		$studentID = $_SESSION['studentID'];
		$dbc = mysql_connect("studentdb-maria.gl.umbc.edu", "XX", "cmsc433") or die(mysql_error());
		mysql_select_db("XX", $dbc);

		//this does a check to see if any student courses were added in the query that happens before this function
		//this is a check to see if a student has taken ANY cmsc required classes at all
		$sql = "SELECT * FROM `StudentCourses` WHERE `studentID` = '$studentID'";
		$isThereNow = mysql_query($sql, $dbc);

		//these are the MAIN database queries that grab recommended classes
		//if they have not taken any classes
		//we select only classes that do not have a prereq
		if (mysql_num_rows($isThereNow)==0){
			$sql = "SELECT DISTINCT Courses.courseID, Courses.name, Courses.prereqs
				FROM  `Courses` WHERE `prereqs` = '' AND `courseType`='$type'";
		}

		//if they have taken classes, then we select classes that only have type of EITHER Sci or SciLab
		else if ($type == "Sci" || $type == "SciLab"){
			$sql = "SELECT DISTINCT Courses.courseID, Courses.name, Courses.prereqs
				FROM  `Courses` 
				INNER JOIN  `StudentCourses` ON (Courses.prereqs LIKE CONCAT('%', StudentCourses.courseID, '%') OR Courses.prereqs LIKE '')
				AND StudentCourses.studentID =  '$studentID' AND Courses.courseType = '$type' WHERE Courses.courseID NOT IN

				(
				    SELECT Courses.courseID FROM `Courses` INNER JOIN `StudentCourses` ON Courses.courseID = StudentCourses.courseID AND StudentCourses.studentID = '$studentID'
				)";
		} 
		//otherwise we go through the other types of classes available and select those classes based on existing student classes
		else {
		$sql = "SELECT DISTINCT Courses.courseID, Courses.name, Courses.prereqs
				FROM  `Courses` 
				INNER JOIN  `StudentCourses` ON (Courses.prereqs LIKE CONCAT('%', StudentCourses.courseID, '%') OR Courses.prereqs LIKE '')
				AND StudentCourses.studentID =  '$studentID' AND Courses.courseType Like '%$type%' WHERE Courses.courseID NOT IN

				(
				    SELECT Courses.courseID FROM `Courses` INNER JOIN `StudentCourses` ON Courses.courseID = StudentCourses.courseID AND StudentCourses.studentID = '$studentID'
				)";
		}

		//this grabs all of the recommended classes based on the above if / then statements
		$classes = mysql_query($sql, $dbc);

		$i = 1;
		print mysql_error();

		//loop to display the classes a student can take
		while($row = mysql_fetch_assoc($classes)){

			//for each classes that is returned, make sure to grab their prereqs
			$preClasses = explode(" ", $row['prereqs']);

			//because our SQL query does not check for multiple prereqs for a class we must do it here
			//if a class has more than one prereq
			if (sizeof($preClasses) > 1){

				//query to see if the student has the required prereq classes
				$sql = "SELECT COUNT(*) FROM `StudentCourses` WHERE `studentID` = '$studentID' AND (`courseID` = '$preClasses[0]' OR  `courseID` = '$preClasses[1]')";
				$rs = $FUNCTIONCOMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
				$count = mysql_fetch_row($rs);

				//query to see if a student has taken at least 1 400 level cmsc class
				$sql = "SELECT COUNT(*) FROM `StudentCourses` WHERE `studentID` = '$studentID' AND `courseID` LIKE 'CMSC4%'";
				$rs = $FUNCTIONCOMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
				$special = mysql_fetch_row($rs);

				//if they have taken 1 or more 400 level class, we increase count
				if ($special[0] > 0){
					$count[0] += 1;
				}

				//if the class has the number of required classes based off of the queries above, display it
				if ($count[0] > 1){
					echo "<p class=\"class\">" . $row['courseID'] . ": " . $row['name'] . "</p>";
					if( $i % 3 == 0){
						echo "<br>";
					}
					$i++;
				} else {
					continue;
				}

			}

			//if a class does not have multiple prereqs and it was returned based off of the main database queries
			//display it
			else{
				echo "<p class=\"class\">" . $row['courseID'] . ": " . $row['name'] . "</p>";
				if( $i % 3 == 0){
					echo "<br>";
				}
				$i++;
			}
		}
	}
?>

<!-- Display all of the recommended student classes by calling the php function classes()-->
<p style='background-color:white'>The classes you should take going forward include: </p>
<form id="allClasses">
<fieldset>
	<legend>Core Computer Science</legend>
	<?php classes("CScore");?>
</fieldset>
<fieldset>
	<legend>Required Math</legend>
	<?php classes("Reqmath");?>
</fieldset>
<fieldset>
	<legend>Required Stat</legend>
	<?php classes("Reqstat");?>
</fieldset>
<fieldset>
	<legend>Science</legend>
	<?php classes("Sci");?>
</fieldset>
<fieldset>
	<legend>Science with Lab</legend>
	<?php classes("SciLab");?>
</fieldset>
<fieldset>
	<legend>Computer Science Electives</legend>
	<?php classes("CSelec");?>
</fieldset>
<fieldset>
	<legend>Technical Electives</legend>
	<?php classes("Techelec");?>
</fieldset>
<fieldset>
	<legend>Other Compter Science</legend>
	<?php classes("otherCS");?>
</fieldset>
</form>


</div>
</div>

</body>
</html>