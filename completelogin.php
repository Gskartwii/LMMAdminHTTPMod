<?php
	session_start();
	if (isset($_SESSION['un'])) die();
	$un=mysql_real_escape_string($_POST['un']);
	$pw=md5($_POST['pw']);
	require("mysqlconn.php");
	$r=mysql_query("SELECT * FROM roblox_lmm_regs WHERE un='$un' AND pw='$pw'");
	echo mysql_error();
	if (mysql_fetch_assoc($r)) {
		$_SESSION['un']=$un;
		echo "Successful login, $un! Go to the <a href='index.php'>home page</a> now.";
	}
	else
		header("Location: login.php?al=Username or password incorrect!");
?>