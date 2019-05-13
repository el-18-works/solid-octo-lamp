#!/usr/bin/python3

class TapeScan :
	@classmethod 
	def onglobal(ipse, data) : pass
	@classmethod 
	def onchar(ipse, data) : pass

	@classmethod
	def read_property(ipse, s) :
		if len(s) == 0 :
			return s
		if s[-1] == '"' :
			i =len(s)-1
			j =s.rfind('"', 0, i)
			while j > 0 :
				if s[j-1] != '"' :
					break
				s =s[:j]+s[j+1:]
				i =j-1
				j =s.rfind('"', 0, i)
			if j != 0 :
				ipse.error("trailing characters after quoted string '%s'"%s) 
			return s[1:-1]
		elif s[0] == '"' :
			ipse.error("trailing characters after quoted string '%s'"%s) 
		else :
			if s.count(".") == 1 : 
				return float(s)
			else :
				return int(s)
		return s

	def scan_globals(ipse) :
		key,value =next(ipse.get())
		if key == "COMMENT" :
			pass
		elif key in ["STARTFONT"] :
			ipse.data[key] =float(value)
		elif key in ["CONTENTVERSION","METRICSSET"] :
			ipse.data[key] = int(value)
		elif key in ["FONT"] :
			ipse.data[key] = value
		elif key in ["SIZE","FONTBOUNDINGBOX"] :
			ipse.data[key] =[int(i) for i in value.split(" ")]
		elif key in ["SWIDTH","DWIDTH", "SWIDTH1", "DWIDTH1", "VVECTOR"] :
			if "STARTFONT" not in ipse.data or ipse.data["STARTFONT"] <= 2.1 :
				ipse.info("Version 2.1 or prior allows metrics keyword '%s' only at the glyph level"%key)
			ipse.metrics[key] =int(value) if key != "VVECTOR" else [int(i) for i in value.split(" ")]
		elif key == "STARTPROPERTIES" :
			props ={}
			nprops =int(value)
			while nprops :
				nprops -=1
				key,value =next(ipse.get())
				if key == "ENDPROPERTIES" :
					ipse.info("Properties count mismatch")
					break
				props[key] =ipse.read_property(value)
			key,value =next(ipse.get())
			while key != "ENDPROPERTIES" :
				ipse.info("Properties count mismatch")
				props[key] =ipse.read_property(value)
			if props :
				ipse.data["properties"] =props
			return False
		return True

	def read_char(ipse) :
		key,value =next(ipse.get())
		if key == "STARTCHAR" :
			if len(key) > 14 and ("STARTFONT" not in ipse.data or ipse.data["STARTFONT"] <= 2.1) :
				ipse.info("In versions prior to 2.2, STARTCHAR was limited to a string of 14 characters")
			ipse.c["char"] =value
		elif key == "ENCODING" :
			ipse.c["encoding"] =int(value)
		elif key == "SWIDTH" :
			ipse.c["swidth"] =[int(i) for i in value.split(" ")]
		elif key == "DWIDTH" :
			ipse.c["dwidth"] =[int(i) for i in value.split(" ")]
		elif key == "SWIDTH1" :
			if ipse.data["METRICSSET"] == 0 :
				ipse.info("If METRICSSET is 1 or 2, both SWIDTH1 and DWIDTH1 must be present; if METRICCSSET is 0, both shoud be absent")
			ipse.c["swidth1"] =[int(i) for i in value.split(" ")]
		elif key == "DWIDTH1" :
			if ipse.data["METRICSSET"] == 0 :
				ipse.info("If METRICSSET is 1 or 2, both SWIDTH1 and DWIDTH1 must be present; if METRICCSSET is 0, both shoud be absent")
			ipse.c["dwidth1"] =[int(i) for i in value.split(" ")]
		elif key == "VVECTOR" :
			if ipse.data["METRICSSET"] == 0 :
				ipse.info("If METRICCSSET is 0, VVECTOR should be absent")
			ipse.c["vvector"] =[int(i) for i in value.split(" ")]
		elif key == "BBX" :
			ipse.c["bbx"] =[int(i) for i in value.split(" ")]
		elif key == "BITMAP" :
			a =[]
			for n in range(ipse.c["bbx"][1]) :
				key,value =next(ipse.get())
				if key == "ENDCHAR" :
					ipse.error("Char length mismatch")
				a.append(int(key, 16))
			key,value =next(ipse.get())
			if key != "ENDCHAR" :
				ipse.error("Char length mismatch")
			ipse.c["bitmap"] =a
			return False
		elif key == "ENDFONT" :
			ipse.error("Glyphs count mismatch")
		else :
			ipse.error("Unexpected data", key, value)
		return True

	def __call__(ipse) :
		ipse.data ={}
		ipse.metrics ={}
		try :
			ipse.data["METRICSSET"] =0
			while ipse.scan_globals() : pass
			ipse.onglobal(ipse.data)
			key,value =next(ipse.get())
			if key != "CHARS" :
				ipse.error("CHARS expected")
			ipse.nglyphs =int(value)
			for n in range(ipse.nglyphs) :
				ipse.c ={}
				while ipse.read_char() : pass
				if "char" not in ipse.c :
					ipse.error(ipse.c)
				if "swidth1" not in ipse.c and "SWIDTH1" in ipse.data :
					ipse.c["swidth1"] = ipse.data["SWIDTH1"]
				if "dwidth1" not in ipse.c and "DWIDTH1" in ipse.data :
					ipse.c["dwidth1"] = ipse.data["DWIDTH1"]
				if "vvector" not in ipse.c and "VVECTOR" in ipse.data :
					ipse.c["vvector"] = ipse.data["VVECTOR"]
				if ("swidth1" not in ipse.c or "dwidth1" not in ipse.c or "vvector" not in ipse.c) and ipse.data["METRICSSET"] != 0 :
					ipse.info("If METRICSSET is 1 or 2, both SWIDTH1 and DWIDTH1 must be present; if METRICCSSET is 0, both shoud be absent")
				ipse.onchar(ipse.c)
			key,value =next(ipse.get())
			if key != "ENDFONT" :
				ipse.error("Glyphs count mismatch")
		except StopIteration as e :
			return
	

class BDFParse :

	def info(ipse, message) :
		print("%s:%d: %s"%(ipse.file_name, ipse.pos, message))

	def error(ipse, message) :
		print("%s:%d: %s"%(ipse.file_name, ipse.pos, message))
		exit(1)

	def unput(ipse, c) :
		ipse.unput_buffer.push(c)

	def put(ipse) :
		for l in ipse.input :
			ipse.pos +=1
			s =l.find(" ")
			yield l[:s],l[s+1:-1] if s > 0 else l[:-1],

	def on(ipse, event, data) :
		pass

	def __call__(ipse, onchar =lambda x : x, onglobal =lambda x : x) :
		ts =TapeScan()
		ts.info =ipse.info
		ts.error =ipse.error
		ts.get =ipse.put
		ts.unget =ipse.unput
		ts.onchar =onchar
		ts.onglobal =onglobal
		ipse.pos =0
		return ts()

if __name__ == "__main__" :
	from sys import argv, stdout
	debug =len(argv) > 1 and "debug" in argv 
	output =stdout
	def putout(s) :
		output.write(s)
		output.write("\n")
		
	bp =BDFParse()
	bp.file_name ="fonts/k8x12.bdf"
	bp.input =open("fonts/k8x12.bdf")
	def onglobal(data, debug=debug) :
		global off
		fbbx, fbby, xoff, yoff =data["FONTBOUNDINGBOX"]
		fbb=fbbx,fbby
		off =xoff,yoff
		if debug :
			print(data)
			print (fbb)

	def onchar(data, debug=debug) :
		encoding =data["encoding"]
		bbox =data["bbx"]
		bitmap =data["bitmap"]
		char =data["char"]
		if debug :
			print("encoding=",encoding,bitmap,bbox)
			for i in range(9-(bbox[1]+bbox[3])) :
				print()
			for b in bitmap : 
				b |= (1<<16)
				print(bin(b)[-8:].replace("1", "#").replace("0", " ")+":")
			for i in range(off[1], bbox[3]) :
				print()
			input(data)
		putout("    case %s : /* %s */"%(hex(int(encoding)), char))
		i =(9-(bbox[1]+bbox[3]))
		for b in bitmap : 
			b |= (1<<16)
			for j,c in enumerate(bin(b)[-8:]) :
				if c == "1" :
					putout("      callback(%d,%d);"%(j,i))
			i +=1
		putout("      break;")

	def amont() :
		putout("function %s(encoding, callback) {"%fnc)
		putout("  switch (encoding) {")
	def aval() :
		putout("  }")
		putout("}")

	from sys import argv 
	if len(argv) > 3 and argv[1] == "makejs" :
		print("make js")
		output =open(argv[2], "w")
		fnc =argv[3]
		amont()
		bp(onchar,onglobal)
		aval()
	else :
		bp(onchar,onglobal)

