
WORKS=createtable-txt insert-init
FLGS=$(foreach i,$(WORKS),cache/$(i).flg)

.IMPORT : MYSHARE

all : $(FLGS)

.PHONY clean :
	rm -f $(FLGS)

cache/createtable-txt.flg :
	sudo MYSQL_PWD=@ mysql -u l18 -sB <createtable-txt.sql
	touch $@

cache/insert-init.flg :
	./insert.py data.txt cache/insert.sql
	cat cache/insert.sql
	read x
	sudo MYSQL_PWD=@ mysql -u l18 l18 -sB <cache/insert.sql
	touch $@	
