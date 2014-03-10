<?php
$page_title = "Manage Assessment Types";
require_once("../lib/sessions.php");
require_once("../lib/val.php");
require_once("header.php");

if(!logged_in()) {
	$_SESSION["errors"] = "Please log in to view this page";
	redirect_to("login.php");
}


$at_query = "SELECT * FROM AssessmentTypes;";
$at_result = mysqli_query($con, $at_query);


//POST Handling

if (isset($_POST["submit"])){

	$required_fields = array("at_text");
	
	val_presences($required_fields);
	if(empty($errors)) { 
		$at_text = mysql_prep($_POST["at_text"]);
		
		if(isset($_GET["e"])) {
			$edit_at = mysql_prep($_GET["e"]);
			$update_query = "UPDATE AssessmentTypes SET
									AT_Text = '{$at_text}'
									WHERE AT_ID = {$edit_at};";
										
			if (mysqli_query($con, $update_query)) {
				mysqli_free_result($at_result);
				redirect_to("admin-asstypes.php");
			} 
			
		} else {
			$new_at_query = "INSERT INTO AssessmentTypes (AT_Text)
									 VALUES ('{$at_text}');";
									 
			if (mysqli_query($con, $new_at_query)) {
				mysqli_free_result($at_result);

				redirect_to("admin-asstypes.php");
			}
		}
	}
}

if(isset($_GET["d"])) {
	$at_d_id = mysql_prep($_GET["d"]);
	$remove_query = "DELETE FROM AssessmentTypes WHERE AT_ID = {$at_d_id};";
	
	
		
	if (mysqli_query($con, $remove_query)){
				
			redirect_to("admin-asstypes.php");	
	}	
}


?>
<script type="text/javascript">
	$(document).ready(function () {
		<?php
			if(isset($_GET["e"])) {
				$edit_id = $_GET["e"];
				$edit_at_info = find_asstype_by_id($edit_id);
				$edit_text = $edit_at_info["AT_Text"];
				echo "$('#asstype-edit').attr('action', 'admin-asstypes.php?e={$edit_id}');";
				echo "$('#submit').val('Edit Assessment Type');";				
				echo "$('#at_text').val('{$edit_text}');";	
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
		
		<table id="asstypes-table">
			<th>Type</th>			
			<th>Actions</th>
			<?php
				$url_base = clean_uri();
				while ($row = mysqli_fetch_assoc($at_result)) {
					$edit_url = $url_base . "?e=" . $row["AT_ID"];
					$remove_url = $url_base . "?d=" . $row["AT_ID"];
					echo "<tr>
								<td>{$row['AT_Text']}</td>
								<td><a href='{$edit_url}'>Edit</a> | <a href='{$remove_url}'>Remove</a></td>
							</tr>";			
				}
				mysqli_free_result($at_result);
			?>
		</table>
		
			<form id="asstype-edit" method="post" action="admin-asstypes.php">
				<hr>
				<input id="at_text" type="text" name="at_text"></input>
				<label for="at_text">Assessment Type</label><br>
				<input id="submit" type="submit" name="submit" value="New Assessment Type"></input>
			</form>
	
	</div>

</div>


<?php require_once("footer.php"); ?>