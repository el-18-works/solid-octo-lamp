#!/usr/bin/python3

import sys
sys.path.append("cache")
from os.path import dirname
sys.path.append(dirname(__file__))

# spade, 0 heart, 0 diamond, club
# 0 spade, heart, diamond, 0 club
from card_data import element_path, figure_path, etc_path

from svgpath import dgraph


white ="#fffdfa"
red ="#da0a0a"
black ="#010101"
ablack ="rgba(1,1,1,0.333)"
theme ="#210126"

class Card :

	# backfig = {ohana|8r}
	def __init__(ipse, figure, freq, rratio=0.05, gradient=0, backfig="ohana") :
		ipse.figure =figure
		ipse.freq =freq
		ipse.rratio =rratio
		ipse.gradient =gradient
		if backfig == "ohana" :
			ipse.back_path =etc_path[27] # ohana
		elif backfig == "8r" :
			ipse.back_path =etc_path[16] # 8r
		else :
			ipse.error("unknown backfig '%s'"%backfig)
		if figure == "spade" :
			ipse.path =element_path[1]
			ipse.open_path =element_path[5]
			ipse.ace_path =etc_path[0]
			ipse.persona_path =etc_path[10+4], etc_path[10+1], etc_path[10]
			ipse.color =black
		elif figure == "heart" :
			ipse.path =element_path[6]
			ipse.open_path =element_path[2]
			ipse.ace_path =etc_path[1]
			ipse.persona_path =etc_path[10+4], etc_path[10+1], etc_path[10]
			ipse.color =red
		elif figure == "diamond" :
			ipse.path =element_path[7]
			ipse.open_path =element_path[3]
			ipse.ace_path =etc_path[2]
			ipse.persona_path =etc_path[4+4], etc_path[4+1], etc_path[4]
			ipse.color =red
		elif figure == "club" :
			ipse.path =element_path[4]
			ipse.open_path =element_path[8]
			ipse.ace_path =etc_path[3]
			ipse.persona_path =etc_path[4+4], etc_path[4+1], etc_path[4]
			ipse.color =black
		else :
			ipse.error("unknown figure %s"%figure)

	def __call__(ipse, width, num, element_scale=1, ace_scale=2, prefix="") :
# gr =1.618
# poker size 2.5 x 3.5 inches (64x89mm), aka B8 size.
# bridge size 2.25 x 3.5 inches (57 x 89mm)
		if num == 0 :
			return ipse.back(width, ace_scale, prefix)
		rsize =width*ipse.rratio
		size =width, width/5*7
		svg ='<svg width="%f" height="%f" id="%s%s%d">\n'%(size[0], size[1], prefix, ipse.figure, num)
		if ipse.gradient :
			gradradius =size[0]
			svg +='<radialGradient id="'+prefix+'whitegradient" cx="'+str(size[0]/2)+'" cy="'+str(size[1]/2)+'" r="'+str(gradradius)+'" gradientTransform="matrix(1 0 0 1 0 -.25)" gradientUnits="userSpaceOnUse">\n'
			svg +='<stop stop-color="#FDFAF4" offset=".15"/>\n'
			svg +='<stop stop-color="#FDF9F2" offset=".35"/>\n'
			svg +='<stop stop-color="#FCF7F1" offset=".5"/>\n'
			svg +='<stop stop-color="#FDFDF8" offset=".75"/>\n'
			svg +='<stop stop-color="#FFFDFA" offset="1"/>\n'
			svg +='</radialGradient>\n'
			svg +='<rect x="0" y="0" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(size[0])+'" height="'+str(size[1])+'" stroke="'+ablack+'" fill="url(#'+prefix+'whitegradient)" />\n';
			#svg +='<rect x="0" y="0" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(size[0])+'" height="'+str(size[1])+'" fill="url(#'+prefix+'whitegradient)" />\n';
		else :
			svg +='<rect x="0" y="0" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(size[0])+'" height="'+str(size[1])+'" stroke="'+ablack+'" fill="'+white+'" />\n';
			#svg +='<rect x="0" y="0" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(size[0])+'" height="'+str(size[1])+'" fill="'+white+'" />\n';
		length =size[0]/ipse.freq[0],size[1]/ipse.freq[1]

		ge =dgraph(ipse.path)
		ppath =None
		if num in (11,12,13) :
			w =size[0]*0.6
			h =size[1]*0.8
			x =(size[0]-w)/2
			y =(size[1]-h)/2
			svg +='<rect x="'+str(x)+'" y="'+str(y)+'" width="'+str(w)+'" height="'+str(h)+'" stroke="'+black+'" fill="'+white+'" />\n';
			ppath =ipse.persona_path[num-11]
		elif num == 1 :
			ppath =ipse.ace_path
		if num in (1,2,3,11,12,13) :
			xoffset =size[0]/2,
		else :
			xoffset =(size[0]-length[0])/2, (size[0]+length[0])/2
		if num == 1 :
			yoffset =size[1]/2,
		elif num in (11,12,13) :
			pa =length[1]/2*1.618
			yoffset =size[1]/2-pa, size[1]/2+pa
		else :
			yoffset =(size[1]-length[1])/2-length[1], (size[1]+length[1])/2+length[1]
		if num in (3,6,7) :
			yoffset +=size[1]/2,
		elif num in (8,9,10) :
			yoffset +=(size[1]-length[1])/2, (size[1]+length[1])/2
		offset =[]
		for xo in xoffset :
			for i,yo in enumerate(yoffset) :
				offset.append([i,xo,yo])
		if num == 5 :
			offset.append([0,size[0]/2,size[1]/2])
		if num == 7 :
			offset.append([0,size[0]/2,yoffset[2]-(yoffset[2]-yoffset[0])/2])
		if num in (9,10) :
			offset.append([0,size[0]/2,yoffset[2]-(yoffset[2]-yoffset[0])/2])
		if num == 10 :
			offset.append([1,size[0]/2,size[1]-offset[-1][2]])

		scale =(length[0]/ge.side)*element_scale
		if num == 1 :
			scale *=ace_scale
		if num in (11,12,13) :
			scale *=ace_scale
		m =scale,0,0,scale
		m1 =-scale,0,0,-scale
		if ppath :
			ga =dgraph(ppath)
			for i,xo,yo in offset :
				svg +='<path d="'+ga((xo,yo), m if i%2 else m1)+'" fill="'+ipse.color+'" />\n'
		else :
			for i,xo,yo in offset :
				svg +='<path d="'+ge((xo,yo), m if i%2 else m1)+'" fill="'+ipse.color+'" />\n'
				#svg +='<circle cx="'+str(xo)+'" cy="'+str(yo)+'" r="2" fill="blue" />\n'

		figure_scale =element_scale*0.7
		if num == 10 :
			g0 =dgraph(figure_path[0])
			g1 =dgraph(figure_path[10])
			cx,cy =g1.origin
			p =g1((cx-130,cy), (0.66666,0,0,1))
			p +=g0((cx+130,cy), (0.66666,0,0,1))
			gf =dgraph(p)
		else :
			gf =dgraph(figure_path[num])
		fscale =(length[0]/gf.side)*figure_scale
		gscale =(length[0]/ge.side)*figure_scale*0.75
		xoffset =size[0]-length[0]/5,length[0]/5
		for i,xo in enumerate(xoffset) :
			yoffset =length[1]*0.33333,length[1]*0.85
			if i%2 :
				yoffset =tuple(size[1]-yo for yo in yoffset)
			for j,yo in enumerate(yoffset) :
				if j%2 :
					m =[gscale,0,0,gscale] if i%2 else [-gscale,0,0,-gscale]
					svg +='<path d="'+ge((xo,yo), m)+'" fill="'+ipse.color+'" />\n'
				else :
					m =[fscale,0,0,fscale] if i%2 else [-fscale,0,0,-fscale]
					svg +='<path d="'+gf((xo,yo), m)+'" fill="'+ipse.color+'" />\n'
				#svg +='<circle cx="'+str(xo)+'" cy="'+str(yo)+'" r="2" fill="blue" />\n'
		svg +="</svg>\n"
		return svg

	def back(ipse, width, scale =5, prefix="") :
# gr =1.618
# poker size 2.5 x 3.5 inches (64x89mm), aka B8 size.
# bridge size 2.25 x 3.5 inches (57 x 89mm)
		rsize =width*ipse.rratio
		size =width, width/5*7
		length =size[0]/ipse.freq[0],size[1]/ipse.freq[1]
		svg ='<svg width="%f" height="%f" id="%sback">\n'%(size[0], size[1], prefix)
		if ipse.gradient :
			gradradius =size[0]

			svg +='<radialGradient id="'+prefix+'backgradient" cx="'+str(size[0]/2)+'" cy="'+str(size[1]/2)+'" r="'+str(gradradius)+'" gradientTransform="matrix(1 0 0 1 0 -.25)" gradientUnits="userSpaceOnUse">\n'
	#theme ="#210126"
			svg +='<stop stop-color="#120110" offset=".15"/>'
			svg +='<stop stop-color="#200120" offset=".35"/>'
			svg +='<stop stop-color="#310530" offset=".5"/>'
			svg +='<stop stop-color="#210120" offset=".75"/>'
			svg +='<stop stop-color="#182110" offset="1"/>'
			svg +='</radialGradient>'

		svg +='<rect x="0" y="0" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(size[0])+'" height="'+str(size[1])+'" stroke="'+ablack+'" fill="'+white+'" />\n';
		#svg +='<rect x="0" y="0" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(size[0])+'" height="'+str(size[1])+'" fill="'+white+'" />\n';

		bratio =0.95
		bsize =size[0]*bratio, size[1]*bratio
		xy =(size[0]-bsize[0])/2, (size[1]-bsize[1])/2
		bwidth =width*bratio
		rsize =rsize/2
		svg +='<rect x="'+str(xy[0])+'" y="'+str(xy[1])+'" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(bsize[0])+'" height="'+str(bsize[1])+'" stroke="'+theme+'" fill="'+theme+'" />\n';
		bratio =0.85
		bsize =size[0]*bratio, size[1]*bratio
		xy =(size[0]-bsize[0])/2, (size[1]-bsize[1])/2
		bwidth =width*bratio
		rsize =rsize/2
		if ipse.gradient :
			svg +='<rect x="'+str(xy[0])+'" y="'+str(xy[1])+'" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(bsize[0])+'" height="'+str(bsize[1])+'" stroke="'+white+'" fill="url(#'+prefix+'backgradient)" />\n';
		else :
			svg +='<rect x="'+str(xy[0])+'" y="'+str(xy[1])+'" rx="'+str(rsize)+'" ry="'+str(rsize)+'" width="'+str(bsize[0])+'" height="'+str(bsize[1])+'" stroke="'+white+'" fill="'+theme+'" />\n';
		gb =dgraph(ipse.back_path)
		o =size[0]/2, size[1]/2
		bscale =(length[0]/gb.side)*scale
		m =bscale,0,0,bscale
		svg +='<path d="'+gb(o, m)+'" fill="'+white+'" />\n'
		svg +="</svg>\n"
		return svg

