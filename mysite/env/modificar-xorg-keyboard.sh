#!/bin/sh

setxkbmap -print -verbose 10

echo
echo "configuración desde :"
gsettings get org.gnome.desktop.input-sources xkb-options

gsettings set org.gnome.desktop.input-sources xkb-options "['compose:caps']"
echo
echo "configuración hasta :"
gsettings get org.gnome.desktop.input-sources xkb-options

localectl set-x11-keymap jp asus_laptop "" compose:caps
echo "localectl status :"
localectl status

grep "ECORE_IMF_MODULE" ~/.xinitrc || (
	echo "export ECORE_IMF_MODULE=fcitx" >> ~/.xinitrc
	echo "exort XMODIFIERS=@im=fcitx" >> ~/.xinitrc
)

setxkbmap -print -verbose 10

