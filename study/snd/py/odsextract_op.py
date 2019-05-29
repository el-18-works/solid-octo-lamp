#!/usr/bin/python3

from my.genoptparse import genoptparse
from sys import argv

def hankaku(s) :
	#demx= 0x30fa demn= 0x30a1 dew= 89
	#admx= 0xff9d admn= 0xff66 adw= 55
	#カタカナ 0x30a1~
	#平仮名　 0x3041~
	mp =[1, 11, 2, 12, 3, 13, 4, 14, 5, 15, 16, -16, 17, -17, 18, -18, 19, -19, 20, -20, 21, -21, 22, -22, 23, -23, 24, -24, 25, -25, 26, -26, 27, -27, 9, 28, -28, 29, -29, 30, -30, 31, 32, 33, 34, 35, 36, -36, -136, 37, -37, -137, 38, -38, -138, 39, -39, -139, 40, -40, -140, 41, 42, 43, 44, 45, 6, 46, 7, 47, 8, 48, 49, 50, 51, 52, 53, 0, 54, 0, 0, 55, 0, -13, 0, 0, -54, 0]
	sgn =(0x309b,0x309c,0x300c,0x300d,0x3001,0x3002,0x30fc,0x30fc,0x30fb),(0xff9e,0xff9f,0xff62,0xff63,0xff64,0xff61,0xff70,0xff70,0xff65)
	t =""
	for c in s :
		o =ord(c)
		if o in sgn[0] :
			t +=chr(sgn[1][sgn[0].index(o)])
			continue
		elif o > 0x30a0 and o < 0x30a0+90:
			q =mp[o-0x30a1]
		elif o > 0x3040 and o < 0x3040+90:
			q =mp[o-0x3041]
		else :
			t +=c
			continue
		sem =""
		if q < 0 :
			q =-q
			if q >= 100 :
				q -=100
				sem =chr(sgn[1][1])
			else :
				sem =chr(sgn[1][0])
		t +=chr(0xff66+q)+sem
	return t

print("---")
print(hankaku("あいがある"))
print(hankaku("アイガアルはパー「」"))
exit()


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

