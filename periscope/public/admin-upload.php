<?php
$page_title = "Upload Files";
require_once("header.php");
$session->login_check();


//POST Handling

if (isset($_POST["submit"])){

    if (isset($_FILES["file"])) {

        //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        } else {
            //if file already exists   
            if (file_exists("uploads/" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists. ";
            } else {

                $storagename = $_FILES["file"]["name"];
                move_uploaded_file($_FILES["file"]["tmp_name"], $upload_dir . $storagename); 
                if($work_file = fopen($upload_dir . $storagename, "r")) {
                    if($_POST["table"]=="Unit") {
                        $new_units = array();
                        $unit_count = 0;
                         while($data = fgetcsv($work_file, 1000, ",")) {
                            $new_unit = new Unit();
                            $new_unit->user = (int)$data[0];
                            $new_unit->name = $data[1];
                            $new_unit->gradeLevel = (int)$data[2];
                            $new_unit->subject = (int)$data[3];
                            $new_unit->startDate = $data[4];
                            $new_unit->endDate = $data[5];
                            $new_unit->comments = !empty($data[6]) ? $data[6] : "";
                            $new_units[] = $new_unit;
                            $unit_count++;
                        }

                        foreach($new_units as $new_unit) {
                            $new_unit->insert();
                            echo "<p style='color:white;'>File uploaded!</p>";
                        }
                    } else {
                        $asset_table = $db->mysql_prep($_POST["table"]);
                        $new_assets = array();
                        $asset_count = 0;
                        while($data = fgetcsv($work_file, 1000, ",")) {
                            $new_asset = new $asset_table();
                            $new_asset->id = $data[0];
                            $new_asset->text = $data[1];
                            if($asset_table == "Assessments") {
                                $new_asset->ass_type = $data[2];
                            }
                            $asset_count++;
                        }
                        foreach($new_assets as $new_asset) {
                            $new_asset->insert();
                            echo "<p style='color:white;'>File uploaded!</p>";                        
                        }
                    }
                fclose($work_file); 
                unlink($upload_dir . $storagename);


            } else {
                echo "Couldn't work!";            
            }
        }
    }
    } else {
        echo "No file selected <br />";
    }
}

?>



<section id="content" class="clearfix">
    <?php 
        $session->session_errors();
        list_errors();
    ?>


    <form id="upload-csv" enctype="multipart/form-data" method="post" action="admin-upload.php">
        <hr>
        <p>Uploaded files must be <b>.csv</b> files. They also must have the following columns:</p>

        <h3>For Units:</h3>
        <table>
            <th>Author</th>
            <th>Name</th>
            <th>GradeLevel_id</th>
            <th>Subject_id</th>	
            <th>StartDate</th>
            <th>EndDate</th>
            <th>Comments</th>
            <tr>
                <td>INT</td>
                <td>VARCHAR(30)</td>	
                <td>INT(11)</td>
                <td>VARCHAR(30)</td>
                <td>YYYY-MM-DD</td>
                <td>YYYY-MM-DD</td>	
                <td>VARCHAR(30)</td>
            </tr>			
        </table>

        <h3>For Assets:</h3>
        <table>
            <th>U_ID</th>
            <th>Text</th>
            <tr>
                <td>INT</td>
                <td>VARCHAR(255)</td>			
            </tr>			
        </table>
        <input id="file" type="file" name="file"></input>
        <select id="table" name="table">Select Table
            <option value="Unit">Units</option>
            <option value="EssentialQuestion">Essential Questions</option>
            <option value="Content">Content</option>	
            <option value="Skill">Skills</option>		
            <option value="Activity">Activities</option>
            <option value="Resource">Resources</option>
            <option value="Assessment">Assessments</option>	
        </select>
        <input type="hidden" name="MAX_FILE_SIZE" value="25000" />
        <label for="file">Upload File</label><br>
        <input id="submit" type="submit" name="submit" value="Upload"></input>
    </form>

</section>

<script type="text/javascript">
    $(document).ready(function () {

    });
</script>

<?php require_once("footer.php"); ?>