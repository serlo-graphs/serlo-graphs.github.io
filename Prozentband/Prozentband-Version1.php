<html>
<head>
    <title> Prozentband </title
    <meta http-equiv="Content-Type" content="text/html;charset=utf8" />
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript" src="touchmouse.js"></script>
        <script type="text/javascript">
        $(function(){
			

			
			var startdraglineal= false;
             $("#Lineal-Hidden").on(TouchMouseEvent.DOWN, function(e){
				currentX=e.pageX;
				startdraglineal= true;
                movelineal(e);                
             });
				function movelineal(e){
                  $("#Lineal-Hidden").on(TouchMouseEvent.MOVE, function(e){
                    if(startdraglineal){
                      moveElement(e, false);
                     }
                   });

                }

             $("#Lineal-Hidden").on(TouchMouseEvent.UP, function(e){
                startdraglineal= false;
             });	
			
			
			
			
			var startdragprozentband= false;
             $("#Prozentband-Hidden").on(TouchMouseEvent.DOWN, function(e){
				currentX=e.pageX;
				startdragprozentband= true;
                moveprozentband(e);                
             });
				function moveprozentband(e){
                  $("#Prozentband-Hidden").on(TouchMouseEvent.MOVE, function(e){
                    if(startdragprozentband){
                      moveElement(e, true);
                     }
                   });

                }

             $("#Prozentband-Hidden").on(TouchMouseEvent.UP, function(e){
                startdragprozentband= false;
             });
});
        </script>


</head>
<body>

<?php
    $PBx=80; // x-Koordinate für Beginn der Skalen-Umrandung
    $PBy=290; //y-Koordinate für Beginn der Skalen-Umrandung
    $height=90;  // hibt Höhe des Prozentbandes + Lineals vor
    $distanceScale=195; // gibt an wie groß der Abstand zwischen dem Prozentband und dem Lineal ist
    $numberOfLines=400; // gibt an wieviele Linien pro skala verwendet werden
    $minDistance=1000/$numberOfLines; // ist die minimale Distanz zwischen den Linien erreicht, sind alle Linien auf der Skala zu sehen.
    $PBline_y1=$PBy+$height; // Linien des Prozentbandes schließen mit unterem Rand des Prozentbandes ab 
    $Lline_y1=$PBy+$distanceScale; // Linien des Lineals schließen mit oberen Rand des Lineals ab.
    $lineDistance=5; // zu Beginn kann eine Distanz zwischen den linien vorgegeben werden. Davon abhängig ist wieviele linien zu Beginn auf der Skala erscheinen.
    $lineX=$PBx+5; // x-Koordinate der 0-Linien
    // small, medium, large geben die Größe einer Linie auf einer Skala vor
    $small=25;  
    $medium=30;
    $large=40;
    $numberOfReiter=5; // Anzahl der Reiter, die erzeugt werden können
    $ankerRX=5; // Grad der Abrundung der Ecken in der Horizontalen
    $ankerRY=10; // Grad der Abrundung der Ecken in der Vertikalen 

    /*  Die Funktion malt die inneren Linien auf einer Skala
        Hierbei wird jede 10. Linie lang gezeichnet, jede 5. mittel-lang und die restlichen Linien kurz.
        Insgesammt werden die 0-Linie + 'numberOfLines'-Linien gezeichnet. Es kann also vorab eingesetllt werden, wie viele Linien es geben soll.
        $IDstart gibt den Startwert für die ID vor; so kann später jede Linie über die ID angesprochen werden
    */
    function drawLines ($IDstart, $Ystart, $Lineal){
        global $PBx, $numberOfLines, $PBline_y1, $lineDistance, $lineX, $small, $medium, $large;
        $percent = "%";
        $textGap = -($large+5);

        if($Lineal == true){
            $small = 0-$small;
            $medium = 0-$medium; 
            $large = 0-$large;
            $percent =" ";
            $textGap=0-$textGap+7;
        }

        for($i=0; $i <= $numberOfLines; $i++){
            if($i==0 || $i%10 == 0){
?>
            <line   id="<?php echo($IDstart); ?>"
                    x1="<?php echo($i*$lineDistance+$lineX); ?>" y1="<?php echo($Ystart); ?>"
                    x2="<?php echo($i*$lineDistance+$lineX); ?>" y2="<?php echo($Ystart-$large); ?>"
                    style="stroke:black; stroke-width:2;"
                    visibility="visible" />

            <text   id="<?php echo($IDstart); ?>"
                    x="<?php echo($i*$lineDistance+$lineX-4); ?>" y="<?php echo($Ystart+$textGap); ?>"
                    style="font-size: 12.8px;"                    visibility="visible">
                    <?php echo($i.$percent); ?> </text>

            
<?php
            }
            else if ($i%5==0){
?>             
            <line   id="<?php echo($IDstart); ?>"
                    x1="<?php echo($i*$lineDistance+$lineX); ?>" y1="<?php echo($Ystart); ?>"
                    x2="<?php echo($i*$lineDistance+$lineX); ?>" y2="<?php echo($Ystart-$medium); ?>"
                    style="stroke:black; stroke-width:2;"
                    visibility="visible" />

<?php
            }
            else {
?>
            <line   id="<?php echo($IDstart); ?>"
                    x1="<?php echo($i*$lineDistance+$lineX); ?>" y1="<?php echo($Ystart); ?>"
                    x2="<?php echo($i*$lineDistance+$lineX); ?>" y2="<?php echo($Ystart-$small); ?>"
                    style="stroke:black; stroke-width:2;"
                    visibility="visible" />
<?php                   
            }
            $IDstart++;
        }
    }
    

    function drawReiter(){
        global $numberOfReiter, $ankerRX, $ankerRY;

        for ($i=1; $i<=$numberOfReiter; $i++){
            $visibility = "hidden";
           // if($i==1){
           //    $visibility = "visible";
           // }
?>

        <g  id="Reiter_<?php echo($i); ?>" 
            visibility="<?php echo($visibility); ?>"
            fixedOn ="" >

            <!-- die beiden Rechtecke tiedUp und tiedDown bilden einen Bereich um den Reiter.
                 auf diesen Bereichen wird das Mausevent gestartet, welches den Reiter am Prozentband oder Lineal fixiert.
                 Mausevent wird nur gestartet, wenn nicht auf den Reiter, sondern nur außen herum geklickt wird. -->
            <rect       id="Reiter_<?php echo($i); ?>_fixedUp"
                        x="" y=""
                        height="45" width="80"
                        fill="none" stroke="black"
                        visibility="hidden"
                        style="pointer-events:all;"
                        onclick="checkFixierung(true, <?php echo($i); ?>)" />
    
            <rect       id="Reiter_<?php echo($i); ?>_fixedDown"
                        x="" y=""
                        height="45" width="80"
                        fill="none" stroke="black"
                        visibility="hidden"
                        style="pointer-events:all;"
                        onclick="checkFixierung(false, <?php echo($i); ?>)" />
            
            <polyline   id="Reiter_<?php echo($i); ?>_Polyline"         points=" "
                        fill="#AAAAAA"
                        ondblclick= "deleteReiter(<?php echo($i); ?>)"  />
            
            <!-- Input Feld oben -->
            <foreignObject id="Reiter_<?php echo($i); ?>_oben" x="" y="" width="100" height="150"
                           >
            <div xmlns="http://www.w3.org/1999/xhtml">
                
                <input  id="Reiter_<?php echo($i); ?>_Eingabe1" type="text" 
                        size="1" maxlength="6" 
                        value="" onkeypress="checkKey(event, this.value, true, <?php echo($i); ?>)"
                        ondblclick= "deleteReiter(<?php echo($i); ?>)"  />
            </div>
            </foreignObject>

            <!-- Die Eingabe oben wird in der Einheit % angegeben -->
            <rect       id="Reiter_<?php echo($i); ?>_%Rect"
                        x=""  y=""
                        height="19" width="14"
                        fill="white"
                        stroke="black"
                        style="stroke-width:0.5;"
                        ondblclick= "deleteReiter(<?php echo($i); ?>)" />

            <text       id="Reiter_<?php echo($i); ?>_%Text"
                        x="" y="" 
                        style="font-size: 12.8px;"
                        ondblclick= "deleteReiter(<?php echo($i); ?>)" > % </text>
            
            <!-- Input Feld unten -->
            <foreignObject id="Reiter_<?php echo($i); ?>_unten" x="" y="" width="100" height="150"
                           >
            <div xmlns="http://www.w3.org/1999/xhtml">

                <input  id="Reiter_<?php echo($i); ?>_Eingabe2" type="text" 
                        size="3" maxlength="6" 
                        value="" onkeypress="checkKey(event, this.value, false, <?php echo($i); ?>)"
                        ondblclick= "deleteReiter(<?php echo($i); ?>)" />
            </div>
            </foreignObject>
        </g>

            <!-- Es Werden zwei Ketten gezeichnet, die am Reiter oben und unten befestigt werden können. -->
            <g  fill="none" stroke="black" stroke-width="1.5">
                <ellipse    id="Reiter_<?php echo($i); ?>_Anker11" 
                            cx="" cy=""
                            rx="<?php echo($ankerRX); ?>"  ry="<?php echo($ankerRY); ?>"
                            visibility="hidden"
                            onclick="deFixReiter(true, <?php echo($i); ?>)" />
                <ellipse    id="Reiter_<?php echo($i); ?>_Anker12"
                            cx="" cy=""
                            rx="<?php echo($ankerRX); ?>"  ry="<?php echo($ankerRY); ?>"
                            visibility="hidden"
                            onclick="deFixReiter(true, <?php echo($i); ?>)" />

                <ellipse    id="Reiter_<?php echo($i); ?>_Anker21"
                            cx="" cy=""
                            rx="<?php echo($ankerRX); ?>"  ry="<?php echo($ankerRY); ?>"
                            visibility="hidden"
                            onclick="deFixReiter(false, <?php echo($i); ?>)" />
                <ellipse    id="Reiter_<?php echo($i); ?>_Anker22"
                            cx="" cy=""
                            rx="<?php echo($ankerRX); ?>"  ry="<?php echo($ankerRY); ?>"
                            visibility="hidden"
                            onclick="deFixReiter(false, <?php echo($i); ?>)" />
            </g>

    
 <?php
        }

    }

?>



<svg   width="1200" height="850" >

    

    <rect   id="Prozentband"
            x="<?php echo($PBx); ?>"    y="<?php echo($PBy); ?>"
            width="1030"                height="<?php echo($height); ?>"
            fill="#FF4040" />
    
<?php 
    // Zeichnet Linien für das Prozentband
    // LineList Positionen: 0-200 
    // false = nicht das Lineal
    drawLines(0, $PBline_y1, false);
?>

    <!-- Es wird ein unsichtbarer Bereich über das Prozentband+Skala gelegt, damit das Mausevent nicht durch die gezeichneten Linien gestört wird -->
    <rect   id="Prozentband-Hidden"
            x="<?php echo($PBx); ?>"    y="<?php echo($PBy); ?>"
            width="1030"                height="<?php echo($height); ?>"
            fill="blue"                 style="pointer-events:all; cursor: move;"
            visibility="hidden"
            ondblclick="createHelpline(evt, true)" />

    <rect   id="Lineal"
            x="<?php echo($PBx); ?>"    y="<?php echo($PBy+$distanceScale); ?>"
            width="1030"                height="<?php echo($height); ?>"
            fill="#FFFFFF"
            stroke="black"              stroke-width="2" />

<?php
    // Zeichnet die Linien für das Prozentband
    // LineList Positionen: 201-401 ???
    drawLines($numberOfLines+1, $Lline_y1, true);
?>

    <!-- Es wird ein unsichtbarer Bereich über das Lineal+Skala gelegt, damit das Mausevent nicht durch die gezeichneten Linien gestört wird -->
    <rect   id="Lineal-Hidden"
            x="<?php echo($PBx); ?>"    y="<?php echo($PBy+$distanceScale); ?>"
            width="1030"                height="<?php echo($height); ?>"
            fill="blue"                 style="pointer-events:all; cursor: move;"
            visibility="hidden"
            ondblclick="createHelpline(evt, false)" />

    <!-- In diesem Bereich kann durch das Mausevent 'Doppelklick' ein neuer Reiter erzeugt werden -->
    <rect   id="Reiter-Area"
            x="<?php echo($PBx); ?>"    y="<?php echo($PBy+$height); ?>"
            width="1030"                height="<?php echo($distanceScale-$height); ?>"
            fill="red"                  style="pointer-events:all;"
            visibility="hidden"
            ondblclick="checkFreeReiter(evt)"  />

    
    
<?php
    drawReiter();
?>

    <text   id="Nachricht"
            x="85"                      y="75"
            visibility="hidden"
            style="font-size:20px;" > 
            <tspan id="Z1"> </tspan>
            <tspan id="Z2" x="165" y="105"> </tspan>   
            </text>
    
    <animate xlink:href="#Nachricht"
             id = "NachrichtA"
             attributeName = "visibility"
             beging ="indefinite" dur="20s" 
             from="visible" to="hidden"
    />


    <g id = "Reset"
       style="pointer-events:all; cursor: pointer;"
       onclick="reset()">
       <rect    id="Reset-Rahmen"
                x="710"    y="695"
                width="170" height="55"
                fill="none"
                stroke="black"
                style="stroke-width:2;" />
        <rect   id="Reset-Hintergrund"
                x="720"     y="705"
                height="35" width="150"
                fill="#AAAAAA" />
        <text   id="Reset-Text"
                x="725"    y="730"
                style="font-size: 20px;" >
                Zurücksetzen </text>

    </g>


<script type="text/javascript"><![CDATA[
    
    
    var selectedElement = 0;
    var currentX=0;
    var currentMatrix=0;
    var numberOfLines = <?php echo($numberOfLines); ?>;
    var showableIntervall = document.getElementById("200").getAttribute("x1")-document.getElementById("0").getAttribute("x1"); // Welche Striche werden initial sichtbar?
    var lineX = <?php echo($lineX); ?>;
    var numberOfReiter = <?php echo($numberOfReiter); ?>;
    var reiterCounter = 0;
    var reiterArray = [<?php echo($numberOfReiter); ?>-1];
    var maxAufgaben = 4;

   
    var lineList = document.getElementsByTagName("line");   
    var textList = document.getElementsByTagName("text");
    
    // setzt alle Linien, die über das Prozentband bzw. Lineal hinausgehen auf hidden
    for(var i=0; i<lineList.length; i++){ 
        var line = lineList.item(i);
        var id = line.getAttribute("id");
        if(id>200 && id <401 || id>601){
            line.setAttribute("visibility", "hidden");
        }
    }
    // setzt alle Texte, die über das Prozentband bzw. Lineal hinausgehen auf hidden
    for (var i=0; i<textList.length; i++){
        var text = textList.item(i);
        var id = text.getAttribute("id");
        if(id>200 && id <401 || id>601){
            text.setAttribute("visibility", "hidden");
        }
    }


   
    /* Berechnet die aktuelle Distanz zwischen zwei Linien auf dem Prozentban */
    function distanceProzent (){
        var intervallP = lineList.item(numberOfLines).getAttribute("x1")-lineList.item(0).getAttribute("x1");
        var distanceP = intervallP/numberOfLines;
        return distanceP;
    }

    /* Berechnet die aktuelle Distanz zwischen zwei Linien auf dem Lineal */
    function distanceLineal (){
        var intervallL = lineList.item(2*numberOfLines+1).getAttribute("x1")-lineList.item(numberOfLines+1).getAttribute("x1");
        var distanceL = intervallL/numberOfLines;
        return distanceL;
    }
    
    /* wird bei mousedown auf das Prozentband-hidden bzw. Lineal-hidden aktiviert.
       Die Methode ruft moveElement(event) auf.*/
       
    // hier touchs abfangen... 
    function selectElement(evt){
        selectedElement = evt.target;
        currentX = evt.pageX;


        // hier: PB: true, Lineal: false
        if(Object.is(selectedElement, document.getElementById("Prozentband-Hidden"))){
            selectedElement.setAttribute("onmousemove", "moveElement(evt, true)");
        }
        else if(Object.is(selectedElement, document.getElementById("Lineal-Hidden"))){
            selectedElement.setAttribute("onmousemove", "moveElement(evt, false)"); 
        }

        selectedElement.setAttribute("onmouseup", "deselectElement(evt)");
        selectedElement.setAttribute("onmouseout", "deselectElement(evt)");

    }

    function moveElement(evt, prozentband){

        var startL = 0;
        var startT = 0;
        var secondStartL;
        var secondStartT;
        // evt.pageX: Wo wurde gecklickt? currentX: Wo ist man jetzt mit der Maus?
        var dx = evt.pageX - currentX;
        var neededline;
        var clickedLine;
        var distance;
        var lineNumber;
        var warning;

        if (prozentband == true){
                // Linien
                startL = 1;
                // Texte
                startT = 1;
                secondStartL = numberOfLines+2;
                secondStartT = numberOfLines/10+2;

                distance=distanceProzent();
                neededline = Math.round((currentX-lineX)/distance)-1;
                clickedLine = document.getElementById(neededline);
                lineNumber = neededline;

                // Für Textausgabe
                warning = "Prozentband";

            }
            else {
                startL = numberOfLines+2;
                startT = numberOfLines/10+2;
                secondStartL = 1;
                secondStartT = 1;

                distance=distanceLineal();
                neededline = Math.round((currentX-lineX)/distance)+numberOfLines;
                clickedLine = document.getElementById(neededline);
                lineNumber=neededline-numberOfLines-1;

                warning = "Lineal";
            }

        var newclickedLinePos = parseInt(clickedLine.getAttribute("x1"))+dx;
        var newIntervall = newclickedLinePos-document.getElementById("0").getAttribute("x1");
        var newDistance = newIntervall/lineNumber;
        
    /* DAS WURDE AUSKOMMENTIERT, DA BEI FIXIERUNG=DOWN UND EINGABE_OBEN SICH DAS PROZENTBAND NEU SKALIERT,
       WENN MAN EINE GRENZE EINBAUT WIE KLEIN DIE SKALA WERDEN KANN, TRETEN VIELE PROBLEME AUF DIE SONST AUF ANDERE WEISE ABGEFANGEN WERDEN MÜSSEN !!!
       WENN ES SO BLEIBEN SOLL, DANN MUSS DIE VISISBILITY DER TEXTE GGF. ANDERS GESETZT WERDEN!

        // Warnung, falls bereits alle Linien auf dem Lineal / Prozentband liegen, dass die Skala nicht weiter verkleinert werden kann
        if (newDistance <= (showableIntervall/numberOfLines)){
            setNachricht("Achtung: Du kannst das "+warning+" nicht noch kleiner ziehen.");
        }

        if(newDistance >= <?php echo($minDistance); ?>){  */
            moveLines(startL, newDistance);
            moveText(startT, newDistance);
            moveReiter(prozentband, secondStartL, secondStartT);

      //  }

        currentX = evt.pageX;
    }

    /* Ist das mouseevent auf dem Prozentband bzw. dem Lineal beendet, wird deselctElement aufgerufen. */
    function deselectElement(evt){
        if(selectedElement != 0){
            selectedElement.removeAttribute("onmousemove");
            selectedElement = 0;
        }
    }

    /* Linien werden bewegt */
    function moveLines(startL, newDistance){

        var j=1;

        for (var i=startL; i<(startL+numberOfLines); i++){
            var line = lineList.item(i);
            var pos = j*newDistance+lineX;
            
            line.setAttribute("x1", pos);
            line.setAttribute("x2", pos);

        // Setzt Linien sichtbar/nicht sichtbar, abhängig davon ob sie doch auf dem Prozentband/Lineal liegen oder nicht
            if(pos >= (<?php echo($PBx); ?>+parseInt(document.getElementById("Prozentband").getAttribute("width")))){
               line.setAttribute("visibility", "hidden");
                }
                else {
                    line.setAttribute("visibility", "visible");
                }

          // stehen die Linien zu dicht aneinander werdem alle 'small'-Linien nicht sichtbar
          if (newDistance < 4){
              if(Math.abs(line.getAttribute("y1")-line.getAttribute("y2")) == Math.abs(<?php echo($small); ?>)){
                  line.setAttribute("visibility", "hidden");
              }
          }   
        j++; 
        }
    }

    /* Texte werden bewegt*/
    function moveText(startT, newDistance){

    // jeder 10. Strich hat einen Text
        var l = 10;

        for (var i=startT; i<(startT+numberOfLines/10); i++){
            var text = textList.item(i);
            var pos = l*newDistance+lineX-1;
            text.setAttribute("x", pos);
            l+=10;
        
            // Setzt Text sichtbar/nicht sichtbar, abhängig davon, ob sie noch auf dem Prozentband/Lineal liegen oder nicht
            if(pos >= (<?php echo($PBx); ?>+parseInt(document.getElementById("Prozentband").getAttribute("width")))-20){
                text.setAttribute("visibility", "hidden");
                }
                else {
                    text.setAttribute("visibility", "visible");
                }

            // stehen die Texte zu dicht aneinander wird jeder 2. nicht sichtbar
            if(newDistance < 4){
                if ((l/10)%2 == 0){
                    text.setAttribute("visibility", "hidden");
                }
            }
        }
    }

function checkFreeReiter(evt){
    var firstFreeReiter = 0;

    if (reiterCounter >= <?php echo($numberOfReiter); ?>){

            for(var i = 0; i < reiterArray.length; i++){
                if(reiterArray[i]=="free"){
                    firstFreeReiter = i+1;
                    break;
                    // nimm Reiter an arrayposition i 
                }
            }

            if (firstFreeReiter == 0){
                setNachricht("Achtung: Du kannst nicht noch mehr Reiter hinzufügen.", " ");
            }
            else {
                reiterId = "Reiter_"+firstFreeReiter;
                reiterArray [firstFreeReiter-1] = 0;
                createReiter(evt, reiterId);

            }
            
        }
        else {
            reiterId = "Reiter_"+(reiterCounter+1);
            createReiter(evt, reiterId);
            reiterCounter +=1;
    
        }
    

}


function createReiter(evt, ReiterId){
   
        var x_pos = event.pageX;
        var distanceP = distanceProzent();
        var distanceL = distanceLineal();
        var value_oben = Math.round((x_pos-lineX)/distanceP);
        var value_unten = Math.round((x_pos-lineX)/distanceL);

        document.getElementById(ReiterId+"_Eingabe1").setAttribute("value", value_oben);
        document.getElementById(ReiterId+"_Eingabe1").value=value_oben;
        document.getElementById(ReiterId+"_Eingabe2").setAttribute("value", value_unten);
        document.getElementById(ReiterId+"_Eingabe2").value=value_unten;
        change_Reiterpos(x_pos, ReiterId);


        
        

    }

    /*  Reiter werden bewegt
        Abhängig von der Fixierung werden ggf. die Skalen neu bewegt */
    function moveReiter(prozentband, secondStartL, secondStartT){

        var secondDistance;
        var fixedOn;
        var distanceP;
        var distanceL;

        for (var i=1; i <= reiterCounter; i++){
            if (reiterArray[i-1] != "free"){ // verhindert, dass die "gelöschten" Reiter mitbewegt werden
            var reiterId = "Reiter_"+i;
            fixedOn = document.getElementById(reiterId).getAttribute("fixedOn");

            if (fixedOn == "up"){
                change_Reiterwert_oben(document.getElementById(reiterId+"_Eingabe1").getAttribute("value"), i);
            }
            else if (fixedOn == "down"){
                change_Reiterwert_unten(document.getElementById(reiterId+"_Eingabe2").getAttribute("value"), i);
            }
            else if (fixedOn == "both"){
                
                if (prozentband == true){
                    // Am Prozentband wird gezogen, Reiter wird mit Prozentband mitbewegt
                    distanceP = distanceProzent();
                    var val = document.getElementById(reiterId+"_Eingabe1").getAttribute("value");
                    var x_pos = val*distanceP+lineX;

                    if (x_pos>=(<?php echo($PBx); ?>+parseInt(document.getElementById("Prozentband").getAttribute("width")))-20){
                            setNachricht("Achtung: Mindestens ein Wert kann auf den Skalen nicht richtig angezeigt werden.",  "Du musst beide Skalen wieder verkleinern.");
                    }
                    else {
                        
                        change_Reiterpos(x_pos, reiterId);

                        // Lineal muss entsprechend des momentanen value_unten skaliert werden
                        var value_unten = document.getElementById(reiterId+"_Eingabe2").getAttribute("value");
                        secondDistance = (x_pos-lineX)/value_unten;
                        moveLines(secondStartL, secondDistance);
                        moveText(secondStartT, secondDistance);
                    }
                }
                else {
                    // Am Lineal wird gezogen, Reiter wird mit Lineal bewegt
                    distanceL = distanceLineal();
                    var val = document.getElementById(reiterId+"_Eingabe2").getAttribute("value");
                    var x_pos = val*distanceL+lineX;
                    if (x_pos>=(<?php echo($PBx); ?>+parseInt(document.getElementById("Prozentband").getAttribute("width")))-20){
                            setNachricht("Achtung: Mindestens ein Wert kann auf den Skalen nicht richtig angezeigt werden.", "Du musst beide Skalen wieder verkleinern.");
                    }
                    else {
                        change_Reiterpos(x_pos, reiterId);

                        // Prozentband muss entsprechend des momentanen value-oben skaliert werden
                        var value_oben = document.getElementById(reiterId+"_Eingabe1").getAttribute("value");
                        secondDistance = (x_pos-lineX)/value_oben;
                        moveLines(secondStartL, secondDistance);
                        moveText(secondStartT, secondDistance);
                    }

                }

            }
            else { // Reiter ist gar nicht fixiert

                var x_Spitze = document.getElementById(reiterId+"_oben").getAttribute("x")-3+30;

                distanceP = distanceProzent();
                var neededlinesP = (x_Spitze-lineX)/distanceP;
                var value_oben = Math.round(neededlinesP);
                document.getElementById(reiterId+"_Eingabe1").setAttribute("value", value_oben);
                document.getElementById(reiterId+"_Eingabe1").value=value_oben;

                var distanceL = distanceLineal();
                var neededlinesL = (x_Spitze-lineX)/distanceL;
                var value_unten = Math.round(neededlinesL);
                document.getElementById(reiterId+"_Eingabe2").setAttribute("value", value_unten);
                document.getElementById(reiterId+"_Eingabe2").value=value_unten;

            }
        }
        }
    }

    function deleteReiter(id){
        var reiterId = "Reiter_"+id;
        change_Reiterpos(-5, reiterId);
        document.getElementById(reiterId).setAttribute("fixedOn", " ");
        document.getElementById(reiterId).setAttribute("visibility", "hidden");
        reiterArray[id-1] = "free";

    }


    /* Die Eingabefelder werden erst bestätigt, wenn Enter gedrückt wurde. */
    function checkKey(event, value, up, id){
        if (event.keyCode == 46 || event.keyCode == 44){
            setNachricht("Achtung: Du kannst nur ganze Zahlen eingeben.", " ");
        }

      else if(event.keyCode == 13){

          if (isNaN(value)==true){
              setNachricht("Achtung: Du hast keine Zahl eingegeben.", " ");

          }
          else{

            if (up == true){
                change_Reiterwert_oben(Math.round(value), id);
            }
            else {
                change_Reiterwert_unten(Math.round(value), id);
            }
        }
      }
    }

    /* Überprüft, ob ein Reiter bereits ein Doppelschloss hat
       Wenn ja, liefert er true zurück. */
    function checkDoppelschloss(){
        for (var i = 1; i < numberOfReiter; i++){ 
                    var reiter = document.getElementById("Reiter_"+i);
                    if (reiter.getAttribute("fixedOn")=="both"){
                        return true;
                    }
                }
        return false;
    }


    /* Die Funktion wird aufgerufen, wenn der Wert im oberen Eingabefeld geändert wird.
       Darauf hin springt der Reiter an die richtige Position und schreibt in das untere Eingabefeld den entsprechenden Wert. */
    function change_Reiterwert_oben(val, id){
        
        var ReiterId = "Reiter_"+id;
        var x_Spitze;

        // Wenn der Reiter bereits am Lineal fixiert ist, muss das Prozentband neu skaliert werden
        if (document.getElementById(ReiterId).getAttribute("fixedOn")== "down"){
            // Wenn schon ein Doppelschlossreiter existiert, dann darf keine Skalierung mehr vorgenommen werden!
            if (checkDoppelschloss() == true) {
                x_Spitze = document.getElementById(ReiterId+"_oben").getAttribute("x")-3+30;
                change_Reiterpos(x_Spitze, ReiterId);
            }
            else {
            // zweimal das gleiche?
            document.getElementById(ReiterId+"_Eingabe1").setAttribute("value", val);
            document.getElementById(ReiterId+"_Eingabe1").value=val;

            // x-Koordinate der Spitze des Reiters
            var x_Spitze = document.getElementById(ReiterId+"_oben").getAttribute("x")-3+30;
            var distance = (x_Spitze-lineX)/val;
            moveLines(1, distance);
            moveText(1, distance);
            for (j=1; j <= reiterCounter; j++){
                var distanceP2 = distanceProzent();
                var x_Spitze2 = document.getElementById("Reiter_"+j+"_oben").getAttribute("x")-3+30;
                var neededlinesOben = (x_Spitze2-lineX)/distanceP2;
                var value_oben2 = Math.round(neededlinesOben);
                document.getElementById("Reiter_"+j+"_Eingabe1").setAttribute("value", value_oben2);
                document.getElementById("Reiter_"+j+"_Eingabe1").value=value_oben2;

                var distanceL2 = distanceLineal();
                var neededlinesUnten = (x_Spitze2-lineX)/distanceL2;
                var value_unten2 = Math.round(neededlinesUnten);
                document.getElementById("Reiter_"+j+"_Eingabe2").setAttribute("value", value_unten2);
                document.getElementById("Reiter_"+j+"_Eingabe2").value=value_unten2;

            }

        }
        }
        
        var distanceP = distanceProzent();
        var x_pos = val*distanceP+lineX;


        if (x_pos>=(<?php echo($PBx); ?>+parseInt(document.getElementById("Prozentband").getAttribute("width")))-20){
            setNachricht("Achtung: Mindestens ein Wert kann auf dem Prozentband nicht richtig angezeigt werden.", "Du musst das Prozentband erst neu skalieren.");
        }
        else {
        
        document.getElementById(ReiterId+"_Eingabe1").setAttribute("value", val);
        document.getElementById(ReiterId+"_Eingabe1").value=val;
        change_Reiterpos(x_pos, ReiterId);

        //entsprechendes Value für Reiter_unten 
        var distanceL = distanceLineal();
        var neededlines = (x_pos-lineX)/distanceL;
        var value_unten = Math.round(neededlines);
        document.getElementById(ReiterId+"_Eingabe2").setAttribute("value", value_unten);
        document.getElementById(ReiterId+"_Eingabe2").value=value_unten;

        fixReiter(true, id);
        }
    }

    /* Die Funktion wird aufgerufen, wenn der Wert im unteren Eingabefeld geändert wird.
       Darauf hin springt der Reiter an die richtige Position und schreibt in das obere Eingabefeld den entsprechenden Wert. */
    function change_Reiterwert_unten(val, id){

        var ReiterId = "Reiter_"+id;

        // Wenn der Reiter bereits am Lineal fixiert ist, muss das Prozentband neu skaliert werden
        if (document.getElementById(ReiterId).getAttribute("fixedOn")== "up"){

            if (checkDoppelschloss() == true) {
                x_Spitze = document.getElementById(ReiterId+"_unten").getAttribute("x")-3+30;
                change_Reiterpos(x_Spitze, ReiterId);
            }
            else {

            document.getElementById(ReiterId+"_Eingabe2").setAttribute("value", val);
            document.getElementById(ReiterId+"_Eingabe2").value=val;

            // x-Koordinate der Spitze des Reiters
            var x_Spitze = document.getElementById(ReiterId+"_unten").getAttribute("x")-3+30;
            var distance = (x_Spitze-lineX)/val;
            moveLines((numberOfLines+2), distance);
            moveText((numberOfLines/10+2), distance);
            // Hier value neu machen für andere Reiter
            for (j=1; j <= reiterCounter; j++){
                var distanceP2 = distanceProzent();
                var x_Spitze2 = document.getElementById("Reiter_"+j+"_oben").getAttribute("x")-3+30;
                var neededlinesOben = (x_Spitze2-lineX)/distanceP2;
                var value_oben2 = Math.round(neededlinesOben);
                document.getElementById("Reiter_"+j+"_Eingabe1").setAttribute("value", value_oben2);
                document.getElementById("Reiter_"+j+"_Eingabe1").value=value_oben2;

                var distanceL2 = distanceLineal();
                var neededlinesUnten = (x_Spitze2-lineX)/distanceL2;
                var value_unten2 = Math.round(neededlinesUnten);
                document.getElementById("Reiter_"+j+"_Eingabe2").setAttribute("value", value_unten2);
                document.getElementById("Reiter_"+j+"_Eingabe2").value=value_unten2;

            }
        }
        }

        var distanceL = distanceLineal();
        var x_pos = val*distanceL+lineX;
        
         if (x_pos>=(<?php echo($PBx); ?>+parseInt(document.getElementById("Prozentband").getAttribute("width")))-20){
            setNachricht("Achtung: Mindestens ein Wert kann auf dem Lineal nicht richtig angezeigt werden.", "Du musst das Lineal erst neu skalieren.");
        }
        else {
        document.getElementById(ReiterId+"_Eingabe2").setAttribute("value", val);
        document.getElementById(ReiterId+"_Eingabe2").value=val;
        change_Reiterpos(x_pos, ReiterId);
        
        //entsprechendes Value für Reiter_oben
        var distanceP = distanceProzent();
        var neededlines = (x_pos-lineX)/distanceP;
        var value_oben = Math.round(neededlines);
        document.getElementById(ReiterId+"_Eingabe1").setAttribute("value", value_oben);
        document.getElementById(ReiterId+"_Eingabe1").value=value_oben;

        fixReiter(false, id);
        }
    }


    /* Die Funktion wird durch change_Reiterwert_oben bzw. change_Reiterwert_unten aufgerufen.
       Da der Reiter nur auf der X-Achse verschoben werden kann, bleiben die y-Koordinaten immer gleich.
       Der Übergebene Wert val gibt an, welche x-Koordinate die obere Spitze des Reiters haben muss.
       Abhängig von den Koordinaten der oberen Spitze werden nun die restlichen Koordinaten berechnet, welche nötig sind um den Reiter zu zeichnen. */
    function change_Reiterpos(x, ReiterId) {
                 
    var y_pos=385; // fester Wert
    var x_pos=x;

    // Abhängig von dieser Position die Koordinaten der Eckpunte berechnen
    var p01x=x_pos-30;
    var p01y=y_pos+15;
    var p02x=x_pos-5;
    var p02y=y_pos+15;
    var p03x=x_pos;
    var p03y=y_pos;
    var p04x=x_pos+5;
    var p04y=y_pos+15;
    var p05x=x_pos+30;
    var p05y=y_pos+15;
    var p06x=x_pos+30;
    var p06y=y_pos+45;
    var p07x=x_pos-30;
    var p07y=y_pos+45;
    var p08x=x_pos-30;
    var p08y=y_pos+15;
    // unterer Reiter
    var p09x=x_pos-30;
    var p09y=y_pos+50;
    var p10x=x_pos+30;
    var p10y=y_pos+50;
    var p11x=x_pos+30;
    var p11y=y_pos+80;
    var p12x=x_pos+5;
    var p12y=y_pos+80;
    var p13x=x_pos;
    var p13y=y_pos+95;
    var p14x=x_pos-5;
    var p14y=y_pos+80;
    var p15x=x_pos-30;
    var p15y=y_pos+80;
    var p16x=x_pos-30;
    var p16y=y_pos+50;
    
    // Zusammenfassen der Koordinaten in die Variable points_neu. Diese ersetzt das Attribut points in der polyline und verschiebt damit den Reiter 
    var points_neu=p01x+","+p01y+" "+p02x+","+p02y+" "+p03x+","+p03y+" "+p04x+","+p04y+" "+p05x+","+p05y+" "+p06x+","+p06y+" "+p07x+","+p07y+" "+p08x+","+p08y+" "+ 
                   p09x+","+p09y+" "+p10x+","+p10y+" "+p11x+","+p11y+" "+p12x+","+p12y+" "+p13x+","+p13y+" "+p14x+","+p14y+" "+p15x+","+p15y+" "+p16x+","+p16y;
    
    document.getElementById(ReiterId+"_Polyline").setAttribute("points", points_neu);

    // Das Input-Feld verschieben: Es liegt 5 Pixel weiter rechts als p01x bzw. p09x
    document.getElementById(ReiterId+"_oben").setAttribute("x",p01x+3);
    document.getElementById(ReiterId+"_oben").setAttribute("y",p01y+5);
    document.getElementById(ReiterId+"_%Rect").setAttribute("x", p01x+43);
    document.getElementById(ReiterId+"_%Rect").setAttribute("y", p01y+6);
    document.getElementById(ReiterId+"_%Text").setAttribute("x", p01x+44);
    document.getElementById(ReiterId+"_%Text").setAttribute("y", p01y+20);
    document.getElementById(ReiterId+"_unten").setAttribute("x",p09x+3);
    document.getElementById(ReiterId+"_unten").setAttribute("y",p09y+5);

    
    document.getElementById(ReiterId).setAttribute("visibility", "visible");

    
    // die Ketten werden mitbewegt
    document.getElementById(ReiterId+"_Anker11").setAttribute("cx", p01x+5);
    document.getElementById(ReiterId+"_Anker11").setAttribute("cy", p01y-15);
    document.getElementById(ReiterId+"_Anker12").setAttribute("cx", p01x+5);
    document.getElementById(ReiterId+"_Anker12").setAttribute("cy", p01y-5);
    document.getElementById(ReiterId+"_Anker21").setAttribute("cx", p15x+5);
    document.getElementById(ReiterId+"_Anker21").setAttribute("cy", p15y+5);
    document.getElementById(ReiterId+"_Anker22").setAttribute("cx", p15x+5);
    document.getElementById(ReiterId+"_Anker22").setAttribute("cy", p15y+15);

    // die "Klick"-Rechtecke um den Reiter werden mitbewegt
    document.getElementById(ReiterId+"_fixedUp").setAttribute("x", p01x-15);
    document.getElementById(ReiterId+"_fixedUp").setAttribute("y", p01y-15);
    document.getElementById(ReiterId+"_fixedDown").setAttribute("x", p09x-15);
    document.getElementById(ReiterId+"_fixedDown").setAttribute("y", p09y);
    }


    function checkFixierung(up, id){
        var reiterId = "Reiter_"+id;
        var fixedOn = document.getElementById(reiterId).getAttribute("fixedOn");
        if (up == true){
            if (fixedOn == "up" || fixedOn == "both"){
                deFixReiter(true, id);
            }
            else {
                fixReiter(true, id);
            }
        }
        else { // up == false
            if (fixedOn == "down" || fixedOn == "both"){
                deFixReiter(false, id);
            }
            else {
                fixReiter(false, id);
            }
        }
    }


    /* Die Funktion wird aufgerufen, sobald in eines der Rechtecke (tiedUp bzw. tiedDown) geklickt wird. 
       Abhängig davon auf welches geklickt wurde wird der Reiter an der entsprechenden Skala fixiert. */
    function fixReiter(up, id){
        var ReiterId="Reiter_"+id;
        var reiter = document.getElementById(ReiterId);
        var fixedOn = reiter.getAttribute("fixedOn"); // gibt an, wo der Reiter bereits fixierd ist
        var wantedFix;    // gibt an, wo die neue Fixierung sein soll, abhängig davon wo geklickt wurde bzw. wo die Eingabe gemacht wurde
        var reverseFix; // ist jeweils die gegenüberliegende Seite
        var doubleFix;  // wird true gesetzt, wenn es bereits einen Reiter mit Doppelschloss gibt
       

        if (up == true){
            wantedFix="up";
        }
        else {
            wantedFix="down";
        }
        
        if (wantedFix == "up"){
            if (fixedOn == "down"){

                doubleFix = checkDoppelschloss();

                if (doubleFix == true){
                    // Hier Schloss auf anderer Seite löschen und Schloss auf Seite der Eingabe setzen
                    document.getElementById(ReiterId+"_Anker11").setAttribute("visibility", "visible");
                    document.getElementById(ReiterId+"_Anker12").setAttribute("visibility", "visible");
                    document.getElementById(ReiterId+"_Anker21").setAttribute("visibility", "hidden");
                    document.getElementById(ReiterId+"_Anker22").setAttribute("visibility", "hidden");
                    reiter.setAttribute("fixedOn", "up");
                }
                else {
                    // Hier Doppelschloss setzen
                    document.getElementById(ReiterId+"_Anker11").setAttribute("visibility", "visible");
                    document.getElementById(ReiterId+"_Anker12").setAttribute("visibility", "visible");
                    reiter.setAttribute("fixedOn", "both");
                }

            }
            else if (fixedOn != "both"){
                    document.getElementById(ReiterId+"_Anker11").setAttribute("visibility", "visible");
                    document.getElementById(ReiterId+"_Anker12").setAttribute("visibility", "visible");
                    reiter.setAttribute("fixedOn", "up");
            }
        }

        else { //Jetzt ist wantedFix == "down"
            if (fixedOn == "up"){
                doubleFix = checkDoppelschloss();

                if (doubleFix == true){
                    // Hier Schloss bei up löschen und Schloss bei down setzen
                    document.getElementById(ReiterId+"_Anker11").setAttribute("visibility", "hidden");
                    document.getElementById(ReiterId+"_Anker12").setAttribute("visibility", "hidden");
                    document.getElementById(ReiterId+"_Anker21").setAttribute("visibility", "visible");
                    document.getElementById(ReiterId+"_Anker22").setAttribute("visibility", "visible");
                    reiter.setAttribute("fixedOn", "down");
                }
                else {
                    // Hier Doppelschloss setzen
                    document.getElementById(ReiterId+"_Anker21").setAttribute("visibility", "visible");
                    document.getElementById(ReiterId+"_Anker22").setAttribute("visibility", "visible");
                    reiter.setAttribute("fixedOn", "both");
                }

            }
            else if (fixedOn != "both"){ 
                    document.getElementById(ReiterId+"_Anker21").setAttribute("visibility", "visible");
                    document.getElementById(ReiterId+"_Anker22").setAttribute("visibility", "visible");
                    reiter.setAttribute("fixedOn", "down");
            }
        }
    }


    function deFixReiter(up, id){
        var reiterId = "Reiter_"+id;
        var reiter = document.getElementById(reiterId);

        if(up == true){
            document.getElementById(reiterId+"_Anker11").setAttribute("visibility", "hidden");
            document.getElementById(reiterId+"_Anker12").setAttribute("visibility", "hidden");

            if (document.getElementById(reiterId+"_Anker21").getAttribute("visibility")=="visible"){
                reiter.setAttribute("fixedOn", "down");
            }
            else {
                reiter.setAttribute("fixedOn", " ");
            }
        }
        else {
            document.getElementById(reiterId+"_Anker21").setAttribute("visibility", "hidden");
            document.getElementById(reiterId+"_Anker22").setAttribute("visibility", "hidden");

            if (document.getElementById(reiterId+"_Anker11").getAttribute("visibility")=="visible"){
                reiter.setAttribute("fixedOn", "up");
            }
            else {
                reiter.setAttribute("fixedOn", " ");
            }

        }
    }


    function setNachricht(nachricht1, nachricht2){
        document.getElementById("Z1").textContent=nachricht1;
        document.getElementById("Z2").textContent=nachricht2;
        document.getElementById("NachrichtA").beginElement();
    }

     /* Setzt alle Einstellungen wieder zurück auf Anfang */
    function reset(){

       if (aufgabe <= maxAufgaben){
        window.location = "Prozentband.php?nummer="+aufgabe;
        }
        else {
            setNachricht("Du bist jetzt fertig. Vielen Dank!", " ");
        }
    }

    /* Setzt alle Einstellungen wieder zurück auf Anfang und ändert zusätzlich die Aufgabennummer */
    function nextAufgabe(){
        
        aufgabe +=1;
        reset();


    }
    


    ]]>

</script>
</svg>
</body>
</html>


