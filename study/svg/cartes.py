#!/usr/bin/python3

import sys
sys.path.append("cache")

# spade, 0 heart, 0 diamond, club
# 0 spade, heart, diamond, 0 club
from cartes_data import element_path, figure_path, etc_path

from PIL import Image
from PIL.ImageDraw import ImageDraw


class Stack :
	def __init__(ipse) :
		ipse.a =[]

	def push(ipse, i) :
		ipse.a.append(i)
		return len(ipse.a)

	def pop(ipse) :
		i =ipse.a[-1]
		del ipse.a[-1]
		return i

class Tokenize (Stack) :

	def error(ipse, message) :
			print("Tokenize : %s"%message)
			exit(1)
	def __call__(ipse, buf) :
# S 0
# neg 1
# pos 2
# int part 3
# frac part 4
		ipse.push(0)
		for c in buf :
			s =ipse.pop()
			if s == 0 :
				if c == "-" :
					ipse.push(0)
					ipse.push(0)
					ipse.push(1)
				elif c == "+" :
					ipse.push(0)
					ipse.push(0)
					ipse.push(2)
				elif c >= "0" and c <= "9" :
					ipse.push(0)
					ipse.push(int(c))
					ipse.push(3)
				elif c in (" ","\t") :
					ipse.push(0)
				elif c in ("l","z","t","m","c","s","q","v","h","L","Z","T","M","C","S","Q","V","H") :
					yield c
					ipse.push(0)
			elif s in (1,2) :
				if c >= "0" and c <= "9" :
					ipse.push(s)
					ipse.push(int(c))
					ipse.push(3)
				else :
					ipse.error("a digit expected")
			elif s == 3 :
				if c >= "0" and c <= "9" :
					ipse.push(ipse.pop()*10+int(c))
					ipse.push(s)
				elif c == "." :
					ipse.push(ipse.pop())
					ipse.push(0)
					ipse.push(4)
				else :
					num =ipse.pop()
					s =ipse.pop()
					if s == 0 : 
						ipse.push(s)
					if s == 1 : 
						num =-num
					yield num
					if c in (" ","\t") :
						ipse.push(0)
					elif c in ("l","z","t","m","c","s","q","v","h","L","Z","T","M","C","S","Q","V","H") :
						yield c
						ipse.push(0)
					else :
						ipse.error("unknown token '%c'"%c)	
			elif s == 4 :
				if c >= "0" and c <= "9" :
					fraclen =ipse.pop()
					ipse.push(ipse.pop()*10+int(c))
					ipse.push(fraclen+1)
					ipse.push(s)
				elif c == "." :
					ipse.error("duplicate decimal point")	
				else :
					num =0.1**ipse.pop()*ipse.pop()
					s =ipse.pop()
					if s == 0 : 
						ipse.push(s)
					if s == 1 : 
						num =-num
					yield num
					if c in (" ","\t") :
						ipse.push(0)
					elif c in ("l","z","t","m","c","s","q","v","h","L","Z","T","M","C","S","Q","V","H") :
						yield c
						ipse.push(0)
					else :
						ipse.error("unknown token '%c'"%c)	
		yield "$"

class Parse (Stack) :

	def error(ipse, message) :
			print("Parse : %s"%message)
			exit(1)
	def __call__(ipse, buf) :
# S 0
# M 1 x 2 y <
# C 3 x1 4 y1 5 x2 6 y2 7 x 8 y <
# S 9 x2 10 y2 11 x 12 y <
# V 13 y <
# H 14 x <
# L 15 x 16 y <
# Q 17 x1 18 y1 19 x 20 y <
# T 21 x 22 y <
# Z <
		R =2,8,12 #...
		tokenize =Tokenize()
		token =None
		for la in tokenize(buf) :
			#print(token,la)
			if token == None :
				ipse.push(0)
			elif type(token) in (int,float) :
				s =ipse.pop()
				if s == 0 :
					ipse.error("number not expected")
				elif s == 2 :
					y,x,cmd =token, ipse.pop(), ipse.pop()
					yield cmd,x,y
				elif s == 8 :
					y,x,y2,x2,y1,x1,cmd =token, ipse.pop(), ipse.pop(), ipse.pop(), ipse.pop(), ipse.pop(), ipse.pop()
					yield cmd,x1,y1,x2,y2,x,y
				elif s == 12 :
					y,x,y2,x2,cmd =token, ipse.pop(), ipse.pop(), ipse.pop(), ipse.pop()
					yield cmd,x2,y2,x,y
				elif s == 13 :
					y,cmd =token,  ipse.pop()
					yield cmd,y
				elif s == 14 :
					x,cmd =token,  ipse.pop()
					yield cmd,x
				elif s == 16 :
					y,x,cmd =token, ipse.pop(),ipse.pop()
					yield cmd,x,y
				elif s == 20 :
					y,x,y1,x1,cmd =token, ipse.pop(), ipse.pop(), ipse.pop(), ipse.pop()
					yield cmd,x1,y1,x,y
				elif s == 22 :
					y,x,cmd =token, ipse.pop(),ipse.pop()
					yield cmd,x,y
				else :
					ipse.push(token)
					ipse.push(s+1)
			elif token.lower() == "m" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				ipse.push(0)
				ipse.push(token)
				ipse.push(1)
			elif token.lower() == "c" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				ipse.push(0)
				ipse.push(token)
				ipse.push(3)
			elif token.lower() == "s" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				ipse.push(0)
				ipse.push(token)
				ipse.push(9)
			elif token.lower() == "v" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				ipse.push(0)
				ipse.push(token)
				ipse.push(13)
			elif token.lower() == "h" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				ipse.push(0)
				ipse.push(token)
				ipse.push(14)
			elif token.lower() == "l" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				ipse.push(0)
				ipse.push(token)
				ipse.push(15)
			elif token.lower() == "q" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				ipse.push(0)
				ipse.push(token)
				ipse.push(17)
			elif token.lower() == "t" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				ipse.push(0)
				ipse.push(token)
				ipse.push(21)
			elif token.lower() == "z" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				yield token,
				ipse.push(0)
			elif token.lower() == "$" :
				s =ipse.pop()
				if s != 0 : ipse.error("unexpected token '%s'"%token)
				break
			else :
				ipse.error("unknown token '%s'"%token)
			token =la

class Generate :

	def __init__(ipse, path) :
		ipse.path =path
		mx,my =ipse.bbox()
		ipse.origin =mx[1]+(mx[0]-mx[1])/2,my[1]+(my[0]-my[1])/2
		ipse.side =max(mx[0]-mx[1], my[0]-my[1])

	def upper(ipse) :
		p =Parse()
		cpy =""
		xy0 =0,0
		for code in p(ipse.path) : 
			if code[0].islower() :
				b =[code[0].upper()]
				if code[0] == "h" :
					b.append(code[1]+xy[0])
					xy =code[1]+xy[0],xy[1]
				elif code[0] == "v" :
					b.append(code[1]+xy[1])
					xy =xy[0],code[1]+xy[1]
				else :
					for i in range(1,len(code),2) :
						xy =code[i:i+2]
						xy =xy0[0]+xy[0], xy0[1]+xy[1]
						b +=xy
				code =b
			else :
				xy =code[-2:]
			yield code
			xy0 =xy

	def error(ipse, message) :
		print("Generate : %s"%message)
		exit(1)

	def clone(ipse, mapping=lambda a : a) :
		p =Parse()
		cpy =""
		for code in ipse.upper() : 
			if code[0].islower() :
				ipse.error(code[0])
			cpy +=code[0]
			def form6(n) :
				if type(n) == int : return str(n)
				r =format("%.6f"%n) 
				while r[-1] == "0" : r =r[:-1]
				return r
			if code[0].lower() == "h" :
				cpy +=form6(mapping((code[1],0))[0])
			elif code[0].lower() == "v" :
				cpy +=form6(mapping((0,code[1]))[1])
			else :
				cpy +=" ".join(form6(a) for a in mapping(code[1:]))
		return cpy

	def __call__(ipse, origin, mat2) :
		def f(a) :
			b =tuple()
			for i in range(0,len(a),2) :
				xy =a[i:i+2]
				xy =xy[0]-ipse.origin[0], xy[1]-ipse.origin[1]
				xy =mat2[0]*xy[0]+mat2[1]*xy[1], mat2[2]*xy[0]+mat2[3]*xy[1]
				xy =xy[0]+origin[0], xy[1]+origin[1]
				xy =xy[0],xy[1]
				b +=xy
			return b
		return ipse.clone(f)

	def bbox(ipse) :
		p =Parse()
		x,y =0,0
		from math import inf
		mx,my =(0,inf), (0,inf)
		for q in p(ipse.path) :
			cmd =q[0]
			if cmd.lower() == "z" :
				pass
			elif cmd.lower() == "h" :
				if cmd.islower() :
					x =x+q[-1]
				else :
					x =q[-1]
			elif cmd.lower() == "v" :
				if cmd.islower() :
					y =y+q[-1]
				else :
					y =q[-1]
			else :
				if cmd.islower() :
					x,y =x+q[-2],y+q[-1]
				else :
					x,y =q[-2:]
			mx,my =(max(x,mx[0]),min(x,mx[1])), (max(y,my[0]),min(y,my[1]))
		return mx,my

	def wireframe(ipse, margin=150) :
		mx,my =ipse.bbox()
		sz =int(sum(mx))+margin*2, int(sum(my))+margin*2
		im =Image.new(mode ="RGB", size =sz, color=(255,255,255))
		draw =ImageDraw(im)
		draw.rectangle(xy=(margin,margin,int(sum(mx))+margin,int(sum(my))+margin), outline=(0,0,255))
		p =Parse()
		x,y =0,0
		for q  in p(ipse.path) :
			cmd =q[0]
			x0,y0 =x,y
			if cmd.lower() == "m" :
				if cmd.islower() :
					x,y =x+q[-2],y+q[-1]
				else :
					x,y =q[-2:]
				m =x,y
				continue
			elif cmd.lower() == "z" :
				x,y =m
			elif cmd.lower() == "h" :
				if cmd.islower() :
					x =x+q[-1]
				else :
					x =q[-1]
			elif cmd.lower() == "v" :
				if cmd.islower() :
					y =y+q[-1]
				else :
					y =q[-1]
			else :
				if cmd.islower() :
					x,y =x+q[-2],y+q[-1]
				else :
					x,y =q[-2:]
			#print((x,y,x0,y0))
			draw.line((x+margin,y+margin,x0+margin,y0+margin), fill=(0,0,0))
			#draw.line((x,y,x0,y0), fill=(0,0,0))
		draw.ellipse(xy=(x-2+margin,y-2+margin,x+2+margin,y+2+margin), fill=(0,255,0))
		x,y =ipse.origin
		draw.ellipse(xy=(x-2+margin,y-2+margin,x+2+margin,y+2+margin), fill=(255,0,0))
		#draw.ellipse(xy=(x-2,y-2,x+2,y+2), fill=(255,0,0))
		return im


white ="#fffdfa"
red ="#fa0a0a"
black ="#000000"
theme ="#210126"

class Card :

	def __init__(ipse, figure, freq, rratio=0.05) :
		ipse.figure =figure
		ipse.freq =freq
		ipse.rratio =rratio
		if figure == "spade" :
			ipse.path =element_path[1]
			ipse.open_path =element_path[5]
			ipse.ace_path =etc_path[0]
			ipse.persona_path =etc_path[10+4], etc_path[10+1], etc_path[10]
			ipse.color =black
		elif figure == "heart" :
			ipse.path =element_path[6]
			ipse.open_path =element_path[2]
			ipse.ace_path =etc_path[1]
			ipse.persona_path =etc_path[10+4], etc_path[10+1], etc_path[10]
			ipse.color =red
		elif figure == "diamond" :
			ipse.path =element_path[7]
			ipse.open_path =element_path[3]
			ipse.ace_path =etc_path[2]
			ipse.persona_path =etc_path[4+4], etc_path[4+1], etc_path[4]
			ipse.color =red
		elif figure == "club" :
			ipse.path =element_path[4]
			ipse.open_path =element_path[8]
			ipse.ace_path =etc_path[3]
			ipse.persona_path =etc_path[4+4], etc_path[4+1], etc_path[4]
			ipse.color =black
		else :
			ipse.error("unknown figure %s"%figure)

	def __call__(ipse, width, num, element_scale=1, ace_scale=2, prefix="") :
# gr =1.618
# poker size 2.5 x 3.5 inches (64x89mm), aka B8 size.
# bridge size 2.25 x 3.5 inches (57 x 89mm)
		if num == 0 :
			return ipse.back(width, prefix)
		rsize =width*ipse.rratio
		size =width, width/5*7
		svg ='<svg width="%f" height="%f" id="%s%s%d">\n'%(size[0], size[1], prefix, ipse.figure, num)
		svg +='<rect x="0" y="0" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(size[0])+'" height="'+str(size[1])+'" stroke="'+black+'" fill="'+white+'" />\n';
		length =size[0]/ipse.freq[0],size[1]/ipse.freq[1]

		ge =Generate(ipse.path)
		ppath =None
		if num in (11,12,13) :
			w =size[0]*0.6
			h =size[1]*0.8
			x =(size[0]-w)/2
			y =(size[1]-h)/2
			svg +='<rect x="'+str(x)+'" y="'+str(y)+'" width="'+str(w)+'" height="'+str(h)+'" stroke="'+black+'" fill="'+white+'" />\n';
			ppath =ipse.persona_path[num-11]
		elif num == 1 :
			ppath =ipse.ace_path
		if num in (1,2,3,11,12,13) :
			xoffset =size[0]/2,
		else :
			xoffset =(size[0]-length[0])/2, (size[0]+length[0])/2
		if num == 1 :
			yoffset =size[1]/2,
		elif num in (11,12,13) :
			pa =length[1]/2*1.618
			yoffset =size[1]/2-pa, size[1]/2+pa
		else :
			yoffset =(size[1]-length[1])/2-length[1], (size[1]+length[1])/2+length[1]
		if num in (3,6,7) :
			yoffset +=size[1]/2,
		elif num in (8,9,10) :
			yoffset +=(size[1]-length[1])/2, (size[1]+length[1])/2
		offset =[]
		for xo in xoffset :
			for i,yo in enumerate(yoffset) :
				offset.append([i,xo,yo])
		if num == 5 :
			offset.append([0,size[0]/2,size[1]/2])
		if num == 7 :
			offset.append([0,size[0]/2,yoffset[2]-(yoffset[2]-yoffset[0])/2])
		if num in (9,10) :
			offset.append([0,size[0]/2,yoffset[2]-(yoffset[2]-yoffset[0])/2])
		if num == 10 :
			offset.append([0,size[0]/2,size[1]-offset[-1][2]])

		scale =(length[0]/ge.side)*element_scale
		if num == 1 :
			scale *=ace_scale
		if num in (11,12,13) :
			scale *=ace_scale
		m =scale,0,0,scale
		m1 =-scale,0,0,-scale
		if ppath :
			ga =Generate(ppath)
			for i,xo,yo in offset :
				svg +='<path d="'+ga((xo,yo), m if i%2 else m1)+'" fill="'+ipse.color+'" />\n'
		else :
			for i,xo,yo in offset :
				svg +='<path d="'+ge((xo,yo), m if i%2 else m1)+'" fill="'+ipse.color+'" />\n'
				#svg +='<circle cx="'+str(xo)+'" cy="'+str(yo)+'" r="2" fill="blue" />\n'

		figure_scale =element_scale*0.7
		if num == 10 :
			g0 =Generate(figure_path[0])
			g1 =Generate(figure_path[10])
			cx,cy =g1.origin
			p =g1((cx-130,cy), (0.66666,0,0,1))
			p +=g0((cx+130,cy), (0.66666,0,0,1))
			gf =Generate(p)
		else :
			gf =Generate(figure_path[num])
		fscale =(length[0]/gf.side)*figure_scale
		gscale =(length[0]/ge.side)*figure_scale*0.66666
		xoffset =size[0]-length[0]/5,length[0]/5
		for i,xo in enumerate(xoffset) :
			yoffset =length[1]/2,length[1]
			if i%2 :
				yoffset =tuple(size[1]-yo for yo in yoffset)
			for j,yo in enumerate(yoffset) :
				if j%2 :
					m =[gscale,0,0,gscale] if i%2 else [-gscale,0,0,-gscale]
					svg +='<path d="'+ge((xo,yo), m)+'" fill="'+ipse.color+'" />\n'
				else :
					m =[fscale,0,0,fscale] if i%2 else [-fscale,0,0,-fscale]
					svg +='<path d="'+gf((xo,yo), m)+'" fill="'+ipse.color+'" />\n'
				#svg +='<circle cx="'+str(xo)+'" cy="'+str(yo)+'" r="2" fill="blue" />\n'
		svg +="</svg>\n"
		return svg

	def back(ipse, width, prefix="") :
# gr =1.618
# poker size 2.5 x 3.5 inches (64x89mm), aka B8 size.
# bridge size 2.25 x 3.5 inches (57 x 89mm)
		rsize =width*ipse.rratio
		size =width, width/5*7
		svg ='<svg width="%f" height="%f" id="%sback">\n'%(size[0], size[1], prefix)
		svg +='<rect x="0" y="0" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(size[0])+'" height="'+str(size[1])+'" stroke="'+black+'" fill="'+white+'" />\n';
		bratio =0.95
		bsize =size[0]*bratio, size[1]*bratio
		xy =(size[0]-bsize[0])/2, (size[1]-bsize[1])/2
		bwidth =width*bratio
		rsize =rsize/2
		svg +='<rect x="'+str(xy[0])+'" y="'+str(xy[1])+'" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(bsize[0])+'" height="'+str(bsize[1])+'" stroke="'+theme+'" fill="'+theme+'" />\n';
		bratio =0.85
		bsize =size[0]*bratio, size[1]*bratio
		xy =(size[0]-bsize[0])/2, (size[1]-bsize[1])/2
		bwidth =width*bratio
		rsize =rsize/2
		svg +='<rect x="'+str(xy[0])+'" y="'+str(xy[1])+'" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(bsize[0])+'" height="'+str(bsize[1])+'" stroke="'+white+'" fill="'+theme+'" />\n';
		#bpath =etc_path[27] # ohana
		bpath =etc_path[16] # 8radii
		gb =Generate(bpath)
		xo,yo =size[0]/2, size[1]/2
		scale =-0.5
		m =scale,0,0,scale
		svg +='<path d="'+gb((xo,yo), m)+'" fill="'+white+'" />\n'
		svg +="</svg>\n"
		return svg

