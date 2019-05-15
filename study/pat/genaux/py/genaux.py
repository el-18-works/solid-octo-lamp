#!/usr/bin/env python3

class stack :

  def __init__(ipse) :
    ipse.a =[]
    #ipse.__len__ =ipse.a.__len__  

  def __len__(ipse) :
    return len(ipse.a)

  def push(ipse, x) :
    ipse.a.append(x)

  def pop(ipse) :
    x =ipse.a[-1]
    del ipse.a[-1]
    return x

  def top(ipse) :
    return ipse.a[-1]

class tracepyfile (stack) :

  def error(ipse, msg) :
    print(msg)
    exit(1)

  def __call__(ipse, filename, cb) :
    def resettabs(l) :
      for i in range(len(l)) :
        if not l[i].isspace() :
          return l[:i], l[i:]
      return l,""
    for i,l in enumerate(open(filename)) :
      if i == 0 :
        ipse.push('')
        cb("openfile", None)
        cb("open", l.strip())
        continue
      tabs,line =resettabs(l.rstrip())
      if not line or line[0] == '#' and tabs != ipse.top() :
        cb("comment", line)
      else :
        while len(tabs) < len(ipse.top()) :
          if ipse.top()[:len(tabs)] != tabs :
            ipse.error("%d : '%s' : (close) inconsistent shift spaces/tabs"%(i+1, l))
          ipse.pop()
          cb("close", None)
        if tabs != ipse.top() :
          if len(tabs) > len(ipse.top()) :
            if tabs[:len(ipse.top())] != ipse.top() :
              ipse.error("%d : '%s' : (open) inconsistent shift spaces/tabs"%(i+1, l))
            ipse.push(tabs)
            cb("open", line)
          else :
            ipse.error("%d : '%s' : inconsistent shift spaces/tabs"%(i+1, l))
        else :
          cb("line", line)

    while len(ipse) :
      ipse.pop()
      cb("close", None)
    cb("closefile", None)
    
class blocktree(stack) :

  def error(ipse, msg) :
    print(msg)
    exit(1)

  def __call__(ipse, filename) :
    ipse.filename =filename
    tpf =tracepyfile()
    tpf(filename, ipse.tree)
    return ipse.root

  def tree(ipse, e, data) :
    if e == "openfile" :
      ipse.push({"key" : "", "list":[{'key':'decl', 'data':"file :"}]})
    elif e == "open" :
      key =value =""
      udata =ipse.top()["list"][-1]['data']
      if udata :
        i =0
        while udata[i] not in ('(', ':') and not udata[i].isspace() :
          i +=1
        key =udata[:i]
        if key in ("for", "while", "if", "elif", "else") :
          value =""
        elif key in ("def", "class") :
          while udata[i].isspace() :
            i +=1
          j =i
          while udata[j] not in ('(', ':') and not udata[j].isspace() :
            j +=1
          value =udata[i:j]
        elif key == "file" :
          value =ipse.filename
        else :
          ipse.error("unknown key '%s'"%key)
      ipse.push({"key":key, "name":value, "list":[udata, {'key':'decl', 'data':data}]})
    elif e == "line" :
      ipse.top()["list"].append({'key':'line', 'data':data})
    elif e == "close" :
      data =ipse.pop()
      if data["key"] == "file" :
        ipse.root =data
      ipse.top()["list"][-1] =data
    elif e == "comment" :
      pass
      #print("comment",data)
    elif e == "closefile" :
      ipse.pop()

class filelist :

  def __init__(ipse) :
    ipse.lst ={}
    ipse.bt =blocktree()

  def __call__(ipse, filename, itemname, tab="  ", initindent=0) :
    if filename not in ipse.lst :
      ipse.lst[filename] ={}
      for l in ipse.bt(filename)["list"][1:] :
        if l["key"] in ("class", "def") :
          ipse.lst[filename][l["name"]] =l["list"]
    return ipse.lst[filename][itemname]

#for x in bt(__file__) :
  #print(x)
#print(bt(__file__))
fl =filelist()
print(fl(__file__, "filelist"))

