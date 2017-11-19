<?php

include "top.php";

?>

本系统使用ExaBGP程序，把DDoS攻击的IP信息通过BGP协议发送给上游路由器引流，<br>
将DDoS流量引流到一台Linux服务器，流量经过Linux服务器过滤后再送给下游路由器，<br>
从而完成DDoS流量清洗工作。<p>

部分资料请见<a href=https://github.com/bg6cq/ITTS/blob/master/security/bgp/exabgp/README.md target=_blank>https://github.com/bg6cq/ITTS/blob/master/security/bgp/exabgp/README.md</a><p>

下图是工作原理示意:<p>
<img src=DDoS.png>
