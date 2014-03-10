<?php

/*
Periscope Curriculum Mapping Configuration File
Install scripts to be built to generate this information, but for now, we'll hard code.
Contains:
	DB Connection Info
	Sitewide functions as needed
*/

$bg_directory = "/var/www/periscope/images/bg";
$backgrounds = array_slice(scandir($bg_directory),2);

$db_host = "localhost";
$db_name = "periscope";

//shouldn't be root in release; install should create a user 

$db_user = "root"; 
$db_password = "Umfund1$1";

//Site Name

$site_name = "Periscope Curriculum Mapping";
$banner_name = "Periscope";
$banner_subtitle = "Curriculum Mapping System";

//Asset Definitions

$assets = array("Essential Questions" => "eq", 
					"Content" => "con", 
					"Skills" => "skl",
					"Activities" => "act",
					"Resources" => "rsc",
					"Assessments" => "ass");
					
$upload_dir = "../uploads/";

?> 

