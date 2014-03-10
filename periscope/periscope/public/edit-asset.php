<?php
$page_title = "Edit Asset";
require_once("header.php");
require_once("../lib/val.php");

//Deal with space in EQ name vs. Database//

$asset_title = array_search($_GET["a"], $assets);

if ($_GET["a"] == "eq") 
{
	$asset_table = "EssentialQuestions";
} else {

	$asset_table = $asset_title;
}



$asset_id = mysql_prep(strtoupper($_GET["a"]) . "_ID");
$asset_uid = mysql_prep($_GET["u"]);

if ($_GET["a"] === "ass") {
	//query inner joins AssessmentTypes
	
	$asset_query = "SELECT * FROM {$asset_table} 
						INNER JOIN AssessmentTypes ON {$asset_table}.AT_ID = AssessmentTypes.AT_ID 
						INNER JOIN Units ON {$asset_table}.U_ID = Units.U_ID WHERE {$asset_table}.U_ID = {$asset_uid};";
} else {
	$asset_query = "SELECT * FROM {$asset_table} 
						INNER JOIN Units ON {$asset_table}.U_ID = Units.U_ID WHERE {$asset_table}.U_ID = {$asset_uid};	";
}

$asset_result = mysqli_query($con, $asset_query);

//Post Handling

if (isset($_POST["submit"])){

	$required_fields = array("asset-text");
	
	val_presences($required_fields);
	if(empty($errors)) {
		$asset_text = mysql_prep($_POST["asset-text"]);
		
		if(isset($_GET["eaid"])) {
			
			
			$asset_eaid = mysql_prep($_GET["eaid"]);
			$update_query = "UPDATE {$asset_table} SET
								Text = '{$asset_text}' ";
			if($_GET["a"] === "ass") {
								$assessment_type = mysql_prep($_POST["assessment-type"]); 					
								$update_query .= ", AT_ID = {$assessment_type} ";
			}
			$update_query .= "WHERE {$asset_id} = {$asset_eaid};";			
			
									
			if (mysqli_query($con, $update_query)) {
				echo "<script type='text/javascript'>";
				echo "alert('{$asset_title} updated!');";
				echo "</script>";
				mysqli_free_result($asset_result);
				header("Location: " . clean_uri() . "?u={$asset_uid}&a={$_GET['a']}");
			
				
			} 
			
		}  else {
				
				if($_GET["a"] === "ass") {
					$assessment_type = mysql_prep($_POST["assessment-type"]); 
					$new_asset_query = "INSERT INTO {$asset_table} (U_ID, Text, AT_ID)
										 VALUES ({$asset_uid}, '{$asset_text}', {$assessment_type});";
				} else {
					$new_asset_query = "INSERT INTO {$asset_table} (U_ID, Text)
									 VALUES ({$asset_uid}, '{$asset_text}');";
				}
				 
									
				//Alert box to indicate asset added

				if (mysqli_query($con, $new_asset_query)) {
					echo "<script type='text/javascript'>";
					echo "alert('{$asset_title} updated!');";
					echo "</script>";	
					mysqli_free_result($asset_result);
					header("Location: " . clean_uri() . "?u={$asset_uid}&a={$_GET['a']}");
				}
			}
	}
}


if(isset($_GET["daid"])) {
	$asset_daid = mysql_prep($_GET["daid"]);
	$remove_query = "DELETE FROM {$asset_table} WHERE {$asset_id} = {$asset_daid};";
	
	
		
	if (mysqli_query($con, $remove_query)){
				
			echo "<script language='javascript'>";
			echo "alert('". $asset_title . " deleted!');";
			echo "</script>";
			//Split off aid for redirect to avoid infinite loop
						
			header("Location: " . substr($_SERVER["REQUEST_URI"], 0 , strrpos($_SERVER["REQUEST_URI"], "&")));	
	}	
}

?>

<script>
	
	$(document).ready(function(){ 
		<?php
			$unit_uid = mysql_prep($_GET["u"]);
			$unit_query = "SELECT Name FROM Units WHERE Units.U_ID = {$unit_uid}";
			$unit_info = mysqli_fetch_assoc(mysqli_query($con, $unit_query));
			echo "$('#page-title h1').text('Edit {$unit_info['Name']}: {$asset_title}');";
			echo "$('#backbutton').text('Back to {$unit_info['Name']}');";
			
		?>
		
	
	});
	


</script>

<div id="content-wrapper">

	<div id="content">
		
		<a class="menubutton" id="backbutton" href=<?php echo "'view-unit.php?u={$_GET["u"]}';"?>>Back to Unit</a>
		<?php 
			list_errors();
		?>
	
		<ol id = "asset-list">
			<?php
				while($row = mysqli_fetch_assoc($asset_result)) {
					if(isset($_GET["eaid"]) && $row[$asset_id] == $_GET["eaid"]) {
						$edit_field = $row["Text"];				
					}
										
					echo "<li>" . $row["Text"];
					if ($asset_table == "Assessments") {
									echo "<b> Type: " . $row["AT_Text"] . "</b>";								
					}
					echo " | ";
					
					echo "<a href='" . substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"&")) . "&a={$_GET['a']}&eaid=" . $row[$asset_id] . "'>Edit</a> " .
					 "<a href='". substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"&")) . "&a={$_GET['a']}&daid=" . $row[$asset_id] . "'>Remove</a>" . 
					  "</li>";
				}
			?>
		
		</ol>
	
			
			<?php 				
				
				if(isset($_GET["eaid"])) {
					
					$action_url = "edit-asset.php?u={$asset_uid}&a={$_GET['a']}&eaid={$_GET['eaid']}";			
				} else{
				
					$action_url = "edit-asset.php?u={$asset_uid}&a={$_GET['a']}";
				}				
				
				echo "<form id='editasset' method='post' action='{$action_url}'>";
			?>
				
				
				<input id="asset-text" size= "100" type="text" name="asset-text"><?php echo "{$asset_title} Text";?></input><br>
				<?php
					
					//extra fields for Assessment Type 
				
					if($_GET["a"] === "ass") {
						$at_query = "SELECT * FROM AssessmentTypes ORDER BY AT_Text;";
						$at_result = mysqli_query($con, $at_query);
						echo "<select id=\"assessment-type\" name=\"assessment-type\">";
						while($at_row = mysqli_fetch_assoc($at_result)) {
							echo "<option value = '{$at_row['AT_ID']}'>{$at_row['AT_Text']}</option>";
						}
						mysqli_free_result($at_result);
						echo "</select>";	
						echo "<label for=\"assessment-type\">Assessment Type</label>";		
					}
				?>
			
				<br>
				<?php
					if(isset($edit_field)) {						
						echo "<script type='text/javascript'>$('#asset-text').val('{$edit_field}');</script>";
					} 
					echo "<input type='submit' name='submit'";
					if(isset($_GET["eaid"])) { 
						echo "value='Edit {$asset_title}'>";
					} else {
						echo "value='Add {$asset_title}'>";
					}

				?>
			</form>
	
	</div>

</div>


<?php require_once("footer.php"); ?>