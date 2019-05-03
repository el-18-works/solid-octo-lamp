#!/usr/bin/python3

from PIL import Image
from PIL.ImageDraw import ImageDraw
from PIL.ImageEnhance import Brightness

mai =Image.open("mai-closeup-bg.png")
mai =mai.resize((int(mai.size[0]*1.5), int(mai.size[1]*1.5)))

deplacement =[0.0,0.05]
x,y =100,100

#total =60*5
total =60*20
#for i in range(60*3-30, 60*30+30) :
for i in range(total) :
	#f =Image.new(mode="RGBA", size=[720,480], color=(0xff, 0xff, 0xff, 1))
	#f =Image.new(mode="RGBA", size=[1280,720], color=(0xff, 0xff, 0xff, 1))
	f =mai.crop(box=(int(x),int(y),int(x+1280),int(y+720)))
	x +=deplacement[0]
	y +=deplacement[1]
	sz =mai.size[0]*0.999, mai.size[1]*0.999
	if i%5 == 0 :
		mai =mai.resize((int(sz[0]), int(sz[1])))
	a =i%(60*3)
	if a < 20 :
		a =abs(a-10)
		g =Brightness(f)
		g =g.enhance(a/10)
		#g.show()
		#g =Image.new(mode="RGBA", size=[1280,720], color=(0x02, 0x02, 0x02, int(0xff-0xff*a/30)))
		#f.paste(g,box=(0,0,1280,720))
		g.save(open("cache/myabout/myabout%04d.png"%i, "bw"))
		continue
	#dr =ImageDraw(f)
	#s,ms =divmod(i,60)
	#dr.text(xy=[10,10], text="%02d:%02d.%04d"%(0,s,ms%60), fill=0)
	#dr.text(xy=[10,30], text="(%02d/%02d)"%(i,total), fill=0)

	f.save(open("cache/myabout/myabout%04d.png"%i, "bw"))
	print("%d/%d"%(i,total))

exit()


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
for r in open("monopcmplot.csv") :
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


