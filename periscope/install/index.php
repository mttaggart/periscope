<?php 
$page_title = "Install Periscope";
//require_once("../../lib/sessions.php");
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
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
    <link type="text/css" href="../css/global.css" rel="stylesheet" media="all" >
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <script src="../js/jquery.js"></script>
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <?php
        session_start();
        require_once("../lib/functions.php");


    ?>

    <title><?php echo $page_title . " | " . $site_name; ?></title>
</head>

<body>

    <div id="header-wrapper">

        <header id="banner">
            <hgroup>
                <h1>Periscope</h1>
            </hgroup>
        </header>


        <header id = "page-title">
                <h2><?php echo $page_title;?></h2>			
        </header>

    </div>
    <section id="content">
        <?php 
            if(isset($_GET["stage"])){
                $stage = $_GET["stage"];
            } else {
                $stage = 0;
            }
            
            require_once("install-{$stage}.php");
        
        ?>
    </section>