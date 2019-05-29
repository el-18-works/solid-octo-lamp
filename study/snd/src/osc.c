#include "wave.h"
#include <stdio.h>
/* atoi, atof */
#include <stdlib.h>
/* strlen */
#include <string.h>
/* M_PI, cos */
#include <math.h>
/* errno, strerror */
#include <errno.h>

static FILE *fp;

static STEREO_PCM pcm;

static void echo(double delay, double ratio) {
  int framedelay =delay * pcm.fs;
  for(int i=0; i<pcm.length-framedelay; ++i) {
    pcm.sl[i+framedelay] +=pcm.sl[i]*ratio;
    pcm.sr[i+framedelay] +=pcm.sr[i]*ratio;
  }
}

static void lowpassfreq(double cutoff) {
  double *sl =(double*)calloc(pcm.length, sizeof(double));
  double *sr =(double*)calloc(pcm.length, sizeof(double));
  double RC =1.0/(cutoff*2*M_PI);
  double dt =1.0/pcm.fs;
  double alpha =dt/(RC+dt);
  sl[0] =pcm.sl[0];
  sr[0] =pcm.sr[0];
  for(int i=1; i<pcm.length; ++i) {
    sl[i] =sl[i-1] + (alpha*(pcm.sl[i]-sl[i-1]));
    sr[i] =sr[i-1] + (alpha*(pcm.sr[i]-sr[i-1]));
  }
  for(int i=pcm.length-2; i>0; --i) {
    sl[i] =sl[i+1] + (alpha*(sl[i]-sl[i+1]));
    sr[i] =sr[i+1] + (alpha*(sr[i]-sr[i+1]));
  }
  stereo_wave_free(&pcm);
  pcm.sl =sl;
  pcm.sr =sr;
}

static void margin(double head, double tail) {
  double *sl =(double*)calloc(pcm.length+2*pcm.fs, sizeof(double));
  double *sr =(double*)calloc(pcm.length+2*pcm.fs, sizeof(double));
  for(int n=0; n<(int)(head*pcm.fs); ++n) {
    sl[n] =0;
    sr[n] =0;
  }
  for(int n=0; n<pcm.length; ++n) {
    sl[(int)(head*pcm.fs)+n] =pcm.sl[n];
    sr[(int)(head*pcm.fs)+n] =pcm.sr[n];
  }
  for(int n=pcm.length+(int)(head*pcm.fs); n<pcm.length+(head+tail)*pcm.fs; ++n) {
    sl[n] =0;
    sr[n] =0;
  }
  stereo_wave_free(&pcm);
  pcm.length +=(int)((head+tail)*pcm.fs);
  pcm.sl =sl;
  pcm.sr =sr;
}

int header[4];

static void read_csv_header() {
  char buf[BUFSIZ];
  int p =0;
  for( int c =fgetc(fp), i=0; c != '\n' && c != EOF; c =fgetc(fp)) {
    //fputc(c, stdout);
    if (c == ',') {
      buf[i++] ='\0';
      if (p <= 3 && i != 0)
        header[p] =atoi(buf);
      i =0;
      p++;
    } else {
      buf[i++] =c;
    }
  }
  printf("header : tempo(bpm)=%d, total duration=%d*(60/bpm)s\n", header[0], header[1]);
  if (header[2] < header[3])
    printf("header : rhtym start=%d*(60/bpm)s, stop=%d*(60/bpm)s\n", header[2], header[3]);
}

double beat =-1;
double frame_per_beat =0;
double gain =0, dur =0, freq =0;

static void add_csv_row() {
  double gdf[3];
  char buf[BUFSIZ];
  int p =-1, i=0;
  for( int c =fgetc(fp); c != EOF; c =fgetc(fp)) {
    if (c == ',' || c == '\n') {
      buf[i++] ='\0';
      if (p == -1) {
        if (strlen(buf) == 0)
          beat++;
        else
          beat =atof(buf);
      }
      
      if (p > 2) {
        p =0;
      } 
      gdf[p] =atof(buf);
      if (p == 2) {
        if (gdf[0] !=0 || gdf[1] != 0 || gdf[2] != 0) {
          if (gdf[0] == 0) gdf[0] =gain;
          if (gdf[1] == 0) gdf[1] =dur;
          if (gdf[2] == 0) gdf[2] =freq;
          gain =gdf[0]; dur =gdf[1]; freq =gdf[2];
          printf("beat=%lf,frame=%d gain=%lf, dur=%lf, freq=%lf\n", beat, (int)(beat*frame_per_beat), gain, dur, freq);
          for (int frame=beat*frame_per_beat; frame < (beat+dur)*frame_per_beat-50 && frame < pcm.length; ++frame) {
						// 0.1s
            double att =pcm.fs/5-(frame-pcm.fs/10)-beat*frame_per_beat; if (att < 5) att =5;
						double afreq =frame * freq;// / att*5;
            att =5;
            pcm.sl[frame] +=cos((2*M_PI)/pcm.fs*afreq)*gain/2/att;
            pcm.sr[frame] +=cos((2*M_PI)/pcm.fs*afreq)*gain/2/att;
            pcm.sl[frame] +=cos((4*M_PI)/pcm.fs*afreq)*gain/9/att; // octave
            pcm.sr[frame] +=cos((4*M_PI)/pcm.fs*afreq)*gain/9/att;
            pcm.sl[frame] +=cos((3*M_PI)/pcm.fs*afreq)*gain/50/att; // quinte
            pcm.sr[frame] +=cos((3*M_PI)/pcm.fs*afreq)*gain/50/att;
            pcm.sl[frame] +=cos((5*M_PI)/pcm.fs*afreq)*gain/90/att; // diton (tierce majeure ?)
            pcm.sr[frame] +=cos((5*M_PI)/pcm.fs*afreq)*gain/90/att;
            pcm.sl[frame] +=cos((11*M_PI/7)/pcm.fs*afreq)*gain/250/att; // ??
            pcm.sr[frame] +=cos((11*M_PI/7)/pcm.fs*afreq)*gain/250/att;
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
  printf("---\n");
  beat++;
}

void add_rhythm() {
  int start =header[2], stop =header[3];

  for (int i=start; i<stop; i++) {
  //for (int i=frame_per_beat*start; i<frame_per_beat*stop; i+=frame_per_beat) {
		if (i%3 == 0) continue;
    int width = (i%3 != 1) ? 900 : 180;
    for(int n=i*frame_per_beat; n<i*frame_per_beat+width && n < pcm.length; ++n) {
      if(lrand48()%3 == 0) {
      	double noise =cos((2*M_PI)/pcm.fs*n) / 2;
				pcm.sr[n] /=2;
				pcm.sl[n] /=2;
        if (i % 2) {
          pcm.sr[n] +=noise * sqrt(n)/width;
          pcm.sl[n] +=noise * sqrt(i*frame_per_beat+width-n)/width ;
        } else  {
          pcm.sl[n] +=noise * sqrt(n)/width;
          pcm.sr[n] +=noise * sqrt(i*frame_per_beat+width-n)/width ;
        }
      }
    }
    if (i%3 == 2 && i%12!=2) {
      for(int n=i*frame_per_beat+frame_per_beat/2; n<i*frame_per_beat+frame_per_beat/2+180 && n < pcm.length; ++n) {
        double noise =cos((2*M_PI)/pcm.fs*1*n);
        if(lrand48()%3 == 0) {
					pcm.sr[n] *=0.6;
					pcm.sl[n] *=0.6;
          pcm.sl[n] +=noise*0.25 ;
          pcm.sr[n] +=noise*0.25 ;
        }
      }
    }
  }
}

int main(int argc, char **argv) {
  if (argc < 9) {
    printf("Usage : %s in_gdf_file in_rhythm_file out_wav_file margin_head margin_tail echo_delay echo_ratio lowpass_cutoff\n", argv[0]);
    //printf("Usage : %s csv_file [out_wav_file]\n", argv[0]);
    exit(1);
  }
	char *in_gdf_file =argv[1];
	char *in_rhtym_file =argv[2];
	char *out_wav_file =argv[3];
	double margin_head =atof(argv[4]);
	double margin_tail =atof(argv[5]);
	double echo_delay =atof(argv[6]);
	double echo_ratio =atof(argv[7]);
	double lowpass_cutoff =atoi(argv[7]);

  fp =fopen(argv[1], "r");
  if (! fp) {
    fprintf(stderr, "fopen(\"%s\") : %s(%d)\n", argv[1], strerror(errno), errno);
    return EXIT_FAILURE;
  }

  read_csv_header();

  double total_secs =60.0/header[0]*header[1];
  stereo_wave_alloc(&pcm, total_secs);

  frame_per_beat =60.0/((double)header[0])*pcm.fs;

  while (! feof(fp))
    add_csv_row();

  fclose(fp);

	echo(0.08, 0.33);
  margin(1, 1);
  add_rhythm();
	lowpassfreq(500);

  stereo_wave_write(&pcm, out_wav_file);  

  stereo_wave_free(&pcm);
  return 0;
}


