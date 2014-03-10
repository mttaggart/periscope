<?php
$page_title = "Admin Home";
require_once("../lib/sessions.php");
require_once("header.php");

if(!logged_in()) {
	$_SESSION["errors"] = "Please log in to view this page";
	redirect_to("login.php");
}

?>


<div id="content-wrapper">

	<div id="content">
			<div id="admin-menu">
				<ul id="admin-menu-list">
				
					<li class="menubutton"><a href="edit-admin.php">Manage Admins</a></li>
					<li class="menubutton"><a href="admin-subjects.php">Manage Subjects</a></li>
					<li class="menubutton"><a href="admin-gradelevels.php">Manage Grade Levels</a></li>
					<li class="menubutton"><a href="admin-asstypes.php">Manage Assessment Types</a></li>
					<li class="menubutton"><a href="admin-units.php">Manage Units</a></li>
					<li class="menubutton"><a href="admin-upload.php">Upload Files</a></li>
								
				
				</ul>
			
			</div>
	
	</div>

</div>


<?php require_once("footer.php"); ?>