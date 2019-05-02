#!/usr/bin/python3

from sys import stdout

def trans(f) :
	d ={"B4":493.883,
	"A#4":466.164,
	"Bb4":466.164,
	"A4":440.000,
	"G#4":415.305,
	"Ab4":415.305,
	"G4":391.995,
	"F#4":369.994,
	"Gb4":369.994,
	"F4":349.228,
	"E4":329.628,
	"D#4":311.127,
	"Eb4":311.127,
	"D4":293.665,
	"C#4":277.183,
	"Db4":277.183,
	"C4":261.626,
	"Cb4":246.942,
	"Fb4":329.628}
	if f[-1] != 4 :
		return d[f[:-1]+"4"] * 2**(int(f[-1])-4)
	else :
		return d[[f]]

def oscpp(a) :
	for i,l in enumerate(open(a)) :
		if i == 0 :
			stdout.write(l)
		else :
			r1 =l[:-1].split(",")
			r2 =[r1[0]]
			for j in range(1,len(r1),3) :
				g,d,f =r1[j:j+3]
				if len(f) and f[0].isalpha() :
					f =trans(f)
				r2 +=[g,d,f]
			#print(i,l)
			print(",".join(str(x) for x in r2))


if __name__ == "__main__" :
	from sys import argv
	for a in argv[1:] :
		oscpp(a)
