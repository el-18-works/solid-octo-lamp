
JOBS=cartes_data.py wireframe.flg suite-light.flg
all : $(foreach i,$(JOBS),cache/$(i))

.PHONY : clean test

clean :
	rm -rf cache/*

cache/cartes_data.py : element.svg figure.svg etc.svg
	echo "element_path ={}" > $@
	echo "try :" >> $@
	cat element.svg | grep glyph-name | sed -e "s|glyph-name.*||g" | nl | sed -e "s|\([0-9]\)[ \t]*<glyph d=|element_path[\1]=|" >> $@
	echo "except Exception as e :" >> $@
	echo "\tprint(e)" >> $@
	echo "figure_path =[]" >> $@
	echo "try :" >> $@
	cat figure.svg | grep glyph-name | sed -e "s|glyph-name.*||g" | nl | sed -e "s|.*<glyph d=\(.*\)|    figure_path.append(\1)|" >> $@
	echo "except Exception as e :" >> $@
	echo "\tprint(e)" >> $@
	echo "etc_path =[]" >> $@
	echo "try :" >> $@
	cat etc.svg | grep glyph-name | sed -e "s|glyph-name.*||g" | nl | sed -e "s|.*<glyph d=\(.*\)|    etc_path.append(\1)|" >> $@
	echo "except Exception as e :" >> $@
	echo "\tprint(e)" >> $@

cache/wireframe.flg : cache/cartes_data.py makecartes.py cartes.py
	./makecartes.py wireframe
	touch $@

cache/suite-light.flg : cache/cartes_data.py makecartes.py cartes.py
	./makecartes.py suite-light
	touch $@

test :
	./makecartes.py test
	#./cartes.py
	#xdg-open cache/test.jpg
	xdg-open cache/test.svg

