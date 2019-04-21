#!/usr/bin/python
from bluetooth import bluez
from os.path import expanduser, basename
from os import getenv, getpid
from sys import stdout, stderr
from subprocess import call, Popen
from sys import argv, stdin, stdout, stderr
from json import loads, dumps

rc =loads(open('/usr/local/share/l18/blue.json').read())
my_addr =rc['myair_addr']
my_port =rc['my_port']
cmd =rc['commands']

pout ="/tmp/myserver-p-out%d"%getpid()

def chromium_open(fn) :
    sp =Popen(['/usr/bin/xclip', '-selection', 'clipboard'], stdin=open(fn))
    sp.communicate()
    cmd =['xdotool', 'mousemove', '100', '10', 'click', '1', 'key']
    cmd +=['ctrl+t', 'ctrl+l', 'ctrl+v', 'Return', 'Return']
    call(cmd)
    cmd =['xdotool', 'key', 'Return']
    call(cmd)

def copy_clipboard(fn) :
	sp =Popen(['/usr/bin/xclip', '-selection', 'clipboard'], stdin=open(fn))
	sp.communicate()
	cmd =['xdotool', 'getactivewindow', 'getwindowname']
	#cmd =['xdotool', 'getwindowname',  getenv("WINDOWID")]
	out =open(pout, 'w')
	print (" ".join(cmd).strip())
	sp =Popen(cmd, stdout=out, stderr=stderr)
	sp.communicate()
	out.flush(); out =open(pout, 'r')
	pdata =out.read()
	print (pdata.strip())
	a =pdata.strip().split(" ")
	if "Yakuake" in a :
		print "paste in terminal"
		if "vi" in a :
			cmd =['xdotool', 'key', 'Escape', 'i', 'ctrl+shift+v']
		else :
			cmd =['xdotool', 'key', 'ctrl+shift+v']
	else :
		cmd =['xdotool', 'key', 'ctrl+v']
	call(cmd)

def pipe_shell(fn, sock) :
    out =open(pout, 'w')
    print "sh "+fn
    sp =Popen(['/bin/sh', fn], stdout=out, stderr=stderr)
    sp.communicate()
    out.flush(); out =open(pout, 'r')
    pdata =out.read()
    size =len(pdata)
    pos =0
    while pos < size :
        stderr.write( "sending (%d/%d)\r" % (pos,size))
        sock.send(pdata[pos:pos+1000]);
        pos +=int(sock.recv(1024))
    stderr.write( "sending (%d/%d)\r\n" % (pos,size))

def myserver() :
    #my_addr ="48:D7:05:DF:C6:4C"

    server_sock =bluez.BluetoothSocket( bluez.RFCOMM )

    server_sock.bind(("", my_port))
    server_sock.listen(1)
    while 1 :
        try :
            client_sock, address =server_sock.accept()
        except KeyboardInterrupt as e :
            break
        try :
			print "accepted connection from ", address
			cmd_recv =int(client_sock.recv(1))
			size =[int(client_sock.recv(1024)) for i in range(2)]
			print "received size size %s" % size
        except ValueError as e :
			client_sock.send("no!");
			client_sock.close()
			continue
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

        if cmd_recv == cmd['chromiumurl'] :
            chromium_open(fn) 
        elif cmd_recv == cmd['clipboard'] :
            copy_clipboard(fn) 
        elif cmd_recv == cmd['shell'] :
            client_sock.send("re:");
            pipe_shell(fn, client_sock) 

        client_sock.send("ok.");
        client_sock.close()

    server_sock.close()


if __name__ == '__main__' and len(argv) > 1 :
	port =argv[1]
	my_port =int(port)
	print("port:%d"%my_port)
myserver()

