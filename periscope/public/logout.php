<?php
	require_once("../lib/sessions.php");
	require_once("../lib/functions.php");
	$session->logout();
	redirect_to("login.php");
?>