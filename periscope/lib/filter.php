<script type="text/javascript">

	$(document).ready(function() {
		$("#filter-menu").hide();
		$("#filter-toggle").on("click", function (event) {
                        event.prevenDefault();
			$("#filter-menu").slideToggle(500, "swing", function () {
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
        require_once("database.php");

	function filter_url_rebuild($f_month=null, $f_gl=null, $f_s=null)
	
	{
		//produces clean URL for rebuilding in filter links
		
		if(!$f_month && isset($_GET["month"])) {
			$f_month = $_GET["month"];
		}
	
		if(!$f_gl && isset($_GET["gl"])) {
			$f_gl = $_GET["gl"];
		}
	
		if(!$f_s && isset($_GET["s"])) {
			$f_s = $_GET["s"];
		}
		
		if(!$base) {
			if (strpos($_SERVER["REQUEST_URI"],"?")==0) {
				$url_base = $_SERVER["REQUEST_URI"];
			} else {
				$url_base = substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"?"));
			}
		} else {
			$url_base = $base;		
		}
	
		$url_rebuild = $url_base;
		//NEED TO FIX ? vs &  
		if ($f_month) {
			$url_rebuild .= "?month={$f_month}";
		}
	
		if ($f_gl) {
			if ($f_month) {
				$url_rebuild .= "&gl={$f_gl}";
			} else {
				$url_rebuild .= "?gl={$f_gl}";
			}		
		}
	
		if ($f_s) {
			if ($f_month || $f_gl) {
				$url_rebuild .= "&s={$f_s}";
			} else {
				$url_rebuild .= "?s={$f_s}";
			}	
		}
	
		return $url_rebuild;
	}

	//Current Filter setup  
	
	$current_filters = array("Month"=>"None","Subject"=>"None","Grade Level"=>"None");
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
                        "Dec" => 12
                        );
	
	if(isset($_GET["s"])) {
            $s_id = $db->mysql_prep($_GET["s"]);
            $subject_query = "SELECT * FROM Subjects WHERE S_ID = {$s_id}";
            $subject_result = $db->query($subject_query);
            $subject_info = mysqli_fetch_assoc($subject_result);
            $current_filters["Subject"]=$subject_info["shortname"];
            mysqli_free_result($subject_result);
	}
	if(isset($_GET["month"])) {
            $current_filters["Month"] = array_flip($months)[$_GET["month"]];
	}
	if(isset($_GET["gl"])) {
            $gl_id = $db->mysql_prep($_GET["gl"]);
            $gl_query = "SELECT * FROM GradeLevels WHERE GL_ID = {$gl_id}";
            $gl_result = $db->query($gl_query);
            $gl_info = mysqli_fetch_assoc($gl_result);
            $current_filters["Grade Level"]=$gl_info["level"];
            mysqli_free_result($gl_result);
	}

?>


<section id="filter-container" class="clearfix">

    <button id="filter-toggle" class="button>Filter Units</button>
    <a id="filter-clear" class="button" href="browse.php">Clear All Filters</a>


    <nav id="filter-menu" style="display:block;">
        <h2>Filter Options</h2>

        <section class="filter-options" id="filter-month" class="clearfix">

            <h3>By Month</h3>

            <ul class="filter-list">
                <?php

                    foreach(array_keys($months) as $month) {
                        $month_url = filter_url_rebuild($months[$month]);
                        echo "<li class = \"button\">
                                <a href=\"{$month_url}\">{$month}</a>
                              </li>";

                    }

                ?>

            </ul>

        </section>

            <section class="filter-options" id="filter-gradelevel">

                <h3>By Grade Level</h3>

                <ul class="filter-list">

                    <?php

                        $gradelevel_query = "SELECT * FROM GradeLevels";
                        $gradelevel_result = $db->query($gradelevel_query);

                        while($row = mysqli_fetch_assoc($gradelevel_result)) {
                            $gl_url = filter_url_rebuild(null, $row["GL_ID"], null);
                            echo "<li class = \"button\">
                                    <a href=\"{$gl_url}\">{$row['level']}</a>
                                  </li>";
                        }
                        mysqli_free_result($gradelevel_result);				
                    ?>

                </ul>

            </section>

            <section class="filter-options" id="filter-subject">

                <h3>By Subject</h3>

                <ul class="filter-list">

                    <?php

                        $subject_query = "SELECT * FROM Subjects ORDER BY shortname";
                        $subject_result = $db->query($subject_query);

                        while($row = mysqli_fetch_assoc($subject_result)) {
                            $s_url = filter_url_rebuild(null, null, $f_s=$row["S_ID"]);
                            echo "<li class = \"button\">
                                    <a href=\"{$s_url}\">{$row['shortname']}</a>
                                  </li>";

                        }
                        mysqli_free_result($subject_result);				
                    ?>


                </ul>

            </section>
		
            <section id="current-filters" class="filter-options">
                <h3>Current Filters: </h3>

                <ul class="filter-list" id="current-filter-list">	
                    <?php
                        foreach($current_filters as $filter=>$value) {							
                            echo "<li class=\"button\">{$filter}: {$value}</li>";
                        }					
                    ?>
                </ul>		

            </section>	
	
    </nav>
	
</section>
	


