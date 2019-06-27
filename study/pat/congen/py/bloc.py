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


class stacarbor (stac) :

  def __init__(ipse, comment=None) :
    super().__init__()
    ipse.comment =comment

  def error(ipse, msg) :
    print(msg)
    exit(1)

  def __call__(ipse, inputiter, fasc, pos) :
    def resettabs(l) :
      for i in range(len(l)) :
        if not l[i].isspace() :
          return l[:i], l[i:]
      return l,""
    fragcont =None
    i =0
    for l in inputiter :
      i +=1
      pos(i)
      if len(l)>1 and l[-2] == '\\' :
        fragcont =l
        continue
      if fragcont != None :
        l =fragcont + l
        fragcont =None
      if i == 1 :
        ipse.push('')
        fasc("pushunit", None)
        fasc("push", "")
      tabs,frag =resettabs(l.rstrip())
      if not frag or ipse.comment != None and ipse.comment(l) and (len(tabs) == 0 or tabs != ipse.top()) :
        fasc("comment", frag)
      else :
        while len(tabs) < len(ipse.top()) :
          if ipse.top()[:len(tabs)] != tabs :
            ipse.error("%d : '%s' : (pop) inconsistentes numeri tablationum"%(i+1, l))
          ipse.pop()
          fasc("pop", None)
        if tabs != ipse.top() :
          if len(tabs) > len(ipse.top()) :
            if tabs[:len(ipse.top())] != ipse.top() :
              ipse.error("%d : '%s' : (push) inconsistentes numeri tablationum"%(i+1, l))
            ipse.push(tabs)
            fasc("push", frag)
          else :
            ipse.error("%d : '%s' : inconsistentes numeri tablationum"%(i+1, l))
        else :
          fasc("frag", frag)

    while len(ipse) :
      ipse.pop()
      fasc("pop", None)
    fasc("popunit", None)
    
class blocarbor(stac) :

  def error(ipse, msg) :
    print("%s:%d: %s"%(ipse.unitnom, ipse.lnum, msg))
    exit(1)

  def pos(ipse, lnum) :
    ipse.lnum =lnum

  def arbor(ipse, iterinput, comment="") :
    ipse.unitnom ="*"
    stacarbor(comment=csglob(comment))(iterinput, ipse.fasc, ipse.pos)
    return ipse.radix

  def unitarbor(ipse, unitnom, comment="") :
    ipse.unitnom =unitnom
    return ipse.arbor(open(unitnom), comment)

class mkarbor(blocarbor) :

  def __call__(ipse, iterinput) :
    return ipse.arbor(iterinput)

  def unit(ipse, unitnom) :
    return ipse.unitarbor(unitnom, "#*,*##,--*")

  def fasc(ipse, e, data) :
    if e == "pushunit" :
      ipse.push({"clav" : "", "bloc":[{'clav':'cap', 'data':"unit :"}]})
    elif e == "push" :
      clav =value =""
      udata =ipse.top()["bloc"][-1]['data']
      if udata :
        i =0
        while len(udata)>i and udata[i] not in ('(', ':') and not udata[i].isspace() :
          i +=1
        clav =udata[:i]
        if clav in ("unit", "define", "endef", "ifeq", "ifneq", "ifdef", "else", "endif") :
          value =""
        else :
          clav ="dep"
          value =udata
      ipse.push({"clav":clav, "nom":value, "bloc":[{'clav':'cap', 'data':udata}, {'clav':'frag', 'data':data}]})
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

class pyarbor(blocarbor) :

  def __call__(ipse, iterinput) :
    return ipse.arbor(iterinput, "#*,*##,//*")

  def unit(ipse, unitnom) :
    return ipse.unitarbor(unitnom, "#*,*##,//*")

  def fasc(ipse, e, data) :
    if e == "pushunit" :
      ipse.push({"clav" : "", "bloc":[{'clav':'cap', 'data':"unit :"}]})
    elif e == "push" :
      clav =value =""
      try : udata =ipse.top()["bloc"][-1]['data']
      except Exception as e : ipse.error(e)
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
      ipse.push({"clav":clav, "nom":value, "bloc":[{'clav':'cap', 'data':udata}, {'clav':'frag', 'data':data}]})
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

class unitbloc :

  def __init__(ipse, ba) :
    ipse.__unit ={}
    ipse.ba =ba

  def radix(ipse, nom, iterinput=None) :
    if nom not in ipse.__unit :
      if iterinput == None :
        ipse.__unit[nom] =ipse.ba.unit(nom)
      else :
        ipse.__unit[nom] =ipse.ba(iterinput)
    return ipse.__unit[nom]

class unitmkbloc (unitbloc) :

  def __init__(ipse) :
    super().__init__(mkarbor())
    ipse.__unit ={}

  def item(ipse, nom, iterinput=None) :
    if nom not in ipse.__unit :
      ipse.__unit[nom] ={}
      for l in ipse.radix(nom, iterinput)["bloc"][1:] :
        if l["clav"] == "dep" :
          ipse.__unit[nom][l["nom"]] =l["bloc"]
    return ipse.__unit[nom].items()

class unitpybloc (unitbloc) :

  def __init__(ipse) :
    super().__init__(pyarbor())
    ipse.__unit ={}

  def item(ipse, nom, iterinput=None) :
    if nom not in ipse.__unit :
      ipse.__unit[nom] ={}
      for l in ipse.radix(nom, iterinput)["bloc"][1:] :
        if l["clav"] in ("class", "def") :
          ipse.__unit[nom][l["nom"]] =l["bloc"]
    return ipse.__unit[nom].items()

class pyecho :
  def __init__(ipse, pat, de ,ad=None) :
    ipse.glob =csglob(pat)
    ipse.de =de
    ipse.ad =ad
    ipse.globals ={}

  def __call__(ipse, s) :
    if ipse.glob(s) :
      if ipse.ad == None :
        return exec(s[ipse.de:], ipse.globals)
      else :
        return exec(s[ipse.de:ipse.ad], ipse.globals)
    else :
      return s

class pmunit :

  def write(ipse, out, data, tabshft) :

    def f(data, tabshft, spatpre=0) :

      if data['clav'] in ipse.spatpost :
        spatpost =ipse.spatpost[data['clav']]
      else :
        spatpost =0
      if data['clav'] in ipse.spatpre :
        spatpre =max(spatpre, ipse.spatpre[data['clav']])
      if 'bloc' not in data and spatpre != 0 :
        out.write('\n' * spatpre)

      if data['clav'] == 'cap' :
        if tabshft >= 0 :
          out.write(ipse.tabchr*(ipse.tabshft+tabshft) + data['data'] + '\n')
      elif data['clav'] == 'frag' :
        out.write(ipse.tabchr*(ipse.tabshft+tabshft+1) + ipse.pyecho(data['data']) + '\n')
      elif 'bloc' in data :
        for i,x in enumerate(data['bloc']) :
          if i == len(data['bloc']) - 1 :
            spatpost =max(spatpost, f(x, tabshft+1, spatpre))
          else :
            spatpre =f(x, tabshft+1, spatpre)
      return spatpost
    f(data, tabshft)
    
  def writeradix(ipse, out, unitnom, inputiter=None) :
    ipse.write( out, ipse.ub.radix(unitnom, inputiter), -2 )

  def writeitem(ipse, out, itnom, unitnom, inputiter=None, tabshft=0) :
    itglob =csglob(itnom)
    for clav,blc in ipse.ub.item(unitnom, inputiter) :
      if itglob(clav) :
        for data in blc :
          ipse.write(out, data, tabshft)

  def ls(ipse, unitnom, inputiter=None) :
    return tuple(k for k,v in ipse.ub.item(unitnom, inputiter))

  def cap(ipse, itnom, unitnom, inputiter=None) :
    for k,v in ipse.ub.item(unitnom, inputiter) :
      if k == itnom :
        s =v[0]['data']
        return s.rstrip()[:-1].rstrip()

class unitpy (pmunit) :

  def __init__(ipse, tabchr="  ", tabshft=0) :
    ipse.ub =unitpybloc()
    ipse.tabchr =tabchr
    ipse.tabshft =tabshft
    ipse.spatpre ={"def":2, "class":3}
    ipse.spatpost ={"def":1, "class":2}
    ipse.pyecho =pyecho("#echo *", 6)

class unitmk (pmunit) :

  def __init__(ipse, tabchr="\t", tabshft=0) :
    ipse.ub =unitmkbloc()
    ipse.tabchr =tabchr
    ipse.tabshft =tabshft
    ipse.spatpre ={} # "dep", 
    ipse.spatpost ={} #"dep",
    ipse.pyecho =pyecho("#echo *", 6)

if __name__ == "__main__" :
  from sys import stdout, argv
  if len(argv) == 2 and argv[1] == "test" :
    while 1 :
      print("\n".join(unitpy().ls(__file__)))
      it =input(">> ")
      if it :
        print(unitpy().cap(it, __file__))
        input(">> ")
        unitpy().writeitem(stdout, it, __file__)
      else :
        unitpy().writeradix(stdout, __file__)
      input(">> ")

