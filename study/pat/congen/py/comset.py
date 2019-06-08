#!/usr/bin/env python3

from py.bloc import unitpy

class unit :
  def __init__(ipse) :
    ipse.__a =[]

  def __call__(ipse, *arg) :
    for a in arg :
      ipse.__a.append(a)

  def radix(ipse, out) :
    unitpy().writeradix(out, "comopt", ipse.__a)

class comoptaux :
  def error(ipse, msg) :
    print("comopt :" + msg)
    exit(1)

  def __init__(ipse, debug=False) :
    ipse.debug =debug
    ipse.autoinc =0
    ipse.trans ={0:{}}

  def prim(ipse) :
    ipse.autoinc +=1
    return ipse.autoinc

  def __call__(ipse, cmd, cmdfun) :

    st =0
    for c in cmd :
      if c not in  ipse.trans[st] :
        ipse.trans[st][c] =ipse.prim()
        st =ipse.trans[st][c]
        ipse.trans[st] ={}
      else :
        st =ipse.trans[st][c]
    if "=" in ipse.trans[st] :
      ipse.error("duplicatus mandatus '%s'"%cmd)
    ipse.trans[st]["="] =cmdfun
  
  def __iter__(ipse) :
    preactst =-2
    actst =-1
    yield "st =0"
    yield "while st != 0 or lex.la != '$' :"
    if ipse.debug :
      yield " print(\"%d '%s'\"%(st,lex.la))"
    yield " if st == %d :"%preactst
    yield "  if setoptf[0] == 'arg' :"
    yield "   if lex.la == '=' :"
    yield "    l =lex.getl()"
    yield "    opt[setoptf[1]].append(l[1:])"
    yield "   else :"
    yield "    opt['flgs'] +=setoptf[1]"
    if ipse.debug :
      yield "  else :"
      yield "   error('internalis error')"
    yield "  st =0"
    yield " elif st == %d :"%actst
    yield "  if setoptf[0] == 'arg' :"
    yield "   l =lex.getl()"
    yield "   opt[setoptf[1]].append(l[1:] if len(l) and l[0] == '=' else l)"
    yield "  elif setoptf[0] == 'flg' :"
    yield "   opt['flgs'] +=setoptf[1]"
    yield "   opt['flgs'] +=lex.getl(noinc=1)"
    if ipse.debug :
      yield "  else :"
      yield "   error('internalis error')"
    yield "  st =0"
    for st in sorted(ipse.trans) :
      yield " elif st == %d :"%st
      if '=' in ipse.trans[st] :
        if len(ipse.trans[st]) != 1 :
          ipse.error("indeterminismus")
        opt =ipse.trans[st]["="]
        if opt[0] == '*' :
          yield "  st =%d"%preactst
          yield "  setoptf =('arg', '%s')"%opt[1:]
        elif opt[0] == '+' :
          yield "  st =%d"%actst
          yield "  setoptf =('arg', '%s')"%opt[1:]
        else :
          yield "  st =%d"%actst
          yield "  setoptf =('flg', '%s')"%opt
        continue
      for i,c in enumerate(ipse.trans[st]) :
        s ="  "
        if i != 0 : s +="el"
        yield s + "if lex.la == '%c' :"%c
        yield "   st =%d"%ipse.trans[st][c]
        yield "   lex.getc()"
      yield "  else :"
      if st == 0 :
        yield "   setoptf =('arg', 'args')"
        yield "   st =%d"%actst
      else :
        yield "   error('non exspectato uso aborto')"
        yield "   return"

class comopt :

  def __init__(ipse, optargs, debug=False) :
    ipse.optargs =optargs
    ipse.debug =debug

  def __call__(ipse) :
    u =unit()

    u("class comopt :")

    u(" def __init__(ipse) :")
    u("  import sys")
    u("  ipse.argv =sys.argv[1:]")
    u("  if len(ipse.argv) == 0 :")
    u("   ipse.la ='$'")
    u("  elif len(ipse.argv[0]) == 0 :")
    u("   ipse.la =''")
    u("  else :")
    u("   ipse.la =ipse.argv[0][0]")
    u("  ipse.argi =0")
    u("  ipse.argj =0")

    u(" def getc(ipse) :")
    u("  c =ipse.la")
    u("  if len(ipse.argv[ipse.argi]) == ipse.argj+1 :")
    u("   ipse.argi +=1; ipse.argj =0")
    u("   if len(ipse.argv) == ipse.argi :")
    u("    ipse.la ='$'")
    u("   else :")
    u("    ipse.la ='' if len(ipse.argv[ipse.argi]) == 0 else ipse.argv[ipse.argi][0]")
    u("  else :")
    u("   ipse.argj +=1")
    u("   ipse.la =ipse.argv[ipse.argi][ipse.argj]")
    u("  return c")

    u(" def getl(ipse, noinc=0) :")
    u("  if noinc == 1 and ipse.argj == 0 :")
    u("   return ''")
    u("  if ipse.la == '$' :")
    u("   return '$'")
    u("  else :")
    u("   l =ipse.argv[ipse.argi][ipse.argj:]")
    u("  ipse.argi +=1; ipse.argj =0")
    u("  if len(ipse.argv) == ipse.argi :")
    u("   ipse.la ='$'")
    u("  else :")
    u("   ipse.la ='' if len(ipse.argv[ipse.argi]) == 0 else ipse.argv[ipse.argi][0]")
    u("  return l")

    u(" def error(ipse, msg='error') :")
    u("  print('comopt : %s'%msg)")

    aux =comoptaux(ipse.debug)
    ls =[]
    lutendi =[]
    for cmd,f in ipse.optargs :
      arg ="a"
      if "=" in cmd :
        cmd,arg =cmd.split("=")
      utendi ="  "
      if f[0] == "+" :
        utendi +=", ".join(c+"="+arg for c in cmd.split(","))
      elif f[0] == "*" :
        utendi +=", ".join(c+"[="+arg+"]" for c in cmd.split(","))
      else :
        utendi +=", ".join(cmd.split(","))
      lutendi.append(utendi)

      for c in cmd.split(",") :
        aux(c, f)
      if f[0] in ("*", "+") :
        ls.append(cmd[1])

    opt = "{'flgs':'','args':[]," + ",".join("'%c':[]"%c for c in ls) + "}"
    u(" def __call__(ipse, opt=%s) :"%opt)
    u("  lex =ipse")
    u("  error =ipse.error")

    for i in aux :
      u ("  "+i)

    u("  return opt")

    u("def optutendi(nomen='') :")
    u(" print('Modus utendi %s :'%nomen)")
    for i in lutendi :
      u(" print('%s')"%i)

    u("def comset() :")
    u(" co =comopt()")
    u(" return co()")

    return u

def gengetopt(output, optargs, debug=1) :
  libout =open(output, "w") if type(output) == str else output
  if libout.seekable() and libout.tell() == 0 :
    libout.write("#!/usr/bin/env python3")
  co =comopt(optargs=optargs, debug=debug)
  co().radix(libout)

def main() :
  from sys import stdout, argv
  OPTARGS =[
    ("-f,--file,--makefile", "+f"),
    ("-n,--just-print,--dry-run,--recon", "n"),
    ("-s,--silent,--quiet", "s"),
    ("-j,--jobs", "*j"),
    ("-t,--touch", "t"),
  ]
  gengetopt(stdout, OPTARGS)

main()

