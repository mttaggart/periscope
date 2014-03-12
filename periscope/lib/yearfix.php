<?php
	$page_title = "Year Fix";
	require_once("../public/header.php");
	require_once("sessions.php");
	$school_year = array();
	
	if(intval(date('M')) < 12 && intval(date('M')) > 7) {
		$school_year[] = intval(date("Y"));
	} else{
		$school_year[] = intval(date("Y")) - 1;
	}
		$school_year[] = $school_year[0] + 1;
		
	$unit_query = "SELECT * FROM Units WHERE (StartDate != '0000-00-00' AND EndDate != '0000-00-00')";
	
	$unit_result = mysqli_query($con, $unit_query);
	

?>

<div id="content-wrapper">

	<div id="content">
		<?php
			function month_check($month) {
				if ($month <= 12 && $month >= 7)	{
					return 0;				
				}	elseif($month >= 1 && $month < 7) {
					return 1;				
				}
			}
		
			while($unit = mysqli_fetch_assoc($unit_result)) {
				$start_year = intval(substr($unit["StartDate"],0,4));
				$end_year = intval(substr($unit["StartDate"],0,4));
				$start_month = intval(substr($unit["StartDate"],5,2));
				$end_month = intval(substr($unit["EndDate"],5,2));
				$start_day = intval(substr($unit["StartDate"],8,2));
				$end_day = intval(substr($unit["EndDate"],8,2));
				$new_start_year = $school_year[month_check($start_month)];
				$new_end_year = $school_year[month_check($end_month)];				
				
				echo "<p>Updating Unit {$unit['Name']}:</p>";
				
				$update_query = "UPDATE Units SET
										StartDate = '{$new_start_year}-{$start_month}-{$start_day}',
										EndDate = '{$new_end_year}-{$end_month}-{$end_day}'
										WHERE U_ID = {$unit['U_ID']};";
				if(!mysqli_query($con, $update_query)) {
				 echo "Update Error!";
				}	else {
					echo "<p>Original Start: {$start_month}/{$start_year}, New: {$new_start_year}</p>";
					echo "<p>Original End: {$end_month}/{$end_year}, New: {$new_end_year}</p>";
				}		

			}
			mysqli_free_result($unit_result);
		?>
			
	 <?php var_dump($school_year); ?> 
	</div>

</div>


<?php require_once("footer.php"); ?>