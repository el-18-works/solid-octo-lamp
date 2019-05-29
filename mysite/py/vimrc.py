#!/usr/bin/python3

import pymysql
from os.path import basename
from os import getenv
from sys import argv
import re

DEBUG=0

class MyCon :
	SCHEMA ="vimrc_zq"
	def __init__(ipse, modus="") :
		ipse.schema =ipse.SCHEMA
		ipse.db =pymysql.connect("localhost", "luckxa", "MyLinka", "study")

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
			print("q=",q)
			print("p=",p)
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

	def get(ipse, path) :
		res =ipse.q("SELECT id,path,s FROM "+ipse.schema+" WHERE path='%s'"%path, 1)
		if res :
			return res
		return None, path, ""

	def put(ipse, path, s) :
		id,path,t =ipse.get(path)
		if id != None :
			ipse.m(q="UPDATE "+ipse.schema+" SET s=%s WHERE id="+str(id), p=[s])
		else :
			ipse.m(q="INSERT INTO "+ipse.schema+" (path,s) VALUES('"+path+"',%s)", p=[s])
	
	def check(ipse) :
		res =ipse.q("SELECT * FROM "+ipse.schema)
		return res


mc =MyCon("test" if len(argv) > 1 and "test" in argv else "")
if "debug" in argv :
	mc.test_connect()	

def put(path=None, s="") :
	mc.put(path or getenv("PWD"), s)

def get(path=None) :
	id,path,s =mc.get(path or getenv("PWD"))
	print(s)

if __name__ == "__main__" :
	if len(argv) < 2 :
		exit()
	elif argv[1] == "put" :
		path =argv[2] if len(argv) >= 3 else getenv("PWD")
		s =argv[3] if len(argv) >= 4 else ""
		mc.put(path,s)
	elif argv[1] == "get" :
		path =argv[2] if len(argv) >= 3 else getenv("PWD")
		id,path,s =mc.get(path)
		print(s)
	else :
		for i,path,s in mc.check() :
			print(path,s)

