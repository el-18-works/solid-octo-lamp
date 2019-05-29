#ifndef _MY_WAVE_H
#define _MY_WAVE_H

// 16 bit mono.
struct MONO_PCM {
	int fs;
	int bits;
	long length;
	double *s;
};

// 16 bit stereo.
struct STEREO_PCM {
	int fs;
	int bits;
	long length;
	double *sl;
	double *sr;
};

void wave_dump_header(char *wav_file_name);

void mono_wave_alloc(struct MONO_PCM *pcm, double secs);
void stereo_wave_alloc(struct STEREO_PCM *pcm, double secs);

void mono_wave_free(struct MONO_PCM *pcm);
void stereo_wave_free(struct STEREO_PCM *pcm);

void mono_wave_read(struct MONO_PCM *pcm, char *wav_file_name);
void stereo_wave_read(struct STEREO_PCM *pcm, char *wav_file_name);

void mono_wave_write(struct MONO_PCM *pcm, char *wav_file_name);
void stereo_wave_write(struct STEREO_PCM *pcm, char *wav_file_name);

#endif // _MY_WAVE_H
