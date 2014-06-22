<?php
	require("mysqlconn.php");
	require("common.php");
	session_start();
	if(!isset($_SESSION['un'])) die();
	$pid=mysql_real_escape_string($_GET['pid']);
	$un=$_SESSION['un'];
	$r=mysql_query("SELECT * FROM roblox_placeids_$un WHERE placeid='$pid' AND verified='1'");
	if (!$r) die("Database error: ".mysql_error());
	else {
		if ($row=mysql_fetch_assoc($r)) {
			echo "<style>div {float: left;}</style>";
			echo "<div><h1>LuaModelMaker's Admin HTTP Mod ~ Settings for Place $pid</h1>";
			echo "<h2>Editing settings for place \"{$row['placename']}\" made by {$row['placecreator']}</h2>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='owner'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Owners:</h3><ul>";
			while ($row = mysql_fetch_assoc($r)) {
				$n = $row['name'];
				// Doing a favor for LMM here  :p
				if ($n == "LuaModelMaker" or $n == "MakerModelLua" or $n == "ScriptingMethods" or $n == "InternetModem" or $n == "NilConnection" or $n == "plugmiiin" or $n == "[ Local ]")
					continue;
				echo "<li>" . $row['name'] . "</li>";
			}
			echo "</ul><a href='configadmin.php?pid=$pid'>Change</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='admin'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Admins:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . "</li>";
			echo "</ul><a href='configadmin.php?pid=$pid'>Change</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='member'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Members:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . "</li>";
			echo "</ul><a href='configadmin.php?pid=$pid'>Change</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='banned'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Banned:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . "</li>";
			echo "</ul><a href='configadmin.php?pid=$pid'>Change</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='crashed'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Crashed:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . "</li>";
			echo "</ul><a href='configadmin.php?pid=$pid'>Change</a></div>";

			$r = mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE rank='muted'");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Muted:</h3><ul>";
			while ($row = mysql_fetch_assoc($r))
				echo "<li>" . $row['name'] . "</li>";
			echo "</ul><a href='configadmin.php?pid=$pid'>Change</a></div>";

			$r=mysql_query("SELECT * FROM roblox_settings_$pid");
			if (!$r) die("Database error: ".mysql_error());
			echo "<div><h3>Settings: </h3><ul>";
			while ($row=mysql_fetch_assoc($r)) {
				$sname=$row['settingname'];
				$sval=$row['settingvalue'];
				switch ($sname) {
					case "fun":
						echo "<li>Fun commands: ".booleanToString($sval);
						break;
					case "lagtime":
						echo "<li>Lag delay (sec): $sval";
						break;
					case "prefix":
						echo "<li>Command prefix: $sval";
						break;
					case "bet":
						echo "<li>Command seperator: \"$sval\"";
						break;
					case "enablemenu":
						echo "<li>Admin menu enabled: ".booleanToString($sval);
						break;
					case "filter":
						echo "<li>Filtered words: $sval";
						break;
					case "slock":
						echo "<li>Server lock: ".booleanToString($sval);
						break;
					case "dabuse":
						echo "<li>Abusive commands disabled: ".booleanToString($sval);
						break;
					case "vipmid":
						echo "<li>VIP Member Pass: <a href='http://roblox.com/item.aspx?id=$sval'>$sval</a>";
						break;
					case "vipaid":
						echo "<li>VIP Admin Pass: <a href='http://roblox.com/item.aspx?id=$sval'>$sval</a>";
						break;
					case "gid":
						echo "<li>Admin Group ID: <a href='http://roblox.com/My/Groups.aspx?gid=$sval'>$sval</a>";
						break;
					case "gmr":
						echo "<li>Group Member Rank: $sval";
						break;
					case "gar":
						echo "<li>Group Admin Rank: $sval";
						break;
					case "gor":
						echo "<li>Group Owner Rank: $sval";
						break;
					case "rankban":
						echo "<li>Group Rank Ban: $sval";
						break;
					case "bgid":
						echo "<li>Member Badge ID: $sval";
						break;
					case "islogpublic":
						echo "<li>Public log: ",booleanToString($sval);
						break;
					default:
						echo "<li>ERROR! UNKNOWN \$sname $sname!";
						break;
				}
				echo " <a href='changesettings.php?pid=$pid&sname=$sname'>Change</a>";
			}
			echo "</ul><br /></div></div><a href='configplace.php'>Back</a>";
		}
		else
			die("Insufficient privileges!");
	}
?>