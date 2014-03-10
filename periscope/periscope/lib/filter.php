<script type="text/javascript">

	$(document).ready(function() {
		$("#filter-menu").hide();
		$("#filter-toggle").on("click", function () {
			$("#filter-menu").slideToggle(100, "linear", function () {
				if ($("#filter-menu").is(":visible")) {
					$("#filter-menu").css("display","inline");
				}
			});
		});
		<?php if ($page_title == "Timeline") {
						echo "$('#filter-clear a').attr('href', 'timeline.php');";
				}
				if ($page_title == "Assessments by Type") {
						echo "$('#filter-clear a').attr('href', 'assbytype.php');";
				}
				if ($page_title == "Manage Units") {
						echo "$('#filter-clear a').attr('href', 'admin-units.php');";
				}
				//fix for timeline/assbytype url
				if(strpos($_SERVER["REQUEST_URI"],"?") && $page_title == "Browse Units") {
					$timeline_url = "timeline.php" . substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"],"?"));
					$assbytype_url = "assbytype.php" . substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"],"?"));
					
					echo "$('#timeline a').attr('href', '{$timeline_url}');";
					echo "$('#assbytype a').attr('href', '{$assbytype_url}');";
							
				}
		?>	
	});

</script>


<?php
	require_once("functions.php");
	

	function filter_url_rebuild($f_month=null, $f_gl=null, $f_s=null)
	
	{
		//produces clean URL for rebuilding in filter links; 
		
		if(!$f_month && isset($_GET["month"])) {
			$f_month = $_GET["month"];
		}
	
		if(!$f_gl && isset($_GET["gl"])) {
			$f_gl = $_GET["gl"];
		}
	
		if(!$f_s && isset($_GET["s"])) {
			$f_s = $_GET["s"];
		}
		
		if (strpos($_SERVER["REQUEST_URI"],"?")==0) {
			$url_base = $_SERVER["REQUEST_URI"];
		} else {
			$url_base = substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"?"));
		}
	
		$url_rebuild = $url_base;
		//NEED TO FIX ? vs & 
		if ($f_month) {
			$url_rebuild .= "?month={$f_month}";
		}
	
		if ($f_gl) {
			if (isset($_GET["month"])) {
				$url_rebuild .= "&gl={$f_gl}";
			} else {
				$url_rebuild .= "?gl={$f_gl}";
			}		
		}
	
		if ($f_s) {
			if (isset($_GET["month"]) || isset($_GET["gl"])) {
				$url_rebuild .= "&s={$f_s}";
			} else {
				$url_rebuild .= "?s={$f_s}";
			}	
		}
	
		return $url_rebuild;
	}

	//Current Filter setup
	
	$current_filters = array();
	
	if(isset($_GET["s"])) {
		array_push($current_filters, "Subject");
	}
	if(isset($_GET["month"])) {
		array_push($current_filters, "Month");
	}
	if(isset($_GET["gl"])) {
		array_push($current_filters, "Grade Level");
	}

?>


<div id="filter-container" class="clearfix">

	<div id="filter-toggle" class="menubutton"><a href="#filter-menu">Filter Units<a></div>
	<div id="filter-clear" class="menubutton"><a href="browse.php">Clear All Filters</a></div>
	
	
	<div id="filter-menu" style="display:block;">
	
		<div class="filter-options" id="filter-month" class="clearfix">
		
			<h4>By Month</h4>
			
			<ul class="filter-list">
				<?php
				
					$months = array(
								"Jan" => 1,
								"Feb" => 2,
								"Mar" => 3,
								"Apr" => 4,
								"May" => 5,
								"Jun" => 6,
								"Jul" => 7,
								"Aug" => 8,
								"Sep" => 9,
								"Oct" => 10,
								"Nov" => 11,
								"Dec" => 12);
							
					foreach(array_keys($months) as $month) 
					
					{
						$month_url = filter_url_rebuild($months[$month]);
						echo "<li class = \"menubutton\">
								<a href=\"{$month_url}\">{$month}</a>
								</li>";
											
					}
				
				?>
			
			</ul>
			
		</div>
		
		<div class="filter-options" id="filter-gradelevel">
			
			<h4>By Grade Level</h4>
			
			<ul class="filter-list">
			
				<?php
				
					$gradelevel_query = "SELECT * FROM GradeLevels";
					$gradelevel_result = mysqli_query($con, $gradelevel_query);
					
					while($row = mysqli_fetch_assoc($gradelevel_result))
					
					{
						$gl_url = filter_url_rebuild(null, $row["GL_ID"], null);
						echo "<li class = \"menubutton\">
								<a href=\"{$gl_url}\">{$row['level']}</a>
								</li>";
					
					}
					mysqli_free_result($gradelevel_result);				
				?>
			
			
			</ul>

		</div>
		
		<div class="filter-options" id="filter-subject">
			
			<h4>By Subject</h4>
			
			<ul class="filter-list">
			
				<?php
				
					$subject_query = "SELECT * FROM Subjects ORDER BY shortname";
					$subject_result = mysqli_query($con, $subject_query);
					
					while($row = mysqli_fetch_assoc($subject_result)) 
					
					{
						$s_url = filter_url_rebuild(null, null, $f_s=$row["S_ID"]);
						echo "<li class = \"menubutton\">
								<a href=\"{$s_url}\">{$row['shortname']}</a>
								</li>";
					
					}
					mysqli_free_result($subject_result);				
				?>
			
			
			</ul>

		</div>
		
		<div id="current-filters" class="filter-options">
			<h4>Current Filters: </h4>
			
			<ul class="filter-list" id="current-filter-list">	
				<?php
					if(empty($current_filters)) {
						echo "<li class=\"menubutton\">None</li>";			
					} else {
						foreach($current_filters as $filter) {							
							echo "<li class=\"menubutton\">{$filter}</li>";					
						}				
					}
				?>
			</ul>		
		
		</div>	
	
	</div>
	
</div>
	


