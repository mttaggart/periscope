<?php
$page_title = "Manage Admins";
require_once("header.php");
require_once("../lib/sessions.php");
require_once("../lib/val.php");


if(!logged_in()) {
	$_SESSION["errors"] = "Please log in to view this page";
	redirect_to("login.php");
}


$admins = find_all_admins();

//POST Handling

if (isset($_POST["submit"])){

	$required_fields = array("username", "password");
	
	val_presences($required_fields);
	if(empty($errors)) { 
		$username = mysql_prep($_POST["username"]);
		$password = password_encrypt($_POST["password"]);
		
		if(isset($_GET["e"])) {
			$edit_admin = mysql_prep($_GET["e"]);
			$update_query = "UPDATE Admins SET
									username = '{$username}',
									password = '{$password}' 
									WHERE id = {$edit_admin};";
										
			if (mysqli_query($con, $update_query)) {
				mysqli_free_result($admins);
				redirect_to(clean_uri());	
			} 
			
		} else {
			$new_admin_query = "INSERT INTO Admins (username, password)
									 VALUES ('{$username}', '{$password}');";
									 
			if (mysqli_query($con, $new_admin_query)) {
				mysqli_free_result($admins);

				redirect_to(clean_uri());
			}
		}
	}
}

if(isset($_GET["d"])) {
	$admin_d_id = mysql_prep($_GET["d"]);
	$remove_query = "DELETE FROM Admins WHERE id = {$admin_d_id};";
	
	
		
	if (mysqli_query($con, $remove_query)){
				
			redirect_to(clean_uri());	
	}	
}


?>
<script type="text/javascript">
	$(document).ready(function () {
		<?php
			if(isset($_GET["e"])) {
				$edit_id = $_GET["e"];
				$edit_admin_info = find_admin_by_id($edit_id);
				$edit_username = $edit_admin_info["username"];
				echo "$('#admin-edit').attr('action', 'edit-admin.php?e={$edit_id}');";
				echo "$('#submit').val('Edit Admin');";				
				echo "$('#username').val('{$edit_username}');";	
			}
		?>
	});
</script>
<div id="content-wrapper">

	<div id="content">
		<?php 
			echo errors() . "<br>";
			list_errors();
		?>
		
		<table id="admin-table">
			<th>Username</th>		
			<th>Actions</th>
			<?php
				$url_base = clean_uri();
				while ($row = mysqli_fetch_assoc($admins)) {
					$edit_url = $url_base . "?e=" . $row["id"];
					$remove_url = $url_base . "?d=" . $row["id"];
					echo "<tr>
								<td>{$row['username']}</td>
								<td><a href='{$edit_url}'>Edit</a> | <a href='{$remove_url}'>Remove</a></td>
							</tr>";			
				}
				mysqli_free_result($admins);
			?>
		</table>
		
			<form id="admin-edit" method="post" action="edit-admin.php">
				<hr>
				<input id="username" type="text" name="username"></input>
				<label for="username">Admin Username</label><br>
				<input id="password" type="password" name="password"></input>
				<label for="password">Password</label><br>
				<input id="submit" type="submit" name="submit" value="New Admin"></input>
			</form>
	
	</div>

</div>


<?php require_once("footer.php"); ?>