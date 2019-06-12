#!/usr/bin/env python3

from py.bloc import *

def zuo_vim(opt) :
  pass

def zuo_py(opt) :
  from sys import stdout
  def fpy(fnom) :
    if len(opt['args']) == 0 :
      unitpy().writeradix(stdout, fnom)
    else :
      for a in opt['args'] :
        unitpy().writeitem(stdout, fnom, a)
  for a in opt['f'] if len(opt['f']) else ['Zuo'] :
    fmk(a)

def zuo_main(opt) :
  from sys import stdout
  def fmk(fnom) :
    if len(opt['args']) == 0 :
      unitmk().writeradix(stdout, fnom)
    else :
      for a in opt['args'] :
        unitmk().writeitem(stdout, fnom, a)
  for a in opt['f'] if len(opt['f']) else ['Zuo'] :
    fmk(a)

if __name__ == "__main__" :
  from py.comset import gencomopt
  COMS ={"--":"main", "python":"py", "vim":"vim"}
  OPTARGS =[
  ("-f,--file,--makefile", "+f", "Zuo"),
  ("-n,--just-print,--dry-run,--recon", "n"),
  ("-s,--silent,--quiet", "s"),
  ("-j,--jobs=n", "*j"),
  ("-t,--touch", "t"),
  ]
  gencomopt(COMS, OPTARGS, "zuo")

