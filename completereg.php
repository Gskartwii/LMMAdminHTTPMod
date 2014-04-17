<?php
	$un=$_POST["un"];
	$pw=md5($_POST["pw"]);
	$em=$_POST["em"];
	// mysql_connect() here, not shown :P
	mysql_select_db("3591_other");
	$r=mysql_query("SELECT * FROM roblox_lmm_regs WHERE un='$un'");
	echo mysql_error();
	if (mysql_fetch_array($r))
		header("Location: register.php?al=Username already taken!");
	else {
		mysql_query("INSERT INTO roblox_lmm_regs SET un='$un', pw='$pw', em='$em'");
		echo mysql_error();
		echo "You account has now been created!";
		echo "You can now log in from the <a href='login.php'>login page</a>.";
	}
?>