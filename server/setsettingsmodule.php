<?php
	require("common.php");
	require("mysqlconn.php");
	$in 		= gzdecode($HTTP_RAW_POST_DATA);
	$in 		= json_decode($in);
	$auth 		= $in->{'AuthCode'};
	$ranks 		= $in->{'Ranks'};
	$owners 	= $ranks->{'Owner'};
	$admins 	= $ranks->{'Admin'};
	$members	= $ranks->{'Member'};
	$banned 	= $ranks->{'Banned'};
	$crashed 	= $ranks->{'Crashed'};
	$muted 		= $ranks->{'Muted'};
	$fun 		= safe($in->{'FUN'});
	$lagtime 	= (int) $in->{'LagTime'};
	$prefix 	= safe($in->{'Prefix'});
	$bet 		= safe($in->{'Bet'});
	$adminmenu 	= safe($in->{'EnableAdminMenu'});
	$filter 	= safe(implode(",", $in->{'Filter'}));
	$slock		= safe($in->{'ServerLocked'});
	$dabuse 	= safe($in->{'DisableAbuse'});
	$mid		= (int) $in->{'VIPMemberID'};
	$aid		= (int) $in->{'VIPAdminID'};
	$gid		= (int) $in->{'GroupID'};
	$gmr		= (int) $in->{'GroupMemberRank'};
	$gar		= (int) $in->{'GroupAdminRank'};
	$gor 		= (int) $in->{'GroupOwnerRank'};
	$gban 		= (int) $in->{'RankBan'};
	$bid		= (int) $in->{'BadgeID'};
	$pid		= (int) $_GET['pid'];
		echo $auth . " / " . $pid;
	if (!checkauthcode($auth,$pid)) die("Insufficient permissions!");
	global $pid2;
	$pid2 = $pid;
	function iterate_adminlist($list, $rank) {
		for (
			$i = 0;
			$i < sizeof($list);
			$i++)
			addadmin($GLOBALS["pid2"],$list[$i],$rank);
	}
	iterate_adminlist($owners, "owner");
	iterate_adminlist($admins, "admin");
	iterate_adminlist($members, "member");
	iterate_adminlist($banned, "banned");
	iterate_adminlist($crashed, "crashed");
	iterate_adminlist($muted, "muted");
	setsettings($pid,$fun,$lagtime,$prefix,$bet,$adminmenu,$filter,$slock,$dabuse,$mid,$aid,$gid,$gmr,$gar,$gor,$gban,$bid);
	echo "Succesful!";
?>