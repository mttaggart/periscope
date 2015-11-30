<?php
$page_title = "Login";
require_once("header.php");
if (isset($_POST['submit'])) {
        
    $required_fields = array("username","password");
    val_presences($required_fields);

    if(empty($errors)) {
        $username = $db->mysql_prep($_POST["username"]);
        $password = $db->mysql_prep($_POST["password"]);
        $session->login($username, $password);
        if($session->logged_in()) {
            if ($session->user->is_admin) {
                redirect_to("admin.php");
            } else {
                redirect_to("index.php");
            }            
        } 
    } 
}
?>


<section id="content">
    <?php
        list_errors();
        $session->session_errors();
    ?>

    <form id="admin-login" method="post" action="login.php">
        <center>
            <input id="login" type="text" name="username"></input><br />
            <label for="username">Username</label><br />
            <input id="password" type="password" name="password"></input><br />
            <label for="password">Password</label><br />
            <input class="button" type="submit" name="submit" value="Log In"></input>
        </center>
    </form>

</section>

<?php require_once("footer.php"); ?>