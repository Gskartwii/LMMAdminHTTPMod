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
	mysql_query("UPDATE roblox_placeids_$un SET verified='0' WHERE placecreator='$rblxun'");
	echo "Your account has been deleted. <a href='./'>Go back to the front page</a>"
?>