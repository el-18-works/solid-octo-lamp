
.IMPORT : MYHOME
MYSITE=$(MYHOME)
MYSITE?=/usr/local/my

SQLFILES=pylib.sql vimrc.sql
SQLFLGS=$(foreach f,$(SQLFILES),cache/$(f).flg)
BINFILES=node myunison mypath mkpylib pylib mkweb packme deliver
BINFLGS=$(foreach f,$(BINFILES),cache/$(f).bin.flg)
ENVFILES=myenv vimrc my.mk packme.mk
ENVFLGS=$(foreach f,$(ENVFILES),cache/$(f).flg)
PYLIBFILES=pylib con pyvimrc
PYLIBFLGS=$(foreach f,$(PYLIBFILES),cache/$f.flg)
CLEANFLGS=$(foreach f,$(BINFILES),cache/$(f).clean.flg)

vpath %.sql sql
vpath %.py py

.PHONY : all clean distclean

all :  $(BINFLGS) $(ENVFLGS) $(SQLFLGS) $(PYLIBFLGS)

clean :
	rm -f cache/*.flg

cache/%.clean.flg : bin/%
	rm -f $(MYSITE)/$(<)
	touch $(@)

distclean : $(CLEANFLGS)

cache/%.bin.flg : bin/%
	chmod +x $(PWD)/$(<)
	sudo ln -s $(PWD)/$(<) $(MYSITE)/$(<) || true
	touch $@

cache/myenv.flg : env/myenv.sh
	cat $(HOME)/.bashrc | grep $(PWD)/$(<) || \
		echo ". $(PWD)/$(<)" >> $(HOME)/.bashrc
	touch $@

cache/vimrc.flg : env/vimrc
	test -f $(HOME)/.vimrc || \
		ln -sf $(PWD)/$(<) $(HOME)/.vimrc
	mkdir -p $(HOME)/.vim/run
	touch $@

cache/my.mk.flg : env/my.mk
	test -f $(MYHOME)/share/$< || \
		ln -sf $(PWD)/$< $(MYHOME)/share/$(<F)
	touch $@
	
cache/packme.mk.flg : env/packme.mk
	test -f $(MYHOME)/share/$< || \
		ln -sf $(PWD)/$< $(MYHOME)/share/$(<F)
	touch $@

cache/pylib.sql.flg : pylib.sql
	mysql -u l18 -p@ -sB < $<
	touch $@

cache/vimrc.sql.flg : vimrc.sql
	mysql -u l18 -p@ -sB < $<
	touch $@

.PHONY : bin/packme
bin/packme : py/pack.py cache/packop.py
	tail -n +1 cache/packop.py > $@ 
	echo >> $@
	cat $< >> $@
	python3 $@ -m cache/spec.mk

cache/packme.flg : bin/packme
	test -f $(MYHOME)/bin/$< || \
		ln -sf $(PWD)/$< $(MYHOME)/bin/$(<F)
	touch $@

cache/pylib.flg : py/pylib.py bin/mkpylib
	mkpylib $<
	touch $@

cache/con.flg : py/con.py bin/mkpylib
	mkpylib $<
	touch $@

cache/pyvimrc.flg : vimrc.py bin/mkpylib
	mkpylib $<
	touch $@

cache/packop.py : cache/con.flg
	python3 -c "from my.con import gengetopt; gengetopt('$@', [('-m,--mini','m'),],0)"


