PHP_INI =/usr/local/etc/php/7.3/php.ini
PHP_OPT =-c $(PHP_INI)
INSTALLDIR=/usr/local/share/l18works
PHP=$(PHPBINDIR)/php
PHPBINDIR=/usr/local/Cellar/php/7.3.3/bin

MYSQL_DOWNLOADS_SITE="http://downloads.mysql.com/docs/"
SAKILA_TGZ="sakila-db.tar.gz"

LIBS =l18data.php

.PHONY qa : relational-models.php
	$(PHP) $(PHP_OPT) -f $(<)
	
install : $(LIBS)
	cp -f $(<) $(INSTALLDIR)

MYSQL_DOWNLOADS_SITE="http://downloads.mysql.com/docs/"
$(SAKILA_TGZ) : 
	wget $(MYSQL_DOWNLOADS_SITE)$(SAKILA_TGZ)

sakila-db/sakila.mwb : $(SAKILA_TGZ)
	tar xvf $(<)

