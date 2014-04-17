<!DOCTYPE html>
<?php
require("mysqlconn.php");
$r=mysql_query("SELECT * FROM roblox_log ORDER BY id DESC");
?>
<title>Public log for ~ Adventure ~</title>
<meta charset="UTF-8" />
<select onchange="if (this.selectedIndex){ location=this.options[this.selectedIndex].value;}">
	<option value='#'>Choose one...</option>
	<?php
		$r2=mysql_query("SELECT * FROM roblox_log_sid ORDER BY sid DESC");
		while ($row=mysql_fetch_assoc($r2)) echo "<option value='logpub?sid={$row['sid']}'>Server ID {$row['sid']}, status {$row['status']}</option>";
	?>
	<option value='logpub'>All servers</option>
</select><br />
<form action="logpub" method="GET">
	<input type="checkbox" value="showsys" name="showsys" checked="<?php if ($_GET['showsys']) echo "checked"; ?>" />Hide system messges<br />
	<input type="checkbox" value="showchat" name="showchat" checked="<?php if ($_GET['showchat']) echo "checked"; ?>" />Hide chat messages<br />
	<button type="submit">Update</button>
</form>
<?php
while ($row=mysql_fetch_assoc($r)) {
	if ((!isset($_GET['sid']) || $_GET['sid']==$row['sid'])&&($_GET['showsys']||$row['user']=='<i>SYSTEM</i>')&&($_GET['showchat']||$row['user']!='<i>SYSTEM</i>'))
		echo $row['user'].": ".$row['msg']." (".$row['time']." UNIX timestamp GMT, on SID {$row['sid']})<br />\n";
}
?>
This document is valid! <a href="http://validator.nu/?doc=http%3A%2F%2Fgskartwii.arkku.net%2Froblox%2Flogpub">Proof</a>