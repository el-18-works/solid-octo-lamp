#!/usr/bin/python3

from sys import argv

def gensuite(vn, suite, gnd) :
	figs =["spade", "heart", "diamond", "club"]
	prefix="/var/www/html/ens/res/misc/baraja/"
	print("var %sGrandeur =[%d, %d];"%(suite, int(gnd), int(gnd)/5*7))
	print("var %s =["%suite)
	for i in range(4) :
		for j in range(13) :
			t ="".join(s.strip() for s in open(prefix+"card-"+suite+"_"+figs[i]+"_"+str(j+1)+".svg"))
			print("   '%s',"%t)
	t ="".join(s.strip() for s in open(prefix+"card-"+suite+"_0.svg"))
	print("   '%s'];"%t)

if __name__ == "__main__" and "suite" in argv :
	i =argv.index("suite")
	gensuite(argv[i+1], argv[i+2], argv[i+3])

