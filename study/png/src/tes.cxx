#include <png++/png.hpp>
#include <math.h>
#include <stdio.h>
#include <stdlib.h>

typedef png::uint_32 uint;
typedef png::rgb_pixel pix;
typedef const png::rgb_pixel& pixref;
typedef long double flot;
typedef long long ent;
typedef int vec2[2];
typedef int vec3[3];
typedef flot f2[2];
typedef flot f3[3];

png::image< png::rgb_pixel > image(1280, 720);


void bgcolor(pix rgbpix) {
  for(uint y =0; y < image.get_height(); ++y) {
    for(uint x =0; x < image.get_width(); ++x) {
      image[y][x].red -=rgbpix.red/7;
      image[y][x].green -=rgbpix.green/7;
      image[y][x].blue -=rgbpix.blue/7;
    }
  }
}

void lin(uint dex, uint dey, uint adx, uint ady) {
  pix p(0x6a, 0x51, 0x40);
  //pix p(0xfe, 0xef, 0xab);
  int dx =adx-dex;
  int dy =ady-dey; 
  flot l =sqrtl(dx*dx + dy*dy);
  flot c =dx/l;
  flot s =dy/l;
  //printf("%Lf,%Lf %Lf %d,%d\n",c,s,l, dx,dy);
  flot d =8.6;
  for(flot i=0; i<l; i+=d*drand48()) {
    flot x =dex+i*c;
    flot y =dey+i*s;
    uint ox =x+0.5;
    uint oy =y+0.5;
    if (oy < 2 || ox < 2 || ox > image.get_width()-1 || oy >image.get_height()-1) 
      continue;
    //printf("%Lf,%Lf %d,%d\n",c,s,ox,oy);
    for(uint ux=ox; ux<=ox+1; ++ux) {
      for(uint uy=oy; uy<=oy; ++uy) {
        pix pr =image[uy][ux];
        pr.red =  pr.red+(p.red-pr.red)*drand48();
        pr.green =  pr.green+(p.green-pr.green)*drand48();
        pr.blue =  pr.blue+(p.blue-pr.blue)*drand48();
/*
        pr.red *= (1-fabs(x-ux))*(1-fabs(y-uy)) * drand48();
        pr.green *= (1-fabs(x-ux))*(1-fabs(y-uy)) * drand48();
        pr.blue *= (1-fabs(x-ux))*(1-fabs(y-uy)) * drand48();
*/
        image[uy][ux] =pr;
      }
    }
  }
}

void g_L( const vec2 *p, uint len ) {
  for (uint i=0; i<len; ++i) {
    //printf("%d) %d,%d -> %d,%d\n", i, p[i][0], p[0][1], p[i+1][0], p[i+1][1]);
    lin(p[i][0], p[i][1], p[i+1][0], p[i+1][1]);
  }
}
#define MAX_RECUR_N 128
void subdiv(int *num, flot **res, uint recur_n, const flot d2inf, const flot d2tot, const f2 p0, const f2 p1, const f2 p2, const f2 p3) {
  //printf("%d\n",recur_n);
  const flot f1x =p1[0] - p0[0] - 1.0/3.0*(p3[0] - p0[0]);
  const flot f1y =p1[1] - p0[1] - 1.0/3.0*(p3[1] - p0[1]);
  const flot f2x =p2[0] - p0[0] - 2.0/3.0*(p3[0] - p0[0]);
  const flot f2y =p2[1] - p0[1] - 2.0/3.0*(p3[1] - p0[1]);
  const flot d2 =fmaxl(f1x*f1x + f1y*f1y, f2x*f2x + f2y*f2y);
  if (recur_n < MAX_RECUR_N &&
    d2inf < d2tot &&
    d2inf <= d2 ) {
    const f2 h ={(p1[0]+p2[0])/2, (p1[1]+p2[1])/2};
    const f2 sin0 ={p0[0], p0[1]}; 
    const f2 sin1 ={(p0[0]+p1[0])/2, (p0[1]+p1[1])/2}; 
    const f2 sin2 ={(h[0]+sin1[0])/2, (h[1]+sin1[1])/2};
    const f2 dex3 ={p3[0], p3[1]}; 
    const f2 dex2 ={(p3[0]+p2[0])/2, (p3[1]+p2[1])/2}; 
    const f2 dex1 ={(h[0]+dex2[0])/2, (h[1]+dex2[1])/2};
    const f2 dex0 ={(sin2[0]+dex1[0])/2, (sin2[1]+dex1[1])/2};
    const f2 sin3 ={dex0[0], dex0[1]};
    subdiv(num, res, recur_n+1, d2inf, d2, sin0, sin1, sin2, sin3);
    subdiv(num, res, recur_n+1, d2inf, d2, dex0, dex1, dex2, dex3);
  } else {
    *(*res)++ =p0[0];
    *(*res)++ =p0[1];
    (*num) ++;
  }
}

#define BUF_SIZ 2024
int g_C( const vec2 *vp, uint len, uint n ) {
  uint l =0;
  static f2 p[4];
  if (len == 3) {
    p[0][0] =(flot)vp[0][0]; p[0][1] =(flot)vp[0][1];
    p[1][0] =(flot)vp[1][0]; p[1][1] =(flot)vp[1][1];
    p[2][0] =(flot)vp[1][0]; p[2][1] =(flot)vp[1][1];
    p[3][0] =(flot)vp[2][0]; p[3][1] =(flot)vp[2][1];
  } else if (len == 4) {
    p[0][0] =(flot)vp[0][0]; p[0][1] =(flot)vp[0][1];
    p[1][0] =(flot)vp[1][0]; p[1][1] =(flot)vp[1][1];
    p[2][0] =(flot)vp[2][0]; p[2][1] =(flot)vp[2][1];
    p[3][0] =(flot)vp[3][0]; p[3][1] =(flot)vp[3][1];
  } else {
    return -1;
  }
  static flot res[BUF_SIZ*2];
  flot *resp =res;
  printf("subdiv %p %p\n", resp,res);
  int num =0;
  subdiv(&num, &resp, 0, n*n, n*n+1, p[0], p[1], p[2], p[3]);
  printf("subdiv %p %p\n", resp,res);
  printf("subdiv %d, %ld\n", num, resp-res);
  if (resp == res) {
    return 0;
  }
  for(flot *y0 =--resp, *x0 =--resp, *y1 =--resp, *x1=--resp;
    resp >= res;
    y0 =y1, x0 =x1, y1 =--resp, x1 =--resp) {
    lin(*x0, *y0, *x1, *y1);
  }
  return 0;
}

const int born =5;
const int cote =600;
const int res =10;
const ent unit =cote/res;
const ent cx =image.get_width()/2+unit/2;
const ent cy =image.get_height()/2+unit/2;
vec2 m2[res*res*4];
vec3 m3[res*res*4];

void initm2() {
  for(uint i=0; i<res*2; ++i) {
    for(uint j=0; j<res*2; ++j) {
      m2[i*res*2+j][0] =(j-res) * unit;// * (flot)(res*2-i)/res;
      m2[res*res*4-i*res*2+j][1] =(i-res) * unit;// * (flot)(res*2-i)/(res);
    }
  }
}
void linm2() {
  for(uint i=0; i<res*2; ++i) {
    for(uint j=0; j<res*2; ++j) {
      //printf("%d,%d ",m2[i*res+j][0], m2[i*res+j][1]);
      if (j+1 != res*2)
        lin(m2[i*res*2+j][0] + cx, cy + m2[i*res*2+j][1], m2[i*res*2+j+1][0] + cx, cy + m2[i*res*2+j+1][1]);
      if (i+1 != res*2)
        lin(m2[i*res*2+j][0] + cx, cy + m2[i*res*2+j][1], m2[(i+1)*res*2+j][0] + cx, cy + m2[(i+1)*res*2+j][1]);
    }
  }
}
void initm3() {
  for(uint i=0; i<res*2; ++i) {
    for(uint j=0; j<res*2; ++j) {
      m3[i*res*2+j][0] =m2[i*res*2+j][0];
      m3[i*res*2+j][1] =m2[i*res*2+j][1];
      m3[i*res*2+j][2] =0;
    }
  }
}

int main() {
  for(png::uint_32 y =0; y < image.get_height(); ++y) {
    for(png::uint_32 x =0; x < image.get_width(); ++x) {
      image[y][x] =pix(190-x/10, 235-y/10, 255-(x+y)/30);
    }
  }
  bgcolor(pix(140, 144, 210));
  //initm2();
  //initm3();
  //linm2();

  vec2 vq[4];
  vq[0][0] =1200, vq[0][1] =50;
  vq[1][0] =800, vq[1][1] =500;
  vq[2][0] =200, vq[2][1] =400;
  vq[3][0] =51, vq[3][1] =40;
  g_L(vq, 3);
  g_C(vq, 4, 1);
  //lin(1200,50, 50, 500);
  //lin(1200,500, 100, 200);
  image.set_interlace_type(png::interlace_adam7);
  image.write("cache/tes.png");
  return 0;
}

