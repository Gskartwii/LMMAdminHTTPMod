<?php
	if ($_GET['al']) echo "<script>alert('".$_GET['al']."');</script>";
?>
<html>
	<head>
		<title>LuaModelMaker's Admin HTTP Mod ~ Register</title>
	</head>
	<body>
		<script>
			function validateEmail(email) { 
			    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			    return re.test(email);
			} 
			function checkform() {
				var error;
				var un=	document.getElementById("un").value;
				var pw=	document.getElementById("pw").value;
				var pw2=document.getElementById("pw2").value;
				var em=	document.getElementById("em").value;
				if (pw!=pw2){alert("The passwords do not match");error=true;}
				if (!validateEmail(em)){alert("The email format is incorrect!");error=true;}
				if (error) return false;
				return true;
			}
		</script>
		<h1>Register to LMM Admin HTTP Mod</h1>
		Please fill in this form to register your admin to modify the settings and such where ever you are.<br />
		<form method="post" action="completereg.php" onsubmit="return checkform();">
			Desired username: <input type="text" name="un" id="un" /><br />
			Password (can be any password you want): <input type="password" name="pw" id="pw" /><br />
			Confirm the password: <input type="password" name="pw2" id="pw2" /><br />
			Email: <input type="email" name="em" id="em"<br />
			<button type="submit">Submit</button><br />
		</form>
	</body>
</html>