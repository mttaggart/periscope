<?php
$page_title = "Manage Grade Levels";
require_once("header.php");
$session->login_check();


$gradelevels = GradeLevel::find_all();

//POST Handling

if (isset($_POST["submit"])){

    $required_fields = array("level", "longname");

    val_presences($required_fields);
    if(empty($errors)) { 
        $level = $db->mysql_prep($_POST["level"]);
        $longname = $db->mysql_prep($_POST["longname"]);

        if(isset($_GET["e"])) {
            $edit_id = $db->mysql_prep($_GET["e"]);
            $edit_gradelevel = GradeLevel::id_get($edit_id);
            $edit_gradelevel->level = $level;
            $edit_gradelevel->longName = $longname;
            $edit_gradelevel->update();
            redirect_to("admin-gradelevels.php");

        } else {
            $new_gradelevel = new GradeLevel();
            $new_gradeLevel->level = $level;
            $new_gradeLevel->longName = $longname;
            $new_gradeLevel->insert();
            redirect_to("admin-gradelevels.php");
        }
    }
}

if(isset($_GET["d"])) {
    $delete_id = $db->mysql_prep($_GET["d"]);
    $delete_gradelevel = GradeLevel::id_get($delete_id);
    $delete_gradelevel->delete();
    redirect_to("admin-gradelevels.php");	
}

?>
<script type="text/javascript">
    $(document).ready(function () {
        <?php
            if(isset($_GET["e"])) {
                $edit_id = $_GET["e"];
                $edit_gradelevel = GradeLevel::id_get($edit_id);
                echo "$('#gradelevels-edit').attr('action', 'admin-gradelevels.php?e={$edit_id}');";
                echo "$('#submit').val('Edit Grade Level');";				
                echo "$('#level').val('{$edit_gradelevel->level}');";
                echo "$('#longname').val('{$edit_gradelevel->longName}');";	
            }
        ?>
    });
</script>


<section id="content">
    <?php 
        $session->session_errors();
        echo list_errors();
    ?>

    <table id="gradelevel-table">
        <th>Level</th>
        <th>Longname</th>			
        <th>Actions</th>
        <?php
            $url_base = clean_uri();
            foreach($gradelevels as $gradelevel) {
                $edit_url = $url_base . "?e=" . $gradelevel->id;
                $remove_url = $url_base . "?d=" .$gradelevel->id;
                echo "<tr>
                        <td>{$gradelevel->level}</td>
                        <td>{$gradelevel->longName}</td>
                        <td><a href='{$edit_url}'>Edit</a> | <a href='{$remove_url}'>Remove</a></td>
                      </tr>";	
            }
        ?>
    </table>

    <form id="gradelevels-edit" method="post" action="admin-gradelevels.php">
        <hr>
        <input id="level" type="text" name="level"></input>
        <label for="level">Level</label><br>
        <input id="longname" type="text" name="longname"></input>
        <label for="longname">Level Longname</label><br>
        <input id="submit" type="submit" name="submit" value="New Grade Level"></input>
    </form>

</section>




<?php require_once("footer.php"); ?>