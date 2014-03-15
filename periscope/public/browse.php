

<?php
$page_title = "Browse Units";
require_once("header.php");

$unit_query = "SELECT * FROM Units INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID INNER JOIN Subjects ON Units.Subject_ID = Subjects.S_ID WHERE enabled = 1 ";
if(isset($_GET["month"])) {
	$unit_query .= " AND (MONTH(StartDate) = {$_GET["month"]} OR MONTH(EndDate) = {$_GET["month"]})";	
}

if(isset($_GET["gl"])) {
	$unit_query .= " AND GradeLevel_id = {$_GET["gl"]}";
}

if(isset($_GET["s"])) {
	$unit_query .= " AND Subject_ID = {$_GET["s"]}";
}

if(isset($_POST["sort"])) {
	switch($_POST["sort"]) {
		case null:
			break;
		case "name-asc":
			$unit_query .= " ORDER BY Name ASC";
			break;
		case "name-des":
			$unit_query .= " ORDER BY Name DESC";
			break;
		case "gl-asc":
			$unit_query .= " ORDER BY GradeLevels.level ASC";
			break;
		case "gl-des":
			$unit_query .= " ORDER BY GradeLevels.level DESC";
			break;
		case "s-asc":
			$unit_query .= " ORDER BY Subjects.shortname ASC";
			break;
		case "s-des":
			$unit_query .= " ORDER BY Subjects.shortname DESC";
			break;
		case "startdate-asc":
			$unit_query .= " ORDER BY StartDate ASC";
			break;
		case "startdate-des":
			$unit_query .= " ORDER BY StartDate DESC";
			break;			
		case "enddate-asc":
			$unit_query .= " ORDER BY EndDate ASC";
			break;
		case "enddate-des":
			$unit_query .= " ORDER BY EndDate DESC";
			break;												
		default:
			break;
	}
}

$unit_query .= ";";

//echo $unit_query;

$unit_result = mysqli_query($con, $unit_query);

$current_url = url_rebuild();

?>
<script>
	$(document).ready(function() {
		$('.unit-row').hide();
		$('.page-1').show(300);
		$('#pb1').addClass('clicked');
		$('.pagebutton').click(function () {
			
		});
	});
</script>
<div id="content-wrapper">
	
	

	<div id="content" class="clearfix">
		<?php require_once("../lib/filter.php");?>
		
			<div id="unit-list">
				<div class="pagination-holder clearfix">
					<div id="light-pagination" class="pagination"></div>
				</div>
				
				<script>
					function showPage(pagenum) {
						var pageclass = ".page-" + pagenum;
						var buttonid = "#pb" + pagenum;
						$('.unit-row').hide();
						$(pageclass).show(300);
						$('.pagebutton a').removeClass('clicked');
						$(buttonid).addClass('clicked');	 
				 	}				
				</script>
				<table id="unit-table">
				
					<th>Unit Name</th>
					<th>Grade Level</th>
					<th>Subject</th>
					<th>Start Date</th>
					<th>End Date</th>
					
					<?php	
						$unit_pages = 1;	
						$units_left = true;
						while($units_left){
							$onpage = 0;
							$page_class = "page-{$unit_pages}";
							while($onpage < $perpage){
								if($row=mysqli_fetch_assoc($unit_result)) {
									echo "<tr class=\"unit-row {$page_class}\">";
									echo "<td>" . "<a href='view-unit.php?u=". $row["U_ID"] . "'>" . $row["Name"] . "</a></td>" .
											"<td align=\"center\">" . $row["level"] . "</td>" .
											"<td>" . $row["shortname"] . "</td>" .  
											"<td>" . $row["StartDate"] . "</td>" .
											"<td>" . $row["EndDate"] . "</td>";		
									echo "</tr>";
									$onpage ++;
								} else {
									$units_left = false;
									break;								
								}	
							}
							if($units_left) {
								$unit_pages++;
							}	
						}
					
						mysqli_free_result($unit_result);
					?>
				
				
				</table>
				<div id="pagenav">
					<ul id="page-list">
						<?php
							for($i=1;$i<=$unit_pages;$i++) {
								echo "<li class =\"pagebutton\"><a id=\"pb{$i}\" class = \"editbutton\" href=\"#page-{$i}\" onclick = \"showPage({$i})\">{$i}</a></li>";	
							}
						?>				
					
					</ul>
					<?php echo "<form id=\"sortselect\" action=\"{$current_url}\" method=\"POST\">";?>
						<select name="sort" onchange="this.form.submit()">
							<option>Sort By:</option>
							<optgroup>
								<option value="name-asc">Name: Ascending</option>
								<option value="name-des">Name: Descending</option>
								<option value="gl-asc">Grade Level: Ascending</option>
								<option value="gl-des">Grade Level: Descending</option>
								<option value="s-asc">Subject: Ascending</option>
								<option value="s-des">Subject: Descending</option>
								<option value="startdate-asc">Start Date: Ascending</option>
								<option value="startdate-des">Start Date: Descending</option>
								<option value="enddate-asc">End Date: Ascending</option>
								<option value="enddate-des">End Date: Descending</option>
							</optgroup>
						</select>			
					</form>
				</div>
			</div>
			
			
			<?php echo mapping_options();?>
		
		
	</div>

</div>


<?php require_once("footer.php"); ?>