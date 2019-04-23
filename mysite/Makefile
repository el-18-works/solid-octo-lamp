
SITEBIN=/usr/local/my/bin

BINFILES=node myunison mypath
BINFLGS=$(foreach f,$(BINFILES),cache/$(f).bin.flg)
FILES=myenv vimrc
FLGS=$(foreach f,$(FILES),cache/$(f).flg)

all :  $(BINFLGS) $(FLGS)
.PHONY clean :
	rm -f cache/*.flg

cache/%.bin.flg : %
	chmod +x $(PWD)/$(<)
	sudo ln -s $(PWD)/$(<) $(SITEBIN)/$(<) || true
	touch $@

cache/myenv.flg : myenv.sh
	cat $(HOME)/.bashrc | grep $(PWD)/$(<) || \
		echo ". $(PWD)/$(<)" >> $(HOME)/.bashrc
	touch $@

cache/vimrc.flg : vimrc
	test -f $(HOME)/.vimrc || \
		ln -s $(PWD)/vimrc $(HOME)/.vimrc
	mkdir -p $(HOME)/.vim/run
	touch $@

