<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
<?php
$page_title = "Assessments by Type";
require_once("header.php");

$unit_query = "SELECT * FROM Units INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID 
					INNER JOIN Subjects ON Units.Subject_ID = Subjects.S_ID 
					INNER JOIN Assessments ON Units.U_ID = Assessments.U_ID 
					WHERE enabled = 1";
					
//BEGIN FILTER ADJUSTMENTS

					
if(isset($_GET["month"])) {
	$unit_query .= " AND (MONTH(StartDate) = {$_GET["month"]} OR MONTH(EndDate) = {$_GET["month"]})";	
}

if(isset($_GET["gl"])) {
	$unit_query .= " AND GradeLevel_id = {$_GET["gl"]}";
}

if(isset($_GET["s"])) {
	$unit_query .= " AND Subject_ID = {$_GET["s"]}";
}

//END FILTER ADJUSTMENTS


//$unit_query .= ";";

$unit_result = mysqli_query($con, $unit_query);
$asstype_query = "SELECT * FROM AssessmentTypes";
$asstype_result = mysqli_query($con, $asstype_query);

//build asstype array

$asstypes = array();

while ($asstype = mysqli_fetch_assoc($asstype_result)) {
	
	$type_count_query = $unit_query;
	
	if(isset($_GET["s"]) || isset($_GET["month"]) || isset($_GET["gl"])) {
	
		$type_count_query .= " AND AT_ID = {$asstype['AT_ID']}";
	
	} else{
	
		$type_count_query .= " WHERE AT_ID = {$asstype['AT_ID']}";
	}

	$type_count_result = mysqli_query($con, $type_count_query);
	$asstypes[$asstype["AT_Text"]] = mysqli_num_rows($type_count_result);
	mysqli_free_result($type_count_result);
}
?>

<script type="text/javascript">
    
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
      
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);


      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Assessment');
      data.addColumn('number', 'Occurrences');
  
      <?php
      
      	foreach($asstypes as $type=>$count) {
				echo "data.addRow(['{$type}',$count]);";      	
      	}
      
      ?>

      // Set chart options
      var options = {'title':'Assessments by Type',
                     'width':1000,
                     'height':600};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>

<div id="content-wrapper">
	
	

	<div id="content" class="clearfix">
			
		<?php require_once("../lib/filter.php");?>
		
		<div id="chart_div" align="center">
		
		
		</div>
		<?php echo mapping_options(); ?>
			

	
	</div>

</div>


<?php require_once("footer.php"); ?>