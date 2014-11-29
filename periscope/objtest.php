<?php
    $page_title = "Object Test";
    require_once("header.php");
    require_once("../lib/dbobjects.php");
    require_once("../lib/assets.php");
    require_once("../lib/filters.php");

?>

    <section id="content">
        
        <article id="Units" class="clearfix">
            <?php
            $new = Unit::id_get(13);
            $new->attach_all_assets();
//            $new->attach_asset("EssentialQuestion");
            var_dump($new->assets);
            ?>
            

        </article>

    </section>




<?php require_once("footer.php"); ?>