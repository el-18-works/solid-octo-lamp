
CFILES=tes
OBJS=$(foreach i,$(CFILES),cache/objs/$i.o)
APPS=cache/bin/tes

.PHONY :test 
all : $(APPS)

CC=g++
CFLAGS=`libpng-config --cflags`
LDFLAGS=`libpng-config --ldflags` -lm

cache/objs/%.o : src/%.cxx
	$(CC) $(CFLAGS) -c $< -o $@

cache/bin/tes : $(OBJS)
	$(CC) $< $(LDFLAGS) -o $@

test : cache/bin/tes 
	$<
	xdotool key "super+Tab" && cvlc cache/tes.png && xdotool key "super+Tab"
	echo ok
	xdotool key "super+Tab"

