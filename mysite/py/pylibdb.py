#!/usr/bin/python3

import pymysql
from os.path import basename
from os import getenv
from sys import argv
import re

DEBUG=0

class MyCon :
  SCHEMA ="mysite_pylib_defs"
  def __init__(ipse, modus="") :
    ipse.schema =ipse.SCHEMA
    ipse.db =pymysql.connect("localhost", "l18", "@", "l18")

  def __del__(ipse) :
    ipse.db.close()
    
  def test_connect(ipse) :
    cursor =ipse.db.cursor()
    cursor.execute("SELECT VERSION()")
    data =cursor.fetchone()
    print ("mysql version : %s"%data)

  # DQL
  def q(ipse, q, unicum =False) :
    cursor =ipse.db.cursor()
    if DEBUG : print(q)
    cursor.execute(q)
    res =cursor.fetchone() if unicum else cursor.fetchall()
    return res

  # DML
  def m(ipse, q, p=None) :
    cursor =ipse.db.cursor()
    if DEBUG : 
      print(q)
      print(p)
    try :
      if p == None :
        num =cursor.execute(q)
      else :
        num =cursor.execute(q, p)
      ipse.db.commit()
      return num
    except Exception as e:
      print(e)
      ipse.db.rollback()
      return -1

  def lupdata(ipse, fun) :
    res =ipse.q("SELECT args,rets,fi,li FROM "+ipse.schema+" WHERE def='%s'"%fun, 1)
    if res :
      return {"def":fun, "args":res[0], "rets":set(res[1].split("\n")), "fi":res[2], "li":res[3]}

  def mkdata(ipse, d) :
    lst =[d["def"], d["args"], "\n".join(d["rets"]), d["fi"], d["li"]]
    values ="VALUES(%s, %s, %s, %s, %s)"
    if ipse.m(q="INSERT INTO "+ipse.schema+"(def, args, rets, fi, li) "+values, p=lst) != -1 :
      pass
    else :
      print("error!")
      exit(1)

  def updata(ipse, d) :
    lst =[d["args"], "\n".join(d["rets"]), d["li"]]
    if ipse.m(q="UPDATE "+ipse.schema+" SET args=%s, rets=%s, li=%s WHERE " + "def='%s'"%d["def"], p=lst) != -1 :
      pass
    else :
      print("error!")
      exit(1)

  def prepa(ipse, fi) :
    q ="DELETE FROM "+ipse.schema+" WHERE fi='%s'"%fi
    num =ipse.m(q=q)
    if num >= 0 :
      return num
    else :
      print("error!")
      exit(1)

  # XXX...
  def check(ipse, id) :
    res =ipse.q("SELECT id,a,m,d,h,txt FROM "+ipse.schema+" WHERE id="+str(id))
    for r in res :
      print (r)

mc =MyCon("test" if len(argv) > 1 and "test" in argv else "")
if "debug" in argv :
  mc.test_connect()  

def mkdata(fundefs) :
  if fundefs["rets"] == None :
    fundefs["rets"] =[]
  d =mc.lupdata(fundefs["def"])
  if d :
    if d != fundefs :
      print("updata %s"%fundefs["def"])
      if d["fi"] != fundefs["fi"] :
        print("multiplices definitiones de '%s'"%fundefs["def"])
        exit(1)
      mc.updata(fundefs)
  else :
    print("mkdata %s"%fundefs["def"])
    mc.mkdata(fundefs)

def mkpylib(srcpath) :
  fi =basename(srcpath)
  print("%d nomina anihilata"%mc.prepa(fi))
  destpath =getenv("MYPYLIB")+"/my/"+fi
  o =open(destpath, "w")
  class_pattern =re.compile("^class\s+(\w)+.*:")
  def_pattern =re.compile("^def\s+(\w+)\s*\((.+,?)*\)\s*:")
  ret_pattern =re.compile("^\s+return\s+(.*)$")
  fundef =None
  for li,l in enumerate(open(srcpath)) :
    o.write(l)
    if class_pattern.match(l) :
      if fundef :
        mkdata(fundef)
        fundef =None
      continue
    match =def_pattern.findall(l)
    if len(match) :
      fn, args =match[0]
      flg_infun =True
      if fundef :
        mkdata(fundef)
      fundef ={"fi":fi, "li":li, "def":fn, "args":args, "rets":None}
    if fundef == None :
      continue
    match =ret_pattern.findall(l)
    if len(match) :
      if fundef["rets"] == None :
        fundef["rets"] = set()
      fundef["rets"].add(match[0])
  if fundef :
    mkdata(fundef)
  o.close()

def info(fi=None, df=None) :
  if fi == None :
    res =mc.q("SELECT DISTINCT fi FROM "+mc.schema+"")
    if res :
      for fi in res :
        print(fi[0])
  elif df == None :
    if fi[-3:] != '.py' :
      fi +='.py'
    res =mc.q("SELECT def FROM "+mc.schema+" WHERE fi='%s'"%fi)
    if res :
      for d in res :
        print(d[0])
  else :
    if fi[-3:] != '.py' :
      fi +='.py'
    res =mc.q("SELECT li,args,rets FROM "+mc.schema+" WHERE fi='%s' AND def='%s'"%(fi, df))
    if res :
      for d in res :
        print("%d: (%s) => (%s)"%d)

def remove(srcpath) :
  fi =basename(srcpath)
  if fi[-3:] != '.py' :
    fi +='.py'
  print("%d nomina anihilata"%mc.prepa(fi))
  import os
  try :
    os.remove("/usr/local/my/lib/python3/my/"+fi)
  except :
    pass

if __name__ == "__main__" :
  if "info" in argv :
    idx =argv.index("info")
    if idx<len(argv)-2 : 
      info(argv[idx+1], argv[idx+2])
    elif idx<len(argv)-1 : 
      info(argv[idx+1])
    else : 
      info()
  if "mkpylib" in argv :
    idx =argv.index("mkpylib")
    if idx<len(argv)-1 : 
      mkpylib(argv[idx+1])
  if "remove" in argv :
    idx =argv.index("remove")
    if idx<len(argv)-1 : 
      remove(argv[idx+1])

