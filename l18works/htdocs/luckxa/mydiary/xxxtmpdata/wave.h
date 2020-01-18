#ifndef _MY_WAVE_H
#define _MY_WAVE_H

typedef struct {
  int fs;
  int bits;
  long length;
  double *s;
} MONO_PCM;

typedef struct {
  int fs;
  int bits;
  long length;
  double *s1;
  double *s2;
} STEREO_PCM;

void mono_wave_dump_header(char *file_name);

//void mono_wave_dump_header(MONO_PCM *pcm, char *file_name);
void mono_wave_read(MONO_PCM *pcm, char *file_name);

void mono_wave_write(MONO_PCM *pcm, char *file_name);

void stereo_wave_write(STEREO_PCM *pcm, char *file_name);

#endif // _MY_WAVE_H
