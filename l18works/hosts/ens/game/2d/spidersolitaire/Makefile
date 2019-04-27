
WORKS=cartes.js
FLGS=$(foreach i,$(WORKS),cache/$(i).flg)
DEST=`mypath --web`
all : $(FLGS)

.PHONY cache/cartes.js.flg : jsgen.py
	cat /dev/null > $(DEST)/cartes-light.js
	echo "/*" >> $(DEST)/cartes-light.js
	echo " * generated in " $(PWD) >> $(DEST)/cartes-light.js
	echo "*/" >> $(DEST)/cartes-light.js
	echo "/*" >> $(DEST)/cartes-light.js
	echo " * inner html element for div block" >> $(DEST)/cartes-light.js
	echo "*/" >> $(DEST)/cartes-light.js
	./jsgen.py suite graphisme light 50 >> $(DEST)/cartes-light.js
	cat /dev/null > $(DEST)/cartes-medium.js
	echo "/*" >> $(DEST)/cartes-medium.js
	echo " * generated in " $(PWD) >> $(DEST)/cartes-medium.js
	echo "*/" >> $(DEST)/cartes-medium.js
	echo "/*" >> $(DEST)/cartes-medium.js
	echo " * inner html element for div block" >> $(DEST)/cartes-medium.js
	echo "*/" >> $(DEST)/cartes-medium.js
	./jsgen.py suite graphisme medium 120 >> $(DEST)/cartes-medium.js
	cat /dev/null > $(DEST)/cartes-web.js
	echo "/*" >> $(DEST)/cartes-web.js
	echo " * generated in " $(PWD) >> $(DEST)/cartes-web.js
	echo "*/" >> $(DEST)/cartes-web.js
	echo "/*" >> $(DEST)/cartes-web.js
	echo " * inner html element for div block" >> $(DEST)/cartes-web.js
	echo "*/" >> $(DEST)/cartes-web.js
	./jsgen.py suite graphisme web 180 >> $(DEST)/cartes-web.js

	touch $@

