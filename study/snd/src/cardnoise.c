#include "wave.h"
#include <stdio.h>
#include <stdlib.h>
#include <math.h>

//void mono_wave_dump_header(char *file_name);


static void writenoise( char *file_left, char *file_right) {
	int n;
	static STEREO_PCM spcml, spcmr;
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

/*
 argv[1] = left,
 argv[2] = right
 */
int main(int argc, char *argv[]) {
	int i;
	if (argc != 3) {
		printf("usage : %s left.wav right.wav\n", argv[0]);
		exit(1);
	}
	writenoise(argv[1], argv[2]);
	return 0;
}

