#!/usr/bin/env python3

class stac :
  def __init__(ipse) :
    ipse.__a =[]

  def push(ipse, i) :
    ipse.__a.append(i)
    return len(ipse.__a)

  def pop(ipse, n=1) :
    while n>0 :
      i =ipse.__a[-1]
      del ipse.__a[-1]
      n -=1
    return i

  def top(ipse) :
    return ipse.__a[-1]

  def __len__(ipse) :
    return len(ipse.__a)

  def __iter__(ipse) :
    return iter(ipse.__a)

class entresolve(stac) :

  def __init__(ipse) :
    super().__init__()
    ipse.__a ={}

  def __setitem__(ipse, c, v) :
    ipse.__a[c] =v

  def __getitem__(ipse, c) :
    if c in ipse.__a :
      return ipse.__a[c]
    return chr(int(c[1:])) if len(c) and c[0] == '#' else c


token =["<", "</", "/>", "xml", "DOCTYPE", "comment", "key", "tag", "attrname", "literal", "PUBLIC", "frag", "ENTITY"]

class StateMachine (stac) :

  def __init__(ipse, entres) :
    super().__init__()
    ipse.entres =entres

  def __call__(ipse) :
    token =["<", "</", "/>", "xml", "DOCTYPE", "comment", "key", "tag", "attrname", "literal", "PUBLIC", "frag", "ENTITY"]
    ipse.push(0)
    while ipse.la() :
      c =ipse.ci()
      state =ipse.top()
      #if type(c) in ( str, bytes) : ipse.info("[%d] '%s'"%(state,c))
      #else : ipse.info('[%d] "%s"'%(state,token[c]))
      if  state not in [0,-2,-3,-4,-5,-6,-7,-8,-10,-11,-12,-13,3,6,7,8,10,12,13,26,27] and type(c) == str and c.isspace() :
#      if state not in [-2,-3,-4,-5,-6,-7,-8,-10,-11,-12,-13,3,4,5,6,7,8,10,12,13,27] and type(c) == str and c.isspace() :
        continue
#negative :
      if state == (0) : # S 0
        if c == '<' : # 0 < 1
          if ipse.la() in (33, 63, 47) : # (ord('!'), ord('?'), ord('/')) 
            ipse.push(-1)
          else :
            ipse.push(1)
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
        else : #
          ipse.unget(c)
          ipse.push(26)
      elif state == (-1) :
        if c == "/" : # 1 / <<2 
          ipse.pop(1)
          ipse.unget(token.index("</"))
        elif c == "!" : # 1 ! 2
          ipse.push(-2)
        elif c == "?" : # 1 ? 11
          ipse.push(-11)
        elif c.isalpha() : # 1 alpha <<1 &< : unput
          ipse.pop()
          ipse.unget(token.index("<"))
        else : # 1 else error
          ipse.error("unexpected '%c'"%c)
      elif state == (-11) :
        if c == 'x' : # *11 x 12
          ipse.push(-12)
        else :
          ipse.error("expected 'x'")
      elif state == (-12) :
        if c == 'm' : # *12 m 13
          ipse.push(-13)
        else :
          ipse.error("expected 'm'")
      elif state == (-13) :
        if c == 'l' : # *13 l <<5
          ipse.pop(4)
          ipse.unget(token.index('xml'))
        else :
          ipse.error("expected 'l'")
      elif state == (-2) :
        if c == 'D' :  # *2 D 3
          ipse.push(-3)
        elif c == ('E') :
          ipse.push(-14)
        elif c == ('-') : # *2 - 10
          ipse.push(-10)
        else :
          ipse.error("expected 'D' or '-'")
      elif state == (-3) :
        if c == 'O' :  # *2 O 3
          ipse.push(-4)
        else :
          ipse.error("expected 'O'")
      elif state == (-4) :
        if c == 'C' :  # *2 C 3
          ipse.push(-5)
        else :
          ipse.error("expected 'C'")
      elif state == (-5) :
        if c == 'T' :  # *2 T 3
          ipse.push(-6)
        else :
          ipse.error("expected 'T'")
      elif state == (-6) :
        if c == 'Y' :  # *2 Y 3
          ipse.push(-7)
        else :
          ipse.error("expected 'Y'")
      elif state == (-7) :
        if c == 'P' :  # *2 P 3
          ipse.push(-8)
        else :
          ipse.error("expected 'P'")
      elif state == (-8) :
        if c == 'E' :  # *2 P 3
          ipse.pop(8)
          ipse.unget(token.index("DOCTYPE"))
        else :
          ipse.error("expected 'E'")
      elif state == (-10) : # *10 - <<4
        if c == '-' :
          ipse.pop(3)
          ipse.unget(token.index("comment"))
        else :
          ipse.error("expected '-'")
      elif state == (-14) :
        if c == 'N' :
          ipse.push(-15)
        else :
          ipse.error("expected 'N'")
      elif state == (-15) :
        if c == 'T' :
          ipse.push(-16)
        else :
          ipse.error("expected 'T'")
      elif state == (-16) :
        if c == 'I' :
          ipse.push(-17)
        else :
          ipse.error("expected 'I'")
      elif state == (-17) :
        if c == 'T' :
          ipse.push(-18)
        else :
          ipse.error("expected 'T'")
      elif state == (-18) :
        if c == 'Y' :
          ipse.pop(7)
          ipse.unget(token.index("ENTITY"))
        else :
          ipse.error("expected 'Y'")

#positive :
      elif state == (1) :
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
      elif state == (2) : # 2 > <<3 : def tag-attrset 
        if c == token.index("key") : # 2,9 key &attrname  : a =new attr; a.name=attrname
          ipse.unget(token.index("attrname"))
        elif c == token.index("attrname") : # 2,9 attrname 4
          ipse.push(4)
          a.append({"name":l})
        elif c == token.index("/>") :
          ipse.pop(2)
          t["attr"] =a
          ipse.onto("emptytag", t)
        elif c.isalpha() : # 1,2,9,11 alpha 3 : l =new lex; l.push
          ipse.push(3)
          l =c
        elif c == ">" :
          ipse.pop(2)
          t['attr'] =a
          ipse.onto("opentag", t)
        elif c == "/" :
          ipse.push(25)
        else :
          ipse.error("expected attrname or '>'")
      elif state == (3) :
        if c.isalnum() or c == ':' or c == '-' : # *3 alnum  : l.push
          l +=c
          if ipse.la() in (47, 61, 62) : # ord('/'), ord('='):
            ipse.pop()
            ipse.unget(token.index("key"))
        else : # *3 else <<1 &key
          if not c.isspace() :
            ipse.unget(c)
          ipse.pop()
          ipse.unget(token.index("key"))
      elif state == (4) :
        if c == '=' : # 4 = 5
          ipse.push(5)
        else :
          ipse.error("expected '='")
      elif state == (5) : # 5,18 ' 6 : l = new lex
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
      elif state == (6) :
        if c == "'" : # *6 ' <<2 &literal
          ipse.pop()
          ipse.unget(token.index("literal"))
        elif c == '&' : #
          ipse.push(28)
          el =""
#       elif c == "\\" : # *6 \ 8
#         ipse.push(8)
        else : # *6 else : l.push
          l +=c
      elif state == (7) : # *7 " <<2 &literal
        if c == '"' :
          ipse.pop()
          ipse.unget(token.index("literal"))
        elif c == '&' : #
          ipse.push(28)
          el =""
#       elif c == "\\" : # *7 \ 8
#         ipse.push(8)
        else : # *7 else : l.push
          l +=c
      elif state == (8) :
        if c == 't' : # *8 t <<2 : l.push \tab
          ipse.pop(2)
          l += "\t"
        if c == 'n' : # *8 t <<2 : l.push \nl
          ipse.pop(2)
          l += "\n"
        if c == 'r' : # *8 n <<2 : l.push \cr
          ipse.pop(2)
          l += "\r"
        if c == '\\' :
          ipse.pop(2)
          l += "\\"
        else : # *8 else <<2 : l.push
          ipse.pop(2)
          l +=c
      elif state == (9) : 
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
      elif state == (10) :
        if c == '>' : # *10 > <<3 : def xml-attrset
          ipse.pop(2)
          ipse.onto("xmlattrset", a)
      elif state == (11) :
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
      elif state == (12) :
        if c == token.index('PUBLIC') : # 12 PUBLIC 18
          ipse.push(18)
        elif c == 'P' :
          ipse.push(13)
        else :
          ipse.error("expected PUBLIC")
      elif state == (13) :
        if c == 'U' : # 12 P 13
          ipse.push(14)
        else :
          ipse.error("expected U")
      elif state == (14) :
        if c == 'B' : # *13 U 14
          ipse.push(15)
        else :
          ipse.error("expected B")
      elif state == (15) :
        if c == 'L' : # *14 B 15
          ipse.push(16)
        else :
          ipse.error("expected L")
      elif state == (16) :
        if c == 'I' : # *15 L 16
          ipse.push(17)
        else :
          ipse.error("expected I")
      elif state == (17) :
        if c == 'C' : # *16 I 17
          ipse.pop(6)
          ipse.push(18)
        else :
          ipse.error("expected C")
      elif state == (18) :
        if c == token.index("literal") : # 18 literal 19
          ipse.onto("doctype", l)
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
#     elif state == (19) :
#       if c == '>' : # 19 > <<5
#         ipse.pop(5)
#       else : # 19 else : error
#         ipse.error("expected '>'")
      elif state == (20) : # 20 - 21 
        if c == '-' :
          ipse.push(21)
        else : 
          pass
      elif state == (21) :
        if c == '-' : # 21 - 22 
          ipse.push(22)
        else :# << 1
          ipse.pop()
      elif state == (22) :
        if c == '>' : # 22 >  << 3
          ipse.pop(3)
        else : # << 2
          ipse.pop(2)
      elif state == (23) :
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
      elif state == (24) :
        if c == '>' : # 24 > <<3 : 
          ipse.pop(2)
          t["attr"] =a
          ipse.onto("closetag", t)
        else : # 24 else : error
          ipse.error("expected '>'")
      elif state == (25) :
        if c == '>' : # 25 > <<3 :
          ipse.pop()
          ipse.unget(token.index("/>"))
      elif state == (26) :
        if c == token.index("frag") :
          ipse.pop()
          ipse.push(-1)
          ipse.onto("fragment", l)
          l =""
        elif c == '&' : #
          ipse.push(27)
          ipse.push(28)
          el =""
          l =""
        else :
          l =c
          ipse.push(27)
      elif state == (27) :
        if c == '<' : #
          ipse.pop()
          ipse.unget(token.index("frag"))
        elif c == '&' : #
          ipse.push(28)
          el =""
        else :
          l +=c
# --- ENTITY ---
      elif state == (28) :
        if c == ';' :
          ipse.pop()
          l +=ipse.entres[el]
        else :
          el +=c
# --- DTD ---
      elif state == (29) :
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
      elif state == (30) :
        if c == "'" :
          ipse.push(6)
          l =""
        elif c == '"' :
          ipse.push(7)
          l =""
        elif c == token.index("literal") :
          ipse.pop()
          e['value'] =l
          ipse.onto("entity", e)
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

class csglob :

  def __init__(ipse, pat) :
    ipse.start =0b1
    ipse.star =0b0
    charset =set()
    for p,i in enumerate(pat) :
      if i == ',' :
        ipse.start |=0b10<<p
      elif i == '*' :
        ipse.star |=(0b1<<p)
      else :
        charset.add(i)
    ipse.charset =['\0', '\1'] + sorted(charset)
    ipse.pat =pat+'\0'
    ipse.subset ={}
    de =ipse.compat(ipse.start)
    while de :
      ad =set()
      for d in de :
        ad |=ipse.compat(d)
      de =ad

  def compat(ipse, r) :
    star =r & ipse.star
    s =(r & ~star) | star<<1
    ipse.subset[r] =[ipse.compati(s, ',') | ipse.compati(s, '\0')]
    ipse.subset[r] +=[ipse.compati(s, i) | star for i in ipse.charset[1:]]
    de =set()
    for d in ipse.subset[r] :
      if d not in ipse.subset.keys() :
        de.add(d)
    return de

  def compati(ipse, r, i, star=False) :
    t =0
    for p in range(len(ipse.pat)) :
      if 0b01<<p & r and ipse.pat[p] == i :
          t |=0b10<<p
    return t

  def __call__(ipse, l) :
    r =ipse.start
    t =0b0
    for c in l :
      for i in range(len(ipse.charset)-1, 0, -1) :
        if ipse.charset[i] == c : break
      r =ipse.subset[r][i]
    return bool(ipse.subset[r][0])

#
# eventail
#
class ail :

  def __init__(ipse) :
    ipse.aila ={}

  def on(ipse, ev, fan) :
    if ev not in ipse.aila :
      ipse.aila[ev] =set()
    ipse.aila[ev].add(fan)

  def no(ipse, ev, fan) :
    ipse.aila[ev].discard(fan)

  def onto(ipse, ev, data) :
    for a,b in ipse.aila.copy().items() :
      if a == ev :
        for c in b.copy() :
          c(ev,data)

class eventail (ail) :

  def __init__(ipse) :
    super().__init__()
    ipse.eventaila ={}

  def on(ipse, a0, a1, a2=None) :
    if a2 == None :
      super().on(a0, a1)
    else :
      if a0 not in ipse.eventaila :
        ipse.eventaila[a0] =ail()
        ipse.eventaila[a0].match =csglob(a0)
      ipse.eventaila[a0].on(a1, a2)

  def no(ipse, a0, a1, a2=None) :
    if a2 == None :
      super().no(a0, a1)
    elif a0 in ipse.eventaila :
      ipse.eventaila[a0].no(a1, a2)

  def onto(ipse, *e) :
    if len(e) == 3 :
      tag, ev, data =e
      for a in ipse.eventaila.copy().values() :
        if a.match(tag) :
          a.onto(ev, data)
    elif len(e) == 2 :
      ev, data =e
      super().onto(ev, data)

#
# Input Buffer
#
class inputbuf :

  def info(ipse, arg) :
    print("%s %s"%(ipse.pos(), arg))

  def error(ipse, arg) :
    print("%s %s"%(ipse.pos(), arg))
    exit(-1)

  def pos(ipse) :
    return ""

class fileinputbuf (inputbuf) :

  def la(ipse) :
    c =ipse.input.peek()
    if len(c) == 0 or not c[0] :
      return None
    return c[0]

  def ci(ipse) :
    def f() :
      c =ipse.input.peek()
      if len(c) == 0 or not (c[0]) :
        return None
      c =c[0]
      if (c) & 1<<7 == 0 :
        return ipse.input.read1(1).decode()
      elif (c) & 1<<6 == 0 :
        ipse.error("utf8 decode error")
      elif (c) & 1<<5 == 0 :
        return ipse.input.read1(2).decode()
      elif (c) & 1<<4 == 0 :
        return ipse.input.read1(3).decode()
      elif (c) & 1<<3 == 0 :
        return ipse.input.read1(4).decode()
      else :
        ipse.error("utf8 decode error")
    i,j =ipse.posij
    c =f()
    while c == '\n' :
      i,j =i+1,0
      c =f()
    if len(c) == 0 :
      return None
    ipse.posij =i,j+1
    return c

  def pos(ipse) :
    return ipse.file_name + ":%d:%d:"%ipse.posij
  
  def __init__(ipse, file_name, input=None) :
    ipse.file_name =file_name
    ipse.input =input or open(file_name)
    ipse.posij =1,0

class strinputbuf (inputbuf) :

  def ci(ipse) :
    if ipse.i+1 > ipse.ad :
      return None
    ipse.i +=1
    return ipse.buf[ipse.i-1]

  def la(ipse) :
    if ipse.i+1 > ipse.ad :
      return None
    return ipse.buf[ipse.i].encode('utf8')[0]

  def pos(ipse) :
    return ipse.file_name + " (%d)"%ipse.i
  
  def __init__(ipse, buf, name=None, de=0, ad=-1) :
    super().__init__()
    ipse.buf =buf
    ipse.i, ipse.ad =de, ad if ad >= 0 else len(buf)+ad+1
    ipse.file_name =name or ipse.buf[:12]+ ("..." if len(ipse.buf) > 12 else "")

class inputstac(stac) :

  def ci(ipse) :
    if ipse.unput_buffer != None :
      c =ipse.unput_buffer 
      ipse.unput_buffer =None
      return c
    c =ipse.top().ci()
    if c == None :
      ipse.pop()
      if len(ipse) == 0 :
        return None
    return c

  def la(ipse) :
    return ipse.top().la()

  def unput(ipse, c) :
    ipse.unput_buffer =c

  def pos(ipse) :
    return ipse.top().pos()

  def __init__(ipse) :
    super().__init__()
    ipse.unput_buffer =None

#
# parse
#
class xmlparse (eventail) :

  def info(ipse, message) :
    if ipse.debug >= 2 :
      input("%s: %s"%(ipse.inputs.pos(), message))
    else :
      print("%s: %s"%(ipse.inputs.pos(), message))

  def error(ipse, message) :
    print("%s: %s"%(ipse.inputs.pos(), message))
    exit(1)

  debug =0

  def onemptytag(ipse, name, attr) : pass
  def onopentag(ipse, name, attr) : pass
  def onclosetag(ipse, name, attr) : pass
  def onxmlattrset(ipse, data) : pass
  def ondoctype(ipse, data) : pass
  def onfragment(ipse, data) : pass
  def onentity(ipse, data) : pass

  def onto(ipse, event, data) :
    if event[-3:] == "tag" :
      super().onto(data["name"], event, data["attr"])
    else :
      super().onto(event, data)

    if ipse.debug :
      if event[-3:] == "tag" :
        ipse.info("%s %s %s"%(event, data["name"],data["attr"]))
      else :
        ipse.info("%s '%s'"%(event, data))

  def __call__(ipse) :
    sm =StateMachine(ipse.entres)
    sm.info =ipse.info
    sm.error =ipse.error
    sm.ci =ipse.inputs.ci
    sm.la =ipse.inputs.la
    sm.unget =ipse.inputs.unput
    sm.onto =ipse.onto
    return sm()

  def __init__(ipse) :
    super().__init__()
    ipse.inputs =inputstac()
    ipse.entres =entresolve()
    def onentity (ev, data) : 
      ipse.entres[data["name"]] =data["value"]
    ipse.on("entity", onentity)
    ipse.inputs.push(strinputbuf('<!ENTITY quot    "&#34;"><!ENTITY amp     "&#38;"><!ENTITY lt      "&#60;"><!ENTITY gt      "&#62;"><!ENTITY apos  "&#39;"><!ENTITY OElig   "&#338;"><!ENTITY oelig   "&#339;"><!ENTITY Scaron  "&#352;"><!ENTITY scaron  "&#353;"><!ENTITY Yuml    "&#376;"><!ENTITY circ    "&#710;"><!ENTITY tilde   "&#732;"><!ENTITY ensp    "&#8194;"><!ENTITY emsp    "&#8195;"><!ENTITY thinsp  "&#8201;"><!ENTITY zwnj    "&#8204;"><!ENTITY zwj     "&#8205;"><!ENTITY lrm     "&#8206;"><!ENTITY rlm     "&#8207;"><!ENTITY ndash   "&#8211;"><!ENTITY mdash   "&#8212;"><!ENTITY lsquo   "&#8216;"><!ENTITY rsquo   "&#8217;"><!ENTITY sbquo   "&#8218;"><!ENTITY ldquo   "&#8220;"><!ENTITY rdquo   "&#8221;"><!ENTITY bdquo   "&#8222;"><!ENTITY dagger  "&#8224;"><!ENTITY Dagger  "&#8225;"><!ENTITY permil  "&#8240;"><!ENTITY lsaquo  "&#8249;"><!ENTITY rsaquo  "&#8250;"><!ENTITY euro   "&#8364;">', "xhtml-special.ent"))
    ipse()

class legodoc (stac) :

  def __init__(ipse, debug=0) :
    super().__init__()
    ipse.debug =debug
    ipse.xmlp =xmlparse()
    ipse.xmlp.debug =ipse.debug
    ipse.on =ipse.xmlp.on
    ipse.no =ipse.xmlp.no
    ipse.onto =ipse.xmlp.onto
    ipse.zipglob =csglob("zip,od*")

  def pushfile(ipse, file_name) :
    if ipse.zipglob(file_name[-3:].lower()) :
      from zipfile import ZipFile
      ipse.xmlp.inputs.push(fileinputbuf(file_name, ZipFile(file_name).open("content.xml")))
    else :
      ipse.xmlp.inputs.push(fileinputbuf(file_name))

  def __call__(ipse, file_name) :
    ipse.pushfile(file_name)
    ipse.xmlp()

class odocstyledefs :

  @staticmethod
  def listdata(data) :
    d ={}
    for i in data :
      d[i["name"]] =i["value"]
    return d

  @staticmethod
  def dicdata(data) :
    d ={}
    for i in data :
      if i["name"] == "style:name" :
        name =i["value"]
      else :
        d[i["name"]] =i["value"]
    return name, d

  def __init__(ipse, lodt) :
    ipse.lodt =lodt
    ipse.fontface ={}
    ipse.style ={}
    ipse.lodt.on("office:font-face-decls", "opentag", ipse.fonttags)
    ipse.lodt.on("office:automatic-styles", "opentag", ipse.styletags)

  def curr(ipse, ev, data) :
    ipse.currstyle.update(ipse.listdata(data))

  def aper(ipse, ev, data) :
    ipse.lodt.on("*", "emptytag", ipse.curr)
    nom, dic =ipse.dicdata(data)
    ipse.currstylenom =nom
    ipse.currstyle =dic

  def ferm(ipse, ev, data) :
    ipse.style[ipse.currstylenom] =ipse.currstyle
    del ipse.currstylenom
    del ipse.currstyle
    ipse.lodt.no("*", "emptytag", ipse.curr)

  def styletags (ipse, ev,data) : 
    def em(ev, data) :
      nom, dic =ipse.dicdata(data)
      ipse.style[nom] =dic
    if ev == "opentag" :
      ipse.lodt.on("style:style", "emptytag", em)
      ipse.lodt.on("style:style", "opentag", ipse.aper)
      ipse.lodt.on("style:style", "closetag", ipse.ferm)
      ipse.lodt.no("office:automatic-styles", "opentag", ipse.styletags)
      ipse.lodt.on("office:automatic-styles", "closetag", ipse.styletags)
    elif ev == "closetag" :
      ipse.lodt.no("style:style", "emptytag", em)
      ipse.lodt.no("style:style", "opentag", ipse.aper)
      ipse.lodt.no("style:style", "closetag", ipse.ferm)
      ipse.lodt.no("office:automatic-styles", "closetag", ipse.styletags)
      
  def fonttags (ipse, ev, data) : 
    def em(ev, data) :
      nom, dic =ipse.dicdata(data)
      ipse.fontface[nom] =dic
    if ev == "opentag" :
      ipse.lodt.on("style:font-face", "emptytag", em)
      ipse.lodt.no("office:font-face-decls", "opentag", ipse.fonttags)
      ipse.lodt.on("office:font-face-decls", "closetag", ipse.fonttags)
    elif ev == "closetag" :
      ipse.lodt.no("style:font-face", "emptytag", em)
      ipse.lodt.no("office:font-face-decls", "closetag", ipse.fonttags)

  def __call__(ipse, data) :
    print(data)

class scribextern :

  def __init__(ipse) :
    ipse.lodt = legodoc(debug=1)
    ipse.lodt.on("*:body", "opentag", ipse.openbody)
    ipse.lodt.on("office:*", "opentag", ipse.openoffice)
    ipse.styl =odocstyledefs(ipse.lodt)

  def openbody (ipse, ev, data) : 
    print("OPENBODY")
    ipse.lodt.debug =2

  def openoffice (ipse, ev,data) : 
    print("OPENOFFICE")
    ipse.lodt.debug =0

  def __call__(ipse, fnom) :
    ipse.lodt(fnom)

#def odtread(file_name) :
  #se =scribextern()
  #se(file_name)
  #lodt = legodoc(debug=1)
  #lodt(file_name)
#from sys import argv
#odtread("doc/graphe-exo.odt")

