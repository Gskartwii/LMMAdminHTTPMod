<?php
	require("mysqlconn.php");
	session_start();
	if (!isset($_SESSION['un'])) die();
	$id=mysql_real_escape_string($_GET['id']);
	$un=$_SESSION['un'];
	$rblxun=mysql_fetch_assoc(mysql_query("SELECT accname FROM roblox_verified_accs_$un WHERE accid='$id'"))["accname"];
	//echo $rblxun;
	mysql_query("DELETE FROM roblox_verified_accs_$un WHERE accid='$id'");
	echo mysql_error();
	$r=mysql_query("SELECT * FROM roblox_placeids_{$_SESSION['un']} WHERE placecreator='$rblxun' AND verified='1'");
	if (!$r) die("Database error: ".mysql_error());
	while ($row=mysql_fetch_assoc($r)) {
		try {
			$pid=$row['placeid'];
			mysql_query("START TRANSACTION");
			mysql_query("DROP TABLE roblox_adminlist_$pid");
			mysql_query("DROP TABLE roblox_log_$pid");
			mysql_query("DROP TABLE roblox_log_sid_$pid");
			mysql_query("COMMIT");
		}
		catch (Exception $e) {
			mysql_query("ROLLBACK");
			die(mysql_error());
		}
	}
	mysql_query("UPDATE roblox_placeids_$un SET verified='0' WHERE placecreator='$rblxun'");
	echo mysql_error();
	echo "Your account has been deleted. <a href='./'>Go back to the front page</a>"
?>