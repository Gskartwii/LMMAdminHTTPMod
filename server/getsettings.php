<?php
	require("mysqlconn.php");
	$sname=mysql_real_escape_string($_GET['setting']);
	$pid=mysql_real_escape_string($_GET['pid']);
	$r=mysql_query("SELECT * FROM roblox_settings_$pid WHERE settingname='$sname'");
	if (!$r) {
		if ($pid===0) {
			$r=mysql_query("SELECT * FROM roblox_settings_template WHERE settingname='$sname'");
			if (!$r) die("Database error: ".mysql_error());
		}
	}
	if ($sname=="filter") {
		$retstr="[\n";
		$filter=explode(",", mysql_fetch_assoc($r)['settingvalue']);
		for ($i=0;$i<sizeof($filter);$i++)
			$retstr.="\r\"".$filter[$i]."\",\n";
		die($retstr."]");
	}
	echo mysql_fetch_assoc($r)['settingvalue'];
?>