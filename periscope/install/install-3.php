<?php
if(file_exists("../lib/cfg.php")){
    require_once("../lib/cfg.php");
} else {
    $_SESSION["errors"] = "Config file not found!";
    redirect_to("index.php?stage=2");
}
?>
<?php echo "<p style=\"color:red;\">{$_SESSION["errors"]}</p>"; ?>
<p>
    In the form below, fill out the information for your admin user. This will
    be the account you use to log into Periscope for the first time.
</p>
<form id="admin-user" method="POST" action="index.php?stage=4">
    <input type="text" name="username" value="admin">
    <label for="username">Admin Username</label><br />
    <input type="password" name="password">
    <label for="password">Password</label><br />
    <input type="password" name="password-confirm">
    <label for="password-confirm">Password Confirmation</label><br />
    <input class="button" type="submit" name="submit" value="Next">
</form>