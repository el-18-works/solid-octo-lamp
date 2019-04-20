
MD=$(shell find *.md)
FLGS=$(foreach i,$(MD),cache/$i.flg)

all : $(FLGS)

clean :
	rm -f $(FLGS)

cache/%.md.flg : %.md
	echo $<
	head -n 1 $<
	../novalis.py test debug update $<
	../novalis.py check $<
	touch $@


