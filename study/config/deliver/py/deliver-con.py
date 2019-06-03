#!/usr/bin/env python3
# deliver-con.py binout

from os.path import dirname
from sys import argv
from my.gengetopt import gengetopt

skelfile =dirname(__file__)+'/deliver-skel.py'
print(skelfile)

out =open(argv[1], 'w')

opt =[
	('-c,--cd,--change-dir', '+c'),
	('-n,--dry-run', 'n'),
	('-f,--force', 'f'),
	('-h,--help', 'h'),
]
from sys import stdout
gengetopt(out, opt)

out.write(open(skelfile).read())

