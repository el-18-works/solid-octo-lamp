#include "wave.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

static MONO_PCM pcm;
static STEREO_PCM spcm;

static void monopcm_stat( double de, double ad, char *file_name) {
	mono_wave_dump_header(file_name);
	int n;
	double amax =0, amin =0;
	double a0 =0;

	mono_wave_read(&pcm, file_name);
	
	return;
	//int stride =32000;
	double spec[19980];
	for( double hz =20; hz < 20000; hz+=1 ) {
		spec[(int)hz-20] =0;
	}
	for( double hz =20; hz < 20000; hz+=100 ) {
		for(n=(int)fmin(pcm.length, de*pcm.fs); n<(int)fmin(pcm.length, ad*pcm.fs); ++n) {
			double a =pcm.s[n];
			amax =fmax(a,amax);
			amin =fmin(a,amin);
			a0 +=a/pcm.length;
			spec[(int)hz-20] +=a*sin(M_PI*n*hz/pcm.fs)/((ad-de)*pcm.length);
		}
	}
	printf("length:%ld\n", pcm.length);
	printf("max:%f, min:%f, a0:%lf\n", amax, amin, a0);
	free(pcm.s);
	amax =0; amin =0;
	for( double hz =20; hz < 20000; hz+=100 ) {
		amax =fmax(amax,spec[(int)hz-20]);
		amin =fmin(amin,spec[(int)hz-20]);
	}
	printf("fourier max:%f, min:%f\n", amax, amin);
	FILE *gp =popen("gnuplot -persist", "w");
	fprintf(gp, "set xrange [20:20000]\n");
	fprintf(gp, "set yrange [%f:%f1]\n", amin, amax);
	fprintf(gp, "plot '-' with lines\n");
	for( int hz =20; hz < 20000; hz+=100 ) {
		//printf("%d %f\n",hz,spec[hz-20]);
		fprintf(gp, "%d %f\n",hz,spec[hz-20]);
	}
	fprintf(gp, "e\n");
}

static void monopcm_csv( char *file_name) {
	mono_wave_dump_header(file_name);
	int n;
	double amax =0, amin =0;
	double a0 =0;

	FILE *csvfp =fopen("monopcmplot.csv", "w");

	mono_wave_read(&pcm, file_name);
	//int stride =3200;
	//12;
	for (double idx =0, offset =0; offset < pcm.length; idx +=1, offset =idx/3200*pcm.length*3/2) {
		printf("idx=%d, offset=%lf/%ld\n", (int)idx, offset, pcm.length);
		fprintf(csvfp, "%d,", (int)idx);
		double spec[19980];
		for( double hz =20; hz < 20000; hz+=100) {
			spec[(int)hz-20] =0;
		}
		for( double hz =20; hz < 20000; hz+=100 ) {
			for(int re =0; re<10; ++re) {
				n =offset+re;
				double a =pcm.s[n];
				amax =fmax(a,amax);
				amin =fmin(a,amin);
				a0 +=a/pcm.length;
				spec[(int)hz-20] +=a*sin(M_PI*n*hz/pcm.fs);///(n);
				//printf("re=%d,hz=%lf\n", re, hz);
			}
		}

		printf("length:%ld\n", pcm.length);
		printf("max:%f, min:%f, a0:%lf\n", amax, amin, a0);
		amax =0; amin =0;
		for( double hz =20; hz < 20000; hz+=100 ) {
			amax =fmax(amax,spec[(int)hz-20]);
			amin =fmin(amin,spec[(int)hz-20]);
		}
		printf("fourier max:%f, min:%f\n", amax, amin);
		//FILE *gp =popen("gnuplot -persist", "w");
		//fprintf(gp, "set xrange [20:20000]\n");
		//fprintf(gp, "set yrange [%f:%f1]\n", amin, amax);
		//fprintf(gp, "plot '-' with lines\n");
		for( int hz =20; hz < 20000; hz+=100 ) {
			//printf("%d %f\n",hz,spec[hz-20]);
			//fprintf(gp, "%d %f\n",hz,spec[hz-20]);
			fprintf(csvfp, "%d %f,",hz,spec[hz-20]);
		}
		fprintf(csvfp, "\n");
		//fprintf(gp, "e\n");
	}
	free(pcm.s);
	fclose(csvfp);
}

int main(int argc, char *argv[]) {
	if (argc < 3) {
		printf("Usage : %s de(sec) ad(sec) [files...]\n", argv[1]);
		printf("Usage : %s csv [files...](30fs)\n", argv[1]);
		exit(1);
	}
	if (strcmp(argv[1], "csv") == 0) {
		for(int i=2; i<argc; ++i) {
			monopcm_csv( argv[i]);
		}
		exit(0);
	}
	double de =atof(argv[1]);
	double ad =atof(argv[2]);
	int i;
	for(i=3; i<argc; ++i) {
		monopcm_stat(de, ad, argv[i]);
	}
	return 0;
}


