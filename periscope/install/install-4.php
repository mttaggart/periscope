<?php
    if(isset($_POST["submit"])){
        require_once("../lib/sessions.php");
        require_once("../lib/cfg.php");
        require_once("../lib/functions.php");
        require_once("../lib/database.php");
        require_once("../lib/dbobjects.php");
        
        $db = new MySQLDatabase();
        $username = $db->mysql_prep($_POST["username"]);
        $password = $db->mysql_prep($_POST["password"]);
        $pass_confirm = $db->mysql_prep($_POST["password-confirm"]);
        if($pass_confirm != $password){
            $_SESSION["errors"] = "Passwords and confirmation don't match!";
            redirect_to("index.php?stage=3");
        } else {
            $admin = new User();
            $admin->username = $username;
            $admin->password = $password;
            $admin->is_admin = 1;
            $admin->insert();
        }
    }
    
?>

<p>
    That's it! Periscope is installed. Periscope is now 
    ready to use!
</p>

<p>
    To log in, use the admin username and password you just created. 
    You can change it once you log in.
</p>
<a class="button" href="../login.php">Log In</a>

<?php
    $install_files = scandir(getcwd() . "/../install");
    foreach($install_files as $install){
        unlink($install);
    }
?>