<?php

include "top.php";

$routerip="210.45.230.89";

$limit=" limit 10 ";

if( isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1))  {
	if(isMobile())
		$limit=" limit 100 ";
	else $limit=" limit 2000 ";
        if(isset($_REQUEST["del"]))  {   //del ip
                $id= intval($_REQUEST["id"]);
        	$q="update blackip set end=now(),status='deleting' where id=?";
                $stmt=$mysqli->prepare($q);
                $stmt->bind_param("i",$id);
                $stmt->execute();
		$stmt->close();
        }
        if(isset($_REQUEST["add_do"])) {  //add new
                $prefix = $_REQUEST["prefix"];
                $len = $_REQUEST["len"];
                $next_hop = $_REQUEST["next_hop"];
                $other= $_REQUEST["other"];
                $day = intval($_REQUEST["day"]);
                $msg = $_REQUEST["msg"];
if(0) {
		$q="select count(*) from mylist where inet_aton(ip) = (inet_aton(?) & inet_aton(mask))";
		$stmt=$mysqli->prepare($q);
                $stmt->bind_param("s",$prefix);
                $stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();
}
		$count=1;
		if($count==0)  
			echo "Error: IP ".$prefix." not in mylist, check your input<p>";
		else {
                        $q = "insert into blackip (status,prefix,len,next_hop,other,start,end,msg) values ('adding',?,?,?,?,now(),date_add(now(),interval ? day),?)";
                        $stmt=$mysqli->prepare($q);
                       	$stmt->bind_param("sissis",$prefix,$len,$next_hop,$other,$day,$msg);
                       	$stmt->execute();
			sleep(2);
                }
        }
        if(isset($_REQUEST["modi"])) {  //
		$id=$_REQUEST["id"];
		@$s=$_REQUEST["s"];
		$q="select prefix,len, end, msg from blackip where id=?";
                $stmt=$mysqli->prepare($q);
                $stmt->bind_param("s",$id);
                $stmt->execute();
		$stmt->bind_result($prefix, $len, $end, $msg);
		$stmt->fetch();

		echo "<form action=index.php METHOD=get>";
		echo "<input name=modi_do type=hidden>";
		echo "<input name=id type=hidden value=\"$id\">";
		echo "<input name=s type=hidden value=\"$s\">";
		echo "<table><tr><td>IP: </td><td>$prefix</td><td>len: </td><td>$len</td></tr>";
		echo "<tr><td>过期时间: </td><td><input name=end value=\"$end\"></td><td>消息: </td><td><input name=msg size=100 value=\"$msg\"></td></tr>";
		echo "</table><input name=modi_do type=submit value='修改'>";
		echo "</form><p>";
		$stmt->close();
	}
        if(isset($_REQUEST["modi_do"])) {  //
                $id= $_REQUEST["id"];
                $end = $_REQUEST["end"];
                $msg = $_REQUEST["msg"];
		$q="update blackip set end=?, msg = ? where id =?";
		$stmt=$mysqli->prepare($q);
                $stmt->bind_param("sss", $end, $msg, $id);
                $stmt->execute();
		$stmt->close();
        }
}

echo "<table><tr><td>";
$q="select TIMESTAMPDIFF(second, now(), tm) from lastrun";
$result = $mysqli->query($q);
$r=$result->fetch_array();
if($r[0]<=2)
	echo "ExaBGP <font color=green>running</font>, ";
else echo "ExaBGP <font color=red>not running</font>, ";

$q="select count(*) from blackip where status='added'";
$result = $mysqli->query($q);
$r=$result->fetch_array();
echo "activing routes: ".$r[0]."</td>";
?>
</td>
<?php
if( isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1))  {
	if(isset($_REQUEST["add"])) { // add
		echo "</tr></table>";
?>

<form action=index.php>
增加路由: <br>
前缀:<input name=prefix><br>
preflen:<input name=len value=32 size=3><br>
next_hop:<input name=next_hop value="<?php echo $routerip;?>"></input><br>
生效天数: <input name=day value=10 size=2><br>
其他: <input name=other> 例子: local-preference 65000 community [100:100]<br>
消息: <input name=msg><br>
<input type=submit name=add_do value="添加">
</form>

<?php
	}
	else {
		echo "<td>";
		echo "<a href=index.php?add>add</a>";
		echo "</td>";
		echo "</tr></table>";
	}
} else
		echo "</tr></table>";

@$s=$_REQUEST["s"];
$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by inet_aton(prefix)".$limit;
if($s=="s")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by start desc".$limit;
else if($s=="e")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by end desc".$limit;
else if($s=="n")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by next_hop".$limit;
else if($s=="m")
	$q="select id,prefix,len,next_hop,other,start,end,msg from blackip where status='added' order by msg".$limit;
$result = $mysqli->query($q);
echo "<table border=1 cellspacing=0>";
echo "<tr><th>序号</th><th><a href=index.php>IP</a></th><th><a href=index.php?s=n>next_hop</a></th><th>other</th><th><a href=index.php?s=s>start</a></th><th><a href=index.php?s=e>end</a></th>";
echo "<th><a href=index.php?s=m>MSG</a></th>";
if( isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1))
        echo "<th>cmd</th>";
echo "</tr>\n";
$count=0;
while($r=$result->fetch_array()) {
	$count++;
	echo "<tr><td align=center>";
	echo $count;
	echo "</td><td>";
	echo "<a href=search.php?str=$r[1] target=_blank>$r[1]</a>/$r[2]";
	echo "</td><td>";
	echo $r[3];
	echo "</td><td>";
	echo $r[4];
	echo "</td><td>";
	echo $r[5];
	echo "</td><td>";
	echo $r[6];
	echo "</td><td>";
	echo $r[7];
	echo "</td>";
        if( isset($_SESSION["isadmin"]) && ($_SESSION["isadmin"]==1)) {
                echo "<td><a href=index.php?del&id=$r[0]&s=$s  onclick=\"return confirm('删除 $r[1]/$r[2] ?');\">删除</a>&nbsp;";
                echo "<a href=index.php?modi&id=$r[0]&s=$s>修改</a>";
		echo "</td>";
        };
	echo "</tr>\n";
}
echo "</table>";

?>


