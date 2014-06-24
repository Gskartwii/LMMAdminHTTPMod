<?php
	session_start();
	$un = $_SESSION['un'];
	require("mysqlconn.php");
	if (!isset($_SESSION['un'])) {
		header("Location: login.php");
		die();
	}
?>
<html>
	<head>
		<title>
			LuaModelMaker's Admin HTTP Mod ~ Home
		</title>
	</head>
	<body>
		<h1>LuaModelMaker's Admin HTTP Mod</h1>
		<?php
			$r = mysql_query("SELECT * FROM roblox_codes WHERE un='$un'");
			if (!$r) die("Database error! " . mysql_error());
			echo "Hello, $un! Your authentication code is <code>" . mysql_fetch_assoc($r)["code"] . "</code>";
		?>
		<h3>Menu</h3>
		<a href="configplace.php">Configure your places</a><br />
		<a href="verifyrblxname.php">Verify your ROBLOX account(s)</a><br />
		<a href="sessdes.php">Log out</a>
	</body>
</html>