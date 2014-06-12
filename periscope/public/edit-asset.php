<?php
$page_title = "Edit Asset";
require_once("header.php");
$session->login_check();

$asset_title = array_search($_GET["a"], $assets);

if ($_GET["a"] == "eq") {
    $asset_table = "EssentialQuestions";
} else {
    $asset_table = $asset_title;
}

$asset_id = $db->mysql_prep(strtoupper($_GET["a"]) . "_ID");
$asset_uid = $db->mysql_prep($_GET["u"]);

if ($_GET["a"] === "ass") {
    //query inner joins AssessmentTypes

    $asset_query = "SELECT * FROM {$asset_table} 
                    INNER JOIN AssessmentTypes ON {$asset_table}.AT_ID = AssessmentTypes.AT_ID 
                    INNER JOIN Units ON {$asset_table}.U_ID = Units.U_ID WHERE {$asset_table}.U_ID = {$asset_uid} ORDER BY Rank;";
} else {
    $asset_query = "SELECT * FROM {$asset_table} 
                    INNER JOIN Units ON {$asset_table}.U_ID = Units.U_ID WHERE {$asset_table}.U_ID = {$asset_uid} ORDER BY Rank;";
}

$asset_object = $asset_objects[$asset_table];

$assets = $asset_object::sql_get_set($asset_query);

$asset_result = $db->query($asset_query);
//Post Handling


if (isset($_POST["submit"])){
    $required_fields = array("asset-text");
    val_presences($required_fields);
    if(empty($errors)) {
        $asset_text = $db->mysql_prep($_POST["asset-text"]);
        
        
        if(isset($_GET["eaid"])) {
            
            $asset_eaid = $db->mysql_prep($_GET["eaid"]);
            $working_asset = $asset_object::id_get($asset_eaid);
            $working_asset->text = $asset_text;
            $working_asset->unit = $asset_uid;
            if(get_class($new_asset) == "Assessment") {
                $new_asset->ass_type = (int)$db->mysql_prep($_POST["assessment-type"]); 					
            }	         
            $working_asset->update(); 
            redirect_to("edit-asset.php?u={$asset_uid}&a={$_GET['a']}");

        }  else {
            $new_asset = new $asset_object();
            $new_asset->unit = $asset_uid;
            $new_asset->text = $asset_text;
            if(get_class($new_asset) == "Assessment") {
                $new_asset->ass_type = $db->mysql_prep($_POST["assessment-type"]); 					
            }
            $new_asset->insert();
            redirect_to("edit-asset.php?u={$asset_uid}&a={$_GET['a']}");
        }
    }
}


if(isset($_GET["daid"])) {
    $asset_daid = $db->mysql_prep($_GET["daid"]);
    $delete_asset = $asset_object::id_get($asset_daid);
    $delete_asset->delete();
    redirect_to("edit-asset.php?u={$asset_uid}&a={$_GET['a']}");
}

?>

<script>
	$(document).ready(function(){ 
            $("#asset-list").sortable({
                cursor: "move",
                update: function(event, ui) {
                    var order = $(this).sortable("serialize") + "&type=<?php echo $asset_object?>";
                    $.post("../lib/reorder.php", order);
                }
            });
            $("#asset-list").disableSelection();
            <?php
                $unit_uid = $db->mysql_prep($_GET["u"]);
                $unit = Unit::id_get($unit_uid);
                echo "$('#page-title h1').text('Edit {$unit->name}: {$asset_title}');";
                echo "$('#backbutton').text('Back to {$unit->name}');";

            ?>
	});
</script>



<section id="content">

    <a class="button" id="backbutton" href=<?php echo "'view-unit.php?u={$_GET["u"]}';"?>>Back to Unit</a>
    <p><em>Drag the items to change their order</em></p>
    <?php 
        list_errors();
    ?>

    <ul id = "asset-list">
        <?php
            
            foreach($assets as $asset) {
                if(isset($_GET["eaid"]) && $asset->id == $_GET["eaid"]) {
                    $edit_field = $asset->text;				
                }

                echo "<li id=\"asset_{$asset->id}\">" . data_clean($asset->text);
                if ($asset_table == "Assessments") {
                    $type = AssessmentType::id_get($asset->ass_type);
                    echo "<b> Type: " . data_clean($type->text) . "</b>";								
                }
                echo " | ";

                echo "<a href='" . substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"&")) . "&a={$_GET['a']}&eaid=" . $asset->id . "'>Edit</a> " .
                 "<a href='". substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"&")) . "&a={$_GET['a']}&daid=" . $asset->id . "'>Remove</a>" . 
                  "</li>";
                
            }
        ?>

    </ul>


    <?php 				

        if(isset($_GET["eaid"])) {
            $action_url = "edit-asset.php?u={$unit->id}&a={$_GET['a']}&eaid={$_GET['eaid']}";			
        } else{
            $action_url = "edit-asset.php?u={$unit->id}&a={$_GET['a']}";
        }				
        echo "<form id='editasset' method='post' action='{$action_url}'>";
    ?>

    <input id="asset-text" size= "100" type="text" name="asset-text"><?php echo "{$asset_title} Text";?></input><br>
    <?php

        //extra fields for Assessment Type 

        if($_GET["a"] === "ass") {
            $asstypes = AssessmentType::find_all();
            echo "<select id=\"assessment-type\" name=\"assessment-type\">";
            foreach($asstypes as $asstype) {
                echo "<option value = '{$asstype->id}'>{$asstype->text}</option>";
            }
            echo "</select>";	
            echo "<label for=\"assessment-type\">Assessment Type</label>";		
        }
    ?>

    <br>
    <?php
        if(isset($edit_field)) {						
            echo "<script type='text/javascript'>$('#asset-text').val('{$edit_field}');</script>";
        } 
        echo "<input type='submit' name='submit'";
        if(isset($_GET["eaid"])) { 
            echo "value='Edit {$asset_title}'>";
        } else {
            echo "value='Add {$asset_title}'>";
        }

    ?>
    </form>

</section>

<?php require_once("footer.php"); ?>