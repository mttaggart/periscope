<?php require_once("sessions.php"); ?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="../js/jquery.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="../css/global.css">
<?php
    require_once("cfg.php");
    require_once("dbobjects.php");
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

    if($session->get_last_query()) {
        $unit_query = $session->get_last_query();
    } else {
        $unit_query = "SELECT * FROM Units INNER JOIN Subjects ON Units.Subject_id = Subjects.S_ID INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID WHERE ( enabled = 1";
        if($_SERVER["REQUEST_URI"] == "/periscope/public/admin-units.php") {
            $unit_query .= " OR enabled = 0 )";
        } else {
            $unit_query .= ")";
        }
    }
    
    
    if($session->get_filters()) {
        $active_filters = $session->get_filters();
    } else {
        $active_filters = array("months","gradelevels","subjects");
    }
    
    if(isset($_POST["filter-clear"])) {
        $unit_query = "SELECT * FROM Units INNER JOIN Subjects ON Units.Subject_id = Subjects.S_ID INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID WHERE ( enabled = 1";
        if($_SERVER["REQUEST_URI"] == "/periscope/public/admin-units.php") {
            $unit_query .= " OR enabled = 0 )";
        } else {
            $unit_query .= ")";
    }
        $active_filters = array("months","gradelevels","subjects");
        $session->clear_last_query();
        $session->clear_filters();
    }
    
   
    
    
    if(isset($_POST["submit"])) {
        
        $unit_query = "SELECT * FROM Units INNER JOIN Subjects ON Units.Subject_id = Subjects.S_ID INNER JOIN GradeLevels ON Units.GradeLevel_id = GradeLevels.GL_ID WHERE ( enabled = 1";
        if($_SERVER["REQUEST_URI"] == "/periscope/public/admin-units.php") {
            $unit_query .= " OR enabled = 0 )";
        } else {
            $unit_query .= ")";
        }
        
        $session->clear_last_query();
        $active_filters = array("months","gradelevels","subjects");
        $session->clear_filters();

        function clean_last($query,$dangle) {
            $pos = strrpos($query,$dangle);
            if($pos !==false) {
               $clean = substr_replace($query, "", $pos, strlen($dangle));
               return $clean;
            } else {
                return $query;
            }            
        } 
 
        if(isset($_POST["months"])&& !empty($_POST["months"])) {
            $unit_query .= " AND ( ";
            foreach($_POST["months"] as $month) {
                $unit_query .= "(MONTH(StartDate) = {$months[$month]} OR MONTH(EndDate) = {$months[$month]}) OR ";
                $active_filters["months"][] = $db->mysql_prep($month);
            }
            
            $unit_query = clean_last($unit_query, "OR");
            $unit_query .= " ) ";
        }

        if(isset($_POST["gradelevels"]) && !empty($_POST["gradelevels"])) {
            $unit_query .= " AND ( ";
            foreach($_POST["gradelevels"] as $gradelevel) {
                $unit_query .= "GradeLevel_id = {$gradelevel} OR ";
                $active_filters["gradelevels"][] = $db->mysql_prep($gradelevel);
            }
            $unit_query = clean_last($unit_query, "OR");
            $unit_query .= " ) ";
        }

        if(isset($_POST["subjects"]) && !empty($_POST["subjects"])) {
            $unit_query .= " AND ( ";
            foreach($_POST["subjects"] as $subject) {
                $unit_query .= "Subject_id = {$subject} OR ";
                $active_filters["subjects"][] = $db->mysql_prep($subject);
            }
            $unit_query = clean_last($unit_query, "OR");
            $unit_query .= " ) ";
        }
        
        //Sorting
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
        
        $session->set_filters($active_filters);
        $session->set_last_query($unit_query);

    }

    /*
     * THIS IS WHAT MAKES THE UNIT SET. THIS RIGHT HERE.
     */
//    echo $unit_query; 
    $filtered_units = Unit::sql_get_set($unit_query); 
    
?>



<section id="filter-container" class="clearfix">

    <a id="filter-toggle" class="button" href="#filter-menu">Filter Units</a>
    <form id="filter-clear" action=<?php echo "\"{$_SERVER['REQUEST_URI']}\"";?> method="POST">
        <input type="submit" name="filter-clear" value="Clear Filters" id="filter-clearer" class="button"></input>
    </form>


    <form id="filter-menu" style="display:block;" action=<?php echo "\"{$_SERVER['REQUEST_URI']}\"";?> method="POST">
        <h2>Filter Options</h2>

        <section class="filter-options" id="filter-months" class="clearfix">

            <h3>By Month</h3>

            <ul class="filter-list">
                <?php

                    foreach(array_keys($months) as $month) {
//                        $month_value = array_flip($months)[$month];
                        echo "<li>  
                                <input class=\"filter-check\" id=\"{$month}\" type = \"checkbox\" value=\"{$month}\" name=\"months[]\">
                                <label id=\"{$month}-label\" class=\"button\" for=\"{$month}\">{$month}</a>
                              </li>";
                    }

                ?>

            </ul>

        </section>

            <section class="filter-options" id="filter-gradelevels">

                <h3>By Grade Level</h3>

                <ul class="filter-list">

                    <?php
                        $gradelevels = GradeLevel::find_all();

                        foreach($gradelevels as $gradelevel) {
                            echo "<li>
                                <input class=\"filter-check\" id=\"{$gradelevel->level}\" type = \"checkbox\" value=\"{$gradelevel->id}\" name=\"gradelevels[]\">
                                <label id=\"{$gradelevel->level}-label\" class=\"button\" for=\"{$gradelevel->level}\">{$gradelevel->level}</a>
                              </li>";
                        }				
                    ?>

                </ul>

            </section>

            <section class="filter-options" id="filter-subjects">

                <h3>By Subject</h3>

                <ul class="filter-list">

                    <?php

                        $subject_query = "SELECT * FROM Subjects ORDER BY shortname";
                        $subjects = Subject::sql_get_set($subject_query);

                        foreach($subjects as $subject) {
                            $label_text = str_replace(".", "", $subject->shortName);
                            $label_id = str_replace(" ","-",$label_text);
                            echo "<li>
                                <input class=\"filter-check\" id=\"{$label_id}\" type = \"checkbox\" value=\"{$subject->id}\" name=\"subjects[]\">
                                <label id=\"{$label_id}-label\" class=\"button\" for=\"{$label_id}\">{$label_text}</a>
                              </li>";

                        }				
                    ?>


                </ul>

            </section>
		
            <section id="current-filters" class="filter-options">
                <h3>Current Filters: </h3>

                <ul class="filter-list" id="current-filter-list">	
                    
                </ul>
                

            </section>
        
            <select name="sort" class="button">
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
        
        <input class="button" type="submit" name="submit" value="Apply">
	
    </form>
	
</section>

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
                $("#filter-clearer").on("click", function () {
                    $("#filter-clear").submit();
                });
                $("label").addClass("off");
                $("label.off").click(function () {
                   if($(this).hasClass("off")) {
                       var currentFilters = $("#current-filter-list").html();
                       var newFilter = "<li id=" + $(this).attr("id") + "-active class=\"button\">" + $(this).html() + "</li>";
                       $("#current-filter-list").html(currentFilters + newFilter);
                       $(this).removeClass("off");
                   } else {
                       var locator = "#current-filter-list #" + $(this).attr("id") + "-active";
                       $(locator).remove();
                       $(this).addClass("off");
                   }

                });
		<?php
                    
                    foreach($active_filters as $active_filter=>$values) {
                        if($active_filter) {
                            $filter_name = strval($active_filter);
                            if($values) {
                                foreach($values as $value) {
                                   $checkbox = "#filter-{$filter_name} .filter-check[value='{$value}']";
                                   $selector = "#filter-{$filter_name} .filter-check[value='{$value}'] + label";
                                   echo "$(\"{$checkbox}\").attr('checked','true');";
                                   echo "$(\"{$selector}\").removeClass(\"off\");";
                                   echo "var currentFilters = $(\"#current-filter-list\").html();";
                                   echo "var newFilter = \"<li id=\" + $(\"{$selector}\").attr(\"id\") + \"-active class='button'>\" + $(\"{$selector}\").html() + \"</li>\";";
                                   echo "$(\"#current-filter-list\").html(currentFilters + newFilter);";
                                }   
                           }

                        }
                    }                       
                    

                
		?>
        
        });

</script>



