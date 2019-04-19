#!/usr/bin/python3
import sys

data =open(sys.argv[1]).readlines()
a,m,d =input("年 月 日>>").split(" ")
x,h =data[0].split(":")
txt ="".join(data[1:])

#XXX escape.
stmt= ("INSERT INTO luckxa_mydiary_txt (a,m,d, h,txt) VALUES(%s,%d,%d, '%s', '%s')"%(a,int(m),int(d),h.strip(),txt.strip()))
open(sys.argv[2], "w").write(stmt)

