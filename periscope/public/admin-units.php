<?php
$page_title = "Manage Units";
require_once("../lib/sessions.php");
require_once("../lib/val.php");
require_once("header.php");

if(!logged_in()) {
	$_SESSION["errors"] = "Please log in to view this page";
	redirect_to("login.php");
}

$unit_query = "SELECT * FROM Units INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID INNER JOIN Subjects ON Units.Subject_ID = Subjects.S_ID WHERE (enabled = 0 OR enabled = 1)";
if(isset($_GET["month"])) {
	$unit_query .= " AND (MONTH(StartDate) = {$_GET["month"]} OR MONTH(EndDate) = {$_GET["month"]})";	
}

if(isset($_GET["gl"])) {
	$unit_query .= " AND GradeLevel_id = {$_GET["gl"]}";
}

if(isset($_GET["s"])) {
	$unit_query .= " AND Subject_ID = {$_GET["s"]}";
}

$unit_query .= ";";

$unit_result = mysqli_query($con, $unit_query);

//BEGIN ENABLE/DISABLE QUERIES

if(isset($_GET["d"]) || isset($_GET["e"])){
	
	if(isset($_GET["d"])) {
		$action_id = mysql_prep($_GET["d"]);
		$action = 0;	
	}
	if(isset($_GET["e"])) {
		$action_id = mysql_prep($_GET["e"]);
		$action = 1;	
	}
	$update_query = "UPDATE Units
							SET enabled = {$action}
							WHERE U_ID = {$action_id};";
							
	if(mysqli_query($con, $update_query)) {
		redirect_to(url_rebuild());
	}
}

//END ENABLE/DISABLE QUERIES
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
				<th>Enabled</th>
				
				<?php	
					$unit_pages = 1;
					$units_left = true;
					while($units_left){
						$onpage = 0;
						$page_class = "page-{$unit_pages}";
						while($onpage < $perpage){
							if($row=mysqli_fetch_assoc($unit_result)) {
								$url = filter_url_rebuild();
								if(strpos($url,"?") > 0) {
									$operator = "&";
								} else {
									$operator = "?";								
								}
								if($row["enabled"]) {
									$link_string = "Enabled";
									$link = filter_url_rebuild() . $operator . "d={$row['U_ID']}";								
								} else {
									$link_string = "Disabled";
									$link = filter_url_rebuild() . $operator . "e={$row['U_ID']}";					
								}
								echo "<tr class=\"unit-row {$page_class}\">";
								echo "<td>" . "<a href='view-unit.php?u={$row['U_ID']}'>" . $row["Name"] . "</a></td>" .
										"<td align=\"center\">" . $row["level"] . "</td>" .
										"<td>" . $row["shortname"] . "</td>" .  
										"<td>" . $row["StartDate"] . "</td>" .
										"<td>" . $row["EndDate"] . "</td>" . 
										"<td align=\"center\"><a href = \"{$link}\">{$link_string}</a></td>";		
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
							echo "<li class =\"pagebutton\"><a id= \"pb{$i}\" class = \"editbutton\" href=\"#page-{$i}\" onclick = \"showPage({$i})\">{$i}</a></li>";	
						}
					?>				
				
				</ul>
			</div>

		</div>
		
		<div id="mapping-options" class="clearfix">
			<h2>Mapping Options</h2>
			
			<ul id="mapping-list">
				<li id="timeline" class="menubutton"><a href="timeline.php">Timeline View</a></li>
				<li id="keyword" class="menubutton"><a href="keyword.php">Keyword Search</a></li>
				<li id="assbytype" class="menubutton"><a href="assbytype.php">Assessment Distribution</a></li>
			</ul>	
		
		
		</div>
	</div>

</div>


<?php require_once("footer.php"); ?>