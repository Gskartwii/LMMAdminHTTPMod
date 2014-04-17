<?php
// mysql_connect() here, not shown :P
mysql_select_db("3591_other");
$act=$_GET["action"];
$usr=$_GET["username"];
$msg=$_GET["msg"];
$s=$_GET["sid"];
$killed=false;
$time=gmdate("U");
//echo $usr;
if ($act==null) {
	die("No action set!!");
}
else {
	if ($usr==null) {
		die("No user set!!");
	}
	else {
		switch ($act) {
			case "join":
				mysql_query("INSERT INTO roblox_log SET user='<i>SYSTEM</i>', msg='$usr has joined.', `time`='$time', sid='$s'");
				break;
			case "leave":
				mysql_query("INSERT INTO roblox_log SET user='<i>SYSTEM</i>', msg='$usr has left.', `time`='$time', sid='$s'");
				/*if (!strstr($_GET['plrlist'], "+")) {
					mysql_query("INSERT INTO roblox_log SET user='<i>SYSTEM</i>', msg='Killed server $s.', `time`='$time', sid='$s'");
					mysql_query("UPDATE roblox_log_sid SET status='dead' WHERE sid='$s'");
					$killed=true;
				}*/
				break;
			case "chat":
				mysql_query("INSERT INTO roblox_log SET user='$usr', msg='$msg', `time`='$time', sid='$s'");
				break;
			case "kill":
				mysql_query("INSERT INTO roblox_log SET user='<i>SYSTEM</i>', msg='Killed server $s.', `time`='$time', sid='$s'");
				break;
			default:
				die("Unknown action!!");
				break;
		}
		echo mysql_error();
		if (isset($_GET['plrlist']) && !$killed) {
			mysql_query("INSERT INTO roblox_log SET user='<i>SYSTEM</i>', msg='Current player list: {$_GET['plrlist']}', `time`='$time', sid='$s'");
			echo mysql_error();
		}
	}
}
?>