<?php
	session_start();
	if (isset($_SESSION['un'])) die();
	$un=mysql_real_escape_string($_POST["un"]);
	$pw=md5($_POST["pw"]);
	$em=mysql_real_escape_string($_POST["em"]);
	require("mysqlconn.php");
	require("common.php");
	$r=mysql_query("SELECT * FROM roblox_lmm_regs WHERE un='$un'");
	echo mysql_error();
	if (mysql_fetch_array($r))
		header("Location: register.php?al=Username already taken!");
	else {
		try {
			mysql_query("START TRANSACTION");
			mysql_query("INSERT INTO roblox_lmm_regs SET un='$un', pw='$pw', em='$em'");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_placeids_$un LIKE roblox_placeids_template");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_verified_accs_$un LIKE roblox_verified_accs_template");
			$code=mysql_real_escape_string(randomString("LMMC-", 10));
			mysql_query("INSERT INTO roblox_codes SET un='$un', code='$code'");
			mysql_query("COMMIT");
		}
		catch (Exception $e) {
			mysql_query("ROLLBACK");
			die(mysql_error());
		}
		echo mysql_error();
		echo "You account has now been created!<br />";
		echo "Your unique code for use with the admin script is: <code>$code</code><br />";
		echo "You can now log in from the <a href='login.php'>login page</a>.";
	}
?>