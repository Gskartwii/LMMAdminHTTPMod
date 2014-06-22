<!DOCTYPE html>
<?php
session_start();
$un = $_SESSION['un'];
require("common.php");
$pid=mysql_real_escape_string($_GET['pid']);
$r=mysql_query("SELECT * FROM roblox_log_$pid ORDER BY id DESC");
/*$r2 = mysql_query("SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'roblox_placeids_%'");
while ($row = mysql_fetch_assoc($r2)) {
	$currtn = $row["table_name"];
	$r3 = mysql_query("SELECT * FROM $currtn WHERE verified='1'");
}*/
$owner = getOwnerByPid($pid);
if (!$owner) die("This place has not been activated yet!");
$r2 = mysql_query("SELECT settingvalue FROM roblox_settings_$pid WHERE settingname='islogpublic'");
if (!stringToBoolean(getsetting($pid,"islogpublic")) and $owner != $un) die("This place's log is not public! Your username is $un, the owner's is $owner");
$pn = getPlaceNameByPid($pid);
?>
<title>Public log for <?=$pn ?></title>
<meta charset="UTF-8" />
<select onchange="if (this.selectedIndex){ location=this.options[this.selectedIndex].value;}">
	<option value='#'>Choose one...</option>
	<?php
		$r2=mysql_query("SELECT * FROM roblox_log_sid_$pid ORDER BY sid DESC");
		while ($row=mysql_fetch_assoc($r2)) echo "<option value='logpub.php?sid={$row['sid']}&pid=$pid'>Server ID {$row['sid']}, status {$row['status']}</option>";
	?>
	<option value='logpub.php'>All servers</option>
</select><br />
<form action="logpub.php" method="GET">
	<input type="checkbox" value="showsys" name="showsys" checked="<?php if ($_GET['showsys']) echo "checked"; ?>" />Hide system messges<br />
	<input type="checkbox" value="showchat" name="showchat" checked="<?php if ($_GET['showchat']) echo "checked"; ?>" />Hide chat messages<br />
	<input type="hidden" value="<?=$pid ?>" name="pid" />
	<button type="submit">Update</button>
</form>
<?php
while ($row=mysql_fetch_assoc($r)) {
	if (
		(!isset($_GET['sid'])
		|| $_GET['sid']==$row['sid'])
		&& (
			!$_GET['showsys']
			|| $row['user']!='<i>SYSTEM</i>'
		)
		&& (
			!$_GET['showchat']
			|| $row['user']=='<i>SYSTEM</i>'
		)
	)
		echo $row['user'].": ".$row['msg']." (".$row['time']." UNIX timestamp GMT, on SID {$row['sid']}, placeid $pid)<br />\n";
}
?>
<!--This document is valid! <a href="http://validator.nu/?doc=http%3A%2F%2Fgskartwii.arkku.net%2Froblox%2Flogpub">Proof</a> Who cares if it is valid?-->