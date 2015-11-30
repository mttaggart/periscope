<?php
require_once("dbobjects.php");


class StandardsLibrary extends DBObject{
    public static $table = "StandardsLibraries";
    public static $table_prefix = "STD_L";
    public static $insert_columns = array("Name" => "name");
    
    public $name;
    
    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $new_library = new self();
        
        $new_library->id =         (int)$info_array["STD_L_ID"];
        $new_library->name =       $info_array["Name"];
        return $new_library;
    }
}

class StandardsCategory extends DBObject{
    public static $table = "StandardsCategories";
    public static $table_prefix = "STD_C";
    public static $insert_columns = array("Label" => "label",
                                          "STD_L_ID" => "library");
    
    public $label;
    public $library;
    
    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $new_category = new self();
        
        $new_category->id =         (int)$info_array["STD_C_ID"];
        $new_category->library =    StandardsLibrary::id_get((int)$info_array["STD_L_ID"]);
        $new_category->label =      $info_array["Label"];
        return $new_category;
    }
    
    static public function sql_get_title_set($sql){
//        Returns Categories in an assoc by label
        $categories = static::sql_get_set($sql);
        $title_cats = array();
        foreach($categories as $cat) {
            if(key_exists($cat->label, $title_cats)){
                continue;
            } else{
                $title_cats[$cat->label] = $cat;
            }
        }
        
        return $title_cats;
    }
}

class StandardsSubCategory extends DBObject{
    public static $table = "StandardsSubCategories";
    public static $table_prefix = "STD_SC";
    public static $insert_columns = array("Label" => "label",
                                          "STD_L_ID" => "library",
                                          "STD_C_ID" => "category");
    
    public $label;
    public $library;
    public $category;
    
    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $new_subcategory = new self();
        
        $new_subcategory->id =         (int)$info_array["STD_SC_ID"];
        $new_subcategory->category =   StandardsCategory::id_get((int)$info_array["STD_C_ID"]);
        $new_subcategory->library =    StandardsLibrary::id_get((int)$info_array["STD_L_ID"]);
        $new_subcategory->label =      $info_array["Label"];
        return $new_subcategory;
    }
    
    static public function sql_get_title_set($sql){
//        Returns subcategories in an assoc by label
        $subcategories = static::sql_get_set($sql);
        $title_subcats = array();
        foreach($subcategories as $subcat) {
            if(key_exists($cat->label, $title_subcats)){
                continue;
            } else{
                $title_subcats[$subcat->label] = $subcat;
            }
        }
        
        return $title_subcats;
    }
}

class StandardsListItem extends DBObject{
    public static $table = "StandardsListItems";
    public static $table_prefix = "STD_SC";
    public static $insert_columns = array("Label" => "label",
                                          "Text" => "text",
                                          "STD_L_ID" => "library",
                                          "STD_C_ID" => "category",
                                          "STD_SC_ID" => "subcategory");
    
    public $label;
    public $text;
    public $library;
    public $category;
    public $subcategory;
    
    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $new_sli = new self();
        
        $new_sli->id =         (int)$info_array["STD_I_ID"];
        $new_sli->library =    StandardsLibrary::id_get((int)$info_array["STD_L_ID"]);
        $new_sli->category =   StandardsCategory::id_get((int)$info_array["STD_C_ID"]);
        $new_sli->subcategory =  StandardsSubCategory::id_get((int)$info_array["STD_SC_ID"]);
        $new_sli->label =      $info_array["Label"];
        $new_sli->text =       $info_array["Text"];
        return $new_sli;
    }
}

?>
