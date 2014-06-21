<?php
	require("common.php");
	$pid = safe($_GET['pid']);
	$ret = [];
	$ret = array_merge($ret,getadmins($pid),getsettings($pid));
	echo json_encode($ret);
?>