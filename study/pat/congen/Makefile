
MYBIN=/usr/local/my/bin
.PHONY : install
install : $(MYBIN)/zuo
$(MYBIN)/zuo : cache/bin/zuo
	cp $<  $@
distclean :
	rm -f $(MYBIN)/zuo
cache/bin/zuo : py/zuo.py py/bloc.py
	echo "#!/usr/bin/env python3" > $@
	$< py/bloc.py "*" >> $@
	$< py/zuo.py "*" >> $@
	echo "main()" >> $@
	echo "" >> $@
Makefile : Zuo
	py/bloc.py $< > $@
