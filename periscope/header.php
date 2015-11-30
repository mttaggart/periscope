<?php require_once("lib/sessions.php");?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
    <link rel="stylesheet" href="gantti/styles/css/gantti.css" />
    <!--<link rel="stylesheet" href="../gantti/styles/css/screen.css" />-->

    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
     <!-- Latest compiled and minified CSS -->
    <!--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">-->

    <!-- Optional theme -->
    <!--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">-->


    <!-- Latest compiled and minified JavaScript -->
    <!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>-->

    <link type='text/css' href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' >
    <link type="text/css" href="css/global.css" rel="stylesheet" media="all" >
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <script src="js/jquery.js"></script>
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <?php

//        Connect to periscope database.
        require_once("lib/cfg.php");
        require_once("lib/val.php");
        require_once("lib/functions.php");
        require_once("lib/database.php");
        require_once("lib/dbobjects.php");
        require_once("lib/assets.php");
        require_once("lib/standards.php");


    ?>

    <title><?php echo $page_title . " | " . $site_name; ?></title>
</head>

<body>

    <div id="header-wrapper">

        <header id="banner">
            <hgroup>
                <h1><?php echo $banner_name;?></h1>
            </hgroup>
        </header>
        <div id="burger">
            <img class="button" width="40" id="burger-button" src="images/hb.png" />
        </div>

        <nav id="navbar-main" class="navbar">
            <div id="nav-main" class="navblock">
                <h2>Main Navigation</h2>
                    <ul id="navlinks-main" class="navlinks">
                        <li><a class="button" href="index.php">Home</a></li>
                        <li><a class="button" href="browse.php">Browse Units</a></li>
                        <li><a class="button" href="keyword.php">Keyword Search</a></li>		
                        <li><a class="button" href="new-unit.php">New Unit</a></li>	

                        <?php
                            if($session->logged_in()) {
                                echo "<li><a class=\"button\" href=\"logout.php\">Log Out</a></li>";
                            } else {
                                echo "<li><a class=\"button\" href=\"login.php\">Log In</a></li>";
                            }

                        ?>

                    </ul>                
            </div>
                <?php 
                    if($session->user->is_admin === 1) {
                        require_once("admin-header.php");
                    }
                ?>
        </nav>	

        <header id = "page-title">
                <h2><?php echo $page_title;?></h2>
                <aside class="button">
                    <a href="new-unit.php">New Unit</a>
                </aside>
        </header>

    </div>
	
