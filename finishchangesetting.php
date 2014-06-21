<?php
	require("mysqlconn.php");
	session_start();
	if (!isset($_SESSION['un'])) die();
	$un=$_SESSION['un'];
	$pid=mysql_real_escape_string($_POST['pid']);
	$sval=mysql_real_escape_string($_POST['newval']);
	$stype=mysql_real_escape_string($_POST['stype']);
	$r=mysql_query("SELECT * FROM roblox_placeids_$un WHERE placeid='$pid' AND verified='1'");
	if (!$r) die("Database error: ".mysql_error());
	if (!mysql_fetch_assoc($r)) die("Insufficient privileges!");
	mysql_query("UPDATE roblox_settings_$pid SET settingvalue='$sval' WHERE settingname='$stype'");
	echo mysql_error();
	die("Your settings have been updated! <a href='configsingplace.php?pid=$pid'>Go back to place configuration</a>");
?>