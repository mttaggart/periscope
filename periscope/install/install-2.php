<?php
    require_once("../lib/database.php");
    if(isset($_POST["submit"])){
        $required = array("school","db-host","db-name","db-user","db-pass");
        foreach($required as $req){
            if($_POST[$req]===""){
                $_SESSION["errors"] = "Please fill out all fields";
                redirect_to("index.php?stage=1");
            }
        }
        
        $school = $_POST["school"];
        $db_host = $_POST["db-host"];
        $db_name = $_POST["db-name"];
        $db_user = $_POST["db-user"];
        $db_pass = $_POST["db-pass"];
        
//        Connect to DB Server
        $connection = MySQLDatabase::create($db_host, $db_name, $db_user, $db_pass);
        
        require_once("table-queries.php");
        
        foreach($table_queries as $table_query){
            $result = mysqli_query($connection,$table_query);
            if (!$result) { 	
                die("Database query error: " . mysqli_error($connection) . " (" . mysqli_errno($connection) . ")");		
            }
        }
        
        mysqli_close($connection);
        echo "<p>Database succesfully created!</p>";
        $config = fopen("../lib/cfg.sample.php","r");
        $line_count = 0;
        $config_strings = array();
        while($line = fgets($config)){
            $config_strings[] = $line;
        }
        
        $config_strings[15] = str_replace("localhost", $db_host, $config_strings[15]);
        $config_strings[16] = str_replace("periscope", "$db_name", $config_strings[16]);
        $config_strings[17] = str_replace("root", $db_user, $config_strings[17]);
        $config_strings[18] = str_replace("password", $db_pass, $config_strings[18]);
        $config_strings[19] = str_replace("Demo School", $school, $config_strings[19]);
//        
        
        
        $new_config = fopen("../lib/cfg.php","a");
        foreach($config_strings as $config_string) {
            fwrite($new_config, $config_string);
        }
        fclose($new_config);
        
        if(file_exists("../lib/cfg.old.php")){
            redirect_to("index.php?stage=3");
        } else {
            echo "<p>Could not create config file. Please copy/paste the text "
            . "below into a file called \"cfg.php\" inside Periscope's \"lib\" folder.</p>";
            echo "<textarea cols=\"50\" rows=\"60\">";
                    foreach($config_strings as $config_string){
                        echo $config_string;
                    }
            echo "</textarea>";
            echo "<p>When finished, click on the button below to continue.</p>";
            echo "<a class=\"button\" href=\"index.php?stage=3\">Next</a>";
        }
    }

?>
