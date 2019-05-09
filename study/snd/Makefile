
UNITS=wave wave_dump cardnoise osc
OBJ=cache/obj
BIN=cache/bin
OBJS=$(foreach i,$(UNITS),$(OBJ)/$(i).o)
APPS=wavedump cardnoise osc
BINS=$(foreach i,$(APPS),$(BIN)/$(i))
WAVEDUMPDEP = $(OBJ)/wave.o $(OBJ)/wave_dump.o
MAKENOISEDEP = $(OBJ)/wave.o $(OBJ)/cardnoise.o
OSCDEP = $(OBJ)/wave.o $(OBJ)/osc.o
NOISE=cache/cardnoise-left.wav cache/cardnoise-right.wav
INSTALL=/var/www/html/ens/res/snd
CFLAGS=-c
CFLAGS=-Iinc -c
LDFLAGS=-lm

vpath %.c src
vpath %.py py
vpath %.csv cache
vpath %.wav cache


CC=g++

.PHONY : all clean makenoise install-*
all : $(BINS)
clean :
	rm -rf cache/*/*
	rm -rf cache/*.wav

$(OBJ)/%.o : %.c 
	$(CC) $(CFLAGS) $< -o $@

$(BIN)/wavedump : $(WAVEDUMPDEP)
	$(CC) $^ $(LDFLAGS) -o $@

$(BIN)/cardnoise : $(MAKENOISEDEP)
	$(CC) $^ $(LDFLAGS) -o $@

$(BIN)/osc : $(OSCDEP)
	$(CC) $^ $(LDFLAGS) -o $@

makenoise : $(BINS)
	$(BIN)/cardnoise $(NOISE)
	#xdotool key super+Tab
	#audacity cache/cardnoise-left.wav
	#xdotool key super+Tab
	ffmpeg -i cache/cardnoise-left.wav cache/cardnoise-left.ogg
	ffmpeg -i cache/cardnoise-right.wav cache/cardnoise-right.ogg
	ffmpeg -i cache/cardnoise-left.wav cache/cardnoise-left.mp3
	ffmpeg -i cache/cardnoise-right.wav cache/cardnoise-right.mp3

install-noise :
	mkdir -p $(INSTALL)
	cp cache/cardnoise* $(INSTALL)

MARIO=course-clear game-over castle-theme underground-theme suichu-theme
all : $(foreach i,$(MARIO),cache/$i.ogg)
all : $(foreach i,$(MARIO),cache/$i.wav)
all : $(foreach i,$(MARIO),cache/$i-mono.wav)

%.wav : %.csv $(BINS) oscpp.py
	$(csv2wav)

define csv2wav =
	py/oscpp.py $<  > $(basename $<).gdf.csv
	$(BIN)/osc $(basename $<).gdf.csv $@
endef

%.ogg %-mono.wav : %.wav
	ffmpeg -i $< $(basename $<).ogg
	ffmpeg -i $< -ac 1 -ab 2000 $(basename $<)-mono.wav

test : $(BINS)
	#xdotool key super+Tab
	$(BIN)/osc cache/course-clear.gdf.csv
	#$(BIN)/wavedump 1 1.01 cache/guitar_A5.wav
	#xdotool key super+Tab

	#$(BIN)/cardnoise click 2
	#$(BIN)/cardnoise white 2
	#$(BIN)/cardnoise pink 2
	#$(BIN)/cardnoise red 2

