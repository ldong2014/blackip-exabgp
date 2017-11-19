<?php

include "top.php";

?>

本系统通过mysql数据库控制ExBGP发送受DDoS攻击的IP路由信息给上游路由器引流，<br>
将DDoS流量引流到一台Linux服务器，流量经过Linux服务器过滤后再送给下游路由器，从而完成DDoS清洗工作。<p>

部分资料请见<a href=https://github.com/bg6cq/ITTS/blob/master/security/bgp/exabgp/README.md target=_blank>https://github.com/bg6cq/ITTS/blob/master/security/bgp/exabgp/README.md</a>

<img src=DDoS.png>
