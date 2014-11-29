
<?php
    $page_title = "Timeline";
    require_once("header.php");
    $session->login_check();
    require_once("gantti/lib/gantti.php");
?>

<section id="content">

    <?php require_once("lib/filterform.php");?>

    <?php
        date_default_timezone_set('UTC');
        setlocale(LC_ALL, 'en_US');
        $data = array();
        foreach($filtered_units as $unit) {
            if($unit->startDate <= 0 || $unit->endDate <= 0) {
                echo "BAD DATE<br>";
                continue;
            }
            $data[] = array(
                'id' => $unit->id,
                'label' => $unit->name,
                'start' => date("Y-m-d",$start = $unit->startDate),
                'end' => date("Y-m-d",$unit->endDate),
                //'class' => 'important' 
            );				
        }
        $gantti = new Gantti($data, array(
          'title'      => 'Units',
          'cellwidth'  => 25,
          'cellheight' => 35
        ));
        echo $gantti;
    ?>
    <?php echo mapping_options();?>
</section>

<?php require_once("footer.php"); ?>