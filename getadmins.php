<?php
	require("mysqlconn.php");
	$rank=mysql_real_escape_string($_GET['rank']);
	$pid=mysql_real_escape_string($_GET['pid']);
	if ($pid==="0"&&$rank=="owner") die("[\"Player1\", \"Player2\"]");
	if ($pid==="0") die("[]");
	$r=mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='$rank'");
	if (!$r) die("Database error: ".mysql_error());
	$ret="[\n";
	while ($row=mysql_fetch_assoc($r))
		$ret.="\t\"".$row['name']."\",\n";
	$ret.="]";
	echo $ret;
?>