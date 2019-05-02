#include "wave.h"
#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>

#include <sys/types.h>
#include <sys/stat.h>
#include <time.h>
#include <stdio.h>
#include <stdlib.h>
#include <sys/sysmacros.h>


void mono_wave_dump_header(char *file_name) {
/*
The header of a WAV (RIFF) file is 44 bytes long and has the following format:

Positions	Sample Value	Description
1 - 4	"RIFF"	Marks the file as a riff file. Characters are each 1 byte long.
5 - 8	File size (integer)	Size of the overall file - 8 bytes, in bytes (32-bit integer). Typically, you'd fill this in after creation.
9 -12	"WAVE"	File Type Header. For our purposes, it always equals "WAVE".
13-16	"fmt "	Format chunk marker. Includes trailing null
17-20	16	Length of format data as listed above
21-22	1	Type of format (1 is PCM) - 2 byte integer
23-24	2	Number of Channels - 2 byte integer
25-28	44100	Sample Rate - 32 byte integer. Common values are 44100 (CD), 48000 (DAT). Sample Rate = Number of Samples per second, or Hertz.
29-32	176400	(Sample Rate * BitsPerSample * Channels) / 8.
33-34	4	(BitsPerSample * Channels) / 8.1 - 8 bit mono2 - 8 bit stereo/16 bit mono4 - 16 bit stereo
35-36	16	Bits per sample
37-40	"data"	"data" chunk header. Marks the beginning of the data section.
41-44	File size (data)	Size of the data section.
Sample values are given above for a 16-bit stereo source.
*/
  FILE *fp;
  char riff_chunk_ID[5];
  long riff_chunk_size;
  char riff_form_type[5];
  char fmt_chunk_ID[5];
  long fmt_chunk_size;
  short fmt_wave_format_type;
  short fmt_channel;
  long fmt_samples_per_sec;
  long fmt_bytes_per_sec;
  short fmt_block_size;
  short fmt_bits_per_sample;
  char data_chunk_ID[5];
  long data_chunk_size;
  short data;
  riff_chunk_ID[4] =0;
  riff_form_type[4] =0;
  fmt_chunk_ID[4] =0;
  data_chunk_ID[4] =0;

  fp = fopen(file_name, "rb");
  if (fp == NULL) {
	fprintf(stderr, "dump header : cannot open %s\n", file_name);
	exit(1);
  }

  fread(riff_chunk_ID, 1, 4, fp);
  fread(&riff_chunk_size, 4, 1, fp);
  fread(riff_form_type, 1, 4, fp);
  fread(fmt_chunk_ID, 1, 4, fp);
  fread(&fmt_chunk_size, 4, 1, fp);
  fread(&fmt_wave_format_type, 2, 1, fp);
  fread(&fmt_channel, 2, 1, fp);
  fread(&fmt_samples_per_sec, 4, 1, fp);
  fread(&fmt_bytes_per_sec, 4, 1, fp);
  fread(&fmt_block_size, 2, 1, fp);
  fread(&fmt_bits_per_sample, 2, 1, fp);
  fread(data_chunk_ID, 1, 4, fp);
  fread(&data_chunk_size, 4, 1, fp);

  printf("riff_chunk_ID        : %s\n", riff_chunk_ID);
  printf("riff_chunk_size      : %ld\n", riff_chunk_size);
  printf("riff_form_type       : %s\n", riff_form_type);
  printf("fmt_chunk_ID         : %s\n", fmt_chunk_ID);
  printf("fmt_chunk_size       : %ld\n", fmt_chunk_size);
  printf("fmt_wave_format_type : %d\n", fmt_wave_format_type);
  printf("fmt_channel          : %d\n", fmt_channel);
  printf("fmt_samples_per_sec  : fs=%ld\n", fmt_samples_per_sec);
  printf("fmt_bytes_per_sec    : %ld\n", fmt_bytes_per_sec);
  printf("fmt_block_size       : %d\n", fmt_block_size);
  printf("fmt_bits_per_sample  : bits=%d,db=%f\n", fmt_bits_per_sample, 20*log10(exp2(fmt_bits_per_sample)));
  printf("data_chunk_ID        : %s\n", data_chunk_ID);
  printf("data_chunk_size      : %ld\n", data_chunk_size);
}

void mono_wave_read(MONO_PCM *pcm, char *file_name) {
  FILE *fp;
  int n;
  char riff_chunk_ID[5];
  long riff_chunk_size;
  char riff_form_type[5];
  char fmt_chunk_ID[5];
  long fmt_chunk_size;
  short fmt_wave_format_type;
  short fmt_channel;
  long fmt_samples_per_sec;
  long fmt_bytes_per_sec;
  short fmt_block_size;
  short fmt_bits_per_sample;
  char data_chunk_ID[5];
  long data_chunk_size;
  short data;
  riff_chunk_ID[4] =0;
  riff_form_type[4] =0;
  fmt_chunk_ID[4] =0;
  data_chunk_ID[4] =0;

  fp = fopen(file_name, "rb");

  fread(riff_chunk_ID, 1, 4, fp);
  fread(&riff_chunk_size, 4, 1, fp);
  fread(riff_form_type, 1, 4, fp);
  fread(fmt_chunk_ID, 1, 4, fp);
  fread(&fmt_chunk_size, 4, 1, fp);
  fread(&fmt_wave_format_type, 2, 1, fp);
  fread(&fmt_channel, 2, 1, fp);
  fread(&fmt_samples_per_sec, 4, 1, fp);
  fread(&fmt_bytes_per_sec, 4, 1, fp);
  fread(&fmt_block_size, 2, 1, fp);
  fread(&fmt_bits_per_sample, 2, 1, fp);
  fread(data_chunk_ID, 1, 4, fp);
  fread(&data_chunk_size, 4, 1, fp);
  printf("riff_chunk_ID        : %s\n", riff_chunk_ID);
  printf("riff_chunk_size      : %ld\n", riff_chunk_size);
  printf("riff_form_type       : %s\n", riff_form_type);
  printf("fmt_chunk_ID         : %s\n", fmt_chunk_ID);
  printf("fmt_chunk_size       : %ld\n", fmt_chunk_size);
  printf("fmt_wave_format_type : %d\n", fmt_wave_format_type);
  printf("fmt_channel          : %d\n", fmt_channel);
  printf("fmt_samples_per_sec  : fs=%ld\n", fmt_samples_per_sec);
  printf("fmt_bytes_per_sec    : %ld\n", fmt_bytes_per_sec);
  printf("fmt_block_size       : %d\n", fmt_block_size);
  printf("fmt_bits_per_sample  : bits=%d,db=%f\n", fmt_bits_per_sample, 20*log10(exp2(fmt_bits_per_sample)));
  printf("data_chunk_ID        : %s\n", data_chunk_ID);
  printf("data_chunk_size      : %ld\n", data_chunk_size);

  struct stat st;
  if(stat(file_name, &st) == 0) {
  	printf("file size : %ld\n", st.st_size);
	if (data_chunk_size != st.st_size-44) {
  		printf("adjusting data_chunk_size to : %ld\n", st.st_size-44);
		data_chunk_size =st.st_size;
	}
  }

  pcm->fs = fmt_samples_per_sec;
  pcm->bits = fmt_bits_per_sample;
  pcm->length = data_chunk_size / 2;

  pcm->s = (double*)calloc(pcm->length, sizeof(double));

  for (n = 0; n < pcm->length; n++)
  {
    fread(&data, 2, 1, fp);
    pcm->s[n] = (double)data / 32768.0;
  }
  fclose(fp);
}

void stereo_wave_write(STEREO_PCM *pcm, char *file_name) {
  FILE *fp;
  int n;
  char riff_chunk_ID[4];
  long riff_chunk_size;
  char riff_form_type[4];
  char fmt_chunk_ID[4];
  long fmt_chunk_size;
  short fmt_wave_format_type;
  short fmt_channel;
  long fmt_samples_per_sec;
  long fmt_bytes_per_sec;
  short fmt_block_size;
  short fmt_bits_per_sample;
  char data_chunk_ID[4];
  long data_chunk_size;
  short data;
  double s;

  riff_chunk_ID[0] = 'R';
  riff_chunk_ID[1] = 'I';
  riff_chunk_ID[2] = 'F';
  riff_chunk_ID[3] = 'F';
  riff_chunk_size = 36 + pcm->length * 2;
  riff_form_type[0] = 'W';
  riff_form_type[1] = 'A';
  riff_form_type[2] = 'V';
  riff_form_type[3] = 'E';

  fmt_chunk_ID[0] = 'f';
  fmt_chunk_ID[1] = 'm';
  fmt_chunk_ID[2] = 't';
  fmt_chunk_ID[3] = ' ';
  fmt_chunk_size = 16;
  fmt_wave_format_type = 1;
  fmt_channel = 2;
  fmt_samples_per_sec = pcm->fs;
  fmt_bytes_per_sec = 2 * pcm->fs * pcm->bits / 8;
  fmt_block_size = 2 * pcm->bits / 8;
  fmt_bits_per_sample = pcm->bits;

  data_chunk_ID[0] = 'd';
  data_chunk_ID[1] = 'a';
  data_chunk_ID[2] = 't';
  data_chunk_ID[3] = 'a';
  data_chunk_size = pcm->length * 4;

  if (file_name)
    fp = fopen(file_name, "wb");
  else {
    //fp = stdout;
    fp = popen("play - >/dev/null 2>&1","w");
  }

  fwrite(riff_chunk_ID, 1, 4, fp);
  fwrite(&riff_chunk_size, 4, 1, fp);
  fwrite(riff_form_type, 1, 4, fp);
  fwrite(fmt_chunk_ID, 1, 4, fp);
  fwrite(&fmt_chunk_size, 4, 1, fp);
  fwrite(&fmt_wave_format_type, 2, 1, fp);
  fwrite(&fmt_channel, 2, 1, fp);
  fwrite(&fmt_samples_per_sec, 4, 1, fp);
  fwrite(&fmt_bytes_per_sec, 4, 1, fp);
  fwrite(&fmt_block_size, 2, 1, fp);
  fwrite(&fmt_bits_per_sample, 2, 1, fp);
  fwrite(data_chunk_ID, 1, 4, fp);
  fwrite(&data_chunk_size, 4, 1, fp);

  for (n = 0; n < pcm->length; n++)
  {
    s = (pcm->s1[n] + 1.0) / 2.0 * 65536.0;

    if (s > 65535.0)
    {
      s = 65535.0;
    }
    else if (s < 0.0)
    {
      s = 0.0;
    }
    data = (short)(s + 0.5) - 32768;
    fwrite(&data, 2, 1, fp);

    s = (pcm->s2[n] + 1.0) / 2.0 * 65536.0;

    if (s > 65535.0) {
      s = 65535.0;
    } else if (s < 0.0) {
      s = 0.0;
    }
    data = (short)(s + 0.5) - 32768;
    fwrite(&data, 2, 1, fp);
  }
  fclose(fp);
}

void mono_wave_write(MONO_PCM *pcm, char *file_name) {
  FILE *fp;
  int n;
  char riff_chunk_ID[4];
  long riff_chunk_size;
  char riff_form_type[4];
  char fmt_chunk_ID[4];
  long fmt_chunk_size;
  short fmt_wave_format_type;
  short fmt_channel;
  long fmt_samples_per_sec;
  long fmt_bytes_per_sec;
  short fmt_block_size;
  short fmt_bits_per_sample;
  char data_chunk_ID[4];
  long data_chunk_size;
  short data;
  double s;

  riff_chunk_ID[0] = 'R';
  riff_chunk_ID[1] = 'I';
  riff_chunk_ID[2] = 'F';
  riff_chunk_ID[3] = 'F';
  riff_chunk_size = 36 + pcm->length * 2;
  riff_form_type[0] = 'W';
  riff_form_type[1] = 'A';
  riff_form_type[2] = 'V';
  riff_form_type[3] = 'E';

  fmt_chunk_ID[0] = 'f';
  fmt_chunk_ID[1] = 'm';
  fmt_chunk_ID[2] = 't';
  fmt_chunk_ID[3] = ' ';
  fmt_chunk_size = 16;
  fmt_wave_format_type = 1;
  fmt_channel = 1;
  fmt_samples_per_sec = pcm->fs;
  fmt_bytes_per_sec = pcm->fs * pcm->bits / 8;
  fmt_block_size = pcm->bits / 8;
  fmt_bits_per_sample = pcm->bits;

  data_chunk_ID[0] = 'd';
  data_chunk_ID[1] = 'a';
  data_chunk_ID[2] = 't';
  data_chunk_ID[3] = 'a';
  data_chunk_size = pcm->length * 2;

  if (file_name)
    fp = fopen(file_name, "wb");
  else {
    //fp = stdout;
    fp = popen("play - >/dev/null 2>&1","w");
  }

  fwrite(riff_chunk_ID, 1, 4, fp);
  fwrite(&riff_chunk_size, 4, 1, fp);
  fwrite(riff_form_type, 1, 4, fp);
  fwrite(fmt_chunk_ID, 1, 4, fp);
  fwrite(&fmt_chunk_size, 4, 1, fp);
  fwrite(&fmt_wave_format_type, 2, 1, fp);
  fwrite(&fmt_channel, 2, 1, fp);
  fwrite(&fmt_samples_per_sec, 4, 1, fp);
  fwrite(&fmt_bytes_per_sec, 4, 1, fp);
  fwrite(&fmt_block_size, 2, 1, fp);
  fwrite(&fmt_bits_per_sample, 2, 1, fp);
  fwrite(data_chunk_ID, 1, 4, fp);
  fwrite(&data_chunk_size, 4, 1, fp);

  for (n = 0; n < pcm->length; n++)
  {
    s = (pcm->s[n] + 1.0) / 2.0 * 65536.0;

    if (s > 65535.0)
    {
      s = 65535.0;
    }
    else if (s < 0.0)
    {
      s = 0.0;
    }
    data = (short)(s + 0.5) - 32768;
    fwrite(&data, 2, 1, fp);
  }
  fclose(fp);
}

