
UNITS=wave wave_dump cardnoise
OBJ=cache/obj
BIN=cache/bin
OBJS=$(foreach i,$(UNITS),$(OBJ)/$(i).o)
APPS=wavedump cardnoise
BINS=$(foreach i,$(APPS),$(BIN)/$(i))
WAVEDUMPDEP = $(OBJ)/wave.o $(OBJ)/wave_dump.o
MAKENOISEDEP = $(OBJ)/wave.o $(OBJ)/cardnoise.o
NOISE=cache/cardnoise-left.wav cache/cardnoise-right.wav
INSTALL=/var/www/html/ens/res/snd
CFLAGS=-Iinc -c
LDFLAGS=-lm

CC=g++

.PHONY : all clean makenoise install-*
all : $(BINS)
clean :
	rm -rf cache/*/*
	rm -rf cache/*.wav

$(OBJ)/%.o:src/%.c
	$(CC) $(CFLAGS) $< -o $@

$(BIN)/wavedump : $(WAVEDUMPDEP)
	$(CC) $(WAVEDUMPDEP) $(LDFLAGS) -o $@

$(BIN)/cardnoise : $(MAKENOISEDEP)
	$(CC) $(MAKENOISEDEP) $(LDFLAGS) -o $@

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


test : $(BINS)
	xdotool key super+Tab
	$(BIN)/wavedump 1 1.01 cache/guitar_A5.wav
	#xdotool key super+Tab

	#$(BIN)/cardnoise click 2
	#$(BIN)/cardnoise white 2
	#$(BIN)/cardnoise pink 2
	#$(BIN)/cardnoise red 2

