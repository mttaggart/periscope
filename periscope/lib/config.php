<?php

/*
Periscope Curriculum Mapping Configuration File
Install scripts to be built to generate this information, but for now, we'll hard code.
Contains:
	DB Connection Info
	Sitewide functions as needed
*/

//Timezone setting:
date_default_timezone_set('America/New_York');

$bg_directory = "/var/www/periscope/images/bg";
$backgrounds = array_slice(scandir($bg_directory),2);

define("DB_HOST", "localhost");
define("DB_NAME","periscope");

//shouldn't be root in release; install should create a user 

define("DB_USER", "root"); 
define("DB_PASS", "password");


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
					
$asset_tables = array("EssentialQuestions" => "EQ",
                      "Content" => "CON",
                      "Skills" => "SKL",
                      "Activities" => "ACT",
                      "Resources" => "RSC",
                      "Assessments" => "ASS");
					
$upload_dir = "../uploads/";

//Pagination
$perpage = 20;

?> 

