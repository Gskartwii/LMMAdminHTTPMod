<?php
	require("mysqlconn.php");
	$pid=$_GET['pid'];
	mysql_query("CREATE TABLE IF NOT EXISTS roblox_log_sid_$pid (id int(11) NOT NULL AUTO_INCREMENT, status VARCHAR(255) NOT NULL, sid int(11) NOT NULL, PRIMARY KEY (id), KEY id (id))");
	mysql_query("CREATE TABLE IF NOT EXISTS roblox_log_$pid (id int(11) NOT NULL AUTO_INCREMENT, user VARCHAR(255) NOT NULL, msg VARCHAR(7000) NOT NULL, `time` INT(255) NOT NULL, sid int(11) NOT NULL, PRIMARY KEY (id), KEY id (id))");
	$r=mysql_query("SELECT * FROM roblox_log_sid_$pid ORDER BY sid DESC");
	while ($row=mysql_fetch_assoc($r)) {
		if ($row['status']=="dead") {
			$sid=$row['sid'];
			break;
		}
	}
	$time=gmdate("U");
	if (!isset($sid)) {
		$sid=$row['sid']+1;
		mysql_query("INSERT INTO roblox_log_sid_$pid SET sid='$sid', status='active'");
		mysql_query("INSERT INTO roblox_log_$pid SET user='<i>SYSTEM</i>', msg='Added server $sid!', `time`='$time', sid='$sid'");
	}
	else {
		mysql_query("UPDATE roblox_log_sid_$pid SET status='active' WHERE sid='$sid'");
		mysql_query("INSERT INTO roblox_log_$pid SET user='<i>SYSTEM</i>', msg='Revived dead server $sid!', `time`='$time', sid='$sid'");
	}
	echo $sid;
?>