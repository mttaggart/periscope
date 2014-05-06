<?php
require_once("../lib/database.php");

/*
 * Contains Object definitions for anything coming straight out of the database. Includes:
 * Units
 * Subjects
 * Grade Levels
 * Unit Assets
 * Users
 */

class DBObject {
 
    protected static $table;
    protected static $table_prefix;
    protected static $insert_columns = array(); //associative array linking columns to properties
    public $id;
    
    static public function find_all() {
        
        global $db;
        $found_dbos = array();
        $all_query = "SELECT * FROM " . static::$table;
        $found_result = $db->query($all_query);
        while ($row = mysqli_fetch_assoc($found_result)) {
            $found_dbos[] = static::build_from_sql($row); 
        }
        return $found_dbos;
    }   
    
    static protected function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties
        $new_dbo = new static;
        return $new_dbo;
    }
    
    static public function sql_get_set($sql) {
        global $db;
        $dbo_set = array();
        $result = $db->query($sql);
        while($row = mysqli_fetch_assoc($result)) {
            $dbo_set[]=static::build_from_sql($row);
        }
        return $dbo_set;
    }
    
    static public function sql_get($sql) {
        global $db;
        return static::build_from_sql(mysqli_fetch_assoc($db->query($sql)));
    }
    
    static public function id_get($id) {
        global $db;
        $query = "SELECT * FROM " . static::$table . " WHERE " . static::$table_prefix . "_ID = {$id} LIMIT 1";
        return static::build_from_sql(mysqli_fetch_assoc($db->query($query)));
    }
    
    public function insert() {
        global $db;
        
        $insert_query = "INSERT INTO " . static::$table . " (";
        
        foreach(static::$insert_columns as $column=>$property) {
            $insert_query .=  "{$column}, ";
        }
        $insert_query = substr($insert_query, 0, strrpos($insert_query, ","));
        $insert_query .= ") VALUES ( ";
        
        foreach(static::$insert_columns as $column=>$property) {
            if (is_string($this->$property)) {
               $insert_query .=  " '{$this->$property}',"; 
            } elseif ($property == "startDate" || $property == "endDate") {
               $insert_query .= " '" . date("Y-m-d", $this->$property) . "',";             
            } else {
               $insert_query .=  " {$this->$property},";  
            }            
        }
        $insert_query = substr($insert_query, 0, strrpos($insert_query, ","));
        $insert_query .= ")";
//        echo $insert_query;
       $db->query($insert_query);
    } 
    
    public function update() {
        
        /*
         * For Insert and Update, Unit objects use integer values for user, subject, and gradelevel. 
         * They hook up with whole objects on return from db
         */
        
        global $db;
        $test = static::id_get($this->id);
        $needs_update = false;
        foreach (static::$insert_columns as $column=>$property) {
            if($this->$property != $test->$property) {
                $needs_update = true;
                break;
            }           
        }
        if($needs_update) {
            $update_query = "UPDATE " . static::$table . " SET ";
            
            foreach (static::$insert_columns as $column=>$property) {
                $update_query .= $column . " = ";
                if (is_string($this->$property)) {
                   $update_query .=  " '{$this->$property}', "; 
                } elseif ($property == "startDate" || $property == "endDate") {
                   $update_query .= " " . date("Y-m-d", $this->$property) . ", ";             
                } else {
                   $update_query .=  " {$this->$property}, ";  
                }   
            }
            
            $update_query = substr($update_query, 0, strrpos($update_query, ","));
            $update_query .= " WHERE " . static::$table_prefix . "_ID = {$this->id}";
//            echo $update_query;
            $db->query($update_query);        
        } else {
            return false;
        }
    }
    
    public function delete() {
        global $db;
        $delete_query = "DELETE FROM " . static::$table . " WHERE " . static::$table_prefix . "_ID = {$this->id}";
//        echo $delete_query;
        $db->query($delete_query);        
    }
 
}

class Unit extends DBObject {
    
    public static $table = "Units";
    public static $table_prefix = "U";
    public static $insert_columns = array("USR_ID" => "user",
                                          "Name"=>"name",
                                          "Subject_id"=>"subject",
                                          "GradeLevel_id"=>"gradeLevel",
                                          "StartDate"=>"startDate",
                                          "EndDate"=>"endDate",
                                          "Comments"=>"comments");
    public $user;
    public $name        = "New Unit";
    public $subject     = 0;
    public $gradeLevel  = 0;
    public $startDate   = "0000-00-00";
    public $endDate     = "0000-00-00";
    public $enabled;
    public $comments;
    public $assets;

    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $new_unit = new self();
        $new_unit->id =         (int)$info_array["U_ID"];
        $new_unit->user =       User::id_get((int)$info_array["USR_ID"]);
        $new_unit->name =       $info_array["Name"];
        $new_unit->subject =    Subject::id_get((int)$info_array["Subject_id"]); //should hold Subject Object
        $new_unit->gradeLevel = GradeLevel::id_get((int)$info_array["GradeLevel_id"]); //should hold GradeLevel Object
        $new_unit->startDate =  strtotime($info_array["StartDate"]);
        $new_unit->endDate =    strtotime($info_array["EndDate"]);
        $new_unit->comments =   $info_array["Comments"];
        $new_unit->enabled =    $info_array["enabled"];
        
        return $new_unit;        
    }
    
    static public function find_all_enabled() {
        $unit_query = "SELECT * FROM " . static::$table . " WHERE enabled = 1";
        return static::sql_get_set($unit_query);
    }
    
    public function toggle() {
        global $db;
        $toggle_query = "UPDATE " . self::$table . " SET enabled = ";
        if($this->enabled) {
            //update to disabled
            $toggle_query .= "0";
        } else {
            //update to enabled
            $toggle_query .= "1";
        }
        $toggle_query .= " WHERE " . self::$table_prefix . "_ID = " . $this->id;
        $db->query($toggle_query);
    }
    
    public function attach_asset($asset_type) {
        require_once("assets.php");
        $asset_query = "SELECT * FROM " . $asset_type::$table . " WHERE U_ID = {$this->id}";
        $this->assets[$asset_type] = $asset_type::sql_get_set($asset_query);
        
    }
    
    public function attach_all_assets() {
        require_once("assets.php");
        global $asset_types;
        
        foreach($asset_types as $asset_type) {
            $this->attach_asset($asset_type);
        }
        
    }  
}

class Subject extends DBObject {

    public static $table = "Subjects";
    public static $table_prefix = "S";
    public static $insert_columns = array("shortname"=>"shortName",
                                          "longname"=>"longName");

    public $shortName;
    public $longName; 
    
    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $new_subject = new self();
        
        $new_subject->id =         (int)$info_array["S_ID"];
        $new_subject->shortName =  $info_array["shortname"];
        $new_subject->longName =   $info_array["longname"];
        
        return $new_subject;

    }        
}

class GradeLevel extends DBObject {
    
    public static $table = "GradeLevels";
    public static $table_prefix = "GL";
    public static $insert_columns = array("level"=>"level",
                                          "longname"=>"longName");

    public $level;
    public $longName;
    
    static public function build_from_sql($info_array) {
        //uses an info array from sql_get or id_get to assign properties 
        $new_gradeLevel = new self();
        
        $new_gradeLevel->id =         (int)$info_array["GL_ID"];
        $new_gradeLevel->level =      $info_array["level"];
        $new_gradeLevel->longName =   $info_array["longname"]; 
        return $new_gradeLevel;
    }
    
}

class User extends DBObject{
    
    public static $table = "Users";
    public static $table_prefix = "USR";
    public static $insert_columns = array("username" => "username",
                                          "password" => "password",
                                          "is_admin" => "is_admin");
    
    public $username;
    public $password;
    public $is_admin;
    
    static public function build_from_sql($info_array) {
        $new_user = new self();   
        $new_user->id = (int)$info_array["USR_ID"];
        $new_user->username = $info_array["username"];
        $new_user->password = $info_array["password"];
        $new_user->is_admin = (int)$info_array["is_admin"];
        return $new_user;
    }
    
    static public function sql_get_username($username) {
        $user_query = "SELECT * FROM Users WHERE username = '{$username}'";
        return static::sql_get($user_query);
    }
    
    static public function password_check($password, $existing_hash) {
        // existing hash contains format and salt at start 
        $hash = crypt($password, $existing_hash);
        if ($hash === $existing_hash) {
            return true;
        } else {
            return false;
        }
    }    
    
    private static function generate_salt($length) {
        // Not 100% unique, not 100% random, but good enough for a salt
        // MD5 returns 32 characters

        $unique_random_string = md5(uniqid(mt_rand(), true));

        // Valid characters for a salt are [a-zA-Z0-9./]

        $base64_string = base64_encode($unique_random_string);

        // But not '+' which is valid in base64 encoding

        $modified_base64_string = str_replace('+', '.', $base64_string);

        // Truncate string to the correct length

        $salt = substr($modified_base64_string, 0, $length);

        return $salt;
    }
    
    public function password_encrypt($plain_password) {
        $hash_format = "$2y$10$";
        $salt_length = 22;
        $salt = static::generate_salt($salt_length);
        $format_and_salt = $hash_format . $salt;
        $hash = crypt($plain_password, $format_and_salt);
        return $hash;
    }
    
    public function insert() {
        $this->password = $this->password_encrypt($this->password);
        parent::insert();
    }
    
    public function is_admin() {
        return $this->is_admin;
    }
}

?>

