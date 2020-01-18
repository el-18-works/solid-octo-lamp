
.IMPORT : MYHOME
MYSITE=$(MYHOME)
ifeq ($(MYSITE),)
MYSITE=/usr/local/my
endif
SQLFILES=create-user.sql create-schema.sql create-pylib.sql create-vimrc.sql luckxa-create-txt.sql
SQLFLGS=$(foreach f,$(SQLFILES),cache/$(f).flg)
SQLGENFILES=$(foreach f,$(SQLFILES),cache/sql/$(f))
ODSFILES=doc/myhost-sql.ods
ODSFLGS=$(foreach f,$(ODSFILES),cache/$(f).flg)
BINFILES=node myunison mypath mkpylib pylib mkweb packme deliver
BINFLGS=$(foreach f,$(BINFILES),cache/$(f).bin.flg)
ENVFILES=myenv vimrc my.mk packme.mk
ENVFLGS=$(foreach f,$(ENVFILES),cache/$(f).flg)
PYLIBFILES=pylibdb pylibcon pyvimrc
PYLIBFLGS=$(foreach f,$(PYLIBFILES),cache/$f.flg)
CLEANFLGS=$(foreach f,$(BINFILES),cache/$(f).clean.flg)
BASHRC = $(wildcard ~/.bash_profile)
ifeq ($(BASHRC),)
BASHRC = $(wildcard  ~/.bashrc)
endif
vpath %.sql cache/sql
vpath %.py py
.PHONY : all clean distclean
clean :
	rm -f cache/*.flg
cache/%.clean.flg : bin/%
	rm -f $(MYSITE)/$(<)
	touch $(@)
distclean : $(CLEANFLGS)
.PHONY : env
env : $(ENVFLGS)
all :  env
cache/myenv.flg : env/myenv.sh
	cat $(BASHRC) | grep $(PWD)/$(<) || \
		echo ". $(PWD)/$(<)" >> $(BASHRC)
	touch $@
cache/vimrc.flg : env/vimrc
	test -f $(HOME)/.vimrc || \
		ln -sf $(PWD)/$(<) $(HOME)/.vimrc
	mkdir -p $(HOME)/.vim/run
	touch $@
cache/my.mk.flg : env/my.mk
	test -f $(MYSITE)/share/$< || \
		ln -sf $(PWD)/$< $(MYSITE)/share/$(<F)
	touch $@
.PHONY : bin
bin :  $(BINFLGS)
all :  bin
cache/%.bin.flg : bin/%
	chmod +x $(PWD)/$(<)
	sudo ln -s $(PWD)/$(<) $(MYSITE)/$(<) || true
	touch $@
.PHONY : sql
sql :  $(SQLFLGS)
all :  sql
cache/myhost-sql.ods.flg : doc/myhost-sql.ods
	mkdir -p cache/sql
	miss $< cache/sql
	touch $@
$(SQLGENFILES) : cache/myhost-sql.ods.flg
cache/sql/create-user.sql : cache/myhost-sql.ods.flg
cache/create-user.sql.flg : cache/sql/create-user.sql
	sudo mysql -u root -pMayLily -sB < $<
	touch $@
cache/create-schema.sql.flg : cache/sql/create-schema.sql
	@sudo mysql -u root -pMayLily -sB < $<
	touch $@
cache/luckxa-create-txt.sql.flg : cache/sql/luckxa-create-txt.sql
	@mysql -u luckxa -pMyLinka -sB < $<
	touch $@
cache/create-pylib.sql.flg : cache/sql/create-pylib.sql
	mysql -u l18 -p@ -sB < $<
	touch $@
cache/create-vimrc.sql.flg : cache/sql/create-vimrc.sql
	@mysql -u l18 -p@ -sB < $<
	touch $@
cache/packme.mk.flg : env/packme.mk
	test -f $(MYSITE)/share/$< || \
		ln -sf $(PWD)/$< $(MYSITE)/share/$(<F)
	touch $@
all :  $(PYLIBFLGS)
.PHONY : bin/packme
bin/packme : py/pack.py cache/packop.py
	tail -n +1 cache/packop.py > $@
	echo >> $@
	cat $< >> $@
	python3 $@ -m cache/spec.mk
cache/packme.flg : bin/packme
	test -f $(MYSITE)/bin/$< || \
		ln -sf $(PWD)/$< $(MYSITE)/bin/$(<F)
	touch $@
cache/pylibdb.flg : py/pylibdb.py bin/pylib $(MYSITE)/lib/python3/my/pylibcon.py
	bin/pylib mkpylib $<
	touch $@
cache/pylibcon.flg : py/pylibcon.py bin/pylib $(MYSITE)/lib/python3/my/pylibcon.py
	bin/pylib mkpylib $<
	touch $@
cache/pyvimrc.flg : vimrc.py bin/pylib $(MYSITE)/lib/python3/my/pylibcon.py
	bin/pylib mkpylib $<
	touch $@
$(MYSITE)/lib/python3/my/pylibcon.py :
	mkdir -p $(@D)
	cp py/pylibcon.py $@
	cp py/pylibdb.py $(@D)/pylibdb.py
cache/packop.py : cache/pylibcon.flg
	python3 -c "from my.pylibcon import gengetopt; gengetopt('$@', [('-m,--mini','m'),],0)"
Makefile : Zuo
	zuo -f $< > $@
