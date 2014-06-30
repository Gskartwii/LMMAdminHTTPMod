<?php
	include_once("include/phpQuery/phpQuery.php");
	require("mysqlconn.php");
	require("common.php");
	session_start();
	if(!isset($_SESSION['un'])) die();
	$pid=$_POST['pid'];
	$f=file("http://roblox.com/PlaceItem.aspx?id=$pid");
	$str=implode("\n", $f);
	$doc=phpQuery::newDocument($str);
	$buildername=$doc["body"]->find("div.builder-name span.notranslate a")->attr("original-title");
	$placename=$doc["body"]->find("div.item-header h1.notranslate")->html();
	$placedesc=$doc["body"]->find("pre#PlaceDescription")->html();
	$pid=mysql_real_escape_string($pid);
	$builder=mysql_real_escape_string($buildername);
	$placename=mysql_real_escape_string($placename);
	$placedesc=mysql_real_escape_string($placedesc);
	if (!$builder || !$placename) die("Invalid placeid: \$builder or \$placename were not set. Check the placeid. <a href='configplace.php'>Go back to my places</a>");
	$verified=isVerifiedRBLXAccount($_SESSION['un'], $builder);
	$r=mysql_query("SELECT * FROM roblox_placeids_{$_SESSION['un']} WHERE placeid='$pid'");
	if (!$r) die("Database error: ".mysql_error());
	else {
		if (mysql_fetch_assoc($r))
			die("You already have added this place to your list! <a href='./'>Back to front page</a>");
		else {
			$r=mysql_query("SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'roblox_placeids_%'");
			if (!$r) die("Database error: ".mysql_error());
			while ($row=mysql_fetch_assoc($r)) {
				$r2=mysql_query("SELECT * FROM {$row['table_name']}  WHERE placeid='$pid' AND verified='1'");
				if (!$r2) die("Database error: ".mysql_error());
				if (mysql_fetch_assoc($r2)) die("Someone else has the place added to their list! <a href='./'>Back to front page</a>");
			}
		}
	}
	if ($verified) {
		try {
			mysql_query("START TRANSACTION");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_adminlist_$pid LIKE roblox_adminlist_template");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_log_$pid LIKE roblox_log_template");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_log_sid_$pid LIKE roblox_log_sid_template");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_settings_$pid LIKE roblox_settings_template");
			mysql_query("INSERT roblox_settings_$pid (SELECT * FROM roblox_settings_template)");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_log_userlist_$pid LIKE roblox_log_userlist_template");
			mysql_query("COMMIT");
		}
		catch (Exception $e) {
			mysql_query("ROLLBACK");
			die(mysql_error());
		}
	}
	mysql_query("INSERT INTO roblox_placeids_{$_SESSION['un']} SET placeid='$pid', placename='$placename', placecreator='$builder', placedesc='$placedesc', verified='$verified'");
	echo mysql_error();
	echo "Your place has been added to the database. <a href='configplace.php'>Go back to my places</a><br />";
	if (!$verified)
		echo "Notice you have to verify your ROBLOX name to enable the place.";
?>