#!/bin/bash

while true; do
	date >> exabgp.log
	echo "starting" >> exabgp.log
	/usr/src/exabgp/sbin/exabgp /etc/exabgp/exabgp.conf
	echo "exitd!" >> exabgp.log
	echo  >> exabgp.log
	sleep 10;
done
