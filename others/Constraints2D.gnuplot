#set zlabel "# Solucoes" rotate by 90
set xlabel "# VMs"
set ylabel "# States"
set yrange [1:*]
#set format y "10^{%L}"
set terminal pngcairo size 800,600
#set logscale y

set title "Number of Possible Cloud States"
path = '~/Downloads/Seafile/Doutorado/Codigos/'
file = path.'others/filename.2d.csv'

set output '2D.all.png'
plot for [col=3:4] file using 2:col with lines title columnheader
#plot file using 1:3 with lines title columnheader, file using 1:4 with lines title columnheader,file using 1:5 with lines title columnheader
