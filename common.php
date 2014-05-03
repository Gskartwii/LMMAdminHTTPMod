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
?>