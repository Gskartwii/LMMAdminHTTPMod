<?php
// mysql_connect() here, not shown :P
mysql_select_db("3591_other");
$r=mysql_query("SELECT * FROM roblox_log_sid ORDER BY sid DESC");
while ($row=mysql_fetch_assoc($r)) {
	if ($row['status']=="dead") {
		$sid=$row['sid'];
		break;
	}
}
$time=gmdate("U");
if (!isset($sid)) {$sid=$row['sid']+1;mysql_query("INSERT INTO roblox_log_sid SET sid='$sid', status='active'");mysql_query("INSERT INTO roblox_log SET user='<i>SYSTEM</i>', msg='Added server $sid!', `time`='$time', sid='$sid'");}
else {mysql_query("UPDATE roblox_log_sid SET status='active' WHERE sid='$sid'");mysql_query("INSERT INTO roblox_log SET user='<i>SYSTEM</i>', msg='Revived dead server $sid!', `time`='$time', sid='$sid'");}
echo $sid;
?>