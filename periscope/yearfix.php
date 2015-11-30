<?php
	$page_title = "Year Fix";
	require_once("header.php");
	$school_year = array();
	
	if(intval(date('m')) <= 12 && intval(date('m')) > 7) {
		$school_year[] = intval(date("Y"));
	} else{
            $school_year[] = intval(date("Y")) - 1;
	}
        $school_year[] = $school_year[0] + 1;
                
        $units = Unit::sql_get_set("SELECT * FROM Units WHERE (StartDate != '0000-00-00' AND EndDate != '0000-00-00')");
		
//	$unit_query = "SELECT * FROM Units WHERE (StartDate != '0000-00-00' AND EndDate != '0000-00-00')";
//	
//	$unit_result = mysqli_query($con, $unit_query);
        
        function year_check($date){
            global $school_year;
            $month = intval(date("m",$date));
            $year = intval(date("Y",$date));
            if($month >= 7 && $month <= 12){
                if($year < $school_year[0]){
                    return $date+(24*60*60*365);
                } else {
                    return $date;
                }
            } else {
                if($year<$school_year[1]){
                    return $date+(24*60*60*365);
                } else {
                    return $date;
                }
            }
        }

?>

<div id="content-wrapper">

	<div id="content">
            <h2>School Year: <?php echo $school_year[0] . "-" . $school_year[1]?></h2>
            <?php 
                
                foreach($units as $unit){
                    $startMonth = intval(date("m",$unit->startDate));
                    $startYear =  intval(date("Y",$unit->startDate));
                    $endMonth = intval(date("m",$unit->endDate));
                    $endYear =  intval(date("Y",$unit->endDate));
                    echo "<h4>{$unit->name}</h4>";
                    echo "Old Start: " . date("m/d/Y",$unit->startDate) . "<br>";
                    $unit->startDate = year_check($unit->startDate);
                    echo "New Start: " . date("m/d/Y",$unit->startDate) . "<br>";                    
                    echo "Old End: " . date("m/d/Y",$unit->endDate) . "<br>";
                    $unit->endDate = year_check($unit->endDate);
                    echo "New End: " . date("m/d/Y",$unit->endDate) . "<br>";
                    $unit->user = $unit->user->id;
                    $unit->name = $db->mysql_prep($unit->name);
                    $unit->subject = $unit->subject->id;
                    $unit->gradeLevel = $unit->gradeLevel->id;
                    $unit->comments = $db->mysql_prep($unit->comments);
                    $unit->update();
                }
            
            ?>

	</div>

</div>


<?php require_once("footer.php"); ?>