Run m4 and generate png preview in /tmp via:

m4 linalg.dot.m4 > ../dot/linalg.dot && dot -Tpng -o /tmp/out.png ../dot/linalg.dot