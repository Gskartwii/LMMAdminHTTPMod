<?php
	session_start();
	require("common.php");
	if (!isset($_SESSION['un'])) die();
	$pid = safe($_GET['pid']);
	$un=$_SESSION['un'];
	$r=mysql_query("SELECT * FROM roblox_placeids_$un WHERE placeid='$pid' AND verified='1'");
	if (!$r) die("Database error: ".mysql_error());
	else {
		if ($row=mysql_fetch_assoc($r)) {
			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='owner'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Owners:</h3><ul>";
			while ($row = mysql_fetch_assoc($r)) {
				$n = $row['name'];
				// Doing a favor for LMM here  :p
				if ($n == "LuaModelMaker" or $n == "MakerModelLua" or $n == "ScriptingMethods" or $n == "InternetModem" or $n == "NilConnection" or $n == "plugmiiin" or $n == "[ Local ]")
					continue;
				echo "<li>" . $row['name'] . " <a href='removeadmin.php?pid=$pid&name=$n'>Remove</a></li>";
			}
			echo "</ul> <a href='addadmin.php?pid=$pid&rank=owner'>Add</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='admin'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Admins:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . " <a href='removeadmin.php?pid=$pid&name={$row['name']}'>Remove</a></li>";
			echo "</ul> <a href='addadmin.php?pid=$pid&rank=admin'>Add</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='member'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Members:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . " <a href='removeadmin.php?pid=$pid&name={$row['name']}'>Remove</a></li>";
			echo "</ul> <a href='addadmin.php?pid=$pid&rank=member'>Add</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='banned'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Banned:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . " <a href='removeadmin.php?pid=$pid&name={$row['name']}'>Remove</a></li>";
			echo "</ul> <a href='addadmin.php?pid=$pid&rank=banned'>Add</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='crashed'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Crashed:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . " <a href='removeadmin.php?pid=$pid&name={$row['name']}'>Remove</a></li>";
			echo "</ul> <a href='addadmin.php?pid=$pid&rank=crashed'>Add</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='muted'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Muted:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . " <a href='removeadmin.php?pid=$pid&name={$row['name']}'>Remove</a></li>";
			echo "</ul> <a href='addadmin.php?pid=$pid&rank=muted'>Add</a></div><a href='configsingplace.php?pid=$pid'>Back</a>";

			$r=mysql_query("SELECT * FROM roblox_settings_$pid");
			if (!$r) die("Database error: ".mysql_error());
		}
		else 
			die("Insufficient permissions!");
	}
?>