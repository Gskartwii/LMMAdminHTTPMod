<?php
	session_start();
	if (isset($_SESSION['un'])) die();
	$un=mysql_real_escape_string($_POST["un"]);
	$pw=md5($_POST["pw"]);
	$em=mysql_real_escape_string($_POST["em"]);
	require("mysqlconn.php");
	$r=mysql_query("SELECT * FROM roblox_lmm_regs WHERE un='$un'");
	echo mysql_error();
	if (mysql_fetch_array($r))
		header("Location: register.php?al=Username already taken!");
	else {
		mysql_query("INSERT INTO roblox_lmm_regs SET un='$un', pw='$pw', em='$em'");
		echo mysql_error();
		mysql_query("CREATE TABLE IF NOT EXISTS roblox_placeids_$un (id int(11) NOT NULL AUTO_INCREMENT, placeid int(11) NOT NULL, placename VARCHAR(255) NOT NULL, placedesc TEXT NOT NULL, placecreator VARCHAR(100) NOT NULL, verified SMALLINT NOT NULL, PRIMARY KEY (id), KEY id (id))");
		echo mysql_error();
		mysql_query("CREATE TABLE IF NOT EXISTS roblox_verified_accs_$un (id int(11) NOT NULL AUTO_INCREMENT, accid VARCHAR(255) NOT NULL, accname VARCHAR(255) NOT NULL, PRIMARY KEY (id), KEY id (id))");
		echo mysql_error();
		echo "You account has now been created!";
		echo "You can now log in from the <a href='login.php'>login page</a>.";
	}
?>