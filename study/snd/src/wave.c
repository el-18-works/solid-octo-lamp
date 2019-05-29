#include "wave.h"

#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>

//#include <time.h>
#include <sys/sysmacros.h>

/* isatty */
#include <unistd.h>

#define BOLD(x)     ( isatty(1) ? "\033[1m"    x "\033[0m" : x )
#define COLOR39(x)  ( isatty(1) ? "\e[38;5;039m" x "\e[0m" : x )
#define COLOR112(x) ( isatty(1) ? "\e[38;5;112m" x "\e[0m" : x )
#define COLOR196(x) ( isatty(1) ? "\e[38;5;196m" x "\e[0m" : x )

void wave_dump_header(char *file_name) {
/*
The header of a WAV (RIFF) file is 44 bytes long and has the following format:

Positions  Sample Value      Description
1 - 4      "RIFF"              Marks the file as a riff file. Characters are each 1 byte long.
5 - 8      File size (integer)  Size of the overall file - 8 bytes, in bytes (32-bit integer). 
                                Typically, you'd fill this in after creation.
9 -12      "WAVE"              File Type Header. For our purposes, it always equals "WAVE".
13-16      "fmt "              Format chunk marker. Includes trailing null
17-20      16                  Length of format data as listed above
21-22      1                  Type of format (1 is PCM) - 2 byte integer
23-24      2                  Number of Channels - 2 byte integer
25-28      44100              Sample Rate - 32 byte integer. Common values are 44100 (CD), 48000 (DAT). 
                                Sample Rate = Number of Samples per second, or Hertz.
29-32      176400              (Sample Rate * BitsPerSample * Channels) / 8.
33-34      4                  (BitsPerSample * Channels) / 8.
                  1 - 8 bit mono
                  2 - 8 bit stereo/16 bit mono
                  4 - 16 bit stereo
35-36      16                  Bits per sample
37-40    "data"              "data" chunk header. Marks the beginning of the data section.
41-44      File size (data)  Size of the data section.

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
  fprintf(stderr, "dwave_dump_header : cannot open %s\n", file_name);
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

  printf("%35s : %s\n", COLOR39("riff_chunk_ID") , riff_chunk_ID);
  printf("%35s : %ld\n", COLOR39("riff_chunk_size"), riff_chunk_size);
  printf("%35s : %s (%s)\n", COLOR39("riff_form_type"),  riff_form_type, riff_form_type[0] == 'W' ? BOLD(".wav") : "??");

  printf("%35s : %s (%s)\n",COLOR112("fmt_chunk_ID"), fmt_chunk_ID, fmt_chunk_ID[0] == 'f' ? BOLD("Format chunk marker") : "??");
  printf("%35s : %ld\n", COLOR112("fmt_chunk_size"), fmt_chunk_size);
  printf("%35s : %d (%s)\n", COLOR112("fmt_wave_format_type"), fmt_wave_format_type, fmt_wave_format_type == 1 ? BOLD("PCM") : "??");
  printf("%35s : %d (%s)\n", COLOR112("fmt_channel"), fmt_channel, fmt_channel == 1 ? BOLD("mono") : BOLD("stereo"));
  printf("%35s : fs=%ld\n", COLOR112("fmt_samples_per_sec"), fmt_samples_per_sec);
  printf("%35s : %ld\n", COLOR112("fmt_bytes_per_sec"), fmt_bytes_per_sec);
  printf("%35s : %d\n", COLOR112("fmt_block_size"), fmt_block_size);
  printf("%35s : bits=%d,db=%f\n", COLOR112("fmt_bits_per_sample"), fmt_bits_per_sample, 20*log10(exp2(fmt_bits_per_sample)));

  printf("%35s : %s\n", COLOR196("data_chunk_ID"), data_chunk_ID);
  printf("%35s : %ld\n", COLOR196("data_chunk_size"), data_chunk_size);
}

void mono_wave_alloc(struct MONO_PCM *pcm, double secs) {
  pcm->fs =8000;
  pcm->bits =16;
  pcm->length =int(pcm->fs * secs);
  pcm->s =(double*)calloc(pcm->length, sizeof(double));
  for(int n=0; n<pcm->length; ++n) {
    pcm->s[n] =0;
  }
}

void stereo_wave_alloc(struct STEREO_PCM *pcm, double secs) {
  pcm->fs =8000;
  pcm->bits =16;
  pcm->length =int(pcm->fs * secs);
  pcm->sl =(double*)calloc(pcm->length, sizeof(double));
  pcm->sr =(double*)calloc(pcm->length, sizeof(double));
  for(int n=0; n<pcm->length; ++n) {
    pcm->sl[n] =0;
    pcm->sr[n] =0;
  }
}

void mono_wave_free(struct MONO_PCM *pcm) {
  free(pcm->s);
}

void stereo_wave_free(struct STEREO_PCM *pcm) {
  free(pcm->sl);
  free(pcm->sr);
}

void mono_wave_read(struct MONO_PCM *pcm, char *file_name) {
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

  for (n = 0; n < pcm->length; n++) {
    fread(&data, 2, 1, fp);
    pcm->s[n] = (double)data / 32768.0; // 32768 = 2**15
  }
  fclose(fp);
}

void stereo_wave_read(struct STEREO_PCM *pcm, char *file_name) {
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

  struct stat st;
  if(stat(file_name, &st) == 0) {
  if (data_chunk_size != st.st_size-44) {
      fprintf(stderr, "adjusting data_chunk_size to : %ld\n", st.st_size-44);
    data_chunk_size =st.st_size;
  }
  }

  pcm->fs = fmt_samples_per_sec;
  pcm->bits = fmt_bits_per_sample;
  pcm->length = data_chunk_size / 4;

  pcm->sl = (double*)calloc(pcm->length, sizeof(double));
  pcm->sr = (double*)calloc(pcm->length, sizeof(double));

  for (n = 0; n < pcm->length; n++) {
    fread(&data, 2, 1, fp);
    pcm->sl[n] = (double)data / 32768.0; // 32768 = 2**15
    pcm->sr[n] = (double)data / 32768.0; // 32768 = 2**15
  }
  fclose(fp);
}

void mono_wave_write(struct MONO_PCM *pcm, char *file_name) {
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

  if (file_name != NULL) {
    fp = fopen(file_name, "wb");
  } else {
    fp = stdout;
    //fp = popen("play - >/dev/null 2>&1","w");
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

  for (n = 0; n < pcm->length; n++) {

    s = (pcm->s[n] + 1.0) / 2.0 * 65536.0;

/*   s = pcm->s[n]....                                   */
/*         * B E F O R E *                               */      
/*   1.0 ->|   . .               . .                     */      
/*         | .     .           .     .                   */      
/*   __0___|._______.________.________._____________     */      
/*         |         .     .           .                 */      
/*         |           . .               . .             */      
/*  -1.0 ->|                                             */      

/*   s = pcm->s[n] + 1.0....                             */
/*                                                       */      
/*   2.0 ->|   . .               . .                     */      
/*         | .     .           .     .                   */      
/*   1.0 ->|.       .        .        .                  */      
/*         |         .     .           .                 */      
/*     0 __|___________._._______________._.________     */      
/*         |                                             */      

/*   s = (pcm->s[n] + 1.0) / 2....                       */
/*                                                       */      
/*   1.0 ->|   . .               . .                     */      
/*         | .     .           .     .                   */      
/*   0.5 ->|.       .        .        .                  */      
/*         |         .     .           .                 */      
/*     0 __|___________._._______________._.________     */      
/*         |                                             */      

/*   s = (pcm->s[n] + 1.0) / 2 * 65536                   */
/*         * A F T E R *                                 */      
/* 65536 ->|   . .               . .                     */      
/*         | .     .           .     .                   */      
/* 32768 ->|.       .        .        .                  */      
/*         |         .     .           .                 */      
/*     0 __|___________._._______________._.________     */      
/*         |                                             */      


    if (s > 65535.0) {
      s = 65535.0; // low-pass filter
    } else if (s < 0.0) {
      s = 0.0;     // high-pass filter
    }

/*
   Cは小数を整数に型変換するとき小数点以下をすべて切り捨てる。
   だから、予め0.5を足してから型変換すれば四捨五入の結果が得られる。

    i   | i+0.5 | (short)i | (short)(i+0.5)
  ----+-------+----------+---------------
  0.0 | 0.5   | 0        | 0
  0.1 | 0.7   | 0        | 0
  0.2 | 0.8   | 0        | 0
  0.3 | 0.9   | 0        | 0
  0.4 | 1.0   | 0        | 0   _____四捨__|/
  0.5 | 1.1   | 0        | 1        五入 /|
  0.6 | 1.2   | 0        | 1
  0.7 | 1.3   | 0        | 1
  0.8 | 1.4   | 0        | 1
  0.9 | 1.5   | 0        | 1
  1.0 | 1.6   | 1        | 1
 */
    data = (short)(s + 0.5) - 32768;

/*  data = (short)(s      )                              */
/*         * B E F O R E *                               */      
/* 65535 ->|   . .               . .                     */      
/*         | .     .           .     .                   */      
/*32767.5->|.       .        .        .                  */      
/*         |         .     .           .                 */      
/*     0 __|___________._._______________._.________     */      
/*         |                                             */      

/*  data = (short)(s + 0.5)                              */
/*                                                       */      
/*65535.5->|   . .               . .                     */      
/*         | .     .           .     .                   */      
/* 32768 ->|.       .        .        .                  */      
/*         |         .     .           .                 */      
/*    0.5__|___________._._______________._.________     */      
/*         |                                             */      

/*  data = (short)(s + 0.5) - 32768                      */
/*         * A F T E R *                                 */      
/* 32767.5->|   . .               . .                    */      
/*          | .     .           .     .                  */      
/*   __0____|._______.________.________._____________    */      
/*          |         .     .           .                */      
/*          |           . .               . .            */      
/*-32767.5->|                                            */      
/*                                                       */      
/*                                                       */      

    fwrite(&data, 2, 1, fp);
  }
  fclose(fp);
}

void stereo_wave_write(struct STEREO_PCM *pcm, char *file_name) {
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

  if (file_name != NULL) {
    fp = fopen(file_name, "wb");
  } else {
    fp = stdout;
    //fp = popen("play - >/dev/null 2>&1","w");
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

  for (n = 0; n < pcm->length; n++) {
    s = (pcm->sl[n] + 1.0) / 2.0 * 65536.0;

    if (s > 65535.0)
    {
      s = 65535.0;
    } else if (s < 0.0) {
      s = 0.0;
    }
    data = (short)(s + 0.5) - 32768;
    fwrite(&data, 2, 1, fp);

    s = (pcm->sr[n] + 1.0) / 2.0 * 65536.0;

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

