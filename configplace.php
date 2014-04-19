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
					echo "<a href='#' onclick='return false;' onmouseover='showinfo({$row['placeid']});' onmouseout='hideinfo();' id='{$row['placeid']}'>{$row['placeid']}</a><br />\n";
					echo "<script>infos[{$row['placeid']}]={name: '{$row['placename']}', creator: '{$row['placecreator']}', desc: '{$row['placedesc']}'};</script>";
				}
			}
		?>
		<br />
		<br />
		<a href="placeadd.php">Add a place</a>
		<script>
			var elem=null;
			function showinfo(id) {
				if (elem!==null) return;
				elem=document.createElement("div");
				elem.style.position="absolute";
				elem.style.left=document.getElementById(id).getBoundingClientRect.left+"px";
				elem.style.top=document.getElementById(id).getBoundingClientRect.top+"px";
				elem.style.width="100px";
				elem.style.height="100px";
				elem.style.backgroundColor="white";
				elem.style.border="solid thin black";
				elem.innerHTML=infos[id].name+" by "+infos[id].creator;
				document.body.appendChild(elem);
			}
			function hideinfo() {
				document.body.removeChild(elem);
				elem=null;
			}
		</script>
	</body>
</html>