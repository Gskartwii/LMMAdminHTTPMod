<?php
	session_start();
	require("common.php");
	if (!isset($_SESSION['un'])) die();
	$pid = safe($_GET['pid']);
	$rank = safe($_GET['rank']);
	$un=$_SESSION['un'];
	$r=mysql_query("SELECT * FROM roblox_placeids_$un WHERE placeid='$pid' AND verified='1'");
	if (!$r) die("Database error: ".mysql_error());
	else {
		if ($row=mysql_fetch_assoc($r)) {
			echo "Adding to list \"$rank\".<br /><form method='post' action='finishaddadmin.php'>\n";
			echo "<input type='hidden' value='$rank' name='rank' id='rank' />\n";
			echo "<input type='hidden' value='$pid' name='pid' id='pid' />\n";
			echo "<input type='text' placeholder='Temppeliherra' name='name' id='name' /><br />\n";
			echo "<button type='submit'>Add</button>\n";
			echo "</form>";
		}
		else
			die("Insufficient permissions!");
	}
?>