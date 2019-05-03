#!/usr/bin/python3

import re


#def make(target) :
	
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

class GenOptParse :
	def error(ipse, msg) :
		print(msg)
		exit(1)

	def __init__(ipse) :
		ipse.autoinc =0
		ipse.trans ={0:{}}

	def prim(ipse) :
		ipse.autoinc +=1
		return ipse.autoinc

	def __call__(ipse, cmd, cmdfun) :
		st =0
		for c in cmd :
			if c not in  ipse.trans[st] :
				ipse.trans[st][c] =ipse.prim()
				st =ipse.trans[st][c]
				ipse.trans[st] ={}
			else :
				st =ipse.trans[st][c]
		if "=" in ipse.trans[st] :
			ipse.error("duplicatus mandatus '%s'"%cmd)
		ipse.trans[st]["="] =cmdfun
	
	def __iter__(ipse) :
		actst =-1 # ipse.prim()
		yield "st =0"
		yield "while st != 0 or lex.la != '$' :"
		yield "\tprint( st,lex.la)"
		yield "\tif st == %d :"%actst
		yield "\t\tif setoptf[0] == 'arg' :"
		yield "\t\t\tl =lex.getl()"
		yield "\t\t\topt[setoptf[1]].add(l[1:] if len(l) and l[0] == '=' else l)"
		yield "\t\telif setoptf[0] == 'flg' :"
		yield "\t\t\topt['flgs'].add(setoptf[1])"
		yield "\t\tst =0"
		for st in sorted(ipse.trans) :
			yield "\telif st == %d :"%st
			for i,c in enumerate(ipse.trans[st]) :
				s ="\t\t"
				if i != 0 :
					s +="el"
				if c != '=' :
					yield s + "if lex.la == '%c' :"%c
					yield "\t\t\tst =%d"%ipse.trans[st][c]
					yield "\t\t\tlex.getc()"
			if '=' in ipse.trans[st] :
				yield "\t\tst =%d"%actst
				opt =ipse.trans[st][c]
				if opt[0] == ':' :
					yield "\t\tsetoptf =('arg', '%s')"%opt[1:]
					#yield "\t\tlex.getc()"
				else :
					yield "\t\tsetoptf =('flg', '%s')"%opt
					#yield "\t\tlex.getc()"
				continue
			yield "\t\telse :"
			if st == 0 :
				yield "\t\t\tsetoptf =('arg', 'args')"
				yield "\t\t\tst =%d"%actst
			else :
				yield "\t\t\terror('syntax error')"

class GenMakeOptParse :

	def __init__(ipse) :
		ipse.optargs =[
			("-f,--file,--makefile", ":makefile"),
			("-n,--just-print,--dry-run,--recon", "n"),
			("-s,--silent,--quiet", "s"),
			("-t,--touch", "t")
		]

	def __call__(ipse, out) :

		out.write("\n")
		out.write("class MakeOptParse :\n")
		out.write("\tdef __init__(ipse) :\n")
		out.write("\t\timport sys\n")
		out.write("\t\tipse.argv =sys.argv[1:]\n")
		out.write("\t\tif len(ipse.argv) == 0 :\n")
		out.write("\t\t\tipse.la ='$'\n")
		out.write("\t\telif len(ipse.argv[0]) == 0 :\n")
		out.write("\t\t\tipse.la =''\n")
		out.write("\t\telse :\n")
		out.write("\t\t\tipse.la =ipse.argv[0][0]\n")
		out.write("\t\tipse.argi =0\n")
		out.write("\t\tipse.argj =0\n")
		out.write("\n")
		out.write("\tdef getc(ipse) :\n")
		out.write("\t\tc =ipse.la\n")
		out.write("\t\tif len(ipse.argv[ipse.argi]) == ipse.argj+1 :\n")
		out.write("\t\t\tipse.argi +=1; ipse.argj =0\n")
		out.write("\t\t\tif len(ipse.argv) == ipse.argi :\n")
		out.write("\t\t\t\tipse.la ='$'\n")
		out.write("\t\t\telse :\n")
		out.write("\t\t\t\tipse.la ='' if len(ipse.argv[ipse.argi]) == 0 else ipse.argv[ipse.argi][0]\n")
		out.write("\t\telse :\n")
		out.write("\t\t\tipse.argj +=1\n")
		out.write("\t\t\tipse.la =ipse.argv[ipse.argi][ipse.argj]\n")
		out.write("\t\treturn c\n")
		out.write("\n")
		out.write("\tdef getl(ipse) :\n")
		out.write("\t\tif ipse.la == '$' :\n")
		out.write("\t\t\treturn '$'\n")
		out.write("\t\telif len(ipse.argv[ipse.argi]) == ipse.argj :\n")
		out.write("\t\t\tipse.argi +=1\n")
		out.write("\t\t\tif len(ipse.argv) == ipse.argi :\n")
		out.write("\t\t\t\treturn '$'\n")
		out.write("\t\t\tl =ipse.argv[ipse.argi][0:]\n")
		out.write("\t\telse :\n")
		out.write("\t\t\tl =ipse.argv[ipse.argi][ipse.argj:]\n")
		out.write("\t\tipse.argi +=1; ipse.argj =0\n")
		out.write("\t\tif len(ipse.argv) == ipse.argi :\n")
		out.write("\t\t\tipse.la ='$'\n")
		out.write("\t\telse :\n")
		out.write("\t\t\tipse.la ='' if len(ipse.argv[ipse.argi]) == 0 else ipse.argv[ipse.argi][0]\n")
		out.write("\t\treturn l\n")
		out.write("\n")
		out.write("\tdef error(ipse, msg='error') :\n")
		out.write("\t\tprint('%s'%msg)\n")
		out.write("\t\texit(1)\n")
		out.write("\n")
		out.write("\tdef __call__(ipse, opt={'args':set(),'makefile':set(),'flgs':set()}) :\n")
		out.write("\t\tlex =ipse\n")
		out.write("\t\terror =ipse.error\n")

		gop =GenOptParse()
		for cmd,f in ipse.optargs :
			for c in cmd.split(",") :
				gop(c, f)

		for i in gop :
			out.write ("\t\t"+i)
			out.write("\n")

		out.write("\t\treturn opt\n")

		out.write("\n")
		out.write("def makeoptparse() :\n")
		out.write("\tmop =MakeOptParse()\n")
		out.write("\treturn mop()\n")

if __name__ == "__main__" :
	gmop =GenMakeOptParse()
	from sys import stdout
	testout =open("cache/testmakeparse.py", "w")
	testout.write("#!/usr/bin/python3\n")
	gmop(testout)
	testout.write("print(makeoptparse())")
	exit()
	import sys
	sys.path.append("cache")
	sys.path.append(".")
	try :
		import testmakeparse
		print(dir(testmakeparse))
		from cache.testmakeparse import makeoptparse
		print(makeoptparse())
	except Exception as e:
		print(e)
		pass

