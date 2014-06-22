<?php
	session_start();
	require("common.php");
	if (!isset($_SESSION['un'])) die();
	$pid = safe($_GET['pid']);
	$name = safe($_GET['name']);
	$un=$_SESSION['un'];
	$r=mysql_query("SELECT * FROM roblox_placeids_$un WHERE placeid='$pid' AND verified='1'");
	if (!$r) die("Database error: ".mysql_error());
	else {
		if ($row=mysql_fetch_assoc($r))
			addadmin($pid,$name,"neutral");
		else
			die("Insufficient permissions!");
		echo "$name succesfully removed from rank list! Go <a href='configadmin.php?pid=$pid'>back</a> now.";
	}
?>