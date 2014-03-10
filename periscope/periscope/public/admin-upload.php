<?php
$page_title = "Upload Files";
require_once("../lib/sessions.php");
require_once("header.php");
require_once("../lib/val.php");


if(!logged_in()) {
	$_SESSION["errors"] = "Please log in to view this page";
	redirect_to("login.php");
}


//POST Handling

if (isset($_POST["submit"])){

	if ( isset($_FILES["file"])) {

		//if there was an error uploading the file 
		if ($_FILES["file"]["error"] > 0) {
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		
		} else {
			//if file already exists    
			if (file_exists("uploads/" . $_FILES["file"]["name"])) {
				echo $_FILES["file"]["name"] . " already exists. ";
			} else {
				
         	$storagename = $_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $upload_dir . $storagename); 
            if($work_file = fopen($upload_dir . $storagename, "r")) {
            	if($_POST["table"]=="Units") {
						$new_units = array();
						$unit_count = 0;
						 while($data = fgetcsv($work_file, 1000, ",")) {
						 	$new_units[$unit_count]["U_ID"] = $data[0];
							$new_units[$unit_count]["Name"] = $data[1];
							$new_units[$unit_count]["GradeLevel_id"] = $data[2];
							$new_units[$unit_count]["Subject_id"] = $data[3];
							$new_units[$unit_count]["StartDate"] = $data[4];
							$new_units[$unit_count]["EndDate"] = $data[5];
							$unit_count++;
						}
					
						foreach($new_units as $new_unit) {
						$new_unit_query = "INSERT INTO Units (Name, GradeLevel_id, Subject_id, StartDate, EndDate)
													VALUES ('{$new_unit['Name']}',
													'{$new_unit['GradeLevel_id']}',
													'{$new_unit['Subject_id']}',
													'{$new_unit['StartDate']}',
													'{$new_unit['EndDate']}');";
						if(!mysqli_query($con, $new_unit_query)) {
							echo "Insert error on {$new_unit['Name']}";					
							} else {
								echo "<p style='color:white;'>File uploaded!</p>";
							}
						}
					} else {
						$asset_table = mysql_prep($_POST["table"]);
						$new_assets = array();
						$asset_count = 0;
						while($data = fgetcsv($work_file, 1000, ",")) {
							$new_assets[$asset_count]["U_ID"] = $data[0];
							$new_assets[$asset_count]["Text"] = $data[1];
							if($asset_table == "Assessments") {
								$new_assets[$asset_count]["AT_ID"] = $data[2];
							}
							$asset_count++;
						}
						foreach($new_assets as $new_asset) {
							$new_asset_query = "INSERT INTO {$asset_table} (U_ID, Text";
							if($asset_table == "Assessments") {
								$new_asset_query .= ", AT_ID";
							}						
							$new_asset_query .= ")";
							$new_asset_query .=	" VALUES ('{$new_asset['U_ID']}',
														'{$new_asset['Text']}'";
							if($asset_table == "Assessments") {
								$new_asset_query .= ", '{$new_asset['AT_ID']}'";
							}
														
							$new_asset_query .= ");";
							if(!mysqli_query($con, $new_asset_query)) {
								echo "Insert error on {$new_asset['Text']}";					
							} else {
								echo "<p style='color:white;'>File uploaded!</p>";
							}
						}
					}
					fclose($work_file); 
					unlink($upload_dir . $storagename);
				
				
            } else {
					echo "Couldn't work!";            
            }
            
			}
		}
	} else {
		echo "No file selected <br />";
 	}
}



?>
<script type="text/javascript">
	$(document).ready(function () {
		
	});
</script>
<div id="content-wrapper">

	<div id="content">
		<?php 
			echo errors() . "<br>";
			list_errors();
		?>

		
			<form id="upload-csv" enctype="multipart/form-data" method="post" action="admin-upload.php">
				<hr>
				<p>Uploaded files must be <b>.csv</b> files. They also must have the following columns:</p>
				
				<h3>For Units:</h3>
				<table>
					<th>U_ID</th>
					<th>Name</th>
					<th>GradeLevel_id</th>
					<th>Subject_id</th>	
					<th>StartDate</th>
					<tr>
						<td>INT</td>
						<td>VARCHAR(30)</td>	
						<td>INT(11)</td>
						<td>VARCHAR(30)</td>
						<td>YYYY-MM-DD</td>
						<td>YYYY-MM-DD</td>				
					</tr>			
				</table>
				
				<h3>For Assets:</h3>
				<table>
					<th>U_ID</th>
					<th>Text</th>
					<tr>
						<td>INT</td>
						<td>VARCHAR(255)</td>			
					</tr>			
				</table>
				<input id="file" type="file" name="file"></input>
				<select id="table" name="table">Select Table
					<option value="Units">Units</option>
					<option value="EssentialQuestions">Essential Questions</option>
					<option value="Content">Content</option>	
					<option value="Skills">Skills</option>		
					<option value="Activities">Activities</option>
					<option value="Resources">Resources</option>
					<option value="Assessments">Assessments</option>	
				</select>
				<input type="hidden" name="MAX_FILE_SIZE" value="25000" />
				<label for="file">Upload File</label><br>
				<input id="submit" type="submit" name="submit" value="Upload"></input>
			</form>
	
	</div>

</div>


<?php require_once("footer.php"); ?>