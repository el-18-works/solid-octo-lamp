
SITEBIN=/usr/local/bin

FILES=node
FLGS=$(foreach f,$(FILES),cache/$(f).flg)

all : $(FLGS)

cache/%.flg : %
	sudo ln -s $(PWD)/$(<) $(SITEBIN)/$(<) || true
	touch $@

