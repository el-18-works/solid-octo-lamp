#!/usr/bin/python
from bluetooth import bluez
from os.path import expanduser, basename
from sys import stdout, stderr
from subprocess import call, Popen
from sys import argv, stdin, stdout
from json import loads, dumps

rc =loads(open('blue.json').read())
my_addr =rc['myair_addr']
my_port =rc['my_port']
cmd =rc['commands']

def chromium_open(fn) :
    sp =Popen(['/usr/bin/xclip', '-selection', 'clipboard'], stdin=open(fn))
    sp.communicate()
    cmd =['xdotool', 'mousemove', '100', '10', 'click', '1', 'key']
    cmd +=['ctrl+t', 'ctrl+l', 'ctrl+v', 'Return']
    call(cmd)

def myserver() :
    #my_addr ="48:D7:05:DF:C6:4C"
    #my_port =1

    server_sock =bluez.BluetoothSocket( bluez.RFCOMM )

    server_sock.bind(("", my_port))
    server_sock.listen(1)
    while 1 :
        try :
            client_sock, address =server_sock.accept()
        except KeyboardInterrupt as e :
            break
        print "accepted connection from ", address
        c =int(client_sock.recv(12))
        size =[int(client_sock.recv(1024)) for i in range(2)]
        print "received size size %s" % size
        fn =""
        pos =0
        while pos < size[0] :
            buf =client_sock.recv(size[0])
            pos +=len(buf);
            fn +=buf
        fn =expanduser("~/junk/%s"%basename(fn))
        pos =0
        fd =open(fn, "w")
        print "[%s](%d)" % (fn, size[1])
        while pos < size[1] :
            client_sock.send(str(pos));
            fcnt =client_sock.recv(size[1])
            pos +=len(fcnt);
            stderr.write( "received size (%d/%d)\r" % (pos,size[1]))
            fd.write(fcnt)
        print ""
        fd.close()

        client_sock.send("ok");
        client_sock.close()
        if c == cmd['chromiumurl'] :
            chromium_open(fn) 

    server_sock.close()


myserver()

