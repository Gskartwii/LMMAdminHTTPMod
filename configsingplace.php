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
			echo "<h1>LuaModelMaker's Admin HTTP Mod ~ Settings for Place $pid</h1>";
			echo "<h5>Editing settings for place \"{$row['placename']}\" made by {$row['placecreator']}</h5>";
			$r=mysql_query("SELECT * FROM roblox_settings_$pid");
			if (!$r) die("Database error: ".mysql_error());
			while ($row=mysql_fetch_assoc($r)) {
				$sname=$row['settingname'];
				$sval=$row['settingvalue'];
				switch ($sname) {
					case "fun":
						echo "Fun commands: ".booleanToString($sval);
						break;
					case "lagtime":
						echo "Lag delay (sec): $sval";
						break;
					case "prefix":
						echo "Command prefix: $sval";
						break;
					case "bet":
						echo "Command seperator: \"$sval\"";
						break;
					case "enablemenu":
						echo "Admin menu enabled: ".booleanToString($sval);
						break;
					case "filter":
						echo "Filtered words: $sval";
						break;
					case "slock":
						echo "Server lock: ".booleanToString($sval);
						break;
					case "dabuse":
						echo "Abusive commands disabled: ".booleanToString($sval);
						break;
					case "vipmid":
						echo "VIP Member Pass: <a href='http://roblox.com/item.aspx?id=$sval'>$sval</a>";
						break;
					case "vipaid":
						echo "VIP Admin Pass: <a href='http://roblox.com/item.aspx?id=$sval'>$sval</a>";
						break;
					case "gid":
						echo "Admin Group ID: <a href='http://roblox.com/My/Groups.aspx?gid=$sval'>$sval</a>";
						break;
					case "gmr":
						echo "Group Member Rank: $sval";
						break;
					case "gar":
						echo "Group Admin Rank: $sval";
						break;
					case "gor":
						echo "Group Owner Rank: $sval";
						break;
					case "rankban":
						echo "Group Rank Ban: $sval";
						break;
					case "bgid":
						echo "Member Badge ID: $sval";
						break;
					case "islogpublic":
						echo "Public log: ",booleanToString($sval);
						break;
					default:
						echo "ERROR! UNKNOWN \$sname $sname!";
						break;
				}
				echo " <a href='changesettings.php?pid=$pid&sname=$sname'>Change</a><br />";
			}
		}
		else
			die("Insufficient privileges!");
	}
?>