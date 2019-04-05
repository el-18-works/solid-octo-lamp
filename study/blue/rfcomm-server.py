#!/usr/bin/python2
from bluetooth import bluez

my_addr ="48:D7:05:DF:C6:4C"
my_port =1

server_sock =bluez.BluetoothSocket( bluez.RFCOMM )

server_sock.bind(("", my_port))
server_sock.listen(1)
client_sock, address =server_sock.accept()
print "accepted connection from ", address

data =client_sock.recv(1024)
print "received [%s]" % data

server_sock.close()
client_sock.close()
