<?php

    function data_clean($data_string) {
            return htmlentities(trim($data_string));
    }


    function clean_uri () {
        if (strpos($_SERVER["REQUEST_URI"], "?") == 0){
                return $_SERVER["REQUEST_URI"];
        } else {
                //returns URI without PHP variables. Useful for stripping URIs in preparation for edit/delete links
                return substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"],"?"));
        }
    }

    function url_rebuild($base=null, $f_month=null, $f_gl=null, $f_s=null) {
        //produces clean URL for rebuilding after adding/deleting;
        if(!$f_month && isset($_GET["month"])) {
            $f_month = $_GET["month"];
        }

        if(!$f_gl && isset($_GET["gl"])) {
            $f_gl = $_GET["gl"];
        }

        if(!$f_s && isset($_GET["s"])) {
            $f_s = $_GET["s"];
        }

        if(!$base) {
            if (strpos($_SERVER["REQUEST_URI"],"?")==0) {
                $url_base = $_SERVER["REQUEST_URI"];
            } else {
                $url_base = substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"?"));
            }
        } else {
            $url_base = $base;		
        }


        $url_rebuild = $url_base;
        //NEED TO FIX ? vs &
        if ($f_month) {
            $url_rebuild .= "?month={$f_month}";
        }

        if ($f_gl) {
            if (isset($_GET["month"])) {
                $url_rebuild .= "&gl={$f_gl}";
            } else {
                $url_rebuild .= "?gl={$f_gl}";
            }		
        }

        if ($f_s) {
            if (isset($_GET["month"]) || isset($_GET["gl"])) {
                $url_rebuild .= "&s={$f_s}";
            } else {
                $url_rebuild .= "?s={$f_s}";
            }	
        }

        return $url_rebuild;
    }

    function mapping_options() {

            $timeline = url_rebuild($base="timeline.php");
            $browse = url_rebuild($base="browse.php");
            $keyword = url_rebuild($base="keyword.php");
            $assbytype = url_rebuild($base="assbytype.php");

            $map_nav = "<nav id=\"mapping-options\" class=\"clearfix\">
                            <h2>Mapping Options</h2>

                            <ul id=\"mapping-list\">
                                <li id=\"browse\" class=\"button\"><a href=\"{$browse}\">Browse Units</a></li>				
                                <li id=\"timeline\" class=\"button\"><a href=\"{$timeline}\">Timeline View</a></li>
                                <li id=\"keyword\" class=\"button\"><a href=\"{$keyword}\">Keyword Search</a></li>
                                <li id=\"assbytype\" class=\"button\"><a href=\"{$assbytype}\">Assessment Distribution</a></li>
                            </ul>	
                    </nav>";
            return $map_nav;
    }

    function redirect_to($destination) {
        header("Location: " . $destination);
        exit;
    }

    function list_errors() {
        global $errors;
        echo "<div class=\"errorbox\">";
        echo "<ul>";
        foreach($errors as $field => $error) {
            echo "<li>" . htmlentities($error);
            echo "</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
?>