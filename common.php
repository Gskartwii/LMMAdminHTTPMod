<?php
	require("mysqlconn.php");
	function isVerifiedRBLXAccount($lmmun, $rblxun) {
		$r=mysql_query("SELECT * FROM roblox_verified_accs_$lmmun WHERE accname='$rblxun'");
		if (mysql_fetch_assoc($r))
			return 1;
		return 0;
	}
	function randomString($prefix, $length) {
		$base="";
		for ($i=0x21;$i<0x7F;$i++)
			$base.=chr($i);
		//echo $base;
		$ret=$prefix;
		for ($i=0;$i<$length;$i++)
			$ret.=$base[rand(0,strlen($base))];
		return $ret;
	}
	function curl_trace_redirects($url, $timeout = 15) {
	    $result = array();
	    $ch = curl_init();
	    $trace = true;
	    $currentUrl = $url;
	    $urlHist = array();
	    while($trace && $timeout > 0 && !isset($urlHist[$currentUrl])) {
	        $urlHist[$currentUrl] = true;
	        curl_setopt($ch, CURLOPT_URL, $currentUrl);
	        curl_setopt($ch, CURLOPT_HEADER, true);
	        curl_setopt($ch, CURLOPT_NOBODY, true);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	        $output = curl_exec($ch);
	        if($output === false) {
	            $traceItem = array(
	                'errorno' => curl_errno($ch),
	                'error' => curl_error($ch),
	            );
	            $trace = false;
	        }
	        else {
	            $curlinfo = curl_getinfo($ch);
	            if(isset($curlinfo['total_time'])) {
	                $timeout -= $curlinfo['total_time'];
	            }
	            if(!isset($curlinfo['redirect_url'])) {
	                $curlinfo['redirect_url'] = get_redirect_url($output);
	            }
	            if(!empty($curlinfo['redirect_url'])) {
	                $currentUrl = $curlinfo['redirect_url'];
	            } else {
	                $trace = false;
	            }
	            $traceItem = $curlinfo;
	        }
	        $result[] = $traceItem;
	    }
	    if($timeout < 0) {
	        $result[] = array('timeout' => $timeout);
	    }
	    curl_close($ch);
	    return $result;
	}
	function get_redirect_url($header) {
	    if(preg_match('/^Location:\s+(.*)$/mi', $header, $m)) {
	        return trim($m[1]);
	    }
	    return "";
	}
	function booleanToString($boolean) {
		if ($boolean===true or $boolean==="true")
			return "Yes";
		else return "No";
	}
	function stringToBoolean($str) {
		if ($str==="true") return true;
		else if ($str==="false") return false;
		return !!$str;
	}
	function getOwnerByPid($pid) {
		$r=mysql_query("SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'roblox_placeids_%'");
		if (!$r) die("Database error: ".mysql_error());
		while ($row=mysql_fetch_assoc($r)) {
			$tn=$row['table_name'];
			$r2=mysql_query("SELECT placecreator FROM $tn WHERE placeid='$pid' AND verified='1'");
			if (!$r2) die("Database error: ".mysql_error());
			else if (mysql_fetch_assoc($r2)) return explode("_", $tn)[2];
		}
		return false;
	}
	function checkAuthCode($authcode, $pid) {
		$r=mysql_query("SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'roblox_placeids_%'");
        $pc="";
        $pcun="";
        while ($row=mysql_fetch_assoc($r)) {
            $tn=$row['table_name'];
            $r2=mysql_query("SELECT placecreator FROM $tn WHERE placeid='$pid' AND verified='1'");
            if ($row2=mysql_fetch_assoc($r2)){
                $pc=$row2['placecreator'];
                $pcun=explode("_", $tn)[2];
            }
        }
        if (!$pc) return false;
        $r=mysql_query("SELECT * FROM roblox_codes WHERE un='$pcun' AND code='$authcode'");
        if (!mysql_fetch_assoc($r)) return false;
        return true;
	}
	function exQuery($query) {
		if (!mysql_query($query)) throw new Exception("");
	}
	function safe($val) {
		if ($val===false) return "false";
		else if ($val===true) return "true";
		return mysql_real_escape_string(strval($val));
	}
	function addadmin($pid,$name,$rank) {
		$r=mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE name='$name' AND rank='$rank'");
        if (!$r) die("\nDatabase error: ".mysql_error());
        else {
            if (!mysql_affected_rows()) {
                $r=mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE name='$name'");
                if (!$r) die("\nDatabase error: ".mysql_error());
                if (mysql_affected_rows())
                    $r=mysql_query("UPDATE roblox_adminlist_$pid SET rank='$rank' WHERE name='$name'");
                else
                    $r=mysql_query("INSERT INTO roblox_adminlist_$pid SET rank='$rank', name='$name'");
                if (!$r) die("\nDatabase error: ".mysql_error());
            }
        }
	}
	function setsetting($pid,$stype,$sval) {
		mysql_query("UPDATE roblox_settings_$pid SET settingvalue='$sval' WHERE settingname='$stype'");
		echo mysql_error();
	}
	function getsetting($pid,$stype) {
		$r=mysql_query("SELECT settingvalue FROM roblox_settings_$pid WHERE settingname='$stype'");
		echo mysql_error();
		if (!$r) die();
		if (!mysql_affected_rows()) die("Setting name invalid");
		return mysql_fetch_array($r)[0];
	}
	function getsettings($pid) {
		return [
			"Bet" 				=> getsetting($pid, "bet"),
			"LagTime" 			=> getsetting($pid, "lagtime"),
			"Prefix" 			=> getsetting($pid, "prefix"),
			"FUN" 				=> getsetting($pid, "fun"),
			"EnableAdminMenu" 	=> stringToBoolean(getsetting($pid, "enablemenu")),
			"Filter" 			=> explode(",", getsetting($pid, "filter")),
			"ServerLocked" 		=> stringToBoolean(getsetting($pid, "slock")),
			"DisableAbuse" 		=> stringToBoolean(getsetting($pid, "dabuse")),
			"VIPMemberID" 		=> (int) getsetting($pid, "vipmid"),
			"VIPAdminID" 		=> (int) getsetting($pid, "vipaid"),
			"GroupID" 			=> (int) getsetting($pid, "gid"),
			"GroupMemberRank"	=> (int) getsetting($pid, "gmr"),
			"GroupAdminRank"	=> (int) getsetting($pid, "gar"),
			"GroupOwnerRank"	=> (int) getsetting($pid, "gor"),
			"RankBan"			=> (int) getsetting($pid, "rankban"),
			"BadgeID"			=> (int) getsetting($pid, "bgid")
		];
	}
	function setsettings(
		$pid,
		$fun,
		$lagtime,
		$prefix,
		$bet,
		$enablemenu,
		$filter,
		$slock,
		$dabuse,
		$vipmid,
		$vipaid,
		$gid,
		$gmr,
		$gar,
		$gor,
		$rankban,
		$bdgid) 
	{
		setsetting($pid,"fun",$fun);
		setsetting($pid,"lagtime",$lagtime);
		setsetting($pid,"prefix",$prefix);
		setsetting($pid,"bet",$bet);
		setsetting($pid,"enablemenu",$enablemenu);
		setsetting($pid,"filter",$filter);
		setsetting($pid,"slock",$slock);
		setsetting($pid,"dabuse",$dabuse);
		setsetting($pid,"vipmid",$vipmid);
		setsetting($pid,"vipaid",$vipaid);
		setsetting($pid,"gid",$gid);
		setsetting($pid,"gmr",$gmr);
		setsetting($pid,"gar",$gar);
		setsetting($pid,"gor",$gor);
		setsetting($pid,"rankban",$rankban);
		setsetting($pid,"bgid",$bdgid);
	}
	function getadmins($pid) {
		$ret = [
			"Ranks" => [
				"Owner" => [],
				"Admin" => [],
				"Member" => [],
				"Banned" => [],
				"Crashed" => [],
				"Muted" => []
			]
		];
		$r = mysql_query("SELECT * FROM roblox_adminlist_$pid");
		while ($row = mysql_fetch_assoc($r)) {
			if ($row['rank']=='owner') $ret["Ranks"]["Owner"][] = $row['name'];
			else if ($row['rank']=='admin') $ret["Ranks"]["Admin"][] = $row['name'];
			else if ($row['rank']=='member') $ret["Ranks"]["Member"][] = $row['name'];
			else if ($row['rank']=='banned') $ret["Ranks"]["Banned"][] = $row['name'];
			else if ($row['rank']=='crashed') $ret["Ranks"]["Crashed"][] = $row['name'];
			else if ($row['rank']=='muted') $ret["Ranks"]["Muted"][] = $row['name'];
			else die("ERROR");
		}
		return $ret;
	}
?>