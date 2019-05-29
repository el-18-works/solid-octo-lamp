#!/usr/bin/env python3


class PathFilter :

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
		print(l,ipse.pat)
		#print("   ","%15.30s = star"%bin(ipse.star))
		#print("   ","%15.30s = r "%bin(r))
		for c in l :
			for i in range(len(ipse.charset)-1, 0, -1) :
				if ipse.charset[i] == c : break
			r =ipse.subset[r][i]
			#print(c,i,"%15.30s"%bin(r))
		#print("   ","%15.30s"%bin(ipse.subset[r][0]))
		return bool(ipse.subset[r][0])

	def flect(ipse, g =2, v =2, aux=['x', 'y', 'z']) :
		from random import random, randrange
		charset =ipse.charset[2:] + aux
		r =ipse.start
		p =0
		while 1 :
			if r>>p & 1 and random()*g > 1 :
				break
			p +=1
			if r>>p  == 0 :
				p =0
		
		print(ipse.pat)
		print(" "*p+"^")
		while ipse.pat[p] not in (',', '\0') :
			i =ipse.pat[p]
			if i == '*' :
				yield charset[randrange(0,len(charset))]
				if random()*v > 1 :
					p +=1
			else :
				p +=1
				yield charset[randrange(0,len(charset))] if i == '*' else i

#from sys import argv
pf =PathFilter("ab*c")
pf =PathFilter("ab*c,abc*d,b*b")
print(pf("ab"))
print(pf("abaac"))
print(pf("abxyc"))
print(pf("bcada"))
print(pf("b"))
print(pf("ab"))
print(pf("a"))
print(pf("ba"))
for i in range(12) :
	s ="".join(pf.flect())
	input("#"+s)
	if not pf(s) :
		raise Exception()
	print()

