#!/usr/local/bin/python3

import subprocess
import sys
cmds =["ffprobe", "-show_format", "-show_streams"] + sys.argv[1:]
p = subprocess.Popen(cmds, stderr=subprocess.PIPE, stdin=subprocess.PIPE, stdout=subprocess.PIPE)
out, err=p.communicate()
print(out)
print(str(err, sys.getdefaultencoding()))


