<?php
	session_start();
	if ($_GET['al']) echo "<script>alert('".$_GET['al']."');</script>";
	if (isset($_SESSION['un'])) die();
?>
<html>
	<head>
		<title>LuaModelMaker's Admin HTTP Mod ~ Login</title>
	</head>
	<body>
		<h1>Login to LMM Admin HTTP Mod</h1>
		<form method="post" action="completelogin.php">
			Username: <input type="text" name="un" id="un" /><br />
			Password: <input type="password" name="pw" id="pw" /><br />
			<button type="submit">Login</button><br />
		</form>
		No account? <a href="register.php">Register!</a><br />
		If you have problems, email GSKW: gskartwii at gskartwii dot arkku dot net<br />
		<a href="faq.php">Frequently Asked Questions</a>
	</body>
</html>