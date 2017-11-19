<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"" />
<title>ExaBGP WEB</title>
</head>

<body bgcolor=#dddddd>
<a href=/ target=_blank>流量</a> 
<a href=index.php>生效的路由</a> 
<a href=exp.php>撤回的路由</a> 
<a href=intro.php>介绍</a> 

<?php

$db_host = "localhost";
$db_user = "root";
$db_passwd = "";
$db_dbname = "blackip";

$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if(mysqli_connect_error()){
	echo mysqli_connect_error();
}
session_start();

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

if ( isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) {
	echo "<a href=logout.php>logout</a> ";
}  
?>

技术支持: james@ustc.edu.cn  

<?php 

echo "您的IP地址:";

if (!empty($_SERVER['HTTP_X_REAL_IP'])) echo $_SERVER['HTTP_X_REAL_IP'];
else 
echo  $_SERVER["REMOTE_ADDR"];

echo " ";
$q="select TIMESTAMPDIFF(second, now(), tm) from lastrun";
$result = $mysqli->query($q);
$r=$result->fetch_array();
if($r[0]<=2)
        echo " <font color=green>ExaBGP运行正常</font>";
else echo " <font color=red>ExaBGP未运行</font>";

?>
<hr>

