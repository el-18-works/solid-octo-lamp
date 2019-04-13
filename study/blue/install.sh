#!/bin/sh

sudo mkdir -p /usr/local/my
sudo chown luckxa /usr/local/my
mkdir -p /usr/local/my/share
mkdir -p /usr/local/my/bin
ln -sf $PWD/blue.json /usr/local/my/share/blue.json
ln -sf $PWD/blue-myair.js /usr/local/my/bin/blue-myair
ln -sf $PWD/blue-settings.js /usr/local/my/bin/blue-settings.js

