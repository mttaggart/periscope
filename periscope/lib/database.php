<?php

class MySQLDatabase {
    
    private $connection = null;
    private $connected = false;
    
    public function __construct() {
        $this->connect();
    }
    
    public function connect() {
        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (mysqli_connect_errno()) { 	
            die("Database connection error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");		
        }
        $this->connected = true;
    }
    
    public function disconnect() {
        if(isset($this->connection)) {
            mysqli_close($this->connection);
            unset($this->connection);
        } 
    }
    
    public function query($sql) {
        $result = mysqli_query($this->connection,$sql);
        if (!$result) { 	
            die("Database query error: " . mysqli_error($this->connection) . " (" . mysqli_errno($this->connection) . ")");		
        }
        $this->confirm_query($result);
        return $result;
    }
    
    private function confirm_query($result_set) {
	if (!$result_set) {
            die("Database query failed." . mysqli_error($this->connection));
	}
    }
    
    public function mysql_prep($string) {
        $escaped_string = mysqli_real_escape_string($this->connection, $string);
        return $escaped_string;
    }
    
    public function last_query_id () {
        return mysqli_insert_id($this->connection);
    }
    
    public function find_all_users() {	
	$query  = "SELECT * FROM Admins ORDER BY username ASC";
	$admin_set = $this->query($query);
	$this->confirm_query($admin_set);
	return $admin_set;
    }

    public function find_admin_by_username($username) {
        $safe_username = $this->mysql_prep($username);
        $query  = "SELECT * FROM Admins WHERE username = '{$safe_username}' LIMIT 1";
        echo $query;
        $admin_set = $this->query($query);
        $this->confirm_query($admin_set);
        if($admin = mysqli_fetch_assoc($admin_set)) {
            return $admin;
        } else {
            return null;
        }
    }

    public function find_admin_by_id($id) {
        $safe_id = $this->mysql_prep($id);
        $query  = "SELECT * FROM Admins WHERE id = {$safe_id} LIMIT 1";
        $admin_set = $this->query($query);
        $this->confirm_query($admin_set);
        if($admin = mysqli_fetch_assoc($admin_set)) {
            return $admin;
        } else {
            return null;
        }
    }

    public function find_subject_by_id($id) {
        $safe_id = $this->mysql_prep($id);
        $query  = "SELECT * FROM Subjects WHERE S_ID = {$safe_id} LIMIT 1";
        $subject_set = $this->query($query);
        $this->confirm_query($subject_set);
        if($subject = mysqli_fetch_assoc($subject_set)) {
            return $subject;
        } else {
            return null;
        }
    }

    public function find_gradelevel_by_id($id) {
	$safe_id = $this->mysql_prep($id);
	$query  = "SELECT * FROM GradeLevels WHERE GL_ID = {$safe_id} LIMIT 1";
	$gradelevels_set = $this->query($query);
	$this->confirm_query($gradelevels_set);
	if($gradelevel = mysqli_fetch_assoc($gradelevels_set)) {
            return $gradelevel;
	} else {
            return null;
	}
    }

    public function find_asstype_by_id($id) {
	$safe_id = $this->mysql_prep($id);
	$query  = "SELECT * FROM AssessmentTypes WHERE AT_ID = {$safe_id} LIMIT 1";
	$at_set = $this->query($query);
	$this->confirm_query($at_set);
	if($at = mysqli_fetch_assoc($at_set)) {
            return $at;
	} else {
            return null;
	}
    }
    
    public function attempt_login($username, $password) {
        $admin = $this->find_admin_by_username($username);
        if($admin) {
            // found admin, now check password 
            if(password_check($password, $admin["password"])) {
                // password matches
                return $admin;
            } else {
                // password does not match  
                return false;
            }
        } else {
            // admin not found     
            return false;
        }
    }

}

$db = new MySQLDatabase();
$database =& $db;
?>
