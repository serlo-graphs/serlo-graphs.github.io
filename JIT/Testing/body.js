document.write('\
<title class="templated">Serlo Graphs - Abhängigkeitsbaum für: %TITLE</title>\
<div class="templated">\
<center><h2>Abhängigkeitsbaum für: %TITLE</h2></center>\
</div>\
\
<div id="container">\
\
<div id="left-container">\
\
\
\
<div class="text">\
<h4>\
Hinweise zur Benutzung:\
</h4>\
\
            Ein <b>Linksklick</b> wählt einen Node und klappt seinen Untergraphen auf.<br /><br />\
            Ein <b>Rechtsklick</b> löscht einen Node samt seinem Untergraphen (Seite neu laden zum wiederherstellen des Graphen).<br /><br />\
			Die <b>Orientierung des Graphen</b> kann in der Auswahlbox rechts angepasst werden.<br /><br />\
            Den <b>Hintergrund mit der Maus ziehen</b> um den Graphen zu verschieben.<br /><br />\
            Die <b>Farbe eines Nodes</b> hängt von der Gesamtzahl seiner Abhängigkeiten ab.\
            \
</div>\
\
<div id="id-list"></div>\
        \
</div>\
\
<div id="center-container">\
    <div id="infovis"></div>\
</div>\
\
<div id="right-container">\
\
<h4>Orientierung des Graphen</h4>\
<table>\
    <tr>\
        <td>\
            <label for="r-left">Links </label>\
        </td>\
        <td>\
            <input type="radio" id="r-left" name="orientation" checked="checked" value="left" />\
        </td>\
    </tr>\
    <tr>\
          <td>\
            <label for="r-right">Rechts </label>\
          </td> \
          <td> \
           <input type="radio" id="r-right" name="orientation" value="right" />\
          </td>\
    </tr>\
    <tr>\
         <td>\
            <label for="r-top">Oben </label>\
         </td>\
         <td>\
            <input type="radio" id="r-top" name="orientation" value="top" />\
         </td>\
    </tr>\
    <tr>\
         <td>\
            <label for="r-bottom">Unten </label>\
          </td>\
          <td>\
            <input type="radio" id="r-bottom" name="orientation" value="bottom" />\
          </td>\
    </tr>\
</table>\
\
 <!-- The following is not needed any more, removing nodes now works via right click. -->\
 <!-- <h4>Selection Mode</h4>\
<table>\
    <tr>\
        <td>\
            <label for="s-normal">Normal </label>\
        </td>\
        <td>\
            <input type="radio" id="s-normal" name="selection" checked="checked" value="normal" />\
        </td>\
    </tr>\
   <tr>\
         <td>\
            <label for="s-remove">Remove subtree </label>\
         </td>\
         <td>\
            <input type="radio" id="s-remove" name="selection" value="remove" />\
         </td>\
    </tr>\
</table> -->\
\
</div>\
\
<div id="log"></div>\
</div>\
\
')
