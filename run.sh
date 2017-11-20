#!/bin/bash

while true; do
env exabgp.daemon.user=root exabgp.daemon.daemonize=false exabgp.daemon.pid=/var/run/exabgp.pid \
	exabgp.log.destination=/var/log/exabgp.log /usr/src/exabgp/sbin/exabgp \
	/usr/src/blackhole/blackip-exabgp/exabgp.conf

sleep 10
done
