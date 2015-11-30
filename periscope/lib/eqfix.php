<?php
	$page_title = "EQ Fix";
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
			$eqs = array();
			
			foreach($units as $unit) {
				echo "<p><b>Working on Unit {$unit}</b></p>";
				$eq_query = "SELECT * FROM EssentialQuestions
									WHERE U_ID = {$unit}";
				$eq_result = mysqli_query($con,$eq_query);
				$eqs[$unit]=array();
				while ($eq_row = mysqli_fetch_assoc($eq_result)) {
					if(in_array($eq_row["Text"],$eqs[$unit])) {
						echo "<p style=\"color:red;\">Duplicate Found: \"{$eq_row['Text']}\"</p>";
						$delete_query = "DELETE FROM EssentialQuestions WHERE EQ_ID = {$eq_row['EQ_ID']}";
						if(mysqli_query($con,$delete_query)) {
							echo "<p style=\"color:red;\">Duplicate Deleted!</p>";						
						} else{
							echo "<p style=\"color:red;\">Deletion Error</p>";
						}
						echo "<br>";			
					} else{
						$eqs[$unit][] = $eq_row["Text"];	
						echo "<p style=\"color:green;\">Unique: {$eq_row['Text']}</p>";
					}				
				}
				mysqli_free_result($eq_result);	
			}
			
		?>
			 
	</div>

</div>


<?php require_once("footer.php"); ?>