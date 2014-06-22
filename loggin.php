<?php
require("mysqlconn.php");
require("common.php");
$act=mysql_real_escape_string($_GET["action"]);
$usr=mysql_real_escape_string($_GET["username"]);
$msg=mysql_real_escape_string($_GET["msg"]);
$s=mysql_real_escape_string($_GET["sid"]);
$pid=mysql_real_escape_string($_GET["pid"]);
$killed=false;
$time=gmdate("U");
$auth=mysql_real_escape_string($_GET["auth"]);
if (!checkAuthCode($auth,$pid)) die("Insufficient permissions!");
//echo $usr;
if ($act==null) {
	die("No action set!!");
}
else {
	if ($usr==null) {
		die("No user set!!");
	}
	else {
		//mysql_query("CREATE TABLE IF NOT EXISTS roblox_log_$pid (id int(11) NOT NULL AUTO_INCREMENT, user VARCHAR(255) NOT NULL, msg VARCHAR(7000) NOT NULL, `time` INT(255) NOT NULL, sid int(11) NOT NULL, PRIMARY KEY (id), KEY id (id))");
		switch ($act) {
			case "join":
				mysql_query("INSERT INTO roblox_log_$pid SET user='<i>SYSTEM</i>', msg='$usr has joined.', `time`='$time', sid='$s'");
				break;
			case "leave":
				mysql_query("INSERT INTO roblox_log_$pid SET user='<i>SYSTEM</i>', msg='$usr has left.', `time`='$time', sid='$s'");
				/*if (!strstr($_GET['plrlist'], "+")) {
					mysql_query("INSERT INTO roblox_log SET user='<i>SYSTEM</i>', msg='Killed server $s.', `time`='$time', sid='$s'");
					mysql_query("UPDATE roblox_log_sid SET status='dead' WHERE sid='$s'");
					$killed=true;
				}*/
				break;
			case "chat":
				mysql_query("INSERT INTO roblox_log_$pid SET user='$usr', msg='$msg', `time`='$time', sid='$s'");
				break;
			case "kill":
				mysql_query("INSERT INTO roblox_log_$pid SET user='<i>SYSTEM</i>', msg='Killed server $s.', `time`='$time', sid='$s'");
				mysql_query("UPDATE roblox_log_sid_$pid SET status='dead' WHERE sid='$s'");
				break;
			default:
				die("Unknown action!!");
				break;
		}
		echo mysql_error();
		if (isset($_GET['plrlist']) && !$killed) {
			$list=mysql_real_escape_string($_GET['plrlist']);
			mysql_query("INSERT INTO roblox_log_$pid SET user='<i>SYSTEM</i>', msg='Current player list: $list', `time`='$time', sid='$s'");
			echo mysql_error();
		}
	}
}
?>