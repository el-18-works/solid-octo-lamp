#!/usr/bin/python
from bluetooth import bluez
from os.path import expanduser, basename
from sys import stdout, stderr

my_addr ="48:D7:05:DF:C6:4C"
my_port =1

server_sock =bluez.BluetoothSocket( bluez.RFCOMM )

server_sock.bind(("", my_port))
server_sock.listen(1)
client_sock, address =server_sock.accept()
print "accepted connection from ", address

#client_sock.send("hello my air!");
#data =int(client_sock.recv(1024)),int(client_sock.recv(1024))
data =[int(client_sock.recv(1024)) for i in range(2)]
print "received data size %s" % data
fn =""
pos =0
while pos < data[0] :
    buf =client_sock.recv(data[0])
    pos +=len(buf);
    fn +=buf
fn =basename(fn)
pos =0
fd =open(expanduser("~/junk/%s"%fn), "w")
print "[%s](%d)" % (fn, data[1])
while pos < data[1] :
    client_sock.send(str(pos));
    fcnt =client_sock.recv(data[1])
    pos +=len(fcnt);
    stderr.write( "received data (%d/%d)\r" % (pos,data[1]))
    fd.write(fcnt)
print ""
fd.close()

client_sock.send("ok");

server_sock.close()
client_sock.close()

