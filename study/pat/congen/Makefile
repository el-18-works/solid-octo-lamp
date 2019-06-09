
MYBIN=/usr/local/my/bin
.PHONY : install
install : $(MYBIN)/zuo
uname=$(shell uname)
$(MYBIN)/zuo : cache/bin/zuo
	sudo cp $<  $@
	sudo chown root $@
ifeq ($(uname),Darwin)
	sudo chgrp wheel $@
else
	sudo chgrp root $@
endif
	sudo chmod 0755 $@
.PHONY : distclean
distclean : ; sudo rm -f $(MYBIN)/zuo
cache/bin/zuo : py/zuo.py py/bloc.py py/zuocom.py py/comset.py
	echo "#!/usr/bin/env python3" > $@
	echo "" >> $@
	$< py/bloc.py "*" >> $@
	echo "" >> $@
	$< py/zuocom.py "*" >> $@
	echo "" >> $@
	py/zuocom.py  >> $@
	echo "" >> $@
Makefile : Zuo
	py/zuo.py $< > $@
