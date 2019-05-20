#!/usr/bin/env python3

class stack :

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

#
#
# Input Buffer
#
#

class token :

  def info(ipse, arg) :
    print("%s %s"%(ipse.pos(), arg))

  def error(ipse, arg) :
    print("%s %s"%(ipse.pos(), arg))
    exit(-1)

  def unput(ipse, c) :
    ipse.unput_buffer =c

  def ununput(ipse) :
    if ipse.unput_buffer != None :
      yield ipse.unput_buffer
      ipse.unput_buffer =None

  def put(ipse) :
    ipse.ununput()
    yield c

  def pos(ipse) :
    return ""

class filetoken (token) :

  def put(ipse) :
    for i,l in enumerate(ipse.input) :
      for j,c in enumerate(l) :
        ipse.posij =i,j
        ipse.ununput()
        yield c

  def pos(ipse) :
    return ipse.file_name + ":%d:%d:"%ipse.posij
  
  def __init__(ipse, file_name, input=None) :
    ipse.file_name =file_name
    ipse.input =input

class binfiletoken (filetoken) :

  def put(ipse) :
    for i,l in enumerate(ipse.input) :
      for j,c in enumerate(l.decode()) :
        ipse.posij =i,j
        ipse.ununput()
        yield c

class strtoken (token) :

  def put(ipse) :
    while ipse.i < ipse.ad :
      ipse.ununput()
      yield ipse.buf[ipse.i]

  def pos(ipse) :
    return ipse.buf+"(%d)"%ipse.i
  
  def __init__(ipse, buf, de=0, ad=-1) :
    ipse.buf =buf
    ipse.i =de
    ipse.ad =ad

class tokenstack(stack) :
  
  def put(ipse) :
    while len(ipse) :
      for t in ipse.pop() :
        for c in t :
          yield c

