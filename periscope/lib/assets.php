<?php
require_once("dbobjects.php");

class Asset extends DBObject {
    
    public static $table;
    public static $table_prefix;
    public static $insert_columns = array("Text"=>"text",
                                          "rank"=>"rank",
                                          "unit" => "U_ID");
    public static $type;
    public $unit;
    public $text;
    public $rank;
    
    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $new_asset = new self();
        $id_row = static::$table_prefix . "_ID";
        $new_asset->id =        (int)$info_array[$id_row];
        $new_asset->unit =      (int)$info_array["U_ID"];
        $new_asset->text =      $info_array["Text"];
//        $info_array["rank"] ? $new_asset->rank = (int)$info_array["rank"] : 0; 
//        $new_asset->rank =   $info_array["rank"]; 
        return $new_asset;
    }
    
    static public function unit_get_set($uid) {
        $set_query = "SELECT * FROM " . static::$table . " WHERE U_ID = {$uid}";
        return static::sql_get_set($set_query);
    }
    
}

$asset_types = array("EssentialQuestion",
                     "Content",
                     "Skill",
                     "Activity",
                     "Resource",
                     "Assessment");


class EssentialQuestion extends Asset {
    public static $table = "EssentialQuestions";
    public static $table_prefix = "EQ";
    public static $label = "Essential Questions";
    //public static type     
}

class Content extends Asset {
    public static $table = "Content";
    public static $table_prefix = "CON";
    public static $label = "Content";
    //public static type     
}

class Activity extends Asset {
    public static $table = "Activities";
    public static $table_prefix = "ACT";
    public static $label = "Activities";
    //public static type     
}

class Skill extends Asset {
    public static $table = "Skills";
    public static $table_prefix = "SKL";
    public static $label = "Skills";
    //public static type     
}

class Resource extends Asset {
    public static $table = "Resources";
    public static $table_prefix = "RSC";
    public static $label = "Resources";
    //public static type     
}

class Assessment extends Asset {
    public static $table = "Assessments";
    public static $table_prefix = "ASS";
    public static $label = "Assessments";
    //public static type
    public $ass_type;
    
    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $new_ass = parent::build_from_sql($info_array);
        $new_ass->ass_type = (int)$info_array["AT_ID"];
        return $new_ass;
    } 
}

class AssessmentType extends DBObject {
    public static $table = "AssessmentTypes";
    public static $table_prefix = "AT";
    public static $insert_columns = array("AT_Text"=>"text");
    public $text;
    
    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $id_row = static::$table_prefix . "_ID";
        $new_asstype = new self();
        $new_asstype->id = (int)$info_array[$id_row];
        $new_asstype->text = $info_array["AT_Text"];
        return $new_asstype;
    }    
}

?>
