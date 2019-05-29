
.PHONY : all install-suichu
all : cache/suichu.mp4
cache/suichu.mp4 : py/suichu.py
	./py/suichu.py
	rm -f cache/suichu-mute.mp4
	rm -f cache/suichu.mp4
	ffmpeg -r:v 30 -i cache/suichu/suichu%04d.png -codec:v libx264 -preset veryslow -pix_fmt yuv420p -crf 28 -an cache/suichu-mute.mp4
	ffmpeg -i cache/suichu-mute.mp4 -i cache/theme.ogg -c:v libx264 -c:a libvorbis -longest $@
	xdotool key super+Tab
	vlc $@
	xdotool key super+Tab

cache/suichu.jpg : cache/suichu.mp4
	ffmpeg -ss 4 -i $< -s 400x400 -frames:v 1 $@

#-qscale:v – video quality. Range is 0–10, where 10 is highest quality. 5–7 is a good range to try. If you omit -qscale:v (or the alias -q:v) then ffmpeg will use the default -b:v 200k which will most likely provide a poor quality output, and libtheora may drop/skip frames if the bitrate is too low.
#-qscale:a – audio quality. Range is 0–10, where 10 is highest quality. 3–6 is a good range to try. Default is -qscale:a 3.
cache/suichu.ogv : cache/suichu.mp4
	ffmpeg -i $< -codec:v libtheora -qscale:v 7 -codec:a libvorbis -qscale:a 5 $@

install-suichu : cache/suichu.mp4
	cp $< /var/www/html/ens/game/2d/suichu/
