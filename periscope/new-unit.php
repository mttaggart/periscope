<?php
$page_title = "New Unit";
require_once("header.php");
$session->login_check();


$gradelevels = GradeLevel::find_all();
$subjects = Subject::find_all();

if (isset($_POST["submit"])) {
	
	$required_fields = array("name","gradelevel","subject");
	
	val_presences($required_fields);
	
	if(empty($errors)) {
	
            //Converter for SQL Date format from jQuery datepicker
            $new_unit = new Unit();
            $new_unit->name = $db->mysql_prep($_POST["name"]);
            $new_unit->user = $session->user->id;
            $new_unit->subject = $db->mysql_prep($_POST["subject"]);
            $new_unit->gradeLevel = $db->mysql_prep($_POST["gradelevel"]);
            $new_unit->startDate = strtotime($db->mysql_prep($_POST["startdate"]));
            $new_unit->endDate = strtotime($db->mysql_prep($_POST["enddate"]));
            $new_unit->comments = $db->mysql_prep($_POST["comments"]);
            $new_unit->insert();
            $new_id = $db->last_query_id();
            redirect_to("view-unit.php?u={$new_id}");

	}
}

?>



<section id="content">

    <?php list_errors(); ?>

    <form id="addunit" method="post" action="new-unit.php">
        <label for="name">Unit Name</label><br/>
        <input type="text" name="name"><br>
        <label for="gradelevel">Grade Level</label><br/>
        <select name="gradelevel" class="button">
            <?php 
                foreach($gradelevels as $gradelevel) {
                    echo "<option value = \"{$gradelevel->id}\">{$gradelevel->level}</option>";
                }
            ?>
        </select></br>	

        <label for="subject">Subject</label><br/>				
        <select name="subject" class="button">	
            <?php
                foreach($subjects as $subject) {
                    echo "<option value = \"{$subject->id}\">{$subject->shortName}</option>";
                }
            ?>
        </select><br>
        <input id="from" type="text" name="startdate"><br/>
        Start Date</input><br>
        <input id="to" type="text" name="enddate"><br/>
        End Date</input><br>
        <textarea name="comments"></textarea><br/>
        Comments<br/>
        <input class="button" type="submit" name="submit" value="Create Unit">
    </form>

</section>

<?php require_once("footer.php"); ?>