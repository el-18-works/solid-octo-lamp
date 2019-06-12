#!/usr/bin/env python3

from copy import deepcopy as copy


def menor(A, i, j) :
  m =[]
  for ii in range(len(A)) :
    if ii == i :
      continue
    r =[]
    for jj in range(len(A)) :
      if jj != j :
        r.append(A[ii][jj])
    m.append(r)
  return m

def det(A) :
  n =0
  if len(A) > 2 : 
    for i in range(len(A)) :
      n +=A[0][i] * det(menor(A, 0, i)) * (-1 if i % 2 else 1)
    return n
  for i in range(len(A)) :
    m =1
    for j in range(len(A)) :
      #print(i,j,A[j][(i+j) % len(A)])
      m *=A[j][(i+j) % len(A)]
    n +=m * (-1 if i % 2 else 1)
  return n

def cramer(A, b) :
  try :
    ret =[]
    for n in range(len(A)) :
      m =copy(A)
      for i in range(len(A)) :
        m[i][n] =b[i]
      x =det(m)/det(A)
      ret.append(x)
    return tuple(ret)
  except ZeroDivisionError as e :
    return

def fer(A) :
  m =copy(A)
  if m[1][0] != 0 and abs(m[0][0]) >  abs(m[1][0]) :
    r =m[0]
    m[0] =m[1]
    m[1] =r
  if m[2][0] != 0 and abs(m[1][0]) >  abs(m[2][0]) :
    r =m[1]
    m[1] =m[2]
    m[2] =r
  if m[1][0] != 0 and abs(m[0][0]) >  abs(m[1][0]) :
    r =m[0]
    m[0] =m[1]
    m[1] =r
  try :
    r =m[0][0]
    for i in range(len(m[0])) :
      m[0][i] /=r
    r =m[1][0]
    for i in range(len(m[1])) :
      m[1][i] -=m[0][i]*r
    r =m[2][0]
    for i in range(len(m[1])) :
      m[2][i] -=m[0][i]*r
    r =m[1][1]
    for i in range(1, len(m[1])) :
      m[1][i] /=r
    r =m[0][1]
    for i in range(1, len(m[0])) :
      m[0][i] -=m[1][i]*r
    r =m[2][1]
    for i in range(1, len(m[2])) :
      m[2][i] -=m[1][i]*r
    m[2][2] /=m[2][2]
    m[0][2] -=m[0][2]/m[2][2]
    m[1][2] -=m[1][2]/m[2][2]
  except ZeroDivisionError as e :
    pass
  return m

def rango(A) :
  m =fer(A)
  i =0
  for r in m :
    if sum(r) : i +=1
  return i
  
def rouchefrobenius(A, b) :
  m =copy(A)
  n =copy(A)
  for i in range(len(b)) :
    n[i].append(b[i])
  rm =rango(m)
  rn =rango(n)
  if rm != rn :
    print("incompatible")
    return 1
  elif rm == len(A) :
    print("compatible determinado")
    print("x=%s, y=%s, z=%s"%cramer(A, b))
    return 2
  else :
    print("compatible indeterminado")
    for r in fer(n) :
      if sum(r) == 0 :
        break
      s =""
      for i in range(3) :
        if r[i] != 0 :
          c =str(r[i])+"xyz"[i]
          s += "+"+c if s and r[i] > 0 else c
      print(s + "=" + str(r[3]))
    return 3

m =[[-4, 3, 8],
  [3, 1, -1],
  [2, 4, 6]]
m =[[2, 1, 2], [1, 2, 3], [3, 2, 1]]
#m =[[2, 1, 3], [3, 1, 4], [2, 3, 5]]
b =[ 1,  3,  0]
print("DET",det(m))
print(fer(m))
print(rango(m))
rouchefrobenius(m, b)
#m =[[2, 1, 3, 2], [3, 1, 4, 3], [2, 3, 5, 5]]
m =[[2, 1, 3], [3, 1, 4], [2, 3, 5]]
b =[ 2,  3,  5]
m =[[1, 2, 1], [2, 5, 2], [1, 3, 1]]
b =[ 3,  7,  4]
m =[[2, 3, -1], [1, 4, 1], [1, 2, 3]]
b =[ 1,  0,  4]
rouchefrobenius(m, b)
print("DET",det(m))
m =[[3, 3, 3], [6, 3, 3], [6, 3, 3]]
print("cramer")
print(det(m))
exit()
x,y,z =cramer(m, [3, 3, 3])
print("cramer")
print("x=%s, y=%s z=%s"%(x, y, z))

