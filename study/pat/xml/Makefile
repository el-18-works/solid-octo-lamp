
.PHONY : all
all : install
all : deliver
.PHONY : install
.PHONY : deliver
distclean :
	sudo rm -f $(MYBIN)/miss
install : $(MYBIN)/miss
$(MYBIN)/miss : cache/bin/miss
	sudo cp $< $@
	sudo chgrp root $@
	sudo chown root $@
	sudo chmod 0755 $@
cache/bin/miss : py/miss.py py/myxml.py
	echo "#!/usr/bin/env python3" > $@
	echo "" >> $@
	zuo py/myxml.py "*" >> $@
	zuo py/miss.py "*" >> $@
	echo "main()" >> $@
deliver : ~/sol/mysite/doc/myhost-sql.ods
~/sol/mysite/doc/myhost-sql.ods : doc/myhost-sql.ods
	mkdir -p `dirname $@`
	cp $< $@
Makefile : Zuo
	zuo $< > $@
