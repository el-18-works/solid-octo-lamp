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

#
#
# Parse
#
#

class metisparse(stack) :

# 0,14,15,16 \s - :
# 0 s 1 : shft
# 1 c 2 : shft
# 3 a 4 : shft
# 4 t 5 : shft
# 5 \s <<4 : red SCAT
# 0 g 6 : shft
# 6 r 7 : shft
# 7 a 8 : shft
# 8 m 9 : shft
# 9 \s <<4 : red GRAM
# 10 l 11 : shft
# 11 e 12 : shft
# 12 x 13 : shft
# 13 . <<3 : red LEX
# 0 SCAT 14 : shft
# 0 GRAM 15 : shft
# 0 LEX 16 : shft

# 100 " 102 : shft; l =""
# 101 ' 103 : shft; l =""
# 102 " <<2 : red LIT(l); del l
# 103 ' <<2 : red LIT(l); del l
# 102,103 & 104 : shft; ei =0; l +=i
# 102,103 ENT - : l =l[:-ei] + entresolv(l[-ei+1:])
# 102,103 . - : shft; l +=i
# 104 ; 105 : red ENT
# 104 \w - : shft; ei++; l +=i
# 104 . <<1 : shft; del ei; l +=i
# 105 . <<2 : red ENT


