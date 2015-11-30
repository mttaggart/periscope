<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
<?php
    $page_title = "Assessments by Type";
    require_once("header.php");
    $session->login_check();
?>
<section id="content" class="clearfix">

    <?php require_once("lib/filterform.php");?>
    <div id="chart_div" align="center"></div>
    <?php echo mapping_options(); ?>

</section>

<?php
    $asstypes = AssessmentType::find_all();        
    $asscount = array();

    foreach($asstypes as $asstype) {
        $asscount[$asstype->id] = array("type"=>$asstype->text, "count"=>0);
    }

    foreach($filtered_units as $unit) {
        $unit->attach_asset("Assessment");
        foreach($unit->assets["Assessment"] as $assessment) {
            $asscount[$assessment->ass_type]["count"]++;                
        }
    }       
?>

<script type="text/javascript">
    
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
      
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);


      // Callback that creates and populates a data table, 
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Assessment');
      data.addColumn('number', 'Occurrences');
  
      <?php

      	foreach($asscount as $ass) {
            echo "data.addRow(['{$ass['type']}',{$ass['count']}]);";      	
      	}
      
      ?>

      // Set chart options
      var options = {'title':'Assessments by Type',
                     'width':1000,
                     'height':600};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>

<?php require_once("footer.php"); ?>