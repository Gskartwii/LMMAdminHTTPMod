<?php
	require("mysqlconn.php");
	session_start();
	if(!isset($_SESSION['un'])) die();
	$pid=mysql_real_escape_string($_GET['pid']);
	try {
		mysql_query("START TRANSACTION");
		mysql_query("DELETE FROM roblox_placeids_{$_SESSION['un']} WHERE placeid='$pid'");
		mysql_query("DROP TABLE roblox_log_$pid");
		mysql_query("DROP TABLE roblox_log_sid_$pid");
		mysql_query("COMMIT");
	}
	catch (Exception $e) {
		mysql_query("ROLLBACK");
		die(mysql_error());
	}
	echo "Your place has been succesfully deleted. <a href='configplace.php'>Go back to my places</a>";
?>