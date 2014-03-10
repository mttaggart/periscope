<?php
$page_title = "New Unit";
require_once("header.php");
require_once("../lib/val.php");


$grades_query = "SELECT * FROM GradeLevels";
$grades_result = mysqli_query($con, $grades_query);
$subjects_query = "SELECT * FROM Subjects ORDER BY shortname";
$subjects_result = mysqli_query($con, $subjects_query);

if (isset($_POST["submit"])) {
	
	$required_fields = array("name","gradelevel","subject");
	
	val_presences($required_fields);
	
	if(empty($errors)) {
	
		//Converter for SQL Date format from jQuery datepicker
		
		$unit_name = mysql_prep($_POST["name"]);
		$unit_subject = mysql_prep($_POST["subject"]);
		$unit_gradelevel = mysql_prep($_POST["gradelevel"]);
		$startdate = mysql_prep(js_datefix($_POST["startdate"]));
		$enddate = mysql_prep(js_datefix($_POST["enddate"]));
	
		$add_query = "INSERT INTO Units (Name, GradeLevel_id, Subject_id, StartDate, EndDate)
							VALUES ('{$unit_name}', 
							{$unit_gradelevel}, 
							{$unit_subject}, 
							'{$startdate}', 
							'{$enddate}'
							);";
							
		if (mysqli_query($con, $add_query)) {
			
			//Alert box to indicate unit added.

				echo "<script language='javascript'>";
				echo "alert('Unit " . $unit_name. " added!')";
				echo "</script>";
				header("Location: " . "browse.php" );
		}
	}
}

?>

<div id="content-wrapper">

	<div id="content">
			
			<?php list_errors(); ?>
			
			<form id="addunit" method="post" action="new-unit.php">
				<label for="name">Unit Name</label><input type="text" name="name"><br>
				<label for="gradelevel">Grade Level</label>
				<select name="gradelevel">
					<?php 
						while($row=mysqli_fetch_assoc($grades_result)) 
						{
							echo "<option value = '". $row["GL_ID"] . "'>" . $row["level"] . "</option>";					
						
						}
						mysqli_free_result($grades_result);
					?>
				</select></br>	
				
				<label for="subject">Subject</label>				
				<select name="subject">	
					<?php 
						while($row=mysqli_fetch_assoc($subjects_result)) 
						{
							echo "<option value = '". $row["S_ID"] . "'>" . $row["shortname"] . "</option>"; 
						
						}
						mysqli_free_result($subjects_result);
					?>
				</select><br>
				<input id="from" type="text" name="startdate">Start Date</input><br>
				<input id="to" type="text" name="enddate">End Date</input><br>
				<input type="submit" name="submit" value="Create Unit">
			</form>
	
	</div>

</div>


<?php require_once("footer.php"); ?>