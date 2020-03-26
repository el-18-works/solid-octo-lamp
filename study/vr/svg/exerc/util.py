#!/usr/bin/env python3

def size(fnom) :
  from PIL import Image
  with Image.open(fnom) as im :
    return im.size

def svg(w, h, b=2) :
  svg ={}, []
  svg[0]['xmlns'] ="http://www.w3.org/2000/svg" 
  svg[0]['xmlns:xlink'] ="http://www.w3.org/1999/xlink"
  svg[0]['width']=w
  svg[0]['height']=h

  brn ={}
  brn['x'] =brn['y'] =b/2
  brn['width'] =svg[0]['width']-b
  brn['height'] =svg[0]['height']-b
  brn['fill'] ="rgba(255,255,255,0)" 
  brn['stroke'] ="rgba(200,10,10,10)" 
  brn['stroke-width'] =b
  svg[1].append(('rect', (brn,)))

  return svg

def calc(fnom, m=10, b=2, l=5, opacity=0.6) :

  w,h =size(fnom)

  #svg =({}, [])

  stylet ="rgba(10,100,10,20)"
  fontSize =m*0.6666
  style="stroke:rgba(10,200,10,0.3); stroke-width:0.1"
  style5="stroke:rgba(200,10,10,0.3); stroke-width:0.1"
  style10="stroke:rgba(10,100,100,0.3); stroke-width:0.6"
  def styleattr(i) :
    if i%10 == 0 : return style10
    elif i%5 == 0 : return style5
    else : return style

  def gv() :
    ge =[]
    for i in range(int(h/l)) :
      if i%10 == 0 and i :
        a ={'fill':stylet, 'font-size':fontSize}
        a['transform'] ="rotate(-90 %s,%s)"%(m*0.75, m+i*l)
        a['x'] =m*0.75
        a['y'] =m+i*l
        ge.append(('text', (a, i)))
      a ={'x1':m, 'x2':h+m}
      a['y1'] =a['y2'] =m+i*l
      a['style'] =styleattr(i)
      ge.append(('line', (a,)))
    return ge
  def gh() :
    ge =[]
    for i in range(int(w/l)) :
      if i%10 == 0 and i :
        a ={'fill':stylet, 'font-size':fontSize}
        a['x'] =m+i*l
        a['y'] =m*0.75
        ge.append(('text', (a, i)))
      a ={'y1':m, 'y2':h+m}
      a['x1'] =a['x2'] =m+i*l
      a['style'] =styleattr(i)
      ge.append(('line', (a,)))
    return ge

  g =[]

  with open(fnom, 'rb') as fp :
    from base64 import b64encode
    bdata =b64encode(fp.read()).decode()
    url ='data:image/png;base64,' + bdata
    a ={'x':m/2, 'y':m/2, 'height':w, 'width':h}
    a['xlink:href'] =url
    a['style'] ="opacity:%s"%opacity
    g.append(('image', a))

  g.append(('g', ({}, gh())))
  g.append(('g', ({}, gv())))

  return {}, g


def echosvg(svg, ofnom) :
  fd =open(ofnom, 'w')
  def f(tag, e) :
    if type(e) != tuple : e =e,
    s ='<%s'%( 
    tag if not e[0] else 
    tag + ' ' + ' '.join( i+"='%s'"%j for i,j in e[0].items()) )
    if len(e)<2 : 
      fd.write(s+' />')
    else :
      fd.write(s+'>')
      if type(e[1]) == list :
        for i in e[1] :
          if type(i) == tuple :
            if len(i) == 1 :
              fd.write('<%s />'%i)
            else :
              f(i[0], i[1])
          else :
            fd.write(str(i))
      else :
        fd.write(str(e[1]))
      fd.write('</%s>'%tag)
  f('svg', svg)
  fd.close()

class Path :

  def __init__(ipse) :
    ipse._d =[]
    ipse.instr =[]

  def xy(ipse, a) :
    for i,x in enumerate(a) :
      ipse._d.append(str(x * ipse.k))

  def XY(ipse, a) :
    for i,x in enumerate(a) :
      if i%2 :
        ipse._d.append(str(ipse.y + x * ipse.k))
      else :
        ipse._d.append(str(ipse.x + x * ipse.k))

  def M(ipse, *a) :
    ipse.instr.append(['M', a])

  def L(ipse, *a) :
    ipse.instr.append(['L', a])

  def l(ipse, *a) :
    ipse.instr.append(['l', a])

  def C(ipse, *a) :
    ipse.instr.append(['C', a])

  def c(ipse, *a) :
    ipse.instr.append(['c', a])

  def S(ipse, *a) :
    ipse.instr.append(['S', a])

  def Q(ipse, *a) :
    ipse.instr.append(['Q', a])

  def T(ipse, *a) :
    ipse.instr.append(['T', a])

  def Z(ipse) :
    ipse.instr.append(['Z', []])

  def __str__(ipse) :
    return '<path d="%s" %s />'%(' '.join(ipse._d), ipse.att)

  def d(ipse, x=0, y=0, k=1) :
    ipse.x, ipse.y, ipse.k =x, y, k
    ipse._d =[]
    for x, a in ipse.instr :
      ipse._d.append(x)
      if x.isupper() :
        ipse.XY(a)
      else :
        ipse.xy(a)
    return ' '.join(ipse._d)

if __name__ == "__main__" :
  imfnom ="koume01.png"
  ofnom ="cache/calc.svg"
  svg =calcsvg(imfnom)
  echosvg(svg, ofnom)


