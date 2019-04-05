#!/usr/bin/python2
from bluetooth import bluez

my_addr ="48:D7:05:DF:C6:4C"
my_port =1

sock =bluez.BluetoothSocket( bluez.RFCOMM )
sock.connect((my_addr, my_port))

sock.send("hello!")
sock.close()
