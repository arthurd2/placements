set zlabel "# Solucoes" rotate by 90
set xlabel "# VMs"
set ylabel "# PMs"
#set zrange [0:12]
#set cbrange [0:12]
set pm3d
#set grid nopolar
#set pm3d
#set grid
#set hidden3d
#set palette defined (0 "green", 10 "red")
#set style data lines
set view 60,315
#set terminal pngcairo mono size 800,600
set terminal pngcairo size 800,600


set title "Variação de Quantidade (SC - CC)"
sem = '../src/filename.sem.csv'
com = '../src/filename.com.csv'
max = '../src/filename.max.csv'
delta = '../src/filename.delta.csv'

#splot exp1 with lines , exp2 with lines
#splot com matrix with lines, sem matrix with lines
set output 'com.constraints.png'
splot com with lines
set output 'sem.constraints.png'
splot sem with lines

set zlabel "%" rotate by 90
set output 'delta.constraints.png'
splot delta with lines

set zlabel "%" rotate by 90
set output 'max+constraints.png'
splot max with lines