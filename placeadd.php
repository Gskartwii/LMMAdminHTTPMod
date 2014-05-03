<?php
	session_start();
	if(!isset($_SESSION['un'])) die();
?>
<html>
	<head>
		<title>LuaModelMaker's Admin HTTP Mod ~ Add a place</title>
	</head>
	<body>
		<h1>LuaModelMaker's Admin HTTP Mod ~ Add a place</h1>
		Thanks to the phpQuery team for the awesome jQuery port! This project wouldn't have been possible without them!<br />
		<form method="post" action="finishplaceadd.php">
			Place ID: <input type="number" placeholder="152265393" name="pid" /><br />
			<button type="submit">Add the place</button>
		</form>
	</body>
</html>