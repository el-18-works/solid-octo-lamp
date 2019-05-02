
MYSITE=/usr/local/my

SQLFILES=pylib.sql
SQLFLGS=$(foreach f,$(SQLFILES),cache/$(f).flg)
BINFILES=node myunison mypath mkpylib mkweb
BINFLGS=$(foreach f,$(BINFILES),cache/$(f).bin.flg)
ENVFILES=myenv vimrc
ENVFLGS=$(foreach f,$(ENVFILES),cache/$(f).flg)
CLEANFLGS=$(foreach f,$(BINFILES),cache/$(f).clean.flg)

.PHONY : all clean distclean

all :  $(BINFLGS) $(ENVFLGS) $(SQLFLGS)

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

cache/pylib.sql.flg : sql/pylib.sql
	mysql -u l18 -p@ -sB < $<
	touch $@

