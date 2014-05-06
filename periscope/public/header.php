<?php require_once("../lib/sessions.php");?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
    <link rel="stylesheet" href="../gantti/styles/css/gantti.css" />
    <!--<link rel="stylesheet" href="../gantti/styles/css/screen.css" />-->

    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link type='text/css' href='http://fonts.googleapis.com/css?family=Ubuntu:400,700' rel='stylesheet' >
    <link type="text/css" href="../css/global.css" rel="stylesheet" media="all" >
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <script src="../js/jquery.js"></script>
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <?php

        //Connect to periscope database.
        require_once("../lib/config.php");
        require_once("../lib/val.php");
        require_once("../lib/functions.php");
        require_once("../lib/database.php");
        require_once("../lib/dbobjects.php");
        require_once("../lib/assets.php");


    ?>

    <title><?php echo $page_title . " | " . $site_name; ?></title>
</head>

<body>

    <div id = "header-wrapper">

        <header id = "banner">
            <hgroup>
                <h1><?php echo $banner_name;?></h1>
                <h2><?php echo $banner_subtitle;?></h2>
            </hgroup>

        </header>

        <?php

            if($session->logged_in() && $session->user->is_admin()) {
                echo "<nav id=\"navbar-admin\" class=\"navbar\">

                <ul id=\"navlinks-admin\" class=\"navlinks\">
                    <li><a href=\"admin-users.php\">Manage Users</a></li>
                    <li><a href=\"admin-subjects.php\">Manage Subjects</a></li>
                    <li><a href=\"admin-gradelevels.php\">Manage Grade Levels</a></li>
                    <li><a href=\"admin-asstypes.php\">Manage Assessment Types</a></li>
                    <li><a href=\"admin-units.php\">Manage Units</a></li>
                    <li><a href=\"admin-upload.php\">Upload Files</a></li>

                </ul>
				
                       </nav>";
            }
        ?>

        <nav id="navbar-main" class="navbar">
            <h2>Main Navigation</h2>
                <ul id="navlinks-main" class="navlinks">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="browse.php">Browse Units</a></li>
                    <li><a href="keyword.php">Keyword Search</a></li>		
                    <li><a href="new-unit.php">New Unit</a></li>	
                    <li><a href="admin.php">Administration</a></li>
                    <?php
                        if($session->logged_in()) {
                            echo "<li><a href=\"logout.php\">Log Out</a></li>";
                        } else {
                            echo "<li><a href=\"login.php\">Log In</a></li>";
                        }
                    ?>
                    
                </ul>
        </nav>	

        <header id = "page-title">
                <h2><?php echo $page_title;?></h2>			
        </header>

    </div>
	
