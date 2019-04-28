#include "wave.h"
#include <stdio.h>
#include <stdlib.h>
#include <math.h>

static MONO_PCM pcm;
static STEREO_PCM spcm;

static void monopcm_stat( double de, double ad, char *file_name) {
	mono_wave_dump_header(file_name);
	int n;
	double amax =0, amin =0;
	double a0 =0;

	mono_wave_read(&pcm, file_name);

	double spec[19980];
	for( double hz =20; hz < 20000; hz+=1 ) {
		spec[(int)hz-20] =0;
	}
	for( double hz =20; hz < 20000; hz+=1 ) {
		for(n=(int)fmin(pcm.length, de*pcm.fs); n<(int)fmin(pcm.length, ad*pcm.fs); ++n) {
			double a =pcm.s[n];
			amax =fmax(a,amax);
			amin =fmin(a,amin);
			a0 +=a/pcm.length;
			spec[(int)hz-20] +=a*sin(M_PI*n*hz/pcm.fs)/((ad-de)*pcm.length);
		}
	}
	printf("max:%f, min:%f, a0:%lf\n", amax, amin, a0);
	free(pcm.s);
	amax =0; amin =0;
	for( double hz =20; hz < 20000; hz+=1 ) {
		amax =fmax(amax,spec[(int)hz-20]);
		amin =fmin(amin,spec[(int)hz-20]);
	}
	FILE *gp =popen("gnuplot -persist", "w");
	fprintf(gp, "set xrange [20:20000]\n");
	fprintf(gp, "set yrange [%f:%f1]\n", amin, amax);
	fprintf(gp, "plot '-' with lines\n");
	for( int hz =20; hz < 20000; hz+=1 ) {
		printf("%d %f\n",hz,spec[hz-20]);
		fprintf(gp, "%d %f\n",hz,spec[hz-20]);
	}
	fprintf(gp, "e\n");
}


int main(int argc, char *argv[]) {
	if (argc < 4) {
		printf("Usage : %s de(sec) ad(sec) [files...]\n", argv[1]);
		exit(1);
	}
	double de =atof(argv[1]);
	double ad =atof(argv[2]);
	int i;
	for(i=3; i<argc; ++i) {
		monopcm_stat(de, ad, argv[i]);
	}
	return 0;
}


