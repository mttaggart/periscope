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
                redirect_to("browse.php");
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
        <input type="text" name="username"></input>
        <label for="username">Username</label><br>
        <input type="password" name="password"></input>
        <label for="password">Password</label><br>
        <input type="submit" name="submit" value="Log In"></input>
    </form>

</section>

<?php require_once("footer.php"); ?>