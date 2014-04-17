<?php
    if (!isset($_GET["name"]) || !isset($_GET["rank"])) {
        die("Could not add admin to database: username or rank not defined!");
    }
    else {
        // mysql_connect() here, not shown :P
        //mysql_select_db("3591_other");
        require("mysqlconn.php");
        $pid=$_GET['pid'];
        if ($pid==-1) die("Warning: cannot add modify rank table for placeid -1! Ignoring...");
        mysql_query("CREATE TABLE IF NOT EXISTS roblox_adminlist_$pid (id int(11) NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, rank VARCHAR(255) NOT NULL, PRIMARY KEY(id), KEY id (id))");
        $r=mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE name='{$_GET['name']}' AND rank='{$_GET['rank']}'");
        if (!$r) die("\&#331;Database error: ".mysql_error());
        else {
            if (!mysql_affected_rows()) {
                $r=mysql_query("SELECT * FROM roblox_adminlist_$pid WHERE name='{$_GET['name']}'");
                if (!$r) die("\nDatabase error: ".mysql_error());
                if (mysql_affected_rows())
                    $r=mysql_query("UPDATE roblox_adminlist_$pid SET rank='{$_GET['rank']}' WHERE name='{$_GET['name']}'");
                else
                    $r=mysql_query("INSERT INTO roblox_adminlist_$pid SET rank='{$_GET['rank']}', name='{$_GET['name']}'");
                if (!$r) die("\nDatabase error: ".mysql_error());
            }
        }
    }
?>