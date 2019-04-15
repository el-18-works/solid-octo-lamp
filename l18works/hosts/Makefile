
WORKS=init-mysql php-ini
FLGS=$(foreach i,$(WORKS),cache/$(i).flg)

.IMPORT : MYSHARE
MYPHPINI=$(MYSHARE)/php.ini

all : $(FLGS)

.PHONY clean :
	rm -f $(FLGS)

cache/init-mysql.flg :
	sudo MYSQL_PWD=MayLily mysql -u root -sB <create-user.sql
	touch $@

cache/php-ini.flg :
	@echo "configurando $(MYPHPINI) :"
	php -i | grep include_path | grep $(MYPHPINI) ||\
		./init-php-ini.sh
	touch $@

