<?php
require_once("cfg.php");
require_once("database.php");
require_once("dbobjects.php");
$db = new MySQLDatabase();
$database =& $db;


class Session {
   private $logged_in=false;
   public $user;
   public $errors = array();
   private $last_unit_query;
   private $active_filters;
    
   function __construct() {
      session_start();
      if(isset($_SESSION["logged_in"])) {
          $this->logged_in = $_SESSION["logged_in"];
      }
      if(isset($_SESSION["username"])) {
          $this->user = User::sql_get_username($_SESSION["username"]);
      }
      if(isset($_SESSION["active_filters"])) {
          $this->active_filters = $_SESSION["active_filters"];
      }
      if(isset($_SESSION["last_unit_query"])) {
          $this->last_unit_query = $_SESSION["last_unit_query"];
      }
   }
   
   public function login_check() {
       if(!$this->logged_in) {
           redirect_to("login.php");
       } 
   }
   
   public function admin_login_check(){
       if($this->user->is_admin != 1) {
           redirect_to("login.php");
       }
   }
       
   public function set_last_query($query) {
       $_SESSION["last_unit_query"] = $query;
       $this->last_unit_query = $query;
       
   }

   public function get_last_query() {
       return $this->last_unit_query;
   }
   
   public function clear_last_query() {
       unset($this->last_unit_query);
       unset($_SESSION["last_unit_query"]);
   }
   
   public function set_filters($filters) {
       $_SESSION["active_filters"] = $filters;
       $this->active_filters = $filters;
       
   }
   
   public function get_filters() {
       return $this->active_filters;
   }
   
   public function clear_filters() {
       unset($this->active_filters);
       unset($_SESSION["active_filters"]);
   }
   
   public function login($username, $password) {
       $signin = User::sql_get_username($username);
       if($signin->username == $username) {
           if(User::password_check($password, $signin->password)) {
               echo "password checks";
               $this->logged_in = true;
               $_SESSION["logged_in"] = true;
               $this->user = $signin;
               $_SESSION["username"] = $signin->username;
           }
       } else {
           $this->errors["user"] = "Incorrect username or password";
       }
   }
   
   public function logout() {
       $this->logged_in = false;
       unset($_SESSION["logged_in"]);
       unset($_SESSION["username"]);
       session_destroy();
   }
   
   public function logged_in() {
       return $this->logged_in;
   }
   
   public function session_errors() {
       foreach ($this->errors as $error=>$message) {
           echo $message . "<br>";
       }
       $this->errors = array();
       unset($_SESSION["errors"]);
   }
}


$session = new Session();

//function admin_login_check() {       
//   return isset($_SESSION["admin_id"]);
//}
//
//
//
//function message() {
//	if (isset($_SESSION["message"])) {
//		$output = "<div class=\"message\">";
//		$output .= htmlentities($_SESSION["message"]);
//		$output .= "</div>";
//		
//		// clear message after use
//		
//		$_SESSION["message"] = null;
//		
//		return $output;
//	}
//}
//
//function errors() {
//	if (isset($_SESSION["errors"])) {
//		$errors = $_SESSION["errors"];
//		
//		// clear message after use
//		
//		$_SESSION["errors"] = null;
//		
//		return $errors;
//	}
//}


?>