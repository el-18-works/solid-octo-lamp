#!/usr/bin/python3


#
#
#  optionium parsitio
#
#

OPTARGS =[
	("-f,--file,--makefile", "+f"),
	("-n,--just-print,--dry-run,--recon", "n"),
	("-s,--silent,--quiet", "s"),
	("-j,--jobs", "*j"),
	("-t,--touch", "t"),
]

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

	def __init__(ipse, debug=False) :
		ipse.debug =debug
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
		preactst =-2
		actst =-1
		yield "st =0"
		yield "while st != 0 or lex.la != '$' :"
		if ipse.debug :
			yield "\tprint( st,lex.la)"
		yield "\tif st == %d :"%preactst
		yield "\t\tif setoptf[0] == 'arg' :"
		yield "\t\t\tif lex.la == '=' :"
		yield "\t\t\t\tl =lex.getl()"
		yield "\t\t\t\topt[setoptf[1]].append(l[1:])"
		yield "\t\t\telse :"
		yield "\t\t\t\topt['flgs'] +=setoptf[1]"
		if ipse.debug :
			yield "\t\telse :"
			yield "\t\t\terror('internal error')"
		yield "\t\tst =0"
		yield "\telif st == %d :"%actst
		yield "\t\tif setoptf[0] == 'arg' :"
		yield "\t\t\tl =lex.getl()"
		yield "\t\t\topt[setoptf[1]].append(l[1:] if len(l) and l[0] == '=' else l)"
		yield "\t\telif setoptf[0] == 'flg' :"
		yield "\t\t\topt['flgs'] +=setoptf[1]"
		yield "\t\t\topt['flgs'] +=lex.getl(noinc=1)"
		if ipse.debug :
			yield "\t\telse :"
			yield "\t\t\terror('internal error')"
		yield "\t\tst =0"
		for st in sorted(ipse.trans) :
			yield "\telif st == %d :"%st
			if '=' in ipse.trans[st] :
				if len(ipse.trans[st]) != 1 :
					ipse.error("indeterminismus")
				opt =ipse.trans[st]["="]
				if opt[0] == '*' :
					yield "\t\tst =%d"%preactst
					yield "\t\tsetoptf =('arg', '%s')"%opt[1:]
				elif opt[0] == '+' :
					yield "\t\tst =%d"%actst
					yield "\t\tsetoptf =('arg', '%s')"%opt[1:]
				else :
					yield "\t\tst =%d"%actst
					yield "\t\tsetoptf =('flg', '%s')"%opt
				continue
			for i,c in enumerate(ipse.trans[st]) :
				s ="\t\t"
				if i != 0 : s +="el"
				yield s + "if lex.la == '%c' :"%c
				yield "\t\t\tst =%d"%ipse.trans[st][c]
				yield "\t\t\tlex.getc()"
			yield "\t\telse :"
			if st == 0 :
				yield "\t\t\tsetoptf =('arg', 'args')"
				yield "\t\t\tst =%d"%actst
			else :
				yield "\t\t\terror('syntax error')"

class GenMakeOptParse :

	def __init__(ipse, debug=False) :
		ipse.debug =debug
		ipse.optargs =OPTARGS

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
		out.write("\tdef getl(ipse, noinc=0) :\n")
		out.write("\t\tif noinc == 1 and ipse.argj == 0 :\n")
		out.write("\t\t\treturn ''\n")
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

		gop =GenOptParse(ipse.debug)
		ls =[]
		for cmd,f in ipse.optargs :
			for c in cmd.split(",") :
				gop(c, f)
			if f[0] in ("*", "+") :
				ls.append(cmd[1])
		opt = "{'flgs':'','args':[]," + ",".join("'%c':[]"%c for c in ls) + "}"
		out.write("\tdef __call__(ipse, opt=%s) :\n"%opt)
		out.write("\t\tlex =ipse\n")
		out.write("\t\terror =ipse.error\n")

		for i in gop :
			out.write ("\t\t"+i)
			out.write("\n")

		out.write("\t\treturn opt\n")

		out.write("\n")
		out.write("def makeoptparse() :\n")
		out.write("\tmop =MakeOptParse()\n")
		out.write("\treturn mop()\n")



#
#
#  fixurae parsitio
#
#

trm =[
	("uif", "ifdef,ifndef"),
	("bif", "ifeq,ifneq"),
	("els", "else"),
	("fi", "endif"),
	("(", "("),
	(")", ")"),
	(",", ","),
	("+", " +"),
	("-", " *"),
	(".", ""),
]
rel =[
	("BLO", "COND|REGL|ASSGN|TAG"),
	("COND", "I BLO E BLO F | I BLO F"),
	("I", "uif + VAR"),
	("I", "bif + ( - TRM - , - TRM - )"),
	("I", "bif + LIT + LIT"),
	("E", "els"),
	("F", "fi"),
	("TRM", " REFLIT | REFVAR"),
	("REGL", "TOP GCPL GDEP*"),
	("GDEP", "ASSGN|TAG|TRM"),
	("TOP", "TRM+"),
	("REFLIT", "' LIT* '"),
	("REFLIT", '" LIT* "'),
	("REFVAR", '$ ( - VAR - )'),
	("REFVAR", '$ { - VAR - }'),
	("LIT", 'REFVAR|FRAG'),
	("FRAG", '.*'),
	("VAR", ''),
]

LINEA =[
	("linea", 
		"regula, assignatio, directiva, conditio", 
	""),
	("regula", 
		"topos geodep prerequisitum#assignatio*", 
	""),
	("assignatio", 
		"terminus arithmodep characteristica"
	""),
	("directiva", 
		"tagos tagma", 
	""),
	("conditio", 
		"hetu", 
	""),
]


if __name__ == "__main__" :
	gmop =GenMakeOptParse(debug=1)
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

