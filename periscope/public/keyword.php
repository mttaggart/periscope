 <?php
$page_title = "Keyword Search";
require_once("header.php");
require_once("../lib/val.php");

$asset_tables = array_flip($assets);
$asset_tables["eq"] = "EssentialQuestions";


if(isset($_POST["submit"])) {
	
	$required_fields = array("keywords","allorone");	
	val_presences($required_fields);
	if(empty($errors)) {

		
		$search_assets = $_POST["assets"];
		$keywords = array_map("trim",explode("\n",$_POST["keywords"])); //handles whitespaces after explode
		
		  
		$found_units = array(); //will take form of asset=>array(UID=>array(info))
		
		foreach($search_assets as $search_asset) {
			$asset_key = $asset_tables[$search_asset];
			$found_units[$asset_key] = array();
		}
		foreach(array_keys($found_units) as $asset) {
			$asset_query = "SELECT * FROM {$asset}
								 INNER JOIN Units ON {$asset}.U_ID = Units.U_ID
								 WHERE Units.enabled = 1 AND ( ";
			
			if($_POST["allorone"]=="all") {
				$keyword_operator = "AND";
			} else {
				$keyword_operator = "OR";				
			}					
			
			$first_keyword = true;
			foreach($keywords as $keyword) {
				if(!$first_keyword) {
					$asset_query .= $keyword_operator;					
				}
				$first_keyword = false;
				$asset_query .= " {$asset}.Text LIKE '%{$keyword}%'";				
			}
				
			$asset_query .= ")";
			$asset_result = mysqli_query($con, $asset_query);
			
			while($row=mysqli_fetch_assoc($asset_result)) {
				$found_uid = $row["U_ID"];
				$unit_query = "SELECT * FROM Units
						INNER JOIN GradeLevels on Units.GradeLevel_id = GradeLevels.GL_ID
						INNER JOIN Subjects on Units.Subject_id = Subjects.S_ID
						WHERE Units.enabled = 1";
				if(isset($_GET["month"])) {
					$unit_query .= " AND (MONTH(StartDate) = {$_GET['month']} OR MONTH(EndDate) = {$_GET['month']})";
				}

				if(isset($_GET["gl"])) {
					$unit_query .= " AND GradeLevel_id = {$_GET['gl']}";
				}

				if(isset($_GET["s"])) {
					$unit_query .= " AND Subject_ID = {$_GET['s']}";
				}
		
				$unit_query .= " AND Units.U_ID = {$found_uid}";
						
				$unit_result = mysqli_query($con, $unit_query);
				$found_units[$asset][$found_uid] = mysqli_fetch_assoc($unit_result);
				mysqli_free_result($unit_result);	
			}	
			mysqli_free_result($asset_result);
			
			/*
			So unit info goes likes this: $found_units[$asset_table][$uid][$column][$value]
			Example: $found_units["EssentialQuestions"][11]["level"] == "JK"			
			*/
		}
	
		$unit_title_query = "SELECT * FROM Units INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID INNER JOIN Subjects ON Units.Subject_ID = Subjects.S_ID WHERE ";
		if($_POST["allorone"]=="all") {
				$keyword_operator = "AND";
		} else {
				$keyword_operator = "OR";				
		}					
			
		$first_keyword = true;
		foreach($keywords as $keyword) {
			if(!$first_keyword) {
				$unit_title_query .= $keyword_operator;					
			}
			$first_keyword = false;
			$unit_title_query .= " Name LIKE '%{$keyword}%'";				
		}
	
		$unit_title_result = mysqli_query($con, $unit_title_query);
				
	}
}

?>
<script>
	$(document).ready( function() {
	<?php
		if(!isset($_POST["submit"])) {
			echo "$('#unit-list').hide();";
		}
	?>
	$('#EssentialQuestions-heading').text('Essential Questions');
	$("#checkall").click(function(){
   		$('input:checkbox').not(this).prop('checked', this.checked);
		});
	});
	
</script>


<div id="content-wrapper">
	
	

	<div id="content" class="clearfix">
			
		<?php 
			//require_once("../lib/filter.php");
			list_errors();
		?>
		
		<form id="keyword-search" method="POST" action="keyword.php">
			<label for="keywords">Enter each keyword you wish to search for separated by a line break.</label><br>
			<textarea rows="10" cols="20" name="keywords" value="Enter Keywords Here"></textarea>
			<p>Check the Assets to be included in the search:</p>
			<?php
				echo "<input id=\"checkall\" type = \"checkbox\" name=\"checkall\" value=\"all\"><label for=\"checkall\">Select All</label><br>";
				foreach($assets as $label=>$id) {
					echo "<input id=\"{$id}\" type=\"checkbox\" name=\"assets[]\" value=\"{$id}\">
							<label for=\"{$id}\">{$label}</label>";				
				}
			
			
			?>
			<br>
			<p>Units must contain:</p>
			<input id="all" type="radio" name="allorone" value="all"><label for="all">All keywords</label><br>
			<input id="one" type="radio" name="allorone" value="one"><label for="one">One of the keywords</label><br>
			<br>
			<input type="submit" name="submit" value="Search"></input>
		
		</form>
	
				
			
			
		<?php echo mapping_options();?>
		
		<div id="results">
		
			<div id="unit-list">
				<hr>			
				<h2>Results</h2>
				
				<?php
					echo "<hr><h3 id=\"title-heading\">Title</h3>";
					echo "<table class=\"unit-table\">
									<th>Unit Name</th>
									<th>Grade Level</th>
									<th>Subject</th>
									<th>Start Date</th>
									<th>End Date</th>";
					while($unit = mysqli_fetch_assoc($unit_title_result)) {
						echo "<tr class=\"unit-row\">";
							echo "<td>" . "<a href='view-unit.php?u=". $unit["U_ID"] . "'>" . $unit["Name"] . "</a></td>" .
									"<td>" . $unit["level"] . "</td>" .
									"<td>" . $unit["shortname"] . "</td>" .  
									"<td>" . $unit["StartDate"] . "</td>" .
									"<td>" . $unit["EndDate"] . "</td>";			
							echo "</tr>";										
							
					}
					mysqli_free_result($unit_title_result);
					echo "</table>";						
									
					foreach(array_keys($found_units) as $asset) {
						echo "<hr><h3 id=\"{$asset}-heading\">{$asset}</h3>";
						echo "<table class=\"unit-table\">
									<th>Unit Name</th>
									<th>Grade Level</th>
									<th>Subject</th>
									<th>Start Date</th>
									<th>End Date</th>";
						foreach($found_units[$asset] as $units) {

							echo "<tr class=\"unit-row\">";
							echo "<td>" . "<a href='view-unit.php?u=". $units["U_ID"] . "'>" . $units["Name"] . "</a></td>" .
									"<td>" . $units["level"] . "</td>" .
									"<td>" . $units["shortname"] . "</td>" .  
									"<td>" . $units["StartDate"] . "</td>" .
									"<td>" . $units["EndDate"] . "</td>";			
							echo "</tr>";										
							
						}
						echo "</table>";							
					}
				?>
	
			</div>
		</div>	

	
	</div>

</div>


<?php require_once("footer.php"); ?>