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
    ipse.tabchar =tabchar
    ipse.debug =debug
    ipse.lis =[]
    
  def confun(ipse, nom, lis=None) :
    ipse.lis.append({"fr" : "function "+nom, "lis" : [{"fr" : l} for l in lis]})
    ipse.lis.append({"fr" : "end"})
    if ipse.debug :
      print(ipse.lis)

  def confra(ipse, fr, lis=None) :
    if lis != None :
      ipse.lis.append({"fr" : fr, "lis" : [{"fr" : l} for l in lis]})
    else :
      ipse.lis.append({"fr" : fr})
    if ipse.debug :
      print(ipse.lis)

  def gen(ipse, out=None, nivel=0) :
    from sys import stdout
    def f(lis, nivel) :
      for lin in lis :
        ipse.out.write( ipse.tabchar*nivel + lin["fr"] + "\n")
        if "lis" in lin :
          f(lin["lis"], nivel+1)
    ipse.out =out or stdout
    f(ipse.lis, nivel)

from os import pipe, fdopen, popen, write
a =argumentista(debug=0)
a.confun("helloworld(x)", [ "print('¡hola el mundo, '..x..'!')"])
a.confra("helloworld('foo')")
#a.gen()
a.gen(popen("lua", "w"))

