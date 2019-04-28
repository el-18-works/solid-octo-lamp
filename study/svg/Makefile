
JOBS=card_data.py wireframe.flg suite-light.flg suite-real.flg suite-web.flg suite-medium.flg
all : $(foreach i,$(JOBS),cache/$(i))

.PHONY : clean test install-suite

clean :
	rm -rf cache/*

cache/card_data.py : fonts/element.svg fonts/figure.svg fonts/etc.svg
	echo "element_path ={}" > $@
	echo "try :" >> $@
	cat fonts/element.svg | grep glyph-name | sed -e "s|glyph-name.*||g" | nl | sed -e "s|\([0-9]\)[ \t]*<glyph d=|element_path[\1]=|" >> $@
	echo "except Exception as e :" >> $@
	echo "\tprint(e)" >> $@
	echo "figure_path =[]" >> $@
	echo "try :" >> $@
	cat fonts/figure.svg | grep glyph-name | sed -e "s|glyph-name.*||g" | nl | sed -e "s|.*<glyph d=\(.*\)|    figure_path.append(\1)|" >> $@
	echo "except Exception as e :" >> $@
	echo "\tprint(e)" >> $@
	echo "etc_path =[]" >> $@
	echo "try :" >> $@
	cat fonts/etc.svg | grep glyph-name | sed -e "s|glyph-name.*||g" | nl | sed -e "s|.*<glyph d=\(.*\)|    etc_path.append(\1)|" >> $@
	echo "except Exception as e :" >> $@
	echo "\tprint(e)" >> $@

cache/wireframe.flg : cache/card_data.py makecard.py card.py
	./makecard.py wireframe
	touch $@

cache/suite-light.flg : cache/card_data.py makecard.py card.py
	./makecard.py suite-light
	touch $@

cache/suite-medium.flg : cache/card_data.py makecard.py card.py
	./makecard.py suite-medium
	touch $@

cache/suite-real.flg : cache/card_data.py makecard.py card.py
	./makecard.py suite-real
	touch $@

cache/suite-web.flg : cache/card_data.py makecard.py card.py
	./makecard.py suite-web
	touch $@

SUITEDIR=/var/www/html/ens/res/misc/baraja
install-suite : cache/suite-light.flg cache/suite-real.flg cache/suite-web.flg
	mkdir -p $(SUITEDIR)
	cp cache/card-*svg $(SUITEDIR)

test :
	./makecard.py test
	#./card.py
	#xdg-open cache/test.jpg
	xdg-open cache/test.svg

