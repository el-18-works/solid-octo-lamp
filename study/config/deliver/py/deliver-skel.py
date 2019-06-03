
#class opt :
#  def __init__(ipse) :
#    ipse.a =optparse()
#  def __contains__(ipse, flg) :
#    return flg in ipse.a['flgs']
#  def __getitem__(ipse, arg) :
#    return ipse.a[arg]
#  def __getattr__(ipse, arg) :
#    return ipse.a[arg]
#
#opt =opt()

if 'h' in opt :
  optutendi(nomen='deliver')
  exit()

from os import makedirs, getenv, curdir, chdir
from os.path import dirname, exists, expanduser, abspath, relpath

opt_force ='f' in opt
missing =[]
todo =[]

solenv ={"SOL_HOME" : expanduser("~/sol"), "MY_HOME" : "/usr/local/my"}
solenv["CWD"] =abspath(opt.c[-1] if len(opt.c) else curdir)
solenv["CAT_HOME"] =relpath(solenv["CWD"], solenv["SOL_HOME"]).split('/', 1)[0]
solenv["CACHE"] =solenv["CWD"] + "/cache"
solenv["BIN"] =solenv["CWD"] + "/cache/bin"
solenv["MY_BIN"] =solenv["CWD"] + "/cache/bin"

chdir(solenv["CWD"])

def get(s) :
  if s in solenv :
    return solenv[s]
  return getenv(s)

def solv(s) :
# 0 / 4
# 0 else 1 : path += c
# 1 [ 2
# 1 else 1 : path += c
# 2 ] 1 : path +=getenv(l) 
# 2 else :  l +=c
# 4 / 0 : path += SOL_HOME
# 4 else 0 : path += CAT_HOME + c
  path =""
  state =0
  for c in s :
    if state == 0 :
      if c == '/' :
        state =4
      else :
        state =1
        path +=c
    elif state == 1 :
      if c == '[' :
        state =2
      else :
        state =1
        path +=c
    elif state == 2 :
      if c == ']' :
        state =3
        l =c
      else :
        state =1
        path +='$' + c
    elif state == 3 :
      if c.isalnum() or c == '_' :
        state =3
        l +=c
      else :
        state =1
        path += get(l) + c
    elif state == 4 :
      if '/' :
        state =0
        path +=solenv["SOL_HOME"]
      else :
        state =0
        path +=solenv["CAT_HOME"] + c
  return path

for l in open("Dep/d.lst") :
  de, ad =(solv(x) for x in l.split())
  if not exists(de) :
    missing.append(de)
  else :
    todo.append((de,ad))

if missing :
  print("one of the following file(s) is missing :")
  for m in missing :
    print(m)
  if not opt_force :
    exit(1)

for de,ad in todo :
  if not exists(dirname(ad)) :
    makedirs(dirname(ad))
  if 'n' in opt :
    print("deliver %s -> %s"%(de,ad))
  else :
    buf =open(de, "rb").read()
    print("deliver %s -> %s (%d)"%(de,ad,len(buf)))
    open(ad, "wb").write(buf)

