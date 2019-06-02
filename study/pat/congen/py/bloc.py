#!/usr/bin/env python3

class stac :

  def __init__(ipse) :
    ipse.__a =[]

  def __len__(ipse) :
    return len(ipse.__a)

  def push(ipse, x) :
    ipse.__a.append(x)

  def pop(ipse) :
    x =ipse.__a[-1]
    del ipse.__a[-1]
    return x

  def top(ipse) :
    return ipse.__a[-1]

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
#
# Input Buffer
#
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
    return ipse.file_nom + ":%d:%d:"%ipse.posij
  
  def __init__(ipse, file_nom, input=None) :
    ipse.file_nom =file_nom
    ipse.input =input or open(file_nom)
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
    return ipse.file_nom + " (%d)"%ipse.i
  
  def __init__(ipse, buf, nom=None, de=0, ad=-1) :
    super().__init__()
    ipse.buf =buf
    ipse.i, ipse.ad =de, ad if ad >= 0 else len(buf)+ad+1
    ipse.file_nom =nom or ipse.buf[:12]+ ("..." if len(ipse.buf) > 12 else "")

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
    for a,b in ipse.aila.items() :
      if a == ev :
        for c in b :
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
      for a in ipse.eventaila.values() :
        if a.match(tag) :
          a.onto(ev, data)
    elif len(e) == 2 :
      ev, data =e
      super().onto(ev, data)


class trunit (stac) :

  def __init__(ipse, comment=None) :
    super().__init__()
    ipse.comment =comment

  def error(ipse, msg) :
    print(msg)
    exit(1)

  def __call__(ipse, unitnom, cb) :
    def resettabs(l) :
      for i in range(len(l)) :
        if not l[i].isspace() :
          return l[:i], l[i:]
      return l,""
    for i,l in enumerate(open(unitnom)) :
      if i == 0 :
        ipse.push('')
        cb("pushunit", None)
        cb("push", l.strip())
        continue
      tabs,frag =resettabs(l.rstrip())
      if ipse.comment != None and (not frag or ipse.comment(frag) and tabs != ipse.top()) :
        cb("comment", frag)
      else :
        while len(tabs) < len(ipse.top()) :
          if ipse.top()[:len(tabs)] != tabs :
            ipse.error("%d : '%s' : (pop) inconsistent shift spaces/tabs"%(i+1, l))
          ipse.pop()
          cb("pop", None)
        if tabs != ipse.top() :
          if len(tabs) > len(ipse.top()) :
            if tabs[:len(ipse.top())] != ipse.top() :
              ipse.error("%d : '%s' : (push) inconsistent shift spaces/tabs"%(i+1, l))
            ipse.push(tabs)
            cb("push", frag)
          else :
            ipse.error("%d : '%s' : inconsistent shift spaces/tabs"%(i+1, l))
        else :
          cb("frag", frag)

    while len(ipse) :
      ipse.pop()
      cb("pop", None)
    cb("popunit", None)
    
class blocarbor(stac) :

  def error(ipse, msg) :
    print(msg)
    exit(1)

  def __call__(ipse, unitnom) :
    ipse.unitnom =unitnom
    tf =trunit(comment=csglob("#*,//*"))
    tf(unitnom, ipse.arbor)
    return ipse.radix

  def arbor(ipse, e, data) :
    if e == "pushunit" :
      ipse.push({"clav" : "", "bloc":[{'clav':'decl', 'data':"unit :"}]})
    elif e == "push" :
      clav =value =""
      udata =ipse.top()["bloc"][-1]['data']
      if udata :
        i =0
        while udata[i] not in ('(', ':') and not udata[i].isspace() :
          i +=1
        clav =udata[:i]
        if clav in ("for", "while", "if", "elif", "else") :
          value =""
        elif clav in ("def", "class") :
          while udata[i].isspace() :
            i +=1
          j =i
          while udata[j] not in ('(', ':') and not udata[j].isspace() :
            j +=1
          value =udata[i:j]
        elif clav == "unit" :
          value =ipse.unitnom
        else :
          ipse.error("irrecognita clavis '%s'"%clav)
      ipse.push({"clav":clav, "nom":value, "bloc":[{'clav':'decl', 'data':udata}, {'clav':'frag', 'data':data}]})
    elif e == "frag" :
      ipse.top()["bloc"].append({'clav':'frag', 'data':data})
    elif e == "pop" :
      data =ipse.pop()
      if data["clav"] == "unit" :
        ipse.radix =data
      ipse.top()["bloc"][-1] =data
    elif e == "comment" :
      pass
    elif e == "popunit" :
      ipse.pop()

class pyunitbloc :

  def __init__(ipse) :
    ipse.unit ={}
    ipse.bt =blocarbor()

  def __call__(ipse, nom) :
    if nom not in ipse.unit :
      ipse.unit[nom] ={}
      for l in ipse.bt(nom)["bloc"][1:] :
        if l["clav"] in ("class", "def") :
          ipse.unit[nom][l["nom"]] =l["bloc"]
    return ipse.unit[nom]

class pyitem :

  def __init__(ipse, tabchr="  ", tabshft=0) :
    ipse.fl =pyunitbloc()
    ipse.tabchr =tabchr
    ipse.tabshft =tabshft
    
  def write(ipse, out, unitnom, itnom, tabshft=0) :
    itglob =csglob(itnom)
    def f(data, tabshft) :
      if data['clav'] == 'decl' :
        out.write(ipse.tabchr*(ipse.tabshft+tabshft) + data['data'] + '\n')
      elif data['clav'] == 'frag' :
        out.write(ipse.tabchr*(ipse.tabshft+tabshft+1) + data['data'] + '\n')
      elif 'bloc' in data :
        for x in data['bloc'] :
          f(x, tabshft+1)
    out.write('\n')
    for clav,blc in ipse.fl(unitnom).items() :
      if itglob(clav) :
        for data in blc :
          f(data, tabshft)
    out.write('\n')

  def ls(ipse, unitnom) :
    return tuple(ipse.fl(unitnom).keys())

  def decl(ipse, unitnom, itnom) :
    s =ipse.fl(unitnom)[itnom][0]['data']
    return s.rstrip()[:-1].rstrip()

from sys import stdout
pyitem().write(stdout,__file__, "pyunitbloc")
pyitem().write(stdout,__file__, "pyitem")
print(pyitem().decl(__file__, "pyitem"))
print(pyitem().ls(__file__))
#print(fl(__file__, "pyfilebloc"))

