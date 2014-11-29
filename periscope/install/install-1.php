<h3>Database Setup</h3>
<p>
    Here we're going to enter some details about the installation.
</p>
<form method="POST" action="index.php?stage=2">
    <?php 
        if(isset($_SESSION["errors"])){
            echo "<p style=\"color:red;\">{$_SESSION["errors"]}</p>";
        }
    ?>
    <input type="text" name="school">
    <label for="school">School Name</label><br />
    <input type="text" name="db-host" value="localhost">
    <label for="db-host">Database Host</label><br />
    <input type="text" name="db-name" value="periscope">
    <label for="db-name">Database Name</label><br />
    <input type="text" name="db-user" value="root">
    <label for="db-user">Database User</label><br />
    <input type="text" name="db-pass">
    <label for="db-pass">Database Password</label><br />
    <input class="button" type="submit" value="Next" name="submit">    
</form>