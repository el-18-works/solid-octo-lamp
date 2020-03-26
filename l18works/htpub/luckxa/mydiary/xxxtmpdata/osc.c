#include "wave.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

static FILE *fp;

static STEREO_PCM pcm;
static void allocpcm( double secs ) {
	pcm.fs =8000;
	pcm.bits =16;
	pcm.length =int(pcm.fs * secs);
  	pcm.s1 = (double*)calloc(pcm.length, sizeof(double));
  	pcm.s2 = (double*)calloc(pcm.length, sizeof(double));
	int n;
	for(n=0; n<pcm.length; ++n) {
		pcm.s1[n] =0;
		pcm.s2[n] =0;
	}
}
static void freepcm() {
	free(pcm.s1);
	free(pcm.s2);
}

int header[2];

void csv_readheader() {
	char buf[BUFSIZ];
	int p =0;
	for( int c =fgetc(fp), i=0; c != '\n' && c != EOF; c =fgetc(fp)) {
		//fputc(c, stdout);
		if (c == ',') {
			buf[i++] ='\0';
			if (p <= 1)
				header[p] =atoi(buf);
			i =0;
			p++;
		} else {
			buf[i++] =c;
		}
	}
	fprintf(stdout, "header (%d,%d---)\n", header[0], header[1]);
}

double offset =-1;
double stride =0;
double g =0, d =0, f =0;
void csv_readrow() {
	double gdf[3];
	char buf[BUFSIZ];
	int p =-1, i=0;
	for( int c =fgetc(fp); c != EOF; c =fgetc(fp)) {
		//printf("(%d,%c,%d)",p,c,c);
		if (c == ',' || c == '\n') {
			buf[i++] ='\0';
			if (p==-1) {
				if (strlen(buf) == 0)
					offset++;
				else
					offset =atof(buf);
			}
			
			if (p > 2) {
				p =0;
			} 
			gdf[p] =atof(buf);
			if (p == 2) {
				if (gdf[0] !=0 || gdf[1] != 0 || gdf[2] != 0) {
					if (gdf[0] == 0) gdf[0] =g;
					if (gdf[1] == 0) gdf[1] =d;
					if (gdf[2] == 0) gdf[2] =f;
					g =gdf[0]; d =gdf[1]; f =gdf[2];
					printf("(%lf:%d) gdf=%lf, %lf, %lf\n", offset, (int)(offset*stride), gdf[0], gdf[1], gdf[2]);
					for (int frame=offset*stride; frame <(offset+gdf[1])*stride && frame < pcm.length; ++frame) {
						pcm.s1[frame] +=cos((2*M_PI)/8000*(frame*gdf[2]))*gdf[0]/10;
						pcm.s2[frame] +=cos((2*M_PI)/8000*(frame*gdf[2]))*gdf[0]/10;
						pcm.s1[frame] +=cos((4*M_PI)/8000*(frame*gdf[2]))*gdf[0]/20;
						pcm.s2[frame] +=cos((4*M_PI)/8000*(frame*gdf[2]))*gdf[0]/20;
						pcm.s1[frame] +=cos((3*M_PI)/8000*(frame*gdf[2]))*gdf[0]/200;
						pcm.s2[frame] +=cos((3*M_PI)/8000*(frame*gdf[2]))*gdf[0]/200;
						pcm.s1[frame] +=cos((5*M_PI)/8000*(frame*gdf[2]))*gdf[0]/200;
						pcm.s2[frame] +=cos((5*M_PI)/8000*(frame*gdf[2]))*gdf[0]/200;
						pcm.s1[frame] +=cos((11*M_PI/7)/8000*(frame*gdf[2]))*gdf[0]/1000;
						pcm.s2[frame] +=cos((11*M_PI/7)/8000*(frame*gdf[2]))*gdf[0]/1000;
					}
				}
			}
			p++;
			i =0;
			if (c == '\n')
				break;
		} else {
			buf[i++] =c;
		}
	}
	fprintf(stdout, "---\n");
	offset++;
}

int main(int argc, char **argv) {
	if (argc < 2) {
		printf("Usage : %s csv_file [out_wav_file]\n", argv[0]);
		exit(1);
	}
	printf("Reading %s\n", argv[1]);
	fp =fopen(argv[1], "r");
	csv_readheader();
	double secs =60/((double)header[0])*header[1];
	stride =60/((double)header[0])*8000;
	printf("allocating %lfs\n",secs);
	allocpcm(secs);
	int i=0;
	while (! feof(fp)) {
		csv_readrow();
	}
	fclose(fp);
	fprintf(stderr,"noise\n");

	for (int i=0; i<stride*186; i+=stride) {
	//for (int i=0; i<pcm.length; i+=stride) {
		if (i % 3 == 0) continue;
		int width = (i%3 == 1) ? 900 : 180;
		for(int n=i; n<i+width && n < pcm.length; ++n) {
			double noise =cos((2*M_PI)/8000*550*n);
			if(lrand48()%3 == 0) {

				if ((int)(i/stride) % 2) {
					pcm.s2[n] +=noise/8 *sqrt(n)/width;
					pcm.s1[n] +=noise/8*sqrt(i+width-n)/width ;
				} else  {
					pcm.s1[n] +=noise/8 *sqrt(n)/width;
					pcm.s2[n] +=noise/8*sqrt(i+width-n)/width ;
				}
			}
		}
		if (i%3 == 2 && i%12!=2) {
		//if (i%3 != 1 && i%2 != 1) {
		for(int n=i+stride/2; n<i+stride/2+180 && n < pcm.length; ++n) {
			double noise =cos((2*M_PI)/8000*550*n);
			if(lrand48()%3 == 0) {
				pcm.s1[n] +=noise/50 ;
				pcm.s2[n] +=noise/50 ;
			}
		}
		}
	}
	if (argc == 3) {
		stereo_wave_write(&pcm, argv[2]);	
	}
	stereo_wave_write(&pcm, NULL);	
	freepcm();
	return 0;
}


