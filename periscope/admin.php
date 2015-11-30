<?php
$page_title = "Admin Home";
require_once("header.php");
$session->login_check();
$session->admin_login_check();
?>


<div id="content-wrapper">

	<div id="content">
			<div id="admin-menu">
				<ul id="admin-menu-list">
				
					<li class="button"><a href="admin-users.php">Manage Users</a></li>
					<li class="button"><a href="admin-subjects.php">Manage Subjects</a></li>
					<li class="button"><a href="admin-gradelevels.php">Manage Grade Levels</a></li>
					<li class="button"><a href="admin-asstypes.php">Manage Assessment Types</a></li>
					<li class="button"><a href="admin-units.php">Manage Units</a></li>
					<li class="button"><a href="admin-upload.php">Upload Files</a></li>
								
				
				</ul>
			
			</div>
	
	</div>

</div>


<?php require_once("footer.php"); ?>