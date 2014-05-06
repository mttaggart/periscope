<?php
	$page_title = "Characters Fix";
	require_once("../public/header.php");
	require_once("sessions.php");
	
	$unit_query = "SELECT * FROM Units";
	$unit_result = mysqli_query($con, $unit_query);
	$units = array();
	while($row = mysqli_fetch_assoc($unit_result)) {
		$units[] = $row["U_ID"];	
	}
	mysqli_free_result($unit_result);
	
?>

<div id="content-wrapper">

	<div id="content">
		<?php
			
			foreach($units as $unit) {
				echo "<p><b>Working on Unit {$unit}</b></p>";
				
				foreach ($asset_tables as $table=>$prefix) {
					$asset_id = $prefix . "_ID";
					echo "<p><b>Working on {$table}</b></p>";
					$asset_query = "SELECT * FROM {$table}
									WHERE U_ID = {$unit}";
					
					$asset_result = mysqli_query($con, $asset_query);
					while($asset_row = mysqli_fetch_assoc($asset_result)) {
						$current_id = $asset_row["{$asset_id}"];
						$test_asset = $asset_row["Text"];
						if(strpos($test_asset, "_x000B_") >= 0) {
							echo "<p style=\"color:red;\">Problem Found: \"{$asset_row['Text']}\"</p>";
							$asset_fix = str_replace("_x000B_", " ", $test_asset);
							$update_query = "UPDATE {$table} 
												  SET Text = '{$asset_fix}'
												  WHERE {$asset_id} = {$current_id};";
							if(mysqli_query($con,$update_query)) {
								echo "<p style=\"color:red;\">Problems Removed!</p>";						
							} else{
								echo "<p style=\"color:red;\">Update Error</p>";
							}
						} else {
							echo "<p style = \"color:green;\">No Problems with \"{$asset_row['Text']}\"</p>";		
						}					
					}
					mysqli_free_result($asset_result);	
				}
			} 

		?>
			 
	</div>

</div>


<?php require_once("footer.php"); ?>