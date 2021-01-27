## 简单的ExaBGP WEB客户端

需要发送的路由数据存放在mysql数据库中。

新增加的路由，status 为 adding，需要删除的路由，将status改为 deleting。

blackip-exbgp.php 程序轮询数据库，如果有需要删除的，发送withdraw命令；如果有新增的，发送announce route命令。

web目录为简单的管理界面，其中login.php是登录程序，简单修改即可使用。

centos6中process中做php backlist-exabgp.php汇报 EEROR socket错误。

需执行：


 nohup php -f /usr/src/blackip-exabgp/blackip-exabgp.php > /var/run/exabgp.cmd 2>&1  &

参考资料：

[使用ExaBGP发送BGP路由信息和清洗DDoS流量](https://github.com/bg6cq/ITTS/blob/master/security/bgp/exabgp/README.md)
