<?php
    $page_title = "Home";
    require_once("header.php");
    $session->login_check();
    
//    Here we get the most recently added Units
    $recent_units = Unit::sql_get_set("SELECT * FROM Units ORDER BY U_ID DESC LIMIT 10");
?>



    <section id="content">			
        <h2><?php echo $school_name; ?></h2>
        <div id="intro" class="intro-content">
            <h3>Intro</h3>
            <p>
                Welcome to the demonstration site for Periscope. Use the hamburger
                menu above to add a new unit, or browse existing units.
            </p>
        </div>
        <div id="recent-units" class="intro-content">
            <h3>Recent Units</h3>
            <ul id="recent-list">
                <?php 
                    foreach($recent_units as $unit){
                        echo "<li class=\"unit-row\"><a href=\"view-unit.php?u={$unit->id}\">" .
                        $unit->name . "</a><p>By {$unit->user->username}</p></li>";
                    }
                ?>                
            </ul>
        </div>

    </section>




<?php require_once("footer.php"); ?>