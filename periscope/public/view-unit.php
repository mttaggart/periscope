<?php
$page_title = "View Unit";
require_once("header.php");
$session->login_check();
$unit_uid = $db->mysql_prep($_GET["u"]);
$unit = Unit::id_get($unit_uid);
$unit->attach_all_assets();
$startDate =  $unit->startDate > 0 ? date("m/d/y", $unit->startDate) : "None";
$endDate =  $unit->startDate > 0 ? date("m/d/y", $unit->endDate) : "None";
if($session->logged_in() && ($session->user->id == $unit->user->id || $session->user->is_admin)) {
    
    $my_unit = true;
} else {
    $my_unit = false;
}
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



<section id="content" class="clearfix">	
    <a id="backbutton" class = "button" href=<?php echo "\"browse.php\"";?>>Back to Browse</a>

    <section id="unitinfo">
        <h2><?php echo $unit->name;?></h2>
        <dl>
            <dt>Author</dt>
                <dd><?php echo $unit->user->username;?></dd>
            <dt>Subject:</dt>
                <dd><?php echo $unit->subject->longName;?></dd>
            <dt>Grade Level:</dt>
                <dd><?php echo $unit->gradeLevel->longName;?></dd>
            <dt>Start Date:</dt>
                <dd><date><?php echo $startDate;?></date></dd>
            <dt>End Date:</dt>
                <dd><date><?php echo $endDate;?></date></dd>
            <dt>Comments:</dt>
                <dd><p><?php echo "<i>{$unit->comments}</i>";?></p></dd>
        </dl>
        <?php if($my_unit) {echo "<a class= 'button' href='edit-unit.php?u={$unit_uid}'>Edit Unit</a>";}?>

    </section>

    <ul id="slidenav">
        <li><a class="button" href="#eq" onclick="clicked('eq');">Essential Questions</a></li>
        <li><a class="button" href="#con" onclick="clicked('con');">Content</a></li>
        <li><a class="button" href="#skl" onclick="clicked('skl');">Skills</a></li>
        <li><a class="button" href="#act" onclick="clicked('act');">Activities</a></li>	
        <li><a class="button" href="#rsc" onclick="clicked('rsc');">Resources</a></li>
        <li><a class="button" href="#ass" onclick="clicked('ass');">Assessments</a></li>		
    </ul>			

    <section id="assets">

        <?php

            //fix for EQ table name vs. label 
            
        
            foreach ($asset_types as $asset_type) {
                
                echo "<div class = 'asset-container' id = '". strtolower($asset_type::$table_prefix) . "'>" . "<h3>". $asset_type::$label . "</h3>";
                echo "<ol class = 'asset-list'>";	
                foreach ($unit->assets[$asset_type] as $asset) {
                    if ($asset_type::$table == "Assessments") {
                        $asstype = AssessmentType::id_get($asset->ass_type);
                        echo "<li><b>{$asstype->text}: </b>{$asset->text}</li>";
                    } else {
                        echo "<li>{$asset->text}</li>";
                    } 							
                }
                    					
                echo "</ol>";
                if($my_unit) {
                    echo "<div class = \"button\">
                            <a href='edit-asset.php?u={$unit->id}&a=" . strtolower($asset_type::$table_prefix) . "'>Edit " . $asset_type::$label . "</a>
                            </div>";
                          
                }
                
                echo "</div>";
            }
        ?>


    </section>
                
</section>

<?php require_once("footer.php");?>