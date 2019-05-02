
MD=$(shell find *.md)
FLGS=$(foreach i,$(MD),cache/$i.flg)

.PHONY : all clean init
all : $(FLGS)

clean :
	rm -f $(FLGS)

cache/%.md.flg : %.md
	echo $<
	head -n 1 $<
	../novalis.py debug update $<
	../novalis.py check $<
	touch $@

init :
	bash init.sh
