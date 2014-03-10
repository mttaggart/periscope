<?php
	require_once("../lib/sessions.php");
	require_once("../lib/functions.php");
	$_SESSION["admin_id"] = null;
	$_SESSION["username"] = null;
	redirect_to("login.php");
?>