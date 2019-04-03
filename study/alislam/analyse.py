#!/usr/local/bin/python3
import re

#Check if the string starts with "The" and ends with "Spain":

#help(re)
m =1
LL =open("guerison.txt").readlines()
#for l in open("guerison.txt") :
CC =[]
NN =[]
for l in LL[2:] :
	if l[0] == '#' : continue
	x = re.search("^Sourate (.*) \(Chapitre (\d+)\)$", l)
	if x :
		s,n =x.groups()
		#print(s)
		n =int(n)
		if m + 1 != n : print(m,n)#, exit()
		m =n
		#print(dir(x))
		#print (NN)
		NN.clear()
	else :
		#if 0 and re.search("(R|r)évélation", l) :
			#k ="Lieu de révélation"
		if re.search("^Les circonstances", l) :
			k ="Les circonstances de la Révélation"
		else :
			k =l
		if k not in CC :
			print(k)
			CC.append(k)
		i =CC.index(k)
		if i in NN :
			print (k,l,m,i,NN,CC[i])
			exit()
		NN.append(i)

