#!/usr/bin/env python3

from py.bloc import *

def main() :
  from sys import stdout, argv
  from os.path import basename
  if len(argv) not in (2,3) or not argv[1] :
    print("bloc infile [itemglob]")
    exit(1)
  unit =argv[1]
  if len(argv) == 2 :
    if basename(unit)[0].isupper() :
      mkunit().writeradix(stdout, argv[1])
    else :
      pyunit().writeradix(stdout, argv[1])
  elif len(argv) == 3 :
    if basename(unit)[0].isupper() :
      mkunit().writeitem(stdout, argv[1], argv[2])
    else :
      pyunit().writeitem(stdout, argv[1], argv[2])

if __name__ == "__main__" :
  main()

