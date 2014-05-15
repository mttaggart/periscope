
<?php
$page_title = "Timeline";
require_once("header.php");
require_once("../gantti/lib/gantti.php");

/*$unit_query = "SELECT * FROM Units INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID 
					INNER JOIN Subjects ON Units.Subject_ID = Subjects.S_ID 
					INNER JOIN Assessments ON Units.U_ID = Assessments.U_ID 
					WHERE enabled = 1 AND (StartDate != '0000-00-00' AND EndDate != '0000-00-00')";*/
					
$unit_query = "SELECT * FROM Units INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID 
					INNER JOIN Subjects ON Units.Subject_ID = Subjects.S_ID 
					WHERE enabled = 1 AND (StartDate != '0000-00-00' AND EndDate != '0000-00-00')";

					
//BEGIN FILTER ADJUSTMENTS
					
if(isset($_GET["month"])) {
	$unit_query .= " AND (MONTH(StartDate) = {$_GET['month']} OR MONTH(EndDate) = {$_GET['month']})";	
}

if(isset($_GET["gl"])) {
	$unit_query .= " AND GradeLevel_id = {$_GET['gl']}";
}

if(isset($_GET["s"])) {
	$unit_query .= " AND Subject_ID = {$_GET['s']}";
}

$unit_query .= ";";

$unit_result = mysqli_query($con, $unit_query);
?>

<div id="content-wrapper">
	
	

	<div id="content" class="clearfix">
			
			<?php require_once("../lib/filter.php");?>
			
	
				
			<?php
				date_default_timezone_set('UTC');
				setlocale(LC_ALL, 'en_US');
				
				$data = array();
				
				while($row = mysqli_fetch_assoc($unit_result)) {
					$data[] = array(
						'label' => $row["Name"],
						'start' => $row["StartDate"],
						'end' => $row["EndDate"],
						//'class' => 'important' 
					);				
				
				}
				
				
				$gantti = new Gantti($data, array(
				  'title'      => 'Units',
				  'cellwidth'  => 25,
				  'cellheight' => 35
				));
				
				echo $gantti;


			
					
				
				mysqli_free_result($unit_result);
			
				?>
			
		<?php echo mapping_options();?>

	
	</div>

</div>


<?php require_once("footer.php"); ?>