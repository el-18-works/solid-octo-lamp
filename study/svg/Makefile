
JOBS=cartes_data.py wireframe.flg
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

test :
	./makecartes.py test
	#./cartes.py
	#xdg-open test.jpg
	xdg-open test.svg

