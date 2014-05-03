<?php
	require("mysqlconn.php");
	session_start();
	if(!isset($_SESSION['un'])) die();
	$pid=mysql_real_escape_string($_GET['pid']);
	mysql_query("DELETE FROM roblox_placeids_{$_SESSION['un']} WHERE placeid='$pid'");
	echo mysql_error();
	echo "Your place has been succesfully deleted. <a href='configplace.php'>Go back to my places</a>";
?>