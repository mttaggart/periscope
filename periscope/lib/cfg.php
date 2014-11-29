<?php

/*
Periscope Curriculum Mapping Configuration File
Install scripts to be built to generate this information, but for now, we'll hard code.
Contains:
	DB Connection Info
	Sitewide functions as needed
*/

//error reporting disabled
error_reporting(0);

//Timezone setting:
date_default_timezone_set('America/New_York');

define("DB_HOST", "localhost");
define("DB_NAME","periscope");
define("DB_USER", "root"); 
define("DB_PASS", "Umfund1$1");
define("SCHOOL_NAME", "Demo School");

//Site Details
$site_name = "Periscope Curriculum Mapping";
$banner_name = "Periscope";
$banner_subtitle = "Curriculum Mapping System";

//Asset Definitions

$assets = array("Essential Questions" => "eq", 
					"Content" => "con", 
					"Skills" => "skl",
					"Activities" => "act",
					"Resources" => "rsc",
					"Assessments" => "ass",
                                        "Standards" => "std");
					
$asset_tables = array("EssentialQuestions" => "EQ",
                      "Content" => "CON",
                      "Skills" => "SKL",
                      "Activities" => "ACT",
                      "Resources" => "RSC",
                      "Assessments" => "ASS",
                      "Standards" => "STD");

$asset_objects = array("EssentialQuestions" => "EssentialQuestion",
                      "Content" => "Content",
                      "Skills" => "Skill",
                      "Activities" => "Activity",
                      "Resources" => "Resource",
                      "Assessments" => "Assessment",
                      "Standards" => "Standard");
					
$upload_dir = "../uploads/";

//Pagination
$perpage = 20;

?> 

