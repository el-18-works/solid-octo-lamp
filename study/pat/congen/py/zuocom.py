#!/usr/bin/env python3

from py.bloc import *

def zuo_main(arg) :
  from sys import stdout, argv
  from os.path import basename
  if len(argv) not in (2,3) or not argv[1] :
    print("zuo infile [itemglob]")
    exit(1)
  unit =argv[1]
  if len(argv) == 2 :
    if basename(unit)[0].isupper() :
      unitmk().writeradix(stdout, argv[1])
    else :
      unitpy().writeradix(stdout, argv[1])
  elif len(argv) == 3 :
    if basename(unit)[0].isupper() :
      unitmk().writeitem(stdout, argv[2], argv[1])
    else :
      unitpy().writeitem(stdout, argv[2], argv[1])

if __name__ == "__main__" :
  from py.comset import gencomopt
  from sys import stdout, argv
  COMS ={"--":"main", "make":"make", "zuo":"zuo"}
  OPTARGS =[
  ("-f,--file,--makefile", "+f", "makefile"),
  ("-n,--just-print,--dry-run,--recon", "n"),
  ("-s,--silent,--quiet", "s"),
  ("-j,--jobs=n", "*j"),
  ("-t,--touch", "t"),
  ]
  gencomopt(COMS, OPTARGS, "zuo")

