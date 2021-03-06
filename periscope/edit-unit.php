<?php
$page_title = "Edit Unit";
require_once("header.php");
$session->login_check();



$unit_uid = $db->mysql_prep($_GET['u']);
$unit = Unit::id_get($unit_uid);
$gradelevels = GradeLevel::find_all();
$subjects = Subject::find_all();
$startDate =  $unit->startDate > 0 ? date("m/d/y", $unit->startDate) : "None";
$endDate =  $unit->endDate > 0 ? date("m/d/y", $unit->endDate) : "None";

if (isset($_POST["submit"])){
	
	$required_fields = array("name","gradelevel","subject");
	
	val_presences($required_fields);
	
	if(empty($errors)) {
		
            $unit->name = $db->mysql_prep($_POST["name"]);
            $unit->user = $session->user->id;
            $unit->subject = (int)$db->mysql_prep($_POST["subject"]);
            $unit->gradeLevel = (int)$db->mysql_prep($_POST["gradelevel"]);
//            Dates are milliseconds here, to be converted at the update() method
            $unit->startDate = strtotime($_POST["startdate"]);
            $unit->endDate = strtotime($_POST["enddate"]);
            $unit->comments = $db->mysql_prep($_POST["comments"]);

	$unit->update();
        redirect_to("view-unit.php?u={$unit->id}");     
        }
}

?>
<script>
	
	$(document).ready(function(){ 
		<?php
			echo "$('#page-title h1').text('Edit {$unit->name}');";
			echo "$('#backbutton').text('Back to {$unit->name}');";
			echo "$('#subject').val('{$unit->subject->id}');";
			echo "$('#gradelevel').val('{$unit->gradeLevel->id}');";
		?>
		
	
	});
	


</script>

<section id="content">

    <a id="backbutton" class = "button" href=<?php echo "'view-unit.php?u={$_GET["u"]}';"?>>Back to Unit</a>
    <?php list_errors();?>
    <?php echo"<form id=\"editunit\" method=\"post\" action=\"edit-unit.php?u={$unit->id}\">";?>
        <label for="name">Unit Name</label><br/><?php echo "<input type='text' name='name' value='" . data_clean($unit->name) . "'>";?><br>
        <label for="gradelevel">Grade Level <b>(Current: <?php echo $unit->gradeLevel->level . ")</b>";?></label><br/>
        <select class="button" id="gradelevel" name="gradelevel">
        <?php 
            foreach($gradelevels as $gl) {
                echo "<option value = \"{$gl->id}\">{$gl->level}</option>";					
            }
        ?>
        </select></br>	

        <label for="subject">Subject</label><br/>				
        <select class="button" id="subject" name="subject">	
            <?php 
            foreach($subjects as $s) {
                echo "<option value = \"{$s->id}\">{$s->shortName}</option>";					
            }
            ?>

        </select><br/>   
        <input id="from" type="text" name="startdate" value="<?php echo $startDate;?>"><br/>Start Date</input><br>
        <input id="to" type="text" name="enddate" value="<?php echo $endDate;?>"><br/>End Date</input><br>
        <textarea name="comments"><?php echo data_clean($unit->comments);?></textarea><br/>Comments<br/>
        <input class="button" type="submit" name="submit" value="Update Unit">
        
    </form>

</section>




<?php require_once("footer.php"); ?>