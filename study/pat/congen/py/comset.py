#!/usr/bin/env python3

from py.bloc import unitpy

class unit :
  def __init__(ipse) :
    ipse.__a =[]

  def __call__(ipse, *arg) :
    for a in arg :
      ipse.__a.append(a)

  def radix(ipse, out) :
    unitpy().writeradix(out, "concomopt", ipse.__a)

class concomoptaux :
  def error(ipse, msg) :
    print("concomopt :" + msg)
    exit(1)

  def __init__(ipse, comset =[], debug=0) :
    ipse.debug =debug
    ipse.autoinc =0
    ipse.trans ={0:{}}
    ipse.comset =repr(list(comset))

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
    yield "curcom ='--'"
    yield "initopt =opt.copy()"
    yield "com ={}"
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
    yield "  if setoptf[0] == 'comarg' :"
    yield "   l =lex.getl()"
    yield "   if l in %s :"%ipse.comset
    yield "    if opt != initopt :"
    yield "     com[curcom] =opt"
    yield "    curcom =l"
    yield "    if curcom in com :"
    yield "     opt =com[curcom]"
    yield "    else :"
    yield "     opt =initopt.copy()"
    yield "   else :"
    yield "    opt[setoptf[1]].append(l[1:] if len(l) and l[0] == '=' else l)"
    yield "  elif setoptf[0] == 'arg' :"
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
        yield "   setoptf =('comarg', 'args')"
        yield "   st =%d"%actst
      else :
        yield "   error('non exspectato uso aborto')"
        yield "   return"
    yield "com[curcom] =opt"

class concomopt :

  def __init__(ipse, coms, optargs, debug=False) :
    ipse.coms =coms
    ipse.optargs =optargs
    ipse.debug =debug

  def unit(ipse) :
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

    aux =concomoptaux(ipse.coms, ipse.debug)

    ls =[]
    lutendi =[]
    for a in ipse.optargs :
      if len(a) == 2 :
        cmd,f =a
        if "=" in cmd :
          cmd,param =cmd.split("=")
        else :
          param ='a'
      elif len(a) == 3 :
        cmd,f,param =a
      else :
        ipse.error("imprevisum optarg : %s"%repr(a))
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

    u("  return com")

    #
    # optutendi
    #
    u("def optutendi(nomen='', optargs=%s, subcom=%s) :" % (repr(ipse.optargs), repr(list(ipse.coms.keys()))))
    u(' lutendi =[]')
    u(' for a in optargs :')
    u('   if len(a) == 2 :')
    u('     cmd,f =a')
    u('     if "=" in cmd :')
    u('       cmd,param =cmd.split("=")')
    u('     else :')
    u('       param ="a"')
    u('   elif len(a) == 3 :')
    u('     cmd,f,param =a')
    u('   else :')
    u('     raise Exception("imprevisum optarg : %s" % repr(a))')
    u('   utendi ="  "')
    u('   if f[0] == "+" :')
    u('     utendi +=", ".join(c+"="+param for c in cmd.split(","))')
    u('   elif f[0] == "*" :')
    u('     utendi +=", ".join(c+"[="+param+"]" for c in cmd.split(","))')
    u('   else :')
    u('     utendi +=", ".join(cmd.split(","))')
    u('   lutendi.append(utendi)')
    u(" print('Modus utendi %s :' % nomen)")
    for i in lutendi :
      u(" print('%s')"%i)
    u(" if subcom :")
    u("  print('  <%s> ' % ', '.join(subcom))")

    #
    # comset
    #
    u("def comset() :")
    u(" co =comopt()")
    u(" return co()")

    u("comset =comset()")
    for i,c in enumerate(list(ipse.coms.keys())) : 
      rc =repr(c)
      u("if %s in comset :"%(rc)) # ....
      #u("%sif %s in comset :"%('' if i == 0 else 'el', rc))
      if ipse.coms[c] != None :
        u(" "+ipse.coms[c]+"(comset["+rc+"])")
      else :
        u(" print(%s + ' :')"%(rc))
        u(" for k in comset[%s] :"%(rc))
        u("  print('  ' + k + ' : ' + repr(comset["+rc+"][k]))")

    return u

  def radix(ipse, out) :
    ipse.unit().radix(out)

def gencomopt(coms, optargs, ns=None, out=None, debug=0) :
  if type(coms) != dict :
    coms ={k:k for k in coms}
  if ns != None :
    coms ={k:coms[k].__name__ if callable(coms[k]) else "_".join([ns, str(coms[k])]) for k in coms}
  if out == None :
    from sys import stdout
    libout =stdout
  else :
    libout =open(out, "w") if type(out) == str else out
  if libout.seekable() and libout.tell() == 0 :
    libout.write("#!/usr/bin/env python3\n\n")
  co =concomopt(coms =coms, optargs=optargs, debug=debug)
  co.radix(libout)


if __name__ == "__main__" :
  def main() :
    from sys import stdout, argv
    COMS =["make", "zuo"]
    OPTARGS =[
      ("-f,--file,--makefile", "+f", "makefile"),
      ("-n,--just-print,--dry-run,--recon", "n"),
      ("-s,--silent,--quiet", "s"),
      ("-j,--jobs=n", "*j"),
      ("-t,--touch", "t"),
    ]
    gencomopt(COMS, OPTARGS, "main")

  main()

