<?php
$page_title = "View Unit";
require_once("header.php");
$unit_uid = mysql_prep($_GET["u"]);

$unit_query = "SELECT * FROM Units INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID 
					INNER JOIN Subjects ON Units.Subject_ID = Subjects.S_ID 
					WHERE U_ID = {$unit_uid} AND enabled=1;";
					
$unit_result = mysqli_query($con, $unit_query);
$unit_info = mysqli_fetch_assoc($unit_result);
?>

<script>
	$(document).ready(function() {
	
		$('#eq').show(300);
	
	});
	function clicked(asset) {
		$(".asset-container").hide();
		$("#" + asset).show(300);
	}

		
</script>

<div id="content-wrapper">

	<div id="content">		
			<div id="content-clearfix" class="clearfix">
				<div id="unitinfo">
					
					<h2><?php echo $unit_info["Name"];?></h2>	
						
					<h3>Subject:</h3>
					<p><?php echo $unit_info["longname"];?></p>
					
					<h3>Grade Level:</h3>
					<p><?php echo $unit_info["level"];?></p>
					
					<h3>Start Date:</h3>
					<p><?php echo $unit_info["StartDate"];?></p>
					
					<h3>End Date:</h3>
					<p><?php echo $unit_info["EndDate"];?></p>
					
					<?php echo "<h4><a class= 'menubutton' href='edit-unit.php?u={$unit_uid}'>Edit Unit</a></h4>";?>
					
				</div>
				
				<ul id="slidenav">
					<li><a class="menubutton" href="#eq" onclick="clicked('eq');">Essential Questions</a></li>
					<li><a class="menubutton" href="#con" onclick="clicked('con');">Content</a></li>
					<li><a class="menubutton" href="#skl" onclick="clicked('skl');">Skills</a></li>
					<li><a class="menubutton" href="#act" onclick="clicked('act');">Activities</a></li>	
					<li><a class="menubutton" href="#rsc" onclick="clicked('rsc');">Resources</a></li>
					<li><a class="menubutton" href="#ass" onclick="clicked('ass');">Assessments</a></li>		
				</ul>			
				
				<div id="assets">
				
				
				
				
					<?php
						
						//fix for EQ table name vs. label
											
						foreach (array_keys($assets) as $asset) {
							if ($asset == "Essential Questions") {
								$asset_table = "EssentialQuestions";
							} else {
							
								$asset_table = $asset;
							
							}
							if($asset_table == "Assessments") {
								$asset_query = "SELECT * FROM {$asset_table} 
													INNER JOIN AssessmentTypes ON {$asset_table}.AT_ID = AssessmentTypes.AT_ID 
													WHERE U_ID = {$unit_info['U_ID']};";
							} else {
								$asset_query = "SELECT * FROM {$asset_table} WHERE U_ID = {$unit_uid};";
							}
							$asset_result = mysqli_query($con, $asset_query);
							
							echo "<div class = 'asset-container' id = '". $assets[$asset] . "'>" . "<h3>". $asset . "</h3>";
							echo "<ol class = 'asset-list'>";	
							while($row=mysqli_fetch_assoc($asset_result)) {
								echo "<li>" . $row["Text"];
								if ($asset_table == "Assessments") {
									echo "<b> Type: " . $row["AT_Text"] . "</b>";								
								}
								echo "</li>";						
							}
								
							echo "</ol>";
							
							echo "<div class = 'editbutton'>
										<a href='edit-asset.php?u={$_GET['u']}&a={$assets[$asset]}'>Edit {$asset}</a>
										</div>
									</div>";
								
							mysqli_free_result($asset_result);								
						}
					
					?>
					
					
				</div>
			</div>
	</div>

</div>


<?php require_once("footer.php"); ?>