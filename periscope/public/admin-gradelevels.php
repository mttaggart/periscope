<?php
$page_title = "Manage Grade Levels";
require_once("../lib/sessions.php");
require_once("../lib/val.php");
require_once("header.php");

if(!logged_in()) {
	$_SESSION["errors"] = "Please log in to view this page";
	redirect_to("login.php");
}


$gradelevels_query = "SELECT * FROM GradeLevels;";
$gradelevels_result = mysqli_query($con, $gradelevels_query);


//POST Handling

if (isset($_POST["submit"])){

	$required_fields = array("level", "longname");
	
	val_presences($required_fields);
	if(empty($errors)) { 
		$level = mysql_prep($_POST["level"]);
		$longname = mysql_prep($_POST["longname"]);
		
		if(isset($_GET["e"])) {
			$edit_gradelevel = mysql_prep($_GET["e"]);
			$update_query = "UPDATE GradeLevels SET
									level = '{$level}',
									longname = '{$longname}' 
									WHERE GL_ID = {$edit_gradelevel};";
										
			if (mysqli_query($con, $update_query)) {
				mysqli_free_result($gradelevels_result);
				redirect_to("admin-gradelevels.php");
			} 
			
		} else {
			$new_subject_query = "INSERT INTO GradeLevels (level, longname)
									 VALUES ('{$level}', '{$longname}');";
									 
			if (mysqli_query($con, $new_subject_query)) {
				mysqli_free_result($gradelevels_result);

				redirect_to("admin-gradelevels.php");
			}
		}
	}
}

if(isset($_GET["d"])) {
	$gradelevel_d_id = mysql_prep($_GET["d"]);
	$remove_query = "DELETE FROM GradeLevels WHERE GL_ID = {$gradelevel_d_id};";
	
	
		
	if (mysqli_query($con, $remove_query)){
				
			redirect_to("admin-gradelevels.php");	
	}	
}


?>
<script type="text/javascript">
	$(document).ready(function () {
		<?php
			if(isset($_GET["e"])) {
				$edit_id = $_GET["e"];
				$edit_gradelevel_info = find_gradelevel_by_id($edit_id);
				$edit_level = $edit_gradelevel_info["level"];
				$edit_longname = $edit_gradelevel_info["longname"];
				echo "$('#gradelevels-edit').attr('action', 'admin-gradelevels.php?e={$edit_id}');";
				echo "$('#submit').val('Edit Grade Level');";				
				echo "$('#level').val('{$edit_level}');";
				echo "$('#longname').val('{$edit_longname}');";	
			}
		?>
	});
</script>
<div id="content-wrapper">

	<div id="content">
		<?php 
			echo errors() . "<br>";
			echo list_errors();
		?>
		
		<table id="gradelevel-table">
			<th>Level</th>
			<th>Longname</th>			
			<th>Actions</th>
			<?php
				$url_base = clean_uri();
				while ($row = mysqli_fetch_assoc($gradelevels_result)) {
					$edit_url = $url_base . "?e=" . $row["GL_ID"];
					$remove_url = $url_base . "?d=" . $row["GL_ID"];
					echo "<tr>
								<td>{$row['level']}</td>
								<td>{$row['longname']}</td>
								<td><a href='{$edit_url}'>Edit</a> | <a href='{$remove_url}'>Remove</a></td>
							</tr>";			
				}
				mysqli_free_result($gradelevels_result);
			?>
		</table>
		
			<form id="gradelevels-edit" method="post" action="admin-gradelevels.php">
				<hr>
				<input id="level" type="text" name="level"></input>
				<label for="level">Level</label><br>
				<input id="longname" type="text" name="longname"></input>
				<label for="longname">Level Longname</label><br>
				<input id="submit" type="submit" name="submit" value="New Grade Level"></input>
			</form>
	
	</div>

</div>


<?php require_once("footer.php"); ?>