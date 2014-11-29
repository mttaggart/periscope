<?php
    require_once("../lib/cfg.php");
    require_once("../lib/val.php");
    require_once("../lib/functions.php");
    require_once("../lib/database.php");
    require_once("../lib/dbobjects.php");
    require_once("../lib/assets.php");
    require_once("../lib/standards.php");
    
    if(isset($_GET["sc"])){
        $subcat_id = $db->mysql_prep($_GET["sc"]);
        $standards_query = "SELECT * FROM StandardsListItems " .
                        "WHERE STD_SC_ID = {$subcat_id};";
                        
        $standards = StandardsListItem::sql_get_set($standards_query);

        foreach($standards as $standard){
            echo "<option value='{$standard->id}'>{$standard->label}: {$standard->text}</option>";
        }
        
    } else if(isset($_GET["c"])){
        $cat_id = $db->mysql_prep($_GET["c"]);
        $subcat_query = "SELECT * FROM StandardsSubCategories " .
                        "WHERE STD_C_ID = {$cat_id};";
        $subcats = StandardsSubCategory::sql_get_set($subcat_query);

        foreach($subcats as $subcat){
            echo "<option value='{$subcat->id}'>{$subcat->label}</option>";
        }
                        
    }else if(isset($_GET["l"])){

        $lib_id = $db->mysql_prep($_GET["l"]);      
        $cat_query = "SELECT * FROM StandardsCategories " .
                     "WHERE STD_L_ID = {$lib_id};";
        $cats = StandardsCategory::sql_get_set($cat_query);

        foreach($cats as $cat){
            echo "<option value='{$cat->id}'>{$cat->label}</option>";
        }
    }

?>