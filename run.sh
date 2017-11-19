#!/bin/bash

env exabgp.daemon.user=root exabgp.daemon.daemonize=true exabgp.daemon.pid=/var/run/exabgp.pid \
	exabgp.log.destination=/var/log/exabgp.log /usr/src/exabgp/sbin/exabgp /etc/exabgp.conf

