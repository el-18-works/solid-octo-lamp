
def utendi() :
	OPTARGS =[
		("-f,--file,--makefile=file", "+f"),
		("-n,--just-print,--dry-run,--recon", "n"),
		("-s,--silent,--quiet", "s"),
		("-j,--jobs=n", "*j"),
		("-t,--touch", "t"),
	]
	s =""
	for cmd,f in OPTARGS :
		arg ="a"
		if "=" in cmd :
			cmd,arg =cmd.split("=")
		s +="  "
		if f[0] == "+" :
			s +=", ".join(c+"="+arg for c in cmd.split(","))
		elif f[0] == "*" :
			s +=", ".join(c+"[="+arg+"]" for c in cmd.split(","))
		else :
			s +=", ".join(cmd.split(","))
		#if f[0] in ("*", "+") :
		s +="\n"
	return s

opt =getopt()
if not opt :
	optutendi("`pack`")
	exit(1)

from subprocess import Popen
from sys import stdout, stdin
for i in opt["args"] :
	print(">>"+i)
	p =Popen(["make", "-f/usr/local/my/share/getme.mk", "GMSPECMK="+i], stdout=stdout, stdin=stdin)
	out, err =p.communicate()

