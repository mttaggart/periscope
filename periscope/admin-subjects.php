<?php
$page_title = "Manage Subjects";
require_once("header.php");
$session->login_check();
$session->admin_login_check();
$subjects = Subject::find_all();

//POST Handling

if (isset($_POST["submit"])){

    $required_fields = array("shortname", "longname");

    val_presences($required_fields);
    if(empty($errors)) { 
        $shortname = $db->mysql_prep($_POST["shortname"]);
        $longname = $db->mysql_prep($_POST["longname"]);

        if(isset($_GET["e"])) {
            $edit_id = $db->mysql_prep($_GET["e"]);
            $edit_subject = Subject::id_get($edit_id);
            $edit_subject->shortName = $shortname;
            $edit_subject->longName = $longname;
            $edit_subject->update();
            redirect_to("admin-subjects.php");

        } else {
            $new_subject = new Subject();
            $new_subject->shortName = $shortname;
            $new_subject->longName = $longname;
            $new_subject->insert();
            redirect_to("admin-subjects.php");
        }
    }
}

if(isset($_GET["d"])) {
    $delete_id = $db->mysql_prep($_GET["d"]);
    $delete_subject = Subject::id_get($delete_id);
    $delete_subject->delete();
    redirect_to("admin-subjects.php");
}


?>
<script type="text/javascript">
    $(document).ready(function () {
        <?php
            if(isset($_GET["e"])) {
                $edit_id = $db->mysql_prep($_GET["e"]);
                $edit_subject = Subject::id_get($edit_id);
                echo "$('#subjects-edit').attr('action', 'admin-subjects.php?e={$edit_id}');";
                echo "$('#submit').val('Edit Subject');";				
                echo "$('#shortname').val('{$edit_subject->shortName}');";
                echo "$('#longname').val('{$edit_subject->longName}');";	
            }
        ?>
    });
</script>

<section id="content">
    <?php 
        $session->session_errors();
        echo list_errors();
    ?>

    <table id="subject-table">
        <th>Shortname</th>
        <th>Longname</th>			
        <th>Actions</th>
        <?php
            $url_base = clean_uri();
            foreach($subjects as $subject) {
                $edit_url = $url_base . "?e=" . $subject->id;
                $remove_url = $url_base . "?d=" . $subject->id;
                echo "<tr>
                        <td>{$subject->shortName}</td>
                        <td>{$subject->longName}</td>
                        <td><a class='button' href='{$edit_url}'>Edit</a> | <a class='button' href='{$remove_url}'>Remove</a></td>
                      </tr>";	
            }
        ?>
    </table>

    <form id="subjects-edit" method="post" action="admin-subjects.php">
        <hr>
        <input size="4" id="shortname" type="text" name="shortname"></input>
        <label for="shortname">Subject Shortname</label><br>
        <input id="longname" type="text" name="longname"></input>
        <label for="longname">Subject Longname</label><br>
        <input class="button" id="submit" type="submit" name="submit" value="New Subject"></input>
    </form>

</section>

<?php require_once("footer.php"); ?>