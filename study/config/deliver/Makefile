
BIN=cache/bin
PY=py
BINFILES=deliver
BINS=$(foreach i,$(BINFILES),$(BIN)/$i)

$(BIN)/deliver : $(PY)/deliver-con.py $(PY)/deliver-skel.py
	$< $@

