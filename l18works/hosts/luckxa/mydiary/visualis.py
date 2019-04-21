#!/usr/bin/python3

from base64 import b64encode
from os.path import *

from sys import argv, stdin, stdout, stderr
from subprocess import call, Popen
from os import pipe, read, write, fdopen, close

def copy_clipboard(s) :
	cmd =['/usr/bin/xclip', '-selection', 'clipboard']
	rfd, wfd =pipe()
	sp =Popen(cmd, stdin=fdopen(rfd, "r"), stderr=stderr)
	write(wfd, s.encode()); close(wfd)
	sp.communicate()

s =open(expanduser("~/junk/mai-kimono.jpg"), 'br').read()
#s =open(expanduser("~/junk/kiss-my-melody.png"), 'br').read()
prefix ="data:image/jpg;base64,"
#print(s)
print("<img src='"+prefix+b64encode(s).decode()+"'>")
copy_clipboard(prefix+b64encode(s).decode())
open(expanduser("~/junk/mymelody.html"), "w").write("<img src='"+prefix+b64encode(s).decode()+"'>")

