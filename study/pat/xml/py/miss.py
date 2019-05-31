#!/usr/bin/env python3

from os.path import dirname
from sys import path
path.append(dirname(__file__))
from myxml import legodt, odtstyledefs

class missex :

  def __init__(ipse) :
    ipse.lodt = legodt(debug=1)
    ipse.lodt.on("*:body", "opentag", ipse.openbody)
    ipse.lodt.on("office:*", "opentag", ipse.openoffice)
    ipse.styl =odtstyledefs(ipse.lodt)

  def openbody (ipse, ev, data) : 
    print("OPENBODY")
    ipse.lodt.debug =2

  def openoffice (ipse, ev,data) : 
    print("OPENOFFICE")
    ipse.lodt.debug =0

  def __call__(ipse, fnom) :
    ipse.lodt(fnom)

if __name__ == "__main__" :
  from sys import argv
  def odtread(file_name) :
    se =missex()
    se(file_name)
    #lodt = legodt(debug=1)
    #lodt(file_name)
  from sys import argv
  odtread("doc/graphe-exo.odt")

