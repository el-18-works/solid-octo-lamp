#!/usr/bin/python3

from sys import argv
from datetime import date
import pymysql


from sys import argv, stdin, stdout, stderr
from subprocess import call, Popen
from os import pipe, read, write, fdopen, close

nikidir="niki/"
#nikidir="genko/"

def copy_clipboard(s) :
	cmd =['/usr/bin/xclip', '-selection', 'clipboard']
	rfd, wfd =pipe()
	sp =Popen(cmd, stdin=fdopen(rfd, "r"), stderr=stderr)
	write(wfd,s.encode()); close(wfd)
	sp.communicate()

def paste_clipboard(prefix="") :
	cmd =['/usr/bin/xdotool', 'getactivewindow', 'getwindowname']
	keycmd =['xdotool', 'key']
	rfd, wfd =pipe()
	sp =Popen(cmd, stdout=fdopen(wfd, "w"), stderr=stderr)
	sp.communicate()
	data =read(rfd,1024).decode().strip()
	a =data.split(" ")
	if "Yakuake" in a :
		if "vi" in a :
			key =['Escape', 'i', 'ctrl+shift+v']
		else :
			key =['ctrl+shift+v']
	else :
		key =['ctrl+v']
	if prefix :
		l =[ "space" if i == " " else i for i in list(prefix) ]
		key =l + key
	cmd =keycmd+key+['Return']
	call(cmd)

class stamp : 
	@classmethod 
	def md(ipse) : return "%s-%s-%s-%s.md"%(ipse.a, ipse.m, ipse.d, ipse.id)
	@classmethod 
	def h(ipse) : return "%s月%s日"%(ipse.m, ipse.d)
	@classmethod 
	def mdheader(ipse) : 
		a =["%s-%s-%s-%s"%(ipse.a, ipse.m, ipse.d, ipse.id)]
		a.append("##"+ipse.h())
		return "\n".join(a)

DEBUG="debug" in argv or "info" in argv

class MyCon :
	SCHEMA ="luckxa_mydiary_txt"
	TESTSCHEMA =SCHEMA+"_test"
	def __init__(ipse, modus="") :
		ipse.schema =ipse.TESTSCHEMA if modus.lower() == "test" else ipse.SCHEMA
		ipse.db =pymysql.connect("localhost", "l18", "@", "l18")

	def __del__(ipse) :
		ipse.db.close()
		
	def test_connect(ipse) :
		cursor =ipse.db.cursor()
		cursor.execute("SELECT VERSION()")
		data =cursor.fetchone()
		print ("mysql version : %s"%data)

	# DQL
	def q(ipse, q, unicum =False) :
		cursor =ipse.db.cursor()
		if DEBUG : print(q)
		cursor.execute(q)
		res =cursor.fetchone() if unicum else cursor.fetchall()
		return res

	# DML
	def m(ipse, q, p=None) :
		cursor =ipse.db.cursor()
		if DEBUG : 
			print(q)
			print(p)
		try :
			if p == None :
				cursor.execute(q)
			else :
				cursor.execute(q, p)
			ipse.db.commit()
			return 1
		except Exception as e:
			print(e)
			ipse.db.rollback()
			return 0

	def ultimum(ipse) :
		res =ipse.q("SELECT id,a,m,d FROM "+ipse.schema+" ORDER BY id DESC LIMIT 1", 1)
		class u(stamp) : id,a,m,d =res if res != None else (0,0,0,0)
		return u

	def hodie(ipse, h) :
		txt ="<!-- %d -->"%h.id
		values ="VALUES(%d,%d,%d,%d"%(h.id,h.a,h.m,h.d)
		values +=', %s, %s)'
		#values +=', "%(h)s", "%(txt)s")'
		if ipse.m(q="INSERT INTO "+ipse.schema+"(id,a,m,d,h,txt) "+values, p=[h.h(), txt]) :
		#if ipse.m(q="INSERT INTO "+ipse.schema+"(id,a,m,d,h,txt) "+values, p={"h":h.h(), "txt":txt}) :
			return txt
		else :
			print("error!")
			exit(1)

	def subdex(ipse, id, h, txt) :
		q ="UPDATE "+ipse.schema+" SET h=%(h)s, txt=%(txt)s WHERE id="+str(id)
		if ipse.m(q=q, p={"h":h, "txt":txt}) :
			return txt
		else :
			print("error!")
			exit(1)

	# XXX...
	def check(ipse, id) :
		res =ipse.q("SELECT id,a,m,d,h,txt FROM "+ipse.schema+" WHERE id="+str(id))
		for r in res :
			print (r)

mc =MyCon("test" if len(argv) > 1 and "test" in argv else "")
if "debug" in argv :
	mc.test_connect()	

u =mc.ultimum()
class h(stamp) : 
	id =u.id+1
	hodie =date.today()
	a,m,d =hodie.year-2018, hodie.month, hodie.day
	del hodie

#print(u.md())
#print(h.md())
from os import path

if "update" in argv :
	i =argv.index("update")
	md =argv[i+1]
	arg =md[:-3]
	print ("update----")
	print(md)
	gdr =path.dirname(__file__)+"/"+nikidir
	ls =open(gdr+md).readlines()
	if ls[0].strip() != arg :
		input("tag skew <"+arg+"> <"+ls[0]+"> ?? )")
	class txt :
		h =""
		p =[]
		@staticmethod
		def dump() :
			return txt.h, "\n".join("\n".join(p) for p in txt.p)
		@staticmethod
		def append(s) :
			if not s :
				if not txt.p or not [-1] : return
				txt.p.append([])
			else :
				if not txt.p : txt.p.append([])
				txt.p[-1].append(s)

	for l in ls[1:] :
		if l[:2] == "##" : # h.
			txt.h =l[2:].strip()
		elif l[:1] != "#" : # commentaria.
			txt.append(l.strip())
	h,txt =txt.dump()
	mc.subdex(int(arg.split("-")[-1]), h, txt) 
	exit()
elif "check" in argv :
	i =argv.index("check")
	md =argv[i+1]
	arg =md[:-3]
	mc.check(arg.split("-")[-1])
	exit()
elif "init" in argv :
	i =argv.index("init")
	idamd =argv[i+1:i+1+4]
	res =[int(i) for i in idamd]
	class initstamp(stamp) : id,a,m,d =res
	mc.hodie(initstamp)
	exit()

print ("!!update----")

while 1 :
	c =input(u.md()+" u/h/?)")
	if c == "u" :
		md =nikidir+u.md()
		copy_clipboard(md)
		paste_clipboard("leafpad ")
		#paste_clipboard("vi ")
	elif c == "h" :
		md =nikidir+h.md()
		txt =mc.hodie(h)
		open(md, "w").write("\n".join([h.mdheader(), txt]))
		copy_clipboard(md)
		paste_clipboard("leafpad ")
		#paste_clipboard("vi ")
	else :
		print("u continuare ultimum")
		print("h separare hodie")
		print("? --")
		continue
	break


exit()

