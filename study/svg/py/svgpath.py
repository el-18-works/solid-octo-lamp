#!/usr/bin/python3

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

class DTokenize (Stack) :

	@staticmethod
	def error(message) :
			print("DTokenize : %s"%message)
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
					if c in (",", " ", "\t") :
						ipse.push(0)
					elif c == "-" :
						ipse.push(0)
						ipse.push(1)
					elif c == "+" :
						ipse.push(0)
						ipse.push(2)
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
					if c in (",", " ", "\t") :
						ipse.push(0)
					elif c == "-" :
						ipse.push(0)
						ipse.push(1)
					elif c == "+" :
						ipse.push(0)
						ipse.push(2)
					elif c in ("l","z","t","m","c","s","q","v","h","L","Z","T","M","C","S","Q","V","H") :
						yield c
						ipse.push(0)
					else :
						ipse.error("unknown token '%c'"%c)	
		yield "$"

class DParse (Stack) :

	@staticmethod
	def error(message) :
		print("DParse : %s"%message)
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
		tokenize =DTokenize()
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

class DGraph :

	def __init__(ipse, path) :
		ipse.path =path
		mx,my =ipse.bbox()
		ipse.origin =mx[1]+(mx[0]-mx[1])/2,my[1]+(my[0]-my[1])/2
		ipse.side =max(mx[0]-mx[1], my[0]-my[1])

	def upper(ipse) :
		p =DParse()
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

	def lower(ipse) :
		p =DParse()
		xy0 =0,0
		for code in p(ipse.path) : 
			if code[0] == "M" :
				xy0 =code[1:]
			elif code[0].isupper():
				if code[0] == "M" :
					print("M==>",code,xy0)
				b =[code[0].lower()]
				if code[0] == "H" :
					b.append(xy[0]-code[1])
					xy =xy[1]-code[1],xy[1]
				elif code[0] == "V" :
					b.append(code[1]-xy[1])
					xy =xy[0],xy[1]-code[1]
				else :
					for i in range(1,len(code),2) :
						xy =code[i:i+2]
						xy =xy[0]-xy0[0], xy[1]-xy0[1]
						b +=xy
				code =b
				xy0 =xy[0]+xy0[0], xy[1]+xy0[1]
			else : 
				# lower
				xy =code[-2:]
				xy0 =xy[0]+xy0[0], xy[1]+xy0[1]
			yield code

	@staticmethod
	def error(message) :
		print("DGraph : %s"%message)
		exit(1)

	@staticmethod
	def clone(iterable, mapping=lambda a : a) :
		p =DParse()
		cpy =""
		for code in iterable() : 
			cpy +=code[0]
			def form6(n) :
				if type(n) == int : return str(n)
				r =format("%.6f"%n) 
				while r[-1] == "0" and r[-2] != "." : r =r[:-1]
				return r
			if code[0].lower() == "h" :
				cpy +=form6(mapping((code[1],0))[0])
			elif code[0].lower() == "v" :
				cpy +=form6(mapping((0,code[1]))[1])
			else :
				cpy +=" ".join(form6(a) for a in mapping(code[1:]))
		return cpy

	def __call__(ipse, origin, mat2, upper=1) :
		def f(a) :
			b =tuple()
			for i in range(0,len(a),2) :
				xy =a[i:i+2]
				xy =xy[0]-ipse.origin[0], xy[1]-ipse.origin[1]
				xy =mat2[0]*xy[0]+mat2[1]*xy[1], mat2[2]*xy[0]+mat2[3]*xy[1]
				xy =xy[0]+origin[0], xy[1]+origin[1]
				b +=xy
			return b
		if upper :
			return ipse.clone(ipse.upper, f)
		else :
			g =DGraph(ipse.clone(ipse.upper, f))
			return g.clone(g.lower)

	def bbox(ipse) :
		p =DParse()
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
		p =DParse()
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
			draw.line((x+margin,y+margin,x0+margin,y0+margin), fill=(0,0,0))
		draw.ellipse(xy=(x-2+margin,y-2+margin,x+2+margin,y+2+margin), fill=(0,255,0))
		x,y =ipse.origin
		draw.ellipse(xy=(x-2+margin,y-2+margin,x+2+margin,y+2+margin), fill=(255,0,0))
		return im

def dgraph( d ) :
	return DGraph(d)

