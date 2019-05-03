
.PHONY : all install-myabout test

all : cache/myabout.ogv

#-qscale:v – video quality. Range is 0–10, where 10 is highest quality. 5–7 is a good range to try. If you omit -qscale:v (or the alias -q:v) then ffmpeg will use the default -b:v 200k which will most likely provide a poor quality output, and libtheora may drop/skip frames if the bitrate is too low.
#-qscale:a – audio quality. Range is 0–10, where 10 is highest quality. 3–6 is a good range to try. Default is -qscale:a 3.

#1280x720
cache/myabout.ogv : py/anime.py
	$<
	rm -f $@
	ffmpeg -framerate 60 -i cache/myabout/myabout%04d.png -codec:v libtheora -qscale:v 7 -codec:a libvorbis -qscale:a 5 -pix_fmt yuv420p -an $@
	#ffmpeg -r:v 60 -i cache/myabout/myabout%04d.png -codec:v libtheora -qscale:v 7 -codec:a libvorbis -qscale:a 5 -pix_fmt yuv420p -an $@

install-myabout : cache/myabout.ogv
	cp $< /var/www/html/res

cache/myabout.jpg : cache/myabout.ogv
	ffmpeg -ss 4 -i $< -s 400x400 -frames:v 1 $@


test : cache/myabout.ogv
	#ffmpeg -r:v 60 -i cache/myabout/myabout%04d.png -codec:v libx264 -preset veryslow -pix_fmt yuv420p -crf 28 -an cache/myabout.mp4
	xdotool key super+Tab
	vlc $<
	#vlc cache/myabout.mp4
	xdotool key super+Tab

