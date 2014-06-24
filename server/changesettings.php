<?php
	require("mysqlconn.php");
	$pid=mysql_real_escape_string($_GET['pid']);
	$s=mysql_real_escape_string($_GET['sname']);
	session_start();
	$un=$_SESSION['un'];
	if (!isset($_SESSION['un'])) die();
	$r=mysql_query("SELECT * FROM roblox_placeids_$un WHERE placeid='$pid' AND verified='1'");
	if (!$r) die("Database error: ".mysql_error());
	if (!mysql_fetch_assoc($r)) die("This place has not been verified! <a href='./'>Back to the front page</a>");
	$r=mysql_query("SELECT settingvalue FROM roblox_settings_$pid WHERE settingname='$s'");
	if (!$r) die("Database error: ".mysql_error());
	if (!($row=mysql_fetch_assoc($r))) die("No such setting! <a href='./'>Back to the front page</a>");
	$res=$row['settingvalue'];
	echo "<script src='$s.js'></script>";
	echo "<form action='finishchangesetting.php' method='post' onsubmit='return checkform();'>";
	echo "New value: <input type='text' name='newval' id='newval' value='$res' /><input type='hidden' name='pid' value='$pid' id='pid' /><input type='hidden' name='stype' id='stype' value='$s' />";
	echo "<br /><button type='submit'>Change</button>";
	echo "</form>";
?>