#include "wave.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

//void mono_wave_dump_header(char *file_name);


static STEREO_PCM spcml, spcmr;
static void allocnoise( double secs ) {
	spcml.fs =8000;
	spcml.bits =16;
	spcml.length =int(spcml.fs * secs);
  	spcml.s1 = (double*)calloc(spcml.length, sizeof(double));
  	spcml.s2 = (double*)calloc(spcml.length, sizeof(double));
	spcmr.fs =8000;
	spcmr.bits =16;
	spcmr.length =int(spcml.fs * secs);
  	spcmr.s1 = (double*)calloc(spcml.length, sizeof(double));
  	spcmr.s2 = (double*)calloc(spcml.length, sizeof(double));
	int n;
	for(n=0; n<spcml.length; ++n) {
		spcml.s1[n] =0;
		spcml.s2[n] =0;
		spcmr.s1[n] =0;
		spcmr.s2[n] =0;
	}
}
static void freenoise() {
	free(spcml.s1);
	free(spcml.s2);
	free(spcmr.s1);
	free(spcmr.s2);
}

	
static void cracklenoise( double secs, char *file_left, char *file_right) {
	spcml.fs =8000;
	spcml.bits =16;
	spcml.length =1600;
  	spcml.s1 = (double*)calloc(spcml.length, sizeof(double));
  	spcml.s2 = (double*)calloc(spcml.length, sizeof(double));
	spcmr.fs =8000;
	spcmr.bits =16;
	spcmr.length =1600;
  	spcmr.s1 = (double*)calloc(spcml.length, sizeof(double));
  	spcmr.s2 = (double*)calloc(spcml.length, sizeof(double));

	int n;
	for(n=0; n<spcml.length; ++n) {
		double noise =drand48();
		double x =((double)(n-88)/44*M_PI);
		double sigma =2.0;
		double g =(1/sqrt(2*M_PI) * sigma ) * exp( -0.5 * x * x / sigma );
		spcml.s1[n] =noise*g;
		spcml.s2[n] =0;
		spcmr.s1[n] =0;
		spcmr.s2[n] =noise*g;
		if (lrand48()%2) {
			spcml.s1[n] *=-1;
			spcmr.s2[n] *=-1;
		}
	}
	stereo_wave_write(&spcml, file_left);	
	stereo_wave_write(&spcmr, file_right);	
	free(spcml.s1);
	free(spcml.s2);
	free(spcmr.s1);
	free(spcmr.s2);
}

static void whitenoise( double secs, char *file_left, char *file_right ) {
	allocnoise(secs);
	int n;
	printf("length %ld\n", spcml.length);
	for( double hz =20; hz < 20000; hz*=1.05 ) {
		for(n=0; n<spcml.length; ++n) {
			double h =(2*M_PI)*n*hz/spcml.fs;
			spcml.s1[n] =cos(h)/10;
			spcmr.s2[n] =cos(h)/10;
			//printf("%ld/%ld=%lf\n", n,spcml.length,h);
		}
	}
	stereo_wave_write(&spcml, file_left);	
	stereo_wave_write(&spcmr, file_right);	
	freenoise();
}

static void pinknoise( double secs, char *file_left, char *file_right ) {
	allocnoise(secs);
	int n;
	printf("length %ld\n", spcml.length);
	for( double hz =20; hz < 20000; hz*=1.05 ) {
		for(n=0; n<spcml.length; ++n) {
			double h =(2*M_PI)*n*hz/spcml.fs;
			double d =log2(n/20.0);
			spcml.s1[n] =cos(h)/d;
			spcmr.s2[n] =cos(h)/d;
			//printf("%ld/%ld=%lf\n", n,spcml.length,h);
		}
	}
	stereo_wave_write(&spcml, file_left);	
	stereo_wave_write(&spcmr, file_right);	
	freenoise();
}
static void rednoise( double secs, char *file_left, char *file_right ) {
	allocnoise(secs);
	int n;
	printf("length %ld\n", spcml.length);
	for( double hz =20; hz < 20000; hz*=1.05 ) {
		for(n=0; n<spcml.length; ++n) {
			double h =(2*M_PI)*n*hz/spcml.fs;
			double d =log2(n/20.0);
			spcml.s1[n] =cos(h)/d;
			spcmr.s2[n] =cos(h)/d;
			//printf("%ld/%ld=%lf\n", n,spcml.length,h);
		}
	}
	stereo_wave_write(&spcml, file_left);	
	stereo_wave_write(&spcmr, file_right);	
	freenoise();
}
static void clicknoise( double secs, char *file_left, char *file_right ) {
	allocnoise(secs);
	int n;
	for(n=spcml.length/2; n<spcml.length/2+1; ++n) {
		double h =0.5;
		spcml.s1[n] =cos(h);
		spcmr.s2[n] =cos(h);
		printf("%ld/%ld=%lf\n", n,spcml.length,h);
	}
	stereo_wave_write(&spcml, file_left);	
	stereo_wave_write(&spcmr, file_right);	
	freenoise();
}



/*
 argv[1] = left,
 argv[2] = right
 */
int main(int argc, char *argv[]) {
	if (argc < 3) {
		printf("usage : %s [crackle|white|pink|red] secs [left.wav [right.wav]]\n", argv[0]);
		exit(1);
	}
	char *ssecs =argv[2];
	char *left =NULL;
	char *right =NULL;
	double secs =atof(ssecs);
	if (argc >= 4) {
		left =argv[3];
	}
	if (argc >= 5) {
		right =argv[4];
	}
	if (strcmp(argv[1], "crackle") == 0) {
		cracklenoise(secs, left, right);
	} else if (strcmp(argv[1], "white") == 0) {
		whitenoise(secs, left, right);
	} else if (strcmp(argv[1], "pink") == 0) {
		pinknoise(secs, left, right);
	} else if (strcmp(argv[1], "red") == 0) {
		rednoise(secs, left, right);
	} else if (strcmp(argv[1], "click") == 0) {
		clicknoise(secs, left, right);
	}
	return 0;
}

