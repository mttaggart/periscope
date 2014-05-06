<?php
$page_title = "Manage Units";
require_once("../lib/sessions.php");
require_once("../lib/val.php");
require_once("header.php");
$session->login_check();

//BEGIN ENABLE/DISABLE QUERIES

if(isset($_GET["t"])){
    $toggle_id = $db->mysql_prep($_GET["t"]);
    $toggle_unit = Unit::id_get($toggle_id);
    $toggle_unit->toggle();  
    redirect_to("admin-units.php");
}
//END ENABLE/DISABLE QUERIES
?>

<section id="content" class="clearfix">
    <?php require_once("../lib/filterform.php");?>
    
        <script>
            function showPage(pagenum) {
                var pageclass = ".page-" + pagenum;
                var buttonid = "#pb" + pagenum;
                $('.unit-row').hide();
                $(pageclass).show(300);
                $('.pagebutton a').removeClass('clicked');
                $(buttonid).addClass('clicked');	 
            }				
        </script>
        
        <section id="unit-list">
            <table id="unit-table">

                <th>Unit Name</th>
                <th>Grade Level</th>
                <th>Subject</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Author</th>
                <th>Enabled</th>

                <?php	
                    $unit_pages = 1;	
                    $total_pages = ceil(count($filtered_units)/$perpage);
                    $onpage = 0;
                    
                    foreach($filtered_units as $unit) {
                        $startDate =  $unit->startDate > 0 ? date("m/d/y", $unit->startDate) : "None";
                        $endDate =  $unit->startDate > 0 ? date("m/d/y", $unit->startDate) : "None";
                        $enabled_string = $unit->enabled ? "Enabled" : "Disabled";

                        $page_class = "page-{$unit_pages}";
                        echo "<tr class=\"unit-row {$page_class}\">";
                            echo "<td>" . "<a href='view-unit.php?u={$unit->id}'>{$unit->name}</a></td>"
                            . "<td align=\"center\">{$unit->gradeLevel->level}</td>"
                            . "<td>{$unit->subject->shortName}</td>"
                            . "<td><date>{$startDate}</date></td>"
                            . "<td><date>{$endDate}</date></td>"
                            . "<td>{$unit->user->username}</td>"
                            . "<td><a href=\"admin-units.php?t={$unit->id}\">{$enabled_string}</a></td>";
                        echo "</tr>";
                        $onpage++;
                        if($onpage == $perpage) {
                            $unit_pages++;
                            $onpage = 0;
                        }
                    }

                ?>


            </table>
            <div id="pagenav">
                <ul id="page-list">
                    <?php
                        for($i=1;$i<=$unit_pages;$i++) {
                            echo "<li class =\"pagebutton\"><a id=\"pb{$i}\" class = \"button\" href=\"#page-{$i}\" onclick = \"showPage({$i})\">{$i}</a></li>";	
                        }
                        if($unit_pages > 1) {
                            echo "<li class =\"pagebutton\"><a id=\"prev\" class = \"button\" href=\"#page-\" onclick=\"\"><</a>";
                            echo "<li class =\"pagebutton\"><a id=\"next\" class = \"button\" href=\"#page-\" onclick=\"\">></a>"; 							
                        }
                    ?>				
                </ul>
        </div>
        </section>

        <?php echo mapping_options();?>
    </section>


    


</section>

<script>
	$(document).ready(function() {
		$('.unit-row').hide();
		$('.page-1').show(300);
		$('#pb1').addClass('clicked');
		$('.pagebutton').click(function () {
			var nextpage = parseInt($('.clicked').attr('id').substr(2)) + 1;
			var prevpage = parseInt($('.clicked').attr('id').substr(2)) - 1;
			var nextlink = "#page-" + nextpage;
			var prevlink = "#page-" + prevpage;
			$('#next').attr('href',nextlink);
			$('#next').attr('onclick','showPage('+nextpage+')');
			$('#prev').attr('href',prevlink);
			$('#prev').attr('onclick','showPage('+prevpage+')');
			if(!$('#pb1').hasClass('clicked')) {
				$('#prev').show();			
			} else {
				$('#prev').hide();			
			}
		});
	});
</script>





<?php require_once("footer.php"); ?>