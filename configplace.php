<?php
	session_start();
	if(!isset($_SESSION['un'])) die();
?>
<html>
	<head>
		<title>LuaModelMaker's Admin HTTP Mod ~ Place Configuration</title>
	</head>
	<body>
		<h1>LuaModelMaker's Admin HTTP Mod ~ Place Configuration</h1>
		<script>var infos=[];</script>
		<?php
			require("mysqlconn.php");
			$r=mysql_query("SELECT * FROM roblox_placeids_{$_SESSION['un']}");
			if (!$r)
				echo "Error while fetching places: ".mysql_error();
			else {
				while ($row=mysql_fetch_assoc($r)) {
					$desc=nl2br($row['placedesc']);
					$desc=str_replace(array("\r", "\n"), '<br />', $desc); 
					echo "<a href='#' onclick='return false;' onmouseover='showinfo({$row['placeid']});' onmouseout='hideinfo();' id='{$row['placeid']}'>{$row['placeid']}</a> ";
					if ($row['verified'])
						echo "<span title='Verified place' style='color: green;'>&#x2713;</span> <a href='configsingplace.php?pid={$row['placeid']}'>Configure this place</a> ";
					else
						echo "<span title='Place not verified' style='color: red;'>&#x2717;</span>";
					echo "<a href='updateplace.php?pid={$row['placeid']}'>Update information</a> <a href='deleteplace.php?pid={$row['placeid']}'>Delete place</a><br />\n";
					echo "<script>infos[{$row['placeid']}]={name: '{$row['placename']}', creator: '{$row['placecreator']}', desc: '$desc', verified: ";
					if ($row['verified'])
						echo "true";
					else
						echo "false";
					echo "};</script>";
				}
			}
			$r=mysql_query("SELECT * FROM roblox_auth_codes WHERE un='{$_SESSION['un']}'");
		?>
		<br />
		<br />
		<a href="./">Go back to the front page</a><br />
		<a href="placeadd.php">Add a place</a>
		<script>
			var elem=null;
			function showinfo(id) {
				if (elem!==null) return;
				elem=document.createElement("div");
				elem.style.position="absolute";
				elem.style.left=document.getElementById(id).getBoundingClientRect.left+"px";
				elem.style.top=document.getElementById(id).getBoundingClientRect.top+"px";
				elem.style.width="300px";
				elem.style.height="300px";
				elem.style.backgroundColor="white";
				elem.style.border="solid thin white";
				elem.innerHTML="\""+infos[id].name+"\" by "+infos[id].creator;
				document.body.appendChild(elem);
			}
			function hideinfo() {
				document.body.removeChild(elem);
				elem=null;
			}
		</script>
	</body>
</html>