<?php
	include_once("include/phpQuery/phpQuery.php");
	require("mysqlconn.php");
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
	mysql_query("UPDATE roblox_placeids_{$_SESSION['un']} SET placeid='$pid', placename='$placename', placecreator='$builder', placedesc='$placedesc' WHERE placeid='$pid'");
	echo mysql_error();
	echo "Your place information has been updated. <a href='configplace.php'>Go back to my places</a>";
?>