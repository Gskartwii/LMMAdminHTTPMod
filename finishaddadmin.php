<?php
	session_start();
	require("common.php");
	if (!isset($_SESSION['un'])) die();
	$pid = safe($_POST['pid']);
	$rank = safe($_POST['rank']);
	$name = safe($_POST['name']);
	$un=$_SESSION['un'];
	$r=mysql_query("SELECT * FROM roblox_placeids_$un WHERE placeid='$pid' AND verified='1'");
	if (!$r) die("Database error: ".mysql_error());
	else {
		if ($row=mysql_fetch_assoc($r))
			addadmin($pid,$name,$rank);
		else
			die("Insufficient permissions!");
		echo "$name succesfully added to the list! Go <a href='configadmin.php?pid=$pid'>back</a> now.";
	}
?>