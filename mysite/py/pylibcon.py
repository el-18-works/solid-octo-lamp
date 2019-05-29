#!/usr/bin/python3

#
#
#  concatenatio
#
#

class stack :

  def __init__(ipse) :
    ipse.a =[]

  def __len__(ipse) :
    return len(ipse.a)

  def push(ipse, x) :
    ipse.a.append(x)

  def pop(ipse) :
    x =ipse.a[-1]
    del ipse.a[-1]
    return x

  def top(ipse) :
    return ipse.a[-1]

class tracepyfile (stack) :

  def error(ipse, msg) :
    print(msg)
    exit(1)

  def __call__(ipse, filename, cb) :
    def resettabs(l) :
      for i in range(len(l)) :
        if not l[i].isspace() :
          return l[:i], l[i:]
      return l,""
    for i,l in enumerate(open(filename)) :
      if i == 0 :
        ipse.push('')
        cb("openfile", None)
        cb("open", l.strip())
        continue
      tabs,line =resettabs(l.rstrip())
      if not line or line[0] == '#' and tabs != ipse.top() :
        cb("comment", line)
      else :
        while len(tabs) < len(ipse.top()) :
          if ipse.top()[:len(tabs)] != tabs :
            ipse.error("%d : '%s' : (close) inconsistent shift spaces/tabs"%(i+1, l))
          ipse.pop()
          cb("close", None)
        if tabs != ipse.top() :
          if len(tabs) > len(ipse.top()) :
            if tabs[:len(ipse.top())] != ipse.top() :
              ipse.error("%d : '%s' : (open) inconsistent shift spaces/tabs"%(i+1, l))
            ipse.push(tabs)
            cb("open", line)
          else :
            ipse.error("%d : '%s' : inconsistent shift spaces/tabs"%(i+1, l))
        else :
          cb("line", line)

    while len(ipse) :
      ipse.pop()
      cb("close", None)
    cb("closefile", None)
    
class pyblocktree(stack) :

  def error(ipse, msg) :
    print(msg)
    exit(1)

  def __call__(ipse, filename) :
    ipse.filename =filename
    tpf =tracepyfile()
    tpf(filename, ipse.tree)
    return ipse.root

  def tree(ipse, e, data) :
    if e == "openfile" :
      ipse.push({"key" : "", "list":[{'key':'decl', 'data':"file :"}]})
    elif e == "open" :
      key =value =""
      udata =ipse.top()["list"][-1]['data']
      if udata :
        i =0
        while udata[i] not in ('(', ':') and not udata[i].isspace() :
          i +=1
        key =udata[:i]
        if key in ("for", "while", "if", "elif", "else", "try", "except") :
          value =""
        elif key in ("def", "class") :
          while udata[i].isspace() :
            i +=1
          j =i
          while udata[j] not in ('(', ':') and not udata[j].isspace() :
            j +=1
          value =udata[i:j]
        elif key == "file" :
          value =ipse.filename
        else :
          ipse.error("unknown key '%s'"%key)
      ipse.push({"key":key, "name":value, "list":[{'key':'decl', 'data':udata}, {'key':'line', 'data':data}]})
    elif e == "line" :
      ipse.top()["list"].append({'key':'line', 'data':data})
    elif e == "close" :
      data =ipse.pop()
      if data["key"] == "file" :
        ipse.root =data
      ipse.top()["list"][-1] =data
    elif e == "comment" :
      pass
#      print("comment",data)
    elif e == "closefile" :
      ipse.pop()

class pyfilelist :

  def __init__(ipse) :
    ipse.lst ={}
    ipse.bt =pyblocktree()

  def __call__(ipse, filename) :
    if filename not in ipse.lst :
      ipse.lst[filename] ={}
      for l in ipse.bt(filename)["list"][1:] :
        if l["key"] in ("class", "def") :
          ipse.lst[filename][l["name"]] =l["list"]
    return ipse.lst[filename]

class pyitem :

  def __init__(ipse) :
    ipse.fl =pyfilelist()
    
  def write(ipse, out, filename, itemname, tabchr="  ", tabshft=0) :
    def f(data, tabshft) :
      if data['key'] == 'decl' :
        out.write(tabchr*tabshft + data['data'] + '\n')
      elif data['key'] == 'line' :
        out.write(tabchr*(tabshft+1) + data['data'] + '\n')
      elif 'list' in data :
        for x in data['list'] :
          f(x, tabshft+1)
    out.write('\n')
    for data in ipse.fl(filename)[itemname] :
      f(data, tabshft)
    out.write('\n')

  def ls(ipse, filename) :
    return tuple(ipse.fl(filename).keys())

  def decl(ipse, filename, itemname) :
    s =ipse.fl(filename)[itemname][0]['data']
    s =s.rstrip()[:-1].rstrip()
    if s[0] == 'c' :
      ls =[]
      for l in ipse.fl(filename)[itemname] :
        if l["key"] in ("class", "def") :
          ls.append( "  "+l['list'][0]['data'] )
          #s += "  "+l['list'][0]['data'] + '\n'
    return s + '\n'.join(ls)

#
#
#  optionium parsitio
#
#


class optparsesm :
  def error(ipse, msg) :
    print("optparsesm :" + msg)
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
      yield "\tprint(\"%d '%s'\"%(st,lex.la))"
    yield "\tif st == %d :"%preactst
    yield "\t\tif setoptf[0] == 'arg' :"
    yield "\t\t\tif lex.la == '=' :"
    yield "\t\t\t\tl =lex.getl()"
    yield "\t\t\t\topt[setoptf[1]].append(l[1:])"
    yield "\t\t\telse :"
    yield "\t\t\t\topt['flgs'] +=setoptf[1]"
    if ipse.debug :
      yield "\t\telse :"
      yield "\t\t\terror('internalis error')"
    yield "\t\tst =0"
    yield "\telif st == %d :"%actst
    yield "\t\tif setoptf[0] == 'arg' :"
    yield "\t\t\tl =lex.getl()"
    yield "\t\t\topt[setoptf[1]].append(l[1:] if len(l) and l[0] == '=' else l)"
    yield "\t\telif setoptf[0] == 'flg' :"
    yield "\t\t\topt['flgs'] +=setoptf[1]"
    yield "\t\t\topt['flgs'] +=lex.getl(noinc=1)"
    if ipse.debug :
      yield "\t\telse :"
      yield "\t\t\terror('internalis error')"
    yield "\t\tst =0"
    for st in sorted(ipse.trans) :
      yield "\telif st == %d :"%st
      if '=' in ipse.trans[st] :
        if len(ipse.trans[st]) != 1 :
          ipse.error("indeterminismus")
        opt =ipse.trans[st]["="]
        if opt[0] == '*' :
          yield "\t\tst =%d"%preactst
          yield "\t\tsetoptf =('arg', '%s')"%opt[1:]
        elif opt[0] == '+' :
          yield "\t\tst =%d"%actst
          yield "\t\tsetoptf =('arg', '%s')"%opt[1:]
        else :
          yield "\t\tst =%d"%actst
          yield "\t\tsetoptf =('flg', '%s')"%opt
        continue
      for i,c in enumerate(ipse.trans[st]) :
        s ="\t\t"
        if i != 0 : s +="el"
        yield s + "if lex.la == '%c' :"%c
        yield "\t\t\tst =%d"%ipse.trans[st][c]
        yield "\t\t\tlex.getc()"
      yield "\t\telse :"
      if st == 0 :
        yield "\t\t\tsetoptf =('arg', 'args')"
        yield "\t\t\tst =%d"%actst
      else :
        yield "\t\t\terror('non exspectato uso aborto')"
        yield "\t\t\treturn"

class optparse :

  def __init__(ipse, optargs,debug=False) :
    ipse.optargs =optargs
    ipse.debug =debug

  def __call__(ipse, out) :

    out.write("\n")
    out.write("class GetOpt :\n")
    out.write("\tdef __init__(ipse) :\n")
    out.write("\t\timport sys\n")
    out.write("\t\tipse.argv =sys.argv[1:]\n")
    out.write("\t\tif len(ipse.argv) == 0 :\n")
    out.write("\t\t\tipse.la ='$'\n")
    out.write("\t\telif len(ipse.argv[0]) == 0 :\n")
    out.write("\t\t\tipse.la =''\n")
    out.write("\t\telse :\n")
    out.write("\t\t\tipse.la =ipse.argv[0][0]\n")
    out.write("\t\tipse.argi =0\n")
    out.write("\t\tipse.argj =0\n")
    out.write("\n")
    out.write("\tdef getc(ipse) :\n")
    out.write("\t\tc =ipse.la\n")
    out.write("\t\tif len(ipse.argv[ipse.argi]) == ipse.argj+1 :\n")
    out.write("\t\t\tipse.argi +=1; ipse.argj =0\n")
    out.write("\t\t\tif len(ipse.argv) == ipse.argi :\n")
    out.write("\t\t\t\tipse.la ='$'\n")
    out.write("\t\t\telse :\n")
    out.write("\t\t\t\tipse.la ='' if len(ipse.argv[ipse.argi]) == 0 else ipse.argv[ipse.argi][0]\n")
    out.write("\t\telse :\n")
    out.write("\t\t\tipse.argj +=1\n")
    out.write("\t\t\tipse.la =ipse.argv[ipse.argi][ipse.argj]\n")
    out.write("\t\treturn c\n")
    out.write("\n")
    out.write("\tdef getl(ipse, noinc=0) :\n")
    out.write("\t\tif noinc == 1 and ipse.argj == 0 :\n")
    out.write("\t\t\treturn ''\n")
    out.write("\t\tif ipse.la == '$' :\n")
    out.write("\t\t\treturn '$'\n")
    #out.write("\t\telif len(ipse.argv[ipse.argi]) == ipse.argj :\n")
    #out.write("\t\t\tipse.argi +=1\n")
    #out.write("\t\t\tif len(ipse.argv) == ipse.argi :\n")
    #out.write("\t\t\t\treturn '$'\n")
    #out.write("\t\t\tl =ipse.argv[ipse.argi][0:]\n")
    out.write("\t\telse :\n")
    out.write("\t\t\tl =ipse.argv[ipse.argi][ipse.argj:]\n")
    out.write("\t\tipse.argi +=1; ipse.argj =0\n")
    out.write("\t\tif len(ipse.argv) == ipse.argi :\n")
    out.write("\t\t\tipse.la ='$'\n")
    out.write("\t\telse :\n")
    out.write("\t\t\tipse.la ='' if len(ipse.argv[ipse.argi]) == 0 else ipse.argv[ipse.argi][0]\n")
    out.write("\t\treturn l\n")
    out.write("\n")
    out.write("\tdef error(ipse, msg='error') :\n")
    out.write("\t\tprint('optparse : %s'%msg)\n")
    #out.write("\t\texit(1)\n")
    out.write("\n")

    opsm =optparsesm(ipse.debug)
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
        opsm(c, f)
      if f[0] in ("*", "+") :
        ls.append(cmd[1])
    opt = "{'flgs':'','args':[]," + ",".join("'%c':[]"%c for c in ls) + "}"
    out.write("\tdef __call__(ipse, opt=%s) :\n"%opt)
    out.write("\t\tlex =ipse\n")
    out.write("\t\terror =ipse.error\n")

    for i in opsm :
      out.write ("\t\t"+i)
      out.write("\n")

    out.write("\t\treturn opt\n")

    out.write("\n")
    out.write("def optutendi(nomen='') :\n")
    out.write("\t\n")
    out.write("\tprint('Modus utendi %s :'%nomen)\n")
    for u in lutendi :
      out.write("\tprint('%s')\n"%u)
    out.write("\n")
    out.write("def getopt() :\n")
    out.write("\top =GetOpt()\n")
    out.write("\treturn op()\n")
    out.write("\n")

def genoptinstance(out) :
  out.write("class opt :\n")
  out.write("  def __init__(ipse) :\n")
  out.write("    ipse.a =getopt()\n")
  out.write("  def __contains__(ipse, flg) :\n")
  out.write("    return flg in ipse.a['flgs']\n")
  out.write("  def __getitem__(ipse, arg) :\n")
  out.write("    return ipse.a[arg]\n")
  out.write("  def __getattr__(ipse, arg) :\n")
  out.write("    return ipse.a[arg]\n")
  out.write("opt =opt()\n")
  out.write("\n")

def gengetopt(output, optargs, instance=1, debug=0) :
  op =optparse(optargs=optargs, debug=debug)
  libout =open(output, "w") if type(output) == str else output
  if libout.tell() == 0 :
    libout.write("#!/usr/bin/python3\n")
  op(libout)
  if instance :
    genoptinstance(libout)


