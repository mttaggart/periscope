<?php
$page_title = "Manage Subjects";
require_once("../lib/sessions.php");
require_once("../lib/val.php");
require_once("header.php");

if(!logged_in()) {
	$_SESSION["errors"] = "Please log in to view this page";
	redirect_to("login.php");
}


$subjects_query = "SELECT * FROM Subjects ORDER BY shortname;";
$subjects_result = mysqli_query($con, $subjects_query);


//POST Handling

if (isset($_POST["submit"])){

	$required_fields = array("shortname", "longname");
	
	val_presences($required_fields);
	if(empty($errors)) { 
		$shortname = mysql_prep($_POST["shortname"]);
		$longname = mysql_prep($_POST["longname"]);
		
		if(isset($_GET["e"])) {
			$edit_subject = mysql_prep($_GET["e"]);
			$update_query = "UPDATE Subjects SET
									shortname = '{$shortname}',
									longname = '{$longname}' 
									WHERE S_ID = {$edit_subject};";
										
			if (mysqli_query($con, $update_query)) {
				mysqli_free_result($subjects_result);
				redirect_to("admin-subjects.php");
			} 
			
		} else {
			$new_subject_query = "INSERT INTO Subjects (shortname, longname)
									 VALUES ('{$shortname}', '{$longname}');";
									 
			if (mysqli_query($con, $new_subject_query)) {
				mysqli_free_result($subjects_result);

				redirect_to("admin-subjects.php");
			}
		}
	}
}

if(isset($_GET["d"])) {
	$subject_d_id = mysql_prep($_GET["d"]);
	$remove_query = "DELETE FROM Subjects WHERE S_ID = {$subject_d_id};";
	
	
		
	if (mysqli_query($con, $remove_query)){
				
			redirect_to("admin-subjects.php");	
	}	
}


?>
<script type="text/javascript">
	$(document).ready(function () {
		<?php
			if(isset($_GET["e"])) {
				$edit_id = $_GET["e"];
				$edit_subject_info = find_subject_by_id($edit_id);
				$edit_shortname = $edit_subject_info["shortname"];
				$edit_longname = $edit_subject_info["longname"];
				echo "$('#subjects-edit').attr('action', 'admin-subjects.php?e={$edit_id}');";
				echo "$('#submit').val('Edit Subject');";				
				echo "$('#shortname').val('{$edit_shortname}');";
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
		
		<table id="subject-table">
			<th>Shortname</th>
			<th>Longname</th>			
			<th>Actions</th>
			<?php
				$url_base = clean_uri();
				while ($row = mysqli_fetch_assoc($subjects_result)) {
					$edit_url = $url_base . "?e=" . $row["S_ID"];
					$remove_url = $url_base . "?d=" . $row["S_ID"];
					echo "<tr>
								<td>{$row['shortname']}</td>
								<td>{$row['longname']}</td>
								<td><a href='{$edit_url}'>Edit</a> | <a href='{$remove_url}'>Remove</a></td>
							</tr>";			
				}
				mysqli_free_result($subjects_result);
			?>
		</table>
		
			<form id="subjects-edit" method="post" action="admin-subjects.php">
				<hr>
				<input id="shortname" type="text" name="shortname"></input>
				<label for="shortname">Subject Shortname</label><br>
				<input id="longname" type="text" name="longname"></input>
				<label for="longname">Subject Longname</label><br>
				<input id="submit" type="submit" name="submit" value="New Subject"></input>
			</form>
	
	</div>

</div>


<?php require_once("footer.php"); ?>