<?php

$db_host = "localhost";
$db_user = "root";
$db_passwd = "";
$db_dbname = "blackip";

$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if(mysqli_connect_error()){
        echo mysqli_connect_error();
}

// 开始运行，先把处于added状态的路由发送出去
$q="select id,prefix,len,next_hop,other from blackip where status='added'";
$stmt=$mysqli->prepare($q);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id,$prefix,$len,$next_hop,$other);
while($stmt->fetch()){
	echo "announce route $prefix/$len next-hop $next_hop $other\n";
}
$stmt->close();

while(1) {
	// 更新最后运行时间
	$q="update lastrun set tm=now()";
	$stmt=$mysqli->prepare($q);
	$stmt->execute();
	$stmt->close();
	
	// 更新到时间的路由
	$q="update blackip set status='deleting' where status='added' and end<now()";
	$stmt=$mysqli->prepare($q);
	$stmt->execute();
	$stmt->close();

	// 找出处于deleting(待删除)状态的路由
	$q="select id,prefix,len from blackip where status='deleting'";
	$stmt=$mysqli->prepare($q);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id,$prefix,$len);
	while($stmt->fetch()){
		// 查找是否有同样的前缀，并处于added状态的路由
                $q="select count(*) from blackip where status='added' and id<>? and prefix=? and len=?";
		$stmt2=$mysqli->prepare($q);
		$stmt2->bind_param("sss",$id,$prefix,$len);
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($cnt);
		$stmt2->fetch();
		$stmt2->close();
		if($cnt==0) {  // 如果没有，说明路由需要撤回，发送撤回路由
			echo "withdraw route $prefix/$len\n";
		}
		// 设置为已经删除状态
                $q="update blackip set status='deleted' where id=?";
		$stmt2=$mysqli->prepare($q);
		$stmt2->bind_param("s",$id);
		$stmt2->execute();
		$stmt2->close();
	}
	$stmt->close();

	// 处理新增路由
	$q="select id,prefix,len,next_hop,other from blackip where status='adding'";
	$stmt=$mysqli->prepare($q);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id,$prefix,$len,$next_hop,$other);
	while($stmt->fetch()){
		echo "announce route $prefix/$len next-hop $next_hop $other\n";
                $q="update blackip set status='added' where id=?";
		$stmt2=$mysqli->prepare($q);
		$stmt2->bind_param("s",$id);
		$stmt2->execute();
		$stmt2->close();
        }
	$stmt->close();
	
	sleep(1);
}
?>
