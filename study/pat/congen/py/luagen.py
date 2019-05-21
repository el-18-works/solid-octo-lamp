#!/usr/bin/env python3


class stac :

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


class argumentista (stac) :
  
  def __init__(ipse, tabchar="  ", debug=0) :
    super().__init__()
    ipse.tabchar =tabchar
    ipse.debug =debug
    ipse.push([])
    ipse.lis =[]
    
  def pushfun(ipse, nom) :
    ipse.top().append({"fr" : "function "+nom, "lis" : []})
    super().push([])
    if ipse.debug :
      print(ipse.top())

  def pop(ipse) :
    lis =super().pop()
    ipse.top()[-1]["lis"] =lis
    ipse.top().append({"fr" : "end"})

  def confun(ipse, nom, lis=None) :
    ipse.top().append({"fr" : "function "+nom, "lis" : [{"fr" : l} for l in lis]})
    ipse.top().append({"fr" : "end"})
    if ipse.debug :
      print(ipse.top())

  def con(ipse, fr, lis=None) :
    if lis != None :
      ipse.top().append({"fr" : fr, "lis" : [{"fr" : l} for l in lis]})
    else :
      ipse.top().append({"fr" : fr})
    if ipse.debug :
      print(ipse.top())

  def gen(ipse, out=None, nivel=0) :
    from sys import stdout
    def f(lis, nivel) :
      for lin in lis :
        ipse.out.write( ipse.tabchar*nivel + lin["fr"] + "\n")
        if "lis" in lin :
          f(lin["lis"], nivel+1)
    ipse.out =out or stdout
    f(ipse.top(), nivel)

from os import pipe, fdopen, popen, write
a =argumentista(debug=0)
a.pushfun("helloworld(x)")
a.con("print('¡hola el mundo, '..x..'!')")
a.pop()
#a.confun("helloworld(x)", [ "print('¡hola el mundo, '..x..'!')"])
a.con("helloworld('foo')")
a.gen()
a.gen(popen("lua", "w"))

