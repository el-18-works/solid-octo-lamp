
"
"
"  concatenatio
"
"
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
    
class pyblocktree(stack) :

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
        if key in ("for", "while", "if", "elif", "else", "try", "except") :
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
      ipse.push({"key":key, "name":value, "list":[{'key':'decl', 'data':udata}, {'key':'line', 'data':data}]})
    elif e == "line" :
      ipse.top()["list"].append({'key':'line', 'data':data})
    elif e == "close" :
      data =ipse.pop()
      if data["key"] == "file" :
        ipse.root =data
      ipse.top()["list"][-1] =data
    elif e == "comment" :
      pass
#      print("comment",data)
    elif e == "closefile" :
      ipse.pop()

class pyfilelist :

  def __init__(ipse) :
    ipse.lst ={}
    ipse.bt =pyblocktree()

  def __call__(ipse, filename) :
    if filename not in ipse.lst :
      ipse.lst[filename] ={}
      for l in ipse.bt(filename)["list"][1:] :
        if l["key"] in ("class", "def") :
          ipse.lst[filename][l["name"]] =l["list"]
    return ipse.lst[filename]

class pyitem :

  def __init__(ipse) :
    ipse.fl =pyfilelist()
    
  def write(ipse, out, filename, itemname, tabchr="  ", tabshft=0) :
    def f(data, tabshft) :
      if data['key'] == 'decl' :
        out.write(tabchr*tabshft + data['data'] + '\n')
      elif data['key'] == 'line' :
        out.write(tabchr*(tabshft+1) + data['data'] + '\n')
      elif 'list' in data :
        for x in data['list'] :
          f(x, tabshft+1)
    out.write('\n')
    for data in ipse.fl(filename)[itemname] :
      f(data, tabshft)
    out.write('\n')

  def ls(ipse, filename) :
    return tuple(ipse.fl(filename).keys())

  def decl(ipse, filename, itemname) :
    s =ipse.fl(filename)[itemname][0]['data']
    s =s.rstrip()[:-1].rstrip()
    if s[0] == 'c' :
      ls =[]
      for l in ipse.fl(filename)[itemname] :
        if l["key"] in ("class", "def") :
          ls.append( "  "+l['list'][0]['data'] )
          #s += "  "+l['list'][0]['data'] + '\n'
    return s + '\n'.join(ls)
