
WORKS=init-mysql php
FLGS=$(foreach i,$(WORKS),cache/$(i).flg)

.IMPORT : MYSHARE
MYPHP=$(MYSHARE)/php

all : $(FLGS)

.PHONY clean :
	rm -f $(FLGS)

cache/init-mysql.flg :
	sudo MYSQL_PWD=MayLily mysql -u root -sB <create-user.sql
	touch $@

cache/php.flg :
	@echo "configurando $(MYPHP) :"
	php -i | grep include_path | grep $(MYPHP) ||\
		./init-php-ini.sh
	test -e $(MYPHP) ||\
		ln -sf $(PWD)/php $(MYPHP)
	realpath $(MYPHP) | grep ^$(PWD)/php$$ ||\
		exit 1
	touch $@

