<?php
	include_once("include/phpQuery/phpQuery.php");
	require("mysqlconn.php");
	require("common.php");
	session_start();
	if (!isset($_SESSION['vercode'])) die();
	$rbxun=$_POST['rbxacc'];
	$f=file("http://roblox.com/user.aspx?username=$rbxun");
	$fstr=implode("\n", $f);
	$doc=phpQuery::newDocument($fstr);
	//$b=$doc["body"];
	//$blurb=$b->find("div.UserBlurb")->html();
	$blurb=$doc["div.UserBlurb"]->html();
	$pos=strpos($blurb, $_SESSION['vercode']);
	//echo $_SESSION['vercode'];
	//echo $blurb."<br />\n";
	if ($pos===false) die("Code not found in blurb! <a href='./'>Back to front page!</a>");
	/*$ch=curl_init();
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_URL, "http://roblox.com/user.aspx?username=$rbxun");
	$h=curl_getinfo($ch, CURLINFO_HEADER_OUT);
	$res=curl_exec($ch);
	echo $h;*/
	$res=curl_trace_redirects("http://roblox.com/user.aspx?username=$rbxun");
	$i=0;
	$uid=0;
	foreach ($res as $redi) {
		if (isset($redi['timeout']))
			die("Account verification failed: Reached timeout while fetching user id!");
		else if (isset($redi['error']))
			die("Account verification failed: Error happened while fetching user id! (".$redi['error'].")");
		else {
			//echo $redi['url'];
			//if (!empty($redi['redirect_url']))
				//echo " was redirected by code ".$redi['http_code']." to url ".$redi['redirect_url'];
			//echo "<br />\n";
			if ($i==2) {
				$uid=explode("?ID=", $redi['url'])[1];
			}
			$i++;
		}
	}
	//echo $uid;
	if ($uid===0 || !$uid)
		die("Account verification failed: Couldn't fetch user id because the account doesn't exist (or redirection error?)");
	$r=mysql_query("SELECT * FROM roblox_verified_accs_{$_SESSION['un']} WHERE accid='$uid'");
	if (!$r) die("Database error: ".mysql_error());
	if (mysql_fetch_assoc($r)) die("You already have verified that account. <a href='./'>Back to front page</a>");
	mysql_query("INSERT INTO roblox_verified_accs_{$_SESSION['un']} SET accid='$uid', accname='$rbxun'");
	echo mysql_error();
	mysql_query("UPDATE roblox_placeids_{$_SESSION['un']} SET verified='1' WHERE placecreator='$rbxun'");
	echo mysql_error();
	$r=mysql_query("SELECT * FROM roblox_placeids_{$_SESSION['un']} WHERE placecreator='$rbxun'");
	if (!$r) die("Database error: ".mysql_error());
	while ($row=mysql_fetch_assoc($r)) {
		$id=$row['placeid'];
		try {
			mysql_query("START TRANSACTION");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_adminlist_$id LIKE roblox_adminlist_template");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_log_$id LIKE roblox_log_template");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_log_sid_$id LIKE roblox_log_sid_template");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_settings_$id LIKE roblox_settings_template");
			mysql_query("INSERT roblox_settings_$id SELECT * FROM roblox_settings_template");
			mysql_query("CREATE TABLE IF NOT EXISTS roblox_log_userlist_$id LIKE roblox_log_userlist_template");
			mysql_query("COMMIT");
		}
		catch (Exception $e) {
			mysql_query("ROLLBACK");
			die(mysql_error());
		}
	}
	echo "Your account has now been verified! <a href='./'>Go back to the front page</a>";
?>