<?php
$page_title = "Manage Standards";
require_once("header.php");
$session->login_check();
$session->admin_login_check();
$libraries = StandardsLibrary::find_all();


//POST Handling

if (isset($_POST["submit"])){

    $required_fields = array("library");

    val_presences($required_fields);
    if(empty($errors)) { 
        $name = $db->mysql_prep($_POST["library"]);

        if(isset($_GET["e"])) {
            $edit_id = $db->mysql_prep($_GET["e"]);
            $edit_library = StandardsLibrary::id_get($edit_id);
            $edit_library->name = $name;
            $edit_library->update();
            redirect_to("admin-standards.php");

        } else {
            $new_library = new StandardsLibrary();
            $new_library->name = $name;
            $new_library->insert();
            redirect_to("admin-standards.php");
        }
    }
} elseif (isset($_POST["upload"])) {
     if (isset($_FILES["file"]) && isset($_GET["e"])) {
        $required_fields = array();
        val_presences($required_fields);
        if(empty($errors)){
            $edit_id = $db->mysql_prep($_GET["e"]);
            $edit_library = StandardsLibrary::id_get($edit_id); 
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
                        $new_standards = array();
                        $new_categories = array();
                        $new_subcategories = array();

                        //Assemble Categories
                        while($data = fgetcsv($work_file,1000,",")){
                            if(key_exists($data[1], $new_categories)){
                                continue;                            
                            } else{
                                $new_category = new StandardsCategory();
                                $new_category->label = $db->mysql_prep($data[1]);
                                $new_category->library = $edit_library->id;
                                $new_categories[$new_category->label] = $new_category;                      
                            }
                        }
                        
//                        Insert Categories into DB
                        foreach($new_categories as $new_cat){
                            $new_cat->insert();
                        }
                        
//                        Pull back Categories so we can use IDs for subcats
                        $cat_query = "SELECT * FROM StandardsCategories WHERE STD_L_ID = {$edit_id};";
                        $categories = StandardsCategory::sql_get_title_set($cat_query);
                        
                        rewind($work_file);

                        //Assemble Subcategories
                        while($data = fgetcsv($work_file,1000,",")){
                            if(key_exists($data[2], $new_subcategories)){
                                continue;                            
                            } else{
                                $new_subcategory = new StandardsSubCategory();
                                $new_subcategory->label = $db->mysql_prep($data[2]);
                                $new_subcategory->library = $edit_library->id;
                                $new_subcategory->category = $categories[$db->mysql_prep($data[1])]->id;
                                $new_subcategories[$new_subcategory->label] = $new_subcategory;                                                  
                            }
                        }                      

//                        Insert subcats into DB
                        foreach($new_subcategories as $new_subcat){
                            $new_subcat->insert();
                        }

                        rewind($work_file);
                        
//                        Pull back subcats so we can use both cats and subcats for standards
                        $subcat_query = "SELECT * FROM StandardsSubCategories WHERE STD_L_ID = {$edit_id};";                        
                        $subcategories = StandardsSubCategory::sql_get_title_set($subcat_query);

                        //Assemble and insert Standards
                        while($data = fgetcsv($work_file, 1000, ",")) {
                            $new_standard = new StandardsListItem();
                            $new_standard->label = $db->mysql_prep($data[0]);
                            $new_standard->library = $edit_id;
                            $new_standard->category = $categories[$db->mysql_prep($data[1])]->id;
                            $new_standard->subcategory = $subcategories[$db->mysql_prep($data[2])]->id;
                            $new_standard->text = $db->mysql_prep($data[3]);
                            $new_standard->insert();
                        }
//                        redirect_to("admin-standards.php");
                    }
                }
            }        
        } 
     }
}

if(isset($_GET["d"])) {
    $delete_id = $db->mysql_prep($_GET["d"]);
    $delete_library = StandardsLibrary::id_get($delete_id);
    $delete_library->delete();
    redirect_to("admin-standards.php");	
}

?>
<script type="text/javascript">
    $(document).ready(function () {
        <?php
            if(isset($_GET["e"])) {
                $edit_id = $db->mysql_prep($_GET["e"]);
                $edit_library = StandardsLibrary::id_get($edit_id);
                echo "$('#standards-edit').attr('action', 'admin-standards.php?e={$edit_id}');";
                echo "$('#standards-upload').attr('action', 'admin-standards.php?e={$edit_id}');";
                echo "$('#submit').val('Edit Library');";				
                echo "$('#library').val('{$edit_library->name}');";
            }
        ?>
    });
</script>


<section id="content">
    <?php 
        $session->session_errors();
        echo list_errors();
    ?>
    <h3>Standards Libraries</h3>
    <table id="standards-table">
        <th>Collection</th>			
        <th>Actions</th>
        <?php
            $url_base = clean_uri();
            foreach($libraries as $library) {
                $edit_url = $url_base . "?e=" . $library->id;
                $remove_url = $url_base . "?d=" .$library->id;
                echo "<tr>
                        <td>{$library->name}</td>
                        <td><a class='button' href='{$edit_url}'>Edit</a> | <a class='button' href='{$remove_url}'>Remove</a></td>
                      </tr>";	
            }
        ?>
    </table>

    <form id="standards-edit" method="post" action="admin-standards.php">
        <hr>
        <input id="library" type="text" name="library"></input>
        <label for="library">Library</label><br>
        <input class="button" id="submit" type="submit" name="submit" value="New Library"></input><br /><br />
    </form>
    <hr />
    <form id="standards-upload" enctype="multipart/form-data" method="post" action="admin-standards.php">
        <h3>Upload .csv of Standards</h3>
        <p>
            Upload a comma-separated value (.csv) file of standards to attach to 
            your library. <strong>This will overwrite any existing standards!</strong>
        </p>
        <p>
            Files should have the following columns:
        </p>
        <table>
            <tr>
                <td>Label |</td><td>Category |</td><td>Sub-Category |</td><td>Text</td>                
            </tr>
        </table><br />
        <input class="button" id="file" type="file" name="file"></input>
        <label for="file">Upload File</label><br>
        <input class="button" id="upload" type="submit" name="upload" value="Upload"></input>
    </form>

</section>




<?php require_once("footer.php"); ?>