<?php
$page_title = "Edit Unit";
require_once("header.php");
require_once("../lib/val.php");

$unit_uid = mysql_prep($_GET['u']);
$unit_query = "SELECT * FROM Units INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID INNER JOIN Subjects ON Units.Subject_ID = Subjects.S_ID WHERE U_ID = {$unit_uid}";
$unit_result = mysqli_query($con, $unit_query);
$unit_info = mysqli_fetch_assoc($unit_result);

$grades_query = "SELECT * FROM GradeLevels";
$grades_result = mysqli_query($con, $grades_query);

$subjects_query = "SELECT * FROM Subjects ORDER BY shortname";
$subjects_result = mysqli_query($con, $subjects_query);

if (isset($_POST["submit"])){
	
	$required_fields = array("name","gradelevel","subject");
	
	val_presences($required_fields);
	
	if(empty($errors)) {
		
		$unit_name = mysql_prep($_POST["name"]);
		$unit_subject = mysql_prep($_POST["subject"]);
		$unit_gradelevel = mysql_prep($_POST["gradelevel"]);
		$startdate = mysql_prep(js_datefix($_POST["startdate"]));
		$enddate = mysql_prep(js_datefix($_POST["enddate"]));
		$unit_uid = mysql_prep($_GET["u"]);
	
		$update_query = "UPDATE Units SET 
								Name = '{$unit_name}',
								GradeLevel_id = {$unit_gradelevel}, 
								Subject_id = {$unit_subject}, 
								StartDate = '{$startdate}', 
								EndDate = '{$enddate}'  
								WHERE U_ID = {$unit_uid};";
							
							
		if (mysqli_query($con, $update_query)) {
			echo "<script language='javascript'>";
			echo "alert('Unit " . $_POST["name"]. " updated!')";
			echo "</script>";	
			mysqli_free_result($asset_result);
			header("Location: " . "view-unit.php?u={$unit_uid}");
			
		}
	}
}

?>
<script>
	
	$(document).ready(function(){ 
		<?php
			echo "$('#page-title h1').text('Edit {$unit_info['Name']}');";
			echo "$('#backbutton').text('Back to {$unit_info['Name']}');";
			echo "$('#subject').val('{$unit_info['Subject_id']}');";
			echo "$('#gradelevel').val('{$unit_info['GradeLevel_id']}');";
		?>
		
	
	});
	


</script>

<div id="content-wrapper">

	<div id="content">
	
		<a id="backbutton" class = "menubutton" href=<?php echo "'view-unit.php?u={$_GET["u"]}';"?>>Back to Unit</a>
		<?php list_errors();?>
			
			<?php echo"<form id=\"editunit\" method=\"post\" action=\"edit-unit.php?u={$unit_uid}\">";?>
				<label for="name">Unit Name</label><?php echo "<input type='text' name='name' value='" . $unit_info["Name"] . "'>";?><br>
				<label for="gradelevel">Grade Level <b>(Current: <?php echo $unit_info["level"] . ")</b>";?></label>
				
				<select id="gradelevel" name="gradelevel">
					<?php 
						while($row=mysqli_fetch_assoc($grades_result)) 
						{
							echo "<option value = '". $row["GL_ID"] . "'>" . $row["level"] . "</option>";					
						
						}
						mysqli_free_result($grades_result);
					?>
				</select></br>	
						
				<label for="subject">Subject</label>				
				<select id="subject" name="subject">	
					<?php 
						while($row=mysqli_fetch_assoc($subjects_result)) 
						{
							echo "<option value = '". $row["S_ID"] . "'>" . $row["shortname"] . "</option>";	 //options are subject id	
						
						}
					
						mysqli_free_result($subjects_result);
					?>
				
				</select><br>
				<input id="from" type="text" name="startdate" value= <?php echo "'{$unit_info['StartDate']}'";?>>Start Date</input><br>
				<input id="to" type="text" name="enddate" value= <?php echo "'{$unit_info['EndDate']}'";?>>End Date</input><br>
				<input type="submit" name="submit" value="Update Unit">
			</form>
			

	
	</div>

</div>


<?php require_once("footer.php"); ?>