#!/usr/bin/python3

from my.genoptparse import genoptparse
from sys import argv

def outman() :
	output.write(".TH osc 1\n")
	output.write(".SH NAME\n")
	output.write("ﾋﾟｺﾋﾟｺ ｵｼﾚｰﾀｰ\n")
	output.write(".SH SYNOPSIS\n")
	output.write(".B  osc [-hm][--help|--manual]\n")
	output.write(".SH DESCRIPTION\n")
	output.write("ﾜﾀｼﾊ ﾋﾟｺﾋﾟｺ ｵｼﾚｰﾀｰ ﾃﾞｽ\n")
	output.write(".br\n")
	output.write("ﾋﾟｺﾋﾟｺ ｵﾄｦ ﾀﾞｼﾏｽ\n")
	output.write(".PP\n")
	output.write(".SH COMMAND LINE OPTIONS\n")
	output.write("  -h, --help\n")
	output.write("    ｶﾝﾀﾝﾅﾍﾙﾌﾟｦ ﾋｮｳｼﾞｼﾏｽ\n")
	output.write(".PP\n")
	output.write("  -m, --manual\n")
	output.write("    ﾏﾆｭｱﾙｦ ﾋｮｳｼﾞｼﾏｽ\n")
	output.write(".PP\n")
	output.write(".SH BUGS\n")
	output.write("    ﾊﾞｸﾞﾊ ｱﾘﾏｾﾝ\n")
	output.write(".PP\n")
	output.write(".SH AUTHOR\n")
	output.write("L18WORKS\n")
	output.write(".PP\n")
	output.write("https://l18.work\n")
	exit()

if __name__ == "__main__" and len(argv) >= 2 :
	if len(argv) == 3 :
		output =open(argv[2], "w")
	else :
		from sys import stdout as output
	if argv[1] == "op" :
		pass
	elif argv[1] == "man" :
		outman()


output.write("#!/usr/bin/python3\n")
output.write("#\n")
output.write("# Opciones-proveedor para osc.bin\n")
output.write("#\n")

optargs =[
	("-h,--help", "h"),
	("-m,--manual", "m"),
]
#help(genoptparse)
genoptparse(output, optargs, debug=0)

def cattail(marc) :
	intail =False
	for i in open(__file__) :
		if not intail :
			intail = marc in i
		else :
			output.write(i)
	exit()

cattail("(=^・^=)")

from subprocess import call
from os.path import dirname
bindir =dirname(__file__)
exe =bindir+"/osc.bin"

opt =optparse()
if not opt :
	optutendi()
	exit(1)
elif "m" in opt["flgs"] :
	call(["man", "-l", bindir+"/osc.1"])
	exit(0)
elif "h" in opt["flgs"] :
	optutendi()
	exit(0)

