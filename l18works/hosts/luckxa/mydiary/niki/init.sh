#!/bin/bash

for md in *.md ; do
	../novalis.py debug init `head -n 1 $md | sed "s/\(.*\)-\(.*\)-\(.*\)-\(.*\)/\4 \1 \2 \3/g"`
done

