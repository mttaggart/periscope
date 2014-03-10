<?php
$page_title = "Admin Login";
require_once("../lib/sessions.php");
require_once("../lib/val.php");
require_once("header.php");

if (isset($_POST['submit'])) {
	$required_fields = array("username","password");
	val_presences($required_fields);
	
	if(empty($errors)) {
		$username = $_POST["username"];
		$password = $_POST["password"];
		$found_admin = attempt_login($username, $password);
		if ($found_admin) {
			$_SESSION["admin_id"] = $found_admin["id"];
			$_SESSION["username"] = $found_admin["username"];
			redirect_to ("admin.php");		
		}	else {
			$_SESSION["errors"] = "Username or password is incorrect.";
		} 
	} 
}
?>
<div id="content-wrapper">

	<div id="content">
		<?php 
			echo errors();
		?>
		
			<form id="admin-login" method="post" action="login.php">
				<input type="text" name="username"></input>
				<label for="username">Admin Username</label><br>
				<input type="password" name="password"></input>
				<label for="password">Password</label><br>
				<input type="submit" name="submit" value="Log In"></input>
			</form>
	
	</div>

</div>


<?php require_once("footer.php"); ?>