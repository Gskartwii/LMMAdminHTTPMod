<?php
    if (!isset($_GET["name"]) || !isset($_GET["rank"])) {
        die("Could not add admin to database: username or rank not defined!");
    }
    else {
        require("mysqlconn.php");
        require("common.php");
        $pid=mysql_real_escape_string($_GET['pid']);
        $name=mysql_real_escape_string($_GET['name']);
        $rank=mysql_real_escape_string($_GET['rank']);
        $auth=mysql_real_escape_string($_GET['auth']);
        if ($pid==0) die("Warning: cannot add modify rank table for placeid 0! Ignoring...");
        if (!checkAuthCode($auth,$pid)) die("Insufficient permissions!");
        //mysql_query("CREATE TABLE IF NOT EXISTS roblox_adminlist_$pid (id int(11) NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, rank VARCHAR(255) NOT NULL, PRIMARY KEY(id), KEY id (id))");
        $r=mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE name='$name' AND rank='$rank'");
        if (!$r) die("\nDatabase error: ".mysql_error());
        else {
            if (!mysql_affected_rows()) {
                $r=mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE name='$name'");
                if (!$r) die("\nDatabase error: ".mysql_error());
                if (mysql_affected_rows() and $rank=='neutral')
                    $r = mysql_query("DELETE FROM roblox_adminlist_$pid WHERE name='$name'");
                else if (mysql_affected_rows())
                    $r=mysql_query("UPDATE roblox_adminlist_$pid SET rank='$rank' WHERE name='$name'");
                else
                    $r=mysql_query("INSERT INTO roblox_adminlist_$pid SET rank='$rank', name='$name'");
                if (!$r) die("\nDatabase error: ".mysql_error());
            }
        }
    }
?>