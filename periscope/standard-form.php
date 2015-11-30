<?php  
    $std_libs = StandardsLibrary::find_all();
    $std_cats = StandardsCategory::find_all();
?>

 <select class='button' id='std-lib' name='std-lib'>
 <?php 
    echo "<option value='choose'>Select A Library</option>";
    foreach($std_libs as $std_lib){
        echo "<option value = '{$std_lib->id}'>{$std_lib->name}</option>";
    }
 ?>
</select><br/>	
<label for="std-lib">Standards Library</label><br />
<select class="button" id="std-cat" name="std-cat">
    <option value="choose">Select a Library</option>
</select><br />
<label for="std-cat">Standards Category</label><br />
<select class="button" id="std-subcat" name="std-cat">
    <option value="choose">Select a Category</option>
</select><br />
<label for="std-subcat">Standards Subcategory</label><br />

<select class="button" id="std" name="std">
    <option value="choose">Select a Subcategory</option>
</select><br/>
<label for="std">Standards</label><br />
<div id="autocomplete">
    
</div>

<script>
    $(document).ready(function(){
        $("#std-lib").change(function(){
            console.log("Getting Categories");
            var lib = $(this).val();
            var optString = "?l=" + lib
            $.get("get-standards.php" + optString, function(data){
                $("#std-cat").html("<option>Select Category</option>");
                $("#std-cat").append(data);
            });
        });
        
        $("#std-cat").change(function(){
            console.log("Getting Subcategories");
            var lib = $("#std-lib").val();
            var cat = $(this).val();
            var optString = "?l=" + lib + "&c=" + cat;
            $.get("get-standards.php" + optString, function(data){
                $("#std-subcat").html("<option>Select Subcategory</option>");
                $("#std-subcat").append(data);
            });
        });
        
        $("#std-subcat").change(function(){
            console.log("Getting Standards");
            var lib = $("#std-lib").val();
            var cat = $("#std-cat").val();
            var subCat = $(this).val();
            var optString = "?l=" + lib + "&c=" + cat + "&sc=" + subCat;
            console.log(optString);
            $.get("get-standards.php" + optString, function(data){
                $("#std").html("<option>Select Standard</option");
                $("#std").append(data);
            });
        });
        
        $("#std").change(function(){
            console.log($(this).val());
            var stdVal = "#std option[value='"+$(this).val() + "']";
            $("#asset-text").val($(stdVal).text());
        });      
    });

</script>