<?php

function confirm_query($result_set) {
	if (!$result_set) {
		die("Database query failed.");
	}
}

function mysql_prep($string) {
		global $con;
		
		$escaped_string = mysqli_real_escape_string($con, $string);
		return $escaped_string;
	}

function js_datefix($jsdate) {
//fixes dates from jQuery calendar widget for mySQL format

	if (strpos($jsdate, "/")) {
		return substr($jsdate, 6) . "-" . substr($jsdate, 0,2) . "-" . substr($jsdate, 3,4);
	} else {
		return $jsdate;	
	}
}

function clean_uri () {
	
	if (strpos($_SERVER["REQUEST_URI"], "?") == 0){
		return $_SERVER["REQUEST_URI"];
	} else {
		//returns URI without PHP variables. Useful for stripping URIs in preparation for edit/delete links
		return substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"],"?"));
	}
}

function url_rebuild($base=null, $f_month=null, $f_gl=null, $f_s=null)
	
{
	//produces clean URL for rebuilding after adding/deleting;
	
	if(!$f_month && isset($_GET["month"])) {
		$f_month = $_GET["month"];
	}

	if(!$f_gl && isset($_GET["gl"])) {
		$f_gl = $_GET["gl"];
	}

	if(!$f_s && isset($_GET["s"])) {
		$f_s = $_GET["s"];
	}
	
	if(!$base) {
		if (strpos($_SERVER["REQUEST_URI"],"?")==0) {
			$url_base = $_SERVER["REQUEST_URI"];
		} else {
			$url_base = substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"?"));
		}
	} else {
		$url_base = $base;		
	}
	

	$url_rebuild = $url_base;
	//NEED TO FIX ? vs &
	if ($f_month) {
		$url_rebuild .= "?month={$f_month}";
	}

	if ($f_gl) {
		if (isset($_GET["month"])) {
			$url_rebuild .= "&gl={$f_gl}";
		} else {
			$url_rebuild .= "?gl={$f_gl}";
		}		
	}

	if ($f_s) {
		if (isset($_GET["month"]) || isset($_GET["gl"])) {
			$url_rebuild .= "&s={$f_s}";
		} else {
			$url_rebuild .= "?s={$f_s}";
		}	
	}

	return $url_rebuild;
}

function mapping_options() {

	$timeline = url_rebuild($base="timeline.php");
	$browse = url_rebuild($base="browse.php");
	$keyword = url_rebuild($base="keyword.php");
	$assbytype = url_rebuild($base="assbytype.php");
		
	$map_div = "<div id=\"mapping-options\" class=\"clearfix\">
			<h2>Mapping Options</h2>
		
			<ul id=\"mapping-list\">
				<li id=\"browse\" class=\"menubutton\"><a href=\"{$browse}\">Browse Units</a></li>				
				<li id=\"timeline\" class=\"menubutton\"><a href=\"{$timeline}\">Timeline View</a></li>
				<li id=\"keyword\" class=\"menubutton\"><a href=\"{$keyword}\">Keyword Search</a></li>
				<li id=\"assbytype\" class=\"menubutton\"><a href=\"{$assbytype}\">Assessment Distribution</a></li>
			</ul>	
		</div>";
	return $map_div;
}

function redirect_to($destination) {

	header("Location: " . $destination);
	exit;

}

function list_errors() {

	global $errors;
	
	echo "<div class=\"errorbox\">";
	echo "<ul>";
	foreach($errors as $field => $error) {
			echo "<li>" . htmlentities($error);
			echo "</li>";
		
	}
	echo "</ul>";
	echo "</div>";
}

function generate_salt($length) {
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

function password_encrypt($plain_password) {
	$hash_format = "$2y$10$";
	$salt_length = 22;
	$salt = generate_salt($salt_length);
	$format_and_salt = $hash_format . $salt;
	$hash = crypt($plain_password, $format_and_salt);
	return $hash;
}

function password_check($password, $existing_hash) {
	// existing hash contains format and salt at start  
		
 	$hash = crypt($password, $existing_hash);
	if ($hash === $existing_hash) {
		return true;
	} else {
		return false;
	}
	
	
}

function find_all_admins() {
	global $con;
		
	$query  = "SELECT * ";
	$query .= "FROM Admins ";
	$query .= "ORDER BY username ASC";
	$admin_set = mysqli_query($con, $query);
	confirm_query($admin_set);
	return $admin_set;
	}

function find_admin_by_username($username) {
		global $con;
		
		$safe_username = mysql_prep($username);
		$query  = "SELECT * ";
		$query .= "FROM Admins ";
		$query .= "WHERE username = '{$safe_username}' ";
		$query .= "LIMIT 1";
		$admin_set = mysqli_query($con, $query);
		confirm_query($admin_set);
		if($admin = mysqli_fetch_assoc($admin_set)) {
			return $admin;
		} else {
			return null;
		}
	}

function find_admin_by_id($id) {
	global $con;
	
	$safe_id = mysql_prep($id);
	$query  = "SELECT * ";
	$query .= "FROM Admins ";
	$query .= "WHERE id = {$safe_id} ";
	$query .= "LIMIT 1";
	$admin_set = mysqli_query($con, $query);
	confirm_query($admin_set);
	if($admin = mysqli_fetch_assoc($admin_set)) {
		return $admin;
	} else {
		return null;
	}
}

function find_subject_by_id($id) {
	global $con;
	
	$safe_id = mysql_prep($id);
	$query  = "SELECT * ";
	$query .= "FROM Subjects ";
	$query .= "WHERE S_ID = {$safe_id} ";
	$query .= "LIMIT 1";
	$subject_set = mysqli_query($con, $query);
	confirm_query($subject_set);
	if($subject = mysqli_fetch_assoc($subject_set)) {
		return $subject;
	} else {
		return null;
	}
}

function find_gradelevel_by_id($id) {
	global $con;
	
	$safe_id = mysql_prep($id);
	$query  = "SELECT * ";
	$query .= "FROM GradeLevels ";
	$query .= "WHERE GL_ID = {$safe_id} ";
	$query .= "LIMIT 1";
	$gradelevels_set = mysqli_query($con, $query);
	confirm_query($gradelevels_set);
	if($gradelevel = mysqli_fetch_assoc($gradelevels_set)) {
		return $gradelevel;
	} else {
		return null;
	}
}

function find_asstype_by_id($id) {
	global $con;
	
	$safe_id = mysql_prep($id);
	$query  = "SELECT * ";
	$query .= "FROM AssessmentTypes ";
	$query .= "WHERE AT_ID = {$safe_id} ";
	$query .= "LIMIT 1";
	$at_set = mysqli_query($con, $query);
	confirm_query($at_set);
	if($at = mysqli_fetch_assoc($at_set)) {
		return $at;
	} else {
		return null;
	}
}


function attempt_login($username, $password) {
	$admin = find_admin_by_username($username);
	if ($admin) {
	// found admin, now check password 
	
		if (password_check($password, $admin["password"])) {
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

function logged_in() {
	return isset($_SESSION['admin_id']);
}
	
function confirm_logged_in() {
	if (!logged_in()) {
		redirect_to("login.php");
	}
}

?>