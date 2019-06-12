#!/usr/bin/python3

from PIL import Image
from PIL.ImageDraw import ImageDraw



frame =Image.open("img/suichu.png")
mario =Image.open("img/mario.png")
awa =Image.open("img/awa.png")
gesso =Image.open("img/gesso.png")
oiram =mario.transpose(Image.FLIP_LEFT_RIGHT)

w,h =frame.size
ox =w/2-20
oy =h/2+40


#framel.paste(mario, (200,200))
#framer.paste(oiram, (250,200))
#framel.show()
#mario.show()

stride =120

ff =[]
for i in range(stride) :
	f =frame.copy()
	if i < 60 :
		x =int(ox+60 - i)
		y =int(oy-(i%20)/5)
		f.paste(oiram, [x,y])
		x =int(ox-65)
		y =int(oy-10 - i/3)
		f.paste(gesso, [x,y])
	else :
		x =int(ox-60 + i)
		y =int(oy-(i%20)/5)
		f.paste(mario, [x,y])
		x =int(ox-65)
		y =int(oy-10-20 + (i%60)/3)
		f.paste(gesso, [x,y])
		x =int(ox-65+60)
		y =int(oy- i/3)
		f.paste(awa, [x,y])
	x =int(ox+65)
	y =int(oy-10 - i/2)
	f.paste(awa, [x,y])
	ff.append(f)
	
plot ={}
for r in open("cache/monopcmplot.csv") :
	dat =r[:-2].split(",")
	idx =int(dat[0])
	a =[]
	for d in dat[1:] :
		x,y =d.split()
		a.append(float(y))
	plot[idx] =a
print(len(plot.keys()))
print(len(plot[14]))
print(max(max(y) for y in plot.values()))

total =len(plot.keys())
#total =2200
#total =120*3
for i in range(total) :
	f =ff[i%stride].copy()
	dr =ImageDraw(f)
	x0,y0 =0,f.size[1]/2
	for xpos in range(len(plot[i])) :
		x =(1+xpos) * f.size[0]/len(plot[i])
		y = f.size[1]/2 + plot[i][xpos]*30
		dr.line([x0,y0,x,y], width=2)
		x0,y0 =x,y
		

	f.save(open("cache/suichu/suichu%04d.png"%i, "bw"))
	#ff[i%stride].save(open("cache/suichu/suichu%04d.png"%i, "bw"))
	#if (i+5)%(stride*2) < stride :
		#framel.save(open("cache/suichu/suichu%04d.png"%i, "bw"))
	#else :
		#framer.save(open("cache/suichu/suichu%04d.png"%i, "bw"))
	print("%d/%d"%(i,total))


