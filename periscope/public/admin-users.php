<?php
$page_title = "Manage Users";
require_once("header.php");
$session->login_check();

$users = User::find_all();

//POST Handling

if (isset($_POST["submit"])){

	$required_fields = array("username", "password");
	
	val_presences($required_fields);
	if(empty($errors)) {
            $username = $db->mysql_prep($_POST["username"]);
            $password = $db->mysql_prep($_POST["password"]);
            
            if(isset($_POST["is-admin"])) {
                $is_admin = 1;
            } else {
                $is_admin = 0;
            }
		
            if(isset($_GET["e"])) {
                $edit_id = $db->mysql_prep($_GET["e"]);
                $edit_user = User::id_get($edit_id);
                $edit_user->username = $username;
                $edit_user->password = $edit_user->password_encrypt($password);
                $edit_user->is_admin = $is_admin;
                $edit_user->update();
                redirect_to("admin-users.php");

            } else {
                
                $new_user = new User();
                $new_user->username = $username;
                $new_user->password = $password;
                $new_user->is_admin = $is_admin;
                $new_user->insert(); 
              redirect_to("admin-users.php");

            }
	}
}

if(isset($_GET["d"])) {
    $delete_id = $db->mysql_prep($_GET["d"]);    
    $delete_user = User::id_get($delete_id);
    $delete_user->delete();
    redirect_to("admin-users.php");
}


?>
<script type="text/javascript">
    $(document).ready(function () {
        <?php
            if(isset($_GET["e"])) {
                $edit_id = $db->mysql_prep($_GET["e"]);
                $edit_user = User::id_get($edit_id);
                echo "$('#admin-edit').attr('action', 'admin-users.php?e={$edit_id}');";
                echo "$('#submit').val('Edit User');";				
                echo "$('#username').val('{$edit_user->username}');";
                if($edit_user->is_admin) {
                    echo "$('#is-admin').prop('checked','true');";
                }
            }
            
        ?>
    });
</script>


<section id="content">
    <?php 
        $session->session_errors();
        list_errors();
    ?>

    <table id="admin-table">
        <th>Username</th>
        <th>Admin?</th>
        <th>Actions</th>
        
        <?php
            $url_base = clean_uri();
            
            foreach($users as $user) {
                $edit_url = $url_base . "?e=" . $user->id;
                $remove_url = $url_base . "?d=" . $user->id;
                $admin_string = $user->is_admin === 1 ? "Yes" : "No";
                echo "<tr>
                        <td>{$user->username}</td>
                        <td>{$admin_string}</td>
                        <td><a href='{$edit_url}'>Edit</a> | <a href='{$remove_url}'>Remove</a></td>
                      </tr>";
            }

        ?>
    </table>

    <form id="admin-edit" method="post" action="admin-users.php">
        <hr>
        <input id="username" type="text" name="username"></input>
        <label for="username">Admin Username</label><br>
        <input id="password" type="password" name="password"></input>
        <label for="password">Password</label><br>
        <input id="is-admin" name="is-admin" type="checkbox"></input>
        <label for="is-admin">User is Administrator</label><br>
        <input id="submit" type="submit" name="submit" value="New User"></input>
        
    </form>

</section>




<?php require_once("footer.php"); ?>