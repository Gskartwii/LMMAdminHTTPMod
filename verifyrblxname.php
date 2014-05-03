<?php
	require("common.php");
	require("mysqlconn.php");
	session_start();
	if (!isset($_SESSION['un'])) die();
	$rnd=randomString("LMMV-", 10);
	$_SESSION["vercode"]=$rnd;
?>
<html>
	<head>
		<title>LuaModelMaker's Admin HTTP Mod ~ Verify your ROBLOX account(s)</title>
	</head>
	<body>
		<h1>
			LuaModelMaker's Admin HTTP Mod ~ Verify your ROBLOX account(s)
		</h1>
		Put the following code in your blurb, then type in your username and click on "Submit".<br />
		<big><code><?=htmlspecialchars($rnd) ?></code></big><br />
		<form action="finishaccverify.php" method="post">
			Username: <input type="text" name="rbxacc" id="rbxacc" /><br />
			<button type="submit">Submit</button>
		</form><br />
		<h3>Your accounts</h3>
		<?php
			$r=mysql_query("SELECT * FROM roblox_verified_accs_{$_SESSION['un']}");
			if (!$r)
				echo "Database error: ".mysql_error();
			else {
				while ($row=mysql_fetch_assoc($r))
					echo "ID ".$row['accid']." Username ".$row['accname']." <a href='delveracc.php?id=".$row['accid']."'>Delete account</a><br />";
			}
		?>
		<a href="./">Go back to front page</a>
	</body>
</html>