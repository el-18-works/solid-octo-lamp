
WORKS=init-mysql
FLGS=$(foreach i,$(WORKS),cache/$(i).flg)

all : $(FLGS)

cache/init-mysql.flg :
	sudo MYSQL_PWD=MayLily mysql -u root -sB <create-user.sql

