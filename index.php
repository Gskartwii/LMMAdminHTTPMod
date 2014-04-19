<?php
	session_start();
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
		<h3>Menu</h5>
		<a href="configplace.php">Configure your places</a>
		<a href="sessdes.php">Log out</a>
	</body>
</html>