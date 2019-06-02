#!/usr/bin/env python3

from os.path import dirname
from sys import path
path.append(dirname(__file__))
from myxml import legodoc, odocstyledefs

class textmissex :

  def __init__(ipse) :
    ipse.lodoc = legodoc(debug=1)
    ipse.lodoc.on("*:body", "opentag", ipse.openbody)
    ipse.lodoc.on("office:*", "opentag", ipse.openoffice)
    ipse.styl =odocstyledefs(ipse.lodoc)

  def openbody (ipse, ev, data) : 
    print("OPENBODY")
    ipse.lodoc.debug =2

  def openoffice (ipse, ev,data) : 
    print("OPENOFFICE")
    ipse.lodoc.debug =0

  def __call__(ipse, fnom) :
    ipse.lodoc(fnom)

class calcmissex :

  def style(ipse, data) :
    for d in data :
      if d["name"].split(":")[1] == "style-name" :
        if d["value"] == "Default" :
          return {}
        return ipse.sdefs.style[d["value"]]
    return {}

  def __init__(ipse) :
    ipse.lodoc = legodoc(debug=1)
    ipse.lodoc.on("office:body", "closetag", ipse.closebody)
    ipse.lodoc.on("office:body", "opentag", ipse.openbody)
    ipse.sdefs =odocstyledefs(ipse.lodoc)

  def closetablerow (ipse, ev, data) : 
    ipse.t.append(ipse.cell)
    ipse.cell =[]

  def opentable (ipse, ev, data) : 
    if ipse.t != None :
      ipse.tt[ipse.name] =ipse.t
    ipse.t =[]
    for d in data :
      if d["name"] == "table:name" :
        ipse.name =d["value"]
    if ipse.t != None :
      ipse.tt[ipse.name] =ipse.t

  def cellfrag(ipse, ev, data) :
    ipse.frag +=data

  def closetablecell (ipse, ev, data) : 
    ipse.lodoc.no("fragment", ipse.cellfrag)
    ipse.lodoc.no("text:span", "opentag", ipse.openspan)
    ipse.lodoc.no("table:table-cell", "closetag", ipse.closetablecell)
    ipse.cell.append(ipse.frag)
    del ipse.frag

  def openspan (ipse, ev, data) : 
    s =ipse.style(data)
    if "fo:font-style" in s and s["fo:font-style"] == "italic" :
      ipse.lodoc.on("text:span", "closetag", ipse.closespan)
      ipse.lodoc.no("fragment", ipse.cellfrag)
  
  def closespan (ipse, ev, data) : 
    ipse.lodoc.no("text:span", "closetag", ipse.closespan)
    ipse.lodoc.on("fragment", ipse.cellfrag)
    pass

  def opentablecell (ipse, ev, data) : 
    s =ipse.style(data)
    if "fo:font-style" not in s or s["fo:font-style"] != "italic" :
      ipse.lodoc.on("fragment", ipse.cellfrag)
      ipse.lodoc.on("text:span", "opentag", ipse.openspan)
      ipse.lodoc.on("table:table-cell", "closetag", ipse.closetablecell)
      ipse.frag =""

  def openbody (ipse, ev, data) : 
    ipse.lodoc.on("table:table", "opentag", ipse.opentable)
    ipse.lodoc.on("table:table-cell", "opentag", ipse.opentablecell)
    ipse.lodoc.on("table:table-row", "closetag", ipse.closetablerow)
    ipse.t =None
    ipse.tt ={}
    ipse.cell =[]
    #ipse.lodoc.xmlp.debug =2

  def closebody (ipse, ev, data) : 
    ipse.lodoc.no("table:table", "opentag", ipse.opentable)
    ipse.lodoc.no("table:table-cell", "opentag", ipse.opentablecell)
    ipse.lodoc.no("table:table-row", "closetag", ipse.closetablerow)

  def __call__(ipse, fnom) :
    ipse.lodoc(fnom)
    return ipse.tt

if __name__ == "__main__" :
  from sys import argv
  if len(argv) < 3 :
    print("modus : miss *.ods */   italicis-commentatae csv.")
  input("%s -> %s"%(argv[1], argv[2]))
  de,ad =argv[1:]
  if de[-3:].lower() == "ods" :
    cm =calcmissex()
    for snom,data in cm(argv[1]).items() :
      of =open(ad+"/"+snom.replace(" ", "-")+".sql", "w")
      for l in data :
        if l :
          of.write(" ".join(l) + ";\n")
    exit()
  def odtread(file_name) :
    se =textmissex()
    se(file_name)
    #lodoc = legodoc(debug=1)
    #lodoc(file_name)
  from sys import argv
  odtread("doc/graphe-exo.odt")

