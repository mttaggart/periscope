<?php
$page_title = "Manage Assessment Types";
require_once("header.php");
$session->login_check();
$session->admin_login_check();

$asstypes = AssessmentType::find_all();

//POST Handling

if (isset($_POST["submit"])){

    $required_fields = array("at_text");

    val_presences($required_fields);
    if(empty($errors)) { 
        $at_text = $db->mysql_prep($_POST["at_text"]);

        if(isset($_GET["e"])) {
            $edit_id = $db->mysql_prep($_GET["e"]);
            $edit_asstype = AssessmentType::id_get($edit_id);
            $edit_asstype->text = $at_text;
            $edit_asstype->update();

        } else {
            $new_asstype = new AssessmentType();
            $new_asstype->text = $at_text;
            $new_asstype->insert();
        }
        redirect_to("admin-asstypes.php");
    }
}

if(isset($_GET["d"])) {
    $delete_id = $db->mysql_prep($_GET["d"]);
    $delete_asstype = AssessmentType::id_get($delete_id);
    $delete_asstype->delete();
    redirect_to("admin-asstypes.php");
}

?>



<section id="content">
    <?php 
        $session->session_errors();
        list_errors();
    ?>

    <table id="asstypes-table">
        <th>Type</th>			
        <th>Actions</th>
        <?php
            $url_base = clean_uri();
            
            foreach($asstypes as $asstype) {
                $edit_url = $url_base . "?e=" . $asstype->id;
                $remove_url = $url_base . "?d=" . $asstype->id;
                echo "<tr>
                        <td>{$asstype->text}</td>
                        <td><a class='button' href='{$edit_url}'>Edit</a> | <a class='button' href='{$remove_url}'>Remove</a></td>
                      </tr>";
            }
        ?>
    </table>

    <form id="asstype-edit" method="post" action="admin-asstypes.php">
        <hr>
        <input id="at_text" type="text" name="at_text"></input>
        <label for="at_text">Assessment Type</label><br>
        <input class="button" id="submit" type="submit" name="submit" value="New Assessment Type"></input>
    </form>

</section>

<script type="text/javascript">
    $(document).ready(function () {
        <?php
            if(isset($_GET["e"])) {
                $edit_id = $db->mysql_prep($_GET["e"]);
                $edit_asstype = AssessmentType::id_get($edit_id);
                echo "$('#asstype-edit').attr('action', 'admin-asstypes.php?e={$edit_id}');";
                echo "$('#submit').val('Edit Assessment Type');";				
                echo "$('#at_text').val('{$edit_asstype->text}');";	
            }
        ?>
    });
</script>


<?php require_once("footer.php"); ?>