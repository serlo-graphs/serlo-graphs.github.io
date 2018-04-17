define(definition,note)
define(satz,tab)
define(lemma,component)
define(meta,box3d)
define(korollar,box)
define(beispiel,ellipse)
define(bemerkung,hexagon)
define(notation,septagon)

define(notwendig,normal)
define(optional,onormal)

digraph G {
p1[label="Kartesisches Produkt, Tupel", shape=definition]
p2[label="Quantoren", shape=definition]
p3[label="Reelle Zahlen", shape=definition]
p4[label="Komplexe Zahlen", shape=definition]
p5[label="Stetigkeit", shape=definition]
p6[label="Intervall", shape=definition]
p7[label="Abbildung", shape=definition]
p8[label="Funktion", shape=definition]

m1[label="Basics und Notationen", shape=meta]
m2[label="Schulwissen", shape=meta]

n1_1[label="Die Menge der n-Tupel reeller Zahlen", shape=beispiel]
n1_2[label="Die Menge der komplexwertigen stetigen Funktionen auf [0,1]", shape=beispiel]
n1_3[label="Motivation abstrakte Vektorräume", shape=bemerkung]
n1_4[label="Notation Körper", shape=notation]
n1_5[label="Definition Vektorraum", shape=definition]

p1->m1 [arrowhead=notwendig]
p2->m1 [arrowhead=notwendig]
p4->m1 [arrowhead=notwendig]
p7->m1 [arrowhead=notwendig]

p3->m2 [arrowhead=notwendig]
p5->m2 [arrowhead=notwendig]
p6->m2 [arrowhead=notwendig]
p8->m2 [arrowhead=notwendig]

p3->p6 [arrowhead=notwendig]

n1_1->n1_3 [arrowhead=optional]
n1_2->n1_3 [arrowhead=optional]
n1_3->n1_5 [arrowhead=optional]
n1_4->n1_5 [arrowhead=notwendig]

}