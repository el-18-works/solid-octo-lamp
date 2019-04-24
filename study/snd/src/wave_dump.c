#include "wave.h"
#include <stdio.h>
#include <stdlib.h>
#include <math.h>

static MONO_PCM pcm;
static STEREO_PCM spcm;

static void monopcm_stat( char *file_name) {
	mono_wave_dump_header(file_name);
	int n;
	double amax =0, amin =0;
	double a0 =0;

	mono_wave_read(&pcm, file_name);

	for(n=0; n<pcm.length; ++n) {
		double a =pcm.s[n];
		amax =fmax(a,amax);
		amin =fmin(a,amin);
		a0 +=a/pcm.length;
	}
	printf("max:%f, min:%f, a0:%lf\n", amax, amin, a0);
	free(pcm.s);
}

int main(int argc, char *argv[]) {
	int i;
	for(i=1; i<argc; ++i) {
		monopcm_stat(argv[i]);
	}
	return 0;
}


