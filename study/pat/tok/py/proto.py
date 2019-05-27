#!/usr/bin/env python3

class stac :

  def __init__(ipse) :
    ipse.a =[]
    ipse.__len__ =ipse.a.__len__  

  def push(ipse, x) :
    ipse.a.append(x)

  def pop(ipse) :
    x =ipse.a[-1]
    del ipse.a[-1]
    return x

  def top(ipse) :
    return ipse.a[-1]

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

