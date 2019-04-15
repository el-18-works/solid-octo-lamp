#!/bin/bash

die() {
	exit 1
}

MYPHP=$MYSHARE/php

grepn() {
	find $inifile || die
	ln=`egrep -m 1 -n "include_path[[:space:]]*=" $inifile `
	l=`echo $ln | sed -e "s/:.*//"`
	echo $ln | grep $MYPHP && return
	echo $inifile:$l:
	sudo vi $inifile +$l
}

inifile=`php --ini | grep Loaded | sed -e "s|[^/]*||"`
grepn

if [ x`echo $inifile | grep cli` != x ]; then
	inifile=`echo $inifile | sed -e "s/cli/cgi/"`
	grepn
fi

echo "FORCE-RELOAD LIGHTTPD"
sudo service lighttpd force-reload

