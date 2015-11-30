<?php
require_once("cfg.php");
require_once("val.php");
require_once("functions.php");
require_once("database.php");
require_once("dbobjects.php");
require_once("assets.php");

if(isset($_POST["asset"])) {
    $type = $db->mysql_prep($_POST["type"]);
    $counter =0;
    $reorder_assets = $_POST["asset"];
    foreach($reorder_assets as $reorder) {
        $reorder_obj = $type::id_get($reorder); 
        $reorder_obj->rank = $counter;
        $reorder_obj->update();
        $counter++;
    }
}



?>