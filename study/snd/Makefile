
UNITS=wave plot cardnoise osc
OBJ=cache/obj
BIN=cache/bin
OBJS=$(foreach i,$(UNITS),$(OBJ)/$(i).o)
APPS=plot cardnoise osc
BINS=$(foreach i,$(APPS),$(BIN)/$(i))
WAVEDUMPDEP = $(OBJ)/wave.o $(OBJ)/plot.o
MAKENOISEDEP = $(OBJ)/wave.o $(OBJ)/cardnoise.o
OSCDEP = $(OBJ)/wave.o $(OBJ)/osc.o
NOISE=cache/cardnoise-left.wav cache/cardnoise-right.wav
INSTALL=/var/www/html/ens/res/snd
CFLAGS=-Iinc 
LDFLAGS=-lm

#PLAY=$(shell which cvlc) --no-loop
PLAY?=$(shell which play)
PLAY?=file

vpath %.c src
vpath %.py py
vpath %.csv cache:csv
vpath %.wav cache


CC=g++

.PHONY : all clean makenoise install-* test-osc

test-osc : $(BIN)/osc
	$< -m  || true

all : $(BINS)
clean :
	rm -rf cache/*/*
	rm -rf cache/*.wav

$(OBJ)/%.o : %.c 
	$(CC) $(CFLAGS) -c $< -o $@

$(BIN)/plot : $(WAVEDUMPDEP)
	$(CC) $^ $(LDFLAGS) -o $@

$(BIN)/cardnoise : $(MAKENOISEDEP)
	$(CC) $^ $(LDFLAGS) -o $@

$(BIN)/osc.bin : $(OSCDEP)
	$(CC) $^ $(LDFLAGS) -o $@

$(BIN)/osc.1 : osc_op.py
	$< man $@
	chmod +x $@

$(BIN)/osc : osc_op.py $(BIN)/osc.bin  $(BIN)/osc.1
	$< op $@
	chmod +x $@

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
	rm -f $@
	py/oscpp.py $<  > $(basename $<).gdf.csv
	$(BIN)/osc.bin $(basename $<).gdf.csv $@
	$(PLAY) $@
endef

%.ogg : %.wav
	rm -f $@
	ffmpeg -i $< $(basename $<).ogg

%-mono.wav : %.wav
	rm -f $@
	ffmpeg -i $< -ac 1 -ab 2000 $(basename $<)-mono.wav



test : $(BINS)
	#xdotool key super+Tab
	$(BIN)/osc cache/course-clear.gdf.csv
	#$(BIN)/plot 1 1.01 cache/guitar_A5.wav
	#xdotool key super+Tab

	#$(BIN)/cardnoise click 2
	#$(BIN)/cardnoise white 2
	#$(BIN)/cardnoise pink 2
	#$(BIN)/cardnoise red 2

MYPRJ=~/dev/werk/myprj/samples
CSV=$(foreach i,$(MARIO),cache/$i.csv)
myprj :
	rm -rf $(MYPRJ)	
	mkdir -p $(MYPRJ)	
	mkdir -p $(MYPRJ)/csv
	#$(MYPRJ)/installer.sh
	cp $(CSV)  $(MYPRJ)/csv
	mkdir -p $(MYPRJ)/py
	cp py/oscpp.py $(MYPRJ)/py
	ls -R $(MYPRJ)

