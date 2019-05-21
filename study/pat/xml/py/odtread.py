#!/usr/bin/env python3

class stac :
  def __init__(ipse) :
    ipse.a =[]

  def push(ipse, i) :
    ipse.a.append(i)
    return len(ipse.a)

  def pop(ipse, n=1) :
    while n>0 :
      i =ipse.a[-1]
      del ipse.a[-1]
      n -=1
    return i

  def top(ipse) :
    return ipse.a[-1]

  def __len__(ipse) :
    return len(ipse.a)

class entresolve(stac) :

  def __init__(ipse) :
    super().__init__()
    ipse.b ={}

  def __setitem__(ipse, c,v) :
    ipse.b[c] = chr(int(v[2:-1])) if v[1] == '#' else v

  def __getitem__(ipse, c) :
    return ipse.b[c]


token =["<", "</", "/>", "xml", "DOCTYPE", "comment", "key", "tag", "attrname", "literal", "PUBLIC", "frag", "ENTITY"]

class StateMachine (stac) :

  def __init__(ipse, entres) :
    super().__init__()
    ipse.entres =entres

  def __call__(ipse) :
    ipse.push(0)
    for c in ipse.get() :
      state =ipse.top()
      #if type(c) == str : ipse.info("[%d] '%c'"%(state,c))
      #else : ipse.info('[%d] "%s"'%(state,token[c]))
      if state not in [-2,-3,-4,-5,-6,-7,-8,-10,-11,-12,-13,3,6,7,8,10,12,13,27] and type(c) == str and c.isspace() :
      #if state not in [-2,-3,-4,-5,-6,-7,-8,-10,-11,-12,-13,3,4,5,6,7,8,10,12,13,27] and type(c) == str and c.isspace() :
        continue
#negative :
      if state == 0 : # S 0
        if c == "<" : # 0 < 1
          ipse.push(-1)
        elif c == token.index("<") : # 0 "<" 1 
          ipse.push(1)
        elif c == token.index("xml") : # 0 "xml" 9
          ipse.push(9)
        elif c == token.index("DOCTYPE") : # 0 "DOCTYPE" 11
          ipse.push(11)
        elif c == token.index("ENTITY") : # 0 "ENTITY" 29
          ipse.push(29)
        elif c == token.index("comment") : # 0 "comment" 20
          ipse.push(20)
        elif c == token.index("</") : # 0 "</" 23
          ipse.push(23)
        else : # 0 else error
          ipse.unget(c)
          ipse.push(26)
          #ipse.error("expected '<'")
      elif state == -1 :
        if c == "/" : # 1 / <<2 
          ipse.pop(1)
          ipse.unget(token.index("</"))
        elif c == "!" : # 1 ! 2
          ipse.push(-2)
        elif c == "?" : # 1 ? 11
          ipse.push(-11)
        elif c.isalpha() : # 1 alpha <<1 &< : unput
          ipse.pop()
          ipse.unget(c)
          ipse.unget(token.index("<"))
        else : # 1 else error
          ipse.error("unexpected '%c'"%c)
      elif state == -11 :
        if c == 'x' : # *11 x 12
          ipse.push(-12)
        else :
          ipse.error("expected 'x'")
      elif state == -12 :
        if c == 'm' : # *12 m 13
          ipse.push(-13)
        else :
          ipse.error("expected 'm'")
      elif state == -13 :
        if c == 'l' : # *13 l <<5
          ipse.pop(4)
          ipse.unget(token.index('xml'))
        else :
          ipse.error("expected 'l'")
      elif state == -2 :
        if c == 'D' :  # *2 D 3
          ipse.push(-3)
        elif c == ('E') :
          ipse.push(-14)
        elif c == ('-') : # *2 - 10
          ipse.push(-10)
        else :
          ipse.error("expected 'D' or '-'")
      elif state == -3 :
        if c == 'O' :  # *2 O 3
          ipse.push(-4)
        else :
          ipse.error("expected 'O'")
      elif state == -4 :
        if c == 'C' :  # *2 C 3
          ipse.push(-5)
        else :
          ipse.error("expected 'C'")
      elif state == -5 :
        if c == 'T' :  # *2 T 3
          ipse.push(-6)
        else :
          ipse.error("expected 'T'")
      elif state == -6 :
        if c == 'Y' :  # *2 Y 3
          ipse.push(-7)
        else :
          ipse.error("expected 'Y'")
      elif state == -7 :
        if c == 'P' :  # *2 P 3
          ipse.push(-8)
        else :
          ipse.error("expected 'P'")
      elif state == -8 :
        if c == 'E' :  # *2 P 3
          ipse.pop(8)
          ipse.unget(token.index("DOCTYPE"))
        else :
          ipse.error("expected 'E'")
      elif state == -10 : # *10 - <<4
        if c == '-' :
          ipse.pop(3)
          ipse.unget(token.index("comment"))
        else :
          ipse.error("expected '-'")
      elif state == -14 :
        if c == 'N' :
          ipse.push(-15)
        else :
          ipse.error("expected 'N'")
      elif state == -15 :
        if c == 'T' :
          ipse.push(-16)
        else :
          ipse.error("expected 'T'")
      elif state == -16 :
        if c == 'I' :
          ipse.push(-17)
        else :
          ipse.error("expected 'I'")
      elif state == -17 :
        if c == 'T' :
          ipse.push(-18)
        else :
          ipse.error("expected 'T'")
      elif state == -18 :
        if c == 'Y' :
          ipse.pop(7)
          ipse.unget(token.index("ENTITY"))
        else :
          ipse.error("expected 'Y'")

#positive :
      elif state == 1 :
        if c == token.index("tag") : # 1 tag 2  : a =new attrset
          ipse.push(2)
          a =[]
        elif c == token.index("key") : # 1,11 key &tag  : t =new tag
          ipse.unget(token.index("tag"))
          t ={'name':l}
        elif c.isalpha() : # 1,2,11 alpha 3 : l =new lex; l.push
          ipse.push(3)
          l =c
        else :
          ipse.error("expected tag name")
      elif state == 2 : # 2 > <<3 : def tag-attrset 
        if c == token.index("key") : # 2,9 key &attrname  : a =new attr; a.name=attrname
          ipse.unget(token.index("attrname"))
        elif c == token.index("attrname") : # 2,9 attrname 4
          ipse.push(4)
          a.append({"name":l})
        elif c == token.index("/>") :
          ipse.pop(2)
          t["attr"] =a
          ipse.on("emptytag", t)
        elif c.isalpha() : # 1,2,9,11 alpha 3 : l =new lex; l.push
          ipse.push(3)
          l =c
        elif c == ">" :
          ipse.pop(2)
          t['attr'] =a
          ipse.on("opentag", t)
        elif c == "/" :
          ipse.push(25)
        else :
          ipse.error("expected attrname or '>'")
      elif state == 3 :
        if c.isalnum() or c == ':' or c == '-' : # *3 alnum  : l.push
          l +=c
        else : # *3 else <<1 &key
          if not c.isspace() :
            ipse.unget(c)
          ipse.pop()
          ipse.unget(token.index("key"))
      elif state == 4 :
        if c == '=' : # 4 = 5
          ipse.push(5)
        else :
          ipse.error("expected '='")
      elif state == 5 : # 5,18 ' 6 : l = new lex
        if c == "'" :
          ipse.push(6)
          l =""
        elif c == '"' : # 5,18 " 7
          ipse.push(7)
          l =""
        elif c == token.index("literal") : # 5 literal <<3 : a.value=literal; attrset.push(a)
          ipse.pop(2)
          a[-1]["value"] =l
          #ipse.info("atr %s='%s'"%(a[-1]["name"], a[-1]["value"]))
        else :
          ipse.error("a literal expected")
      elif state == 6 :
        if c == "'" : # *6 ' <<2 &literal
          ipse.pop()
          ipse.unget(token.index("literal"))
#       elif c == "\\" : # *6 \ 8
#         ipse.push(8)
        else : # *6 else : l.push
          l +=c
      elif state == 7 : # *7 " <<2 &literal
        if c == '"' :
          ipse.pop()
          ipse.unget(token.index("literal"))
#       elif c == "\\" : # *7 \ 8
#         ipse.push(8)
        else : # *7 else : l.push
          l +=c
      elif state == 8 :
        if c == 't' : # *8 t <<2 : l.push \tab
          ipse.pop(2)
          l += "\t"
        if c == 'n' : # *8 t <<2 : l.push \nl
          ipse.pop(2)
          l += "\n"
        if c == 'r' : # *8 n <<2 : l.push \cr
          ipse.pop(2)
          l += "\r"
        else : # *8 else <<2 : l.push
          ipse.pop(2)
          l +=c
      elif state == 9 : 
        if c == token.index("key") : # 2,9 key &attrname  : a =new attr; a.name=attrname
          ipse.unget(token.index("attrname"))
          a =[]
        elif c == token.index("attrname") : # 2,9 attrname 4
          ipse.push(4)
          a.append({"name":l})
        elif c == '?' : # 9 ? 10
          ipse.push(10)
        elif c.isalpha() : # 1,2,9,11 alpha 3 : l =new lex; l.push
          ipse.push(3)
          l =c
        else :
          ipse.error("expected attrname or '?'")
      elif state == 10 :
        if c == '>' : # *10 > <<3 : def xml-attrset
          ipse.pop(2)
          ipse.on("xmlattrset", a)
      elif state == 11 :
        if c == token.index("key") : # 1,11 key &tag  : t =new tag
          ipse.unget(token.index("tag"))
          t ={'name':l}
        elif c == token.index('tag') : # 11 tag 12 
          ipse.push(12)
        elif c.isalpha() : # 1,2,9,11 alpha 3 : l =new lex; l.push
          ipse.push(3)
          l =c
        else :
          ipse.error("expected tag name")
      elif state == 12 :
        if c == token.index('PUBLIC') : # 12 PUBLIC 18
          ipse.push(18)
        elif c == 'P' :
          ipse.push(13)
        else :
          ipse.error("expected PUBLIC")
      elif state == 13 :
        if c == 'U' : # 12 P 13
          ipse.push(14)
        else :
          ipse.error("expected U")
      elif state == 14 :
        if c == 'B' : # *13 U 14
          ipse.push(15)
        else :
          ipse.error("expected B")
      elif state == 15 :
        if c == 'L' : # *14 B 15
          ipse.push(16)
        else :
          ipse.error("expected L")
      elif state == 16 :
        if c == 'I' : # *15 L 16
          ipse.push(17)
        else :
          ipse.error("expected I")
      elif state == 17 :
        if c == 'C' : # *16 I 17
          ipse.pop(6)
          ipse.push(18)
        else :
          ipse.error("expected C")
      elif state == 18 :
        if c == token.index("literal") : # 18 literal 19
          ipse.on("doctype", l)
        elif c == "'" : # 5,18 ' 6 : l = new lex
          ipse.push(6)
          l =""
        elif c == '"' : # 5,18 " 7
          ipse.push(7)
          l =""
        elif c == '>' : # 19 > <<5
          ipse.pop(2)
        else :
          ipse.error("expected C")
#     elif state == 19 :
#       if c == '>' : # 19 > <<5
#         ipse.pop(5)
#       else : # 19 else : error
#         ipse.error("expected '>'")
      elif state == 20 : # 20 - 21 
        if c == '-' :
          ipse.push(21)
        else : 
          pass
      elif state == 21 :
        if c == '-' : # 21 - 22 
          ipse.push(22)
        else :# << 1
          ipse.pop()
      elif state == 22 :
        if c == '>' : # 22 >  << 3
          ipse.pop(3)
        else : # << 2
          ipse.pop(2)
      elif state == 23 :
        if c == token.index("key") : # 1,11 key &tag  : t =new tag
          ipse.unget(token.index("tag"))
          t ={'name':l}
        elif c == token.index('tag') : # 23 tag 24 
          ipse.push(24)
        elif c.isalpha() : # 1,2,9,11 alpha 3 : l =new lex; l.push
          ipse.push(3)
          l =c
        else :
          ipse.error("expected tag name")
      elif state == 24 :
        if c == '>' : # 24 > <<3 : 
          ipse.pop(2)
          t["attr"] =a
          ipse.on("closetag", t)
        else : # 24 else : error
          ipse.error("expected '>'")
      elif state == 25 :
        if c == '>' : # 25 > <<3 :
          ipse.pop()
          ipse.unget(token.index("/>"))
      elif state == 26 :
        if c == token.index("frag") :
          ipse.pop()
          ipse.push(-1)
          ipse.on("fragment", l)
          l =""
        else :
          l =c
          ipse.push(27)
      elif state == 27 :
        if c == '<' : #
          ipse.pop()
          ipse.unget(token.index("frag"))
        elif c == '&' : #
          ipse.push(28)
          el =""
        else :
          l +=c
# --- ENTITY ---
      elif state == 28 :
        if c == ';' :
          ipse.pop()
          l +=ipse.entres[el]
        else :
          el +=c
# --- DTD ---
      elif state == 29 :
        if c == '>' : #
          ipse.pop()
        elif c == token.index("key") : #
          e ={'name':l}
          ipse.push(30)
        elif c.isalpha() : #
          ipse.push(3)
          l =c
        else :
          ipse.error("expected tag name")
      elif state == 30 :
        if c == "'" :
          ipse.push(6)
          l =""
        elif c == '"' :
          ipse.push(7)
          l =""
        elif c == token.index("literal") :
          ipse.pop()
          e['value'] =l
          ipse.on("entity", e)
        else :
          ipse.error("a literal expected")


#negative :
# S 0
# 0 < 1
# 0 else error
# 1 / <<2 
# 1 ! 2
# 1 ? 11
# 1 sp <<1 
# 1 alpha <<1 unput
# 1 else error
# *11 x 12
# *12 m 13
# *13 l <<5 &xml
# *2 D 3
# *3 O 4
# *4 C 5
# *5 T 6
# *6 Y 7
# *7 P 8
# *8 E <<9 &DOCTYPE
# *2 - 10
# *10 - <<4 &comment

#positive :
# 0 "<" 1 
# 1 tag 2  : as =new attrset
# 2 > <<3 : def tag-attrset 
# 1,11 key &tag  : t =new tag
# 2,9 key &attrname  : a =new attr; a.name=attrname
# 1,2,9,11 alpha 3 : l =new lex; l.push
# *3 alnum  : l.push
# *3 else <<1 &key
# 2,9 attrname 4
# 4 = 5
# 5,19 ' 6 : l = new lex
# *6 ' <<2 &literal
# *6 \ 8
# *6 else : l.push
# *8 t <<2 : l.push \tab
# *8 n <<2 : l.push \newline
# *8 r <<2 : l.push \carriagereturn
# *8 else <<2 : l.push
# 5,19 " 7
# *7 " <<2 &literal
# *7 \ 8
# *7 else : l.push
# 5 literal <<3 : a.value=literal; attrset.push(a)
# 0 "xml" 9
# 9 ? 10
# *10 > <<3 : def xml-attrset
# 0 "DOCTYPE" 11
# 11 tag 12 
# 12 PUBLIC 19
# 12 P 13
# *13 U 14
# *14 B 15
# *15 L 16
# *16 I 17
# *17 C 18 <<6 PUBLIC
# 19 literal 20
# 20 > <<5
# 20 else : error
# 0 "comment" 21
# 21 - 22 <<1
# *22 - 23 <<1
# *23 > <<2
# 21,22,23 else : noop
# 0 "</" 24
# 24 tag 25
# 25 > <<3 : close tag operation
# 25 else : error
# 0 cdata 26 : l.push
# 26 frag <<1 : on fragment
# 26 else 27
# 27 < <<1 : &frag
# 27 else : l.push


class xmlparse (stac) :

  def info(ipse, message) :
    i,j =ipse.pos
    if ipse.debug >= 2 :
      input("%s:%d:%d: %s"%(ipse.file_name, i+1, j, message))
    else :
      print("%s:%d:%d: %s"%(ipse.file_name, i+1, j, message))

  def error(ipse, message) :
    i,j =ipse.pos
    print("%s:%d:%d: %s"%(ipse.file_name, i+1, j, message))
    exit(1)

  debug =0

  def onemptytag(ipse, name, attr) : pass
  def onopentag(ipse, name, attr) : pass
  def onclosetag(ipse, name, attr) : pass
  def onxmlattrset(ipse, data) : pass
  def ondoctype(ipse, data) : pass
  def onfragment(ipse, data) : pass
  def onentity(ipse, data) : pass

  def on(ipse, event, data) :
    if event == "emptytag" :
      ipse.onemptytag(data["name"], data["attr"])
    elif event == "opentag" :
      ipse.onopentag(data["name"], data["attr"])
    elif event == "closetag" :
      ipse.onclosetag(data["name"], data["attr"])
    elif event == "xmlattrset" :
      ipse.onxmlattrset(data)
    elif event == "doctype" :
      ipse.ondoctype(data)
    elif event == "fragment" :
      ipse.onfragment(data)
    elif event == "entity" :
      ipse.onentity(data)
    if ipse.debug :
      if event == "emptytag" :
        ipse.info("emptytag %s"%data)
      elif event == "opentag" :
        ipse.info("opentag %s %s"%(data["name"],data["attr"]))
      elif event == "closetag" :
        ipse.info("closetag %s %s"%(data["name"],data["attr"]))
      elif event == "xmlattrset" :
        ipse.info("xmlattrset %s"%data)
      elif event == "doctype" :
        ipse.info("doctype %s"%data)
      elif event == "fragment" :
        ipse.info("fragment %s"%data)
      elif event == "entity" :
        ipse.info("entity %s"%data)

  def unput(ipse, c) :
    ipse.unput_buffer.push(c)

  def put(ipse) :
    for i,l in enumerate(ipse.input) :
      for j,c in enumerate(l.decode() if type(l) != str else l) :
        ipse.pos =i,j
        while ipse.unput_buffer :
          yield ipse.unput_buffer.pop()
        yield c

  def __call__(ipse, entres=None) :
    sm =StateMachine(entres)
    sm.info =ipse.info
    sm.error =ipse.error
    sm.get =ipse.put
    sm.unget =ipse.unput
    sm.on =ipse.on
    ipse.unput_buffer =stac()
    return sm()

class odtparse (stac) :
  def otag(ipse, name, attr) :
    if name == "office:body" :
      ipse.xmlp.debug =2
    return

    ns, tn =name.split(":")
    if ns == "table" :
      if tn == "table" :
        if ipse.t != None :
          ipse.tt[ipse.name] =ipse.t
        ipse.t =[]
        for d in attr :
          if d["name"] == "table:name" :
            ipse.name =d["value"]
        if ipse.t != None :
          ipse.tt[ipse.name] =ipse.t
      elif tn == "table-cell" :
        value =None
        for d in attr :
          if d["name"] == "office:value-type" :
            valuetype =d["value"]
          elif d["name"] == "office:value" :
            value =d["value"]
        if valuetype == "string" :
          value = "" if value == None else "'"+value+"'"
        ipse.cell.append(value)
        return
        if valuetype == "float" :
          ipse.cell.append(float(value))
        elif valuetype == "int" :
          ipse.cell.append(int(value))
        elif valuetype == "string" :
          ipse.cell.append(value)
        else :
          input("valuetype %s"%valuetype)

  def cdata(ipse, data) :
    print("cdata "+data)

  def entity(ipse, data) :
    #print("entity %s"%data)
    ipse.entresolve[data["name"]] =data["value"]
    input(ipse.entresolve[data["name"]])

  def ctag(ipse, name, attr) :
    ns, tn =name.split(":")
    if ns == "table" :
      if tn == "table-row" :
        ipse.t.append(ipse.cell)
        ipse.cell =[]

  def __init__(ipse, debug=0) :
    super().__init__()
    ipse.debug =debug
    ipse.entresolve =entresolve()

  def pushfile(ipse, file_name) :
    xmlp =xmlparse()
    xmlp.onopentag =ipse.otag
    xmlp.onclosetag =ipse.ctag
    xmlp.onfragment =ipse.cdata
    xmlp.onentity =ipse.entity
    xmlp.debug =ipse.debug
    if file_name[-3:].lower() in ("xml", "ent") :
      xmlp.file_name =file_name
      xmlp.input =open(file_name)
    else :
      from zipfile import ZipFile
      zf =ZipFile(file_name)
      xmlp.file_name =file_name
      xmlp.input =zf.open("content.xml")
    ipse.push(xmlp)

  def __call__(ipse, file_name) :
    ipse.tt ={}
    ipse.t =None
    ipse.name =None
    ipse.cell =[]
    while len(ipse) :
      ipse.xmlp =ipse.pop()
      ipse.xmlp(ipse.entresolve)
    return ipse.tt

def odtread(filelst, align=0) :
  odtp = odtparse(debug=1)
  for file_name in reversed(filelst) :
    odtp.pushfile(file_name)
  tt = odtp(file_name)
  #tt = odtparse()(file_name)
  if align :
    for t in tt :
      n =max(len(r) for r in tt[t])
      u =[]
      for r in tt[t] :
        for i in range(len(r), n) :
          r.append("")
        u.append(r)
      tt[t] =u
  return tt
from sys import argv
odtread(["doc/xhtml-special.ent","doc/graphe-exo.odt"])

