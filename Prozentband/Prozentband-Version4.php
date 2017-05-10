<html>
<head>
    <title> Prozentband </title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf8" />
    
    <!-- Seite als App laden - ohne Browsersteuerung, keine Ahnung ob es funktioniert -->
    <!-- android -->
    <!--
	<meta name="mobile-web-app-capable" content="yes">
	-->
	<!-- iOS -->
	<!--
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="translucent-black">
	<meta name="apple-mobile-web-app-title" content="My App">
	-->
    
    <!-- Bindet Bibliotheken zum Abfangen von Touch-Events ein -->
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="touchmouse.js"></script>
	
	<!--Unterbindet das Markieren von Text und hoffentlich das Scrollen, keine Ahnung ob Letzteres funktioniert... -->
	<style type="text/css">
		svg * {
			user-select: none;
			-o-user-select: none;
			-ms-user-select: none;
			-moz-user-select: none;
			-webkit-user-select: none;
			-webkit-overflow-scrolling: auto;
		}
	</style>
</head>
<body>

<?php
    $PBx=80; // x-Koordinate für Beginn der Skalen-Umrandung
    $PBy=290; //y-Koordinate für Beginn der Skalen-Umrandung
    $width=1030; // gibt Breite des Prozentbandes + Lineals vor
    $height=90;  // gibt Höhe des Prozentbandes + Lineals vor
    $auxHiddenHeight=90; // gibt Höhe der versteckten Bereiche oberhalb des Prozentbands und unterhalb des Lineals vor
    $distanceScale=195; // gibt an, wie groß der Abstand zwischen dem Prozentband und dem Lineal ist
    
	$distanceAuxLines=10; // gibt an, wie groß der Abstand zwischen dem Ende der Hilfslinie und der gegenüberliegenden Skala ist
    $numberOfAuxLines=3; // gibt maximale Anzahl einseitiger Hilfslinien an
    $numberOfCoupledAuxLines=3; // gibt maximale Anzahl beidseitiger Hilfslinien an
    
    $pointerRadius=20;
    $pointerHiddenHalfWidth=20;
    $pointerArrowHalfWidth=20;
    $pointerRulerArrowColor="aquamarine";
    $pointerPercentArrowColor="aquamarine";
    $pointerCoupledArrowColor="red";
    
    
    $rulerRange=400; // gibt an wieviele Linien pro skala verwendet werden
    $percentRange=400; // gibt an wieviele Linien pro skala verwendet werden
    
    $rulerHalfs=true;
    $rulerTenths=true;
    $percentHalfs=false;
    $percentTenths=false;
    
    $coupled=true;
    
    $PBline_y1=$PBy+$height; // Linien des Prozentbandes schließen mit unterem Rand des Prozentbandes ab 
    $Lline_y1=$PBy+$distanceScale; // Linien des Lineals schließen mit oberen Rand des Lineals ab.
    $lineDistance=5; // zu Beginn kann eine Distanz zwischen den linien vorgegeben werden. Davon abhängig ist wieviele linien zu Beginn auf der Skala erscheinen.
    $lineX=$PBx+5; // x-Koordinate der 0-Linien
    // small, medium, large geben die Größe einer Linie auf einer Skala vor
    $small=25;  
    $medium=30;
    $large=40;

    /*  Die Funktion malt die inneren Linien auf einer Skala
        Hierbei wird jede 10. Linie lang gezeichnet, jede 5. mittel-lang und die restlichen Linien kurz.
        Insgesammt werden die 0-Linie + 'numberOfLines'-Linien gezeichnet. Es kann also vorab eingesetllt werden, wie viele Linien es geben soll.
        $IDstart gibt den Startwert für die ID vor; so kann später jede Linie über die ID angesprochen werden
    */
    
    
    function drawLines ($Ystart, $Ruler){
        global $PBx, $percentRange, $percentHalfs, $percentTenths, $rulerRange, $rulerHalfs, $rulerTenths, $PBline_y1, $lineDistance, $lineX, $small, $medium, $large, $rulerLineNumber, $percentLineNumber;
		$skippedLines=0;
        $percent = "%";
        $textGap = -($large+5);
		$IDstart = 0;
		$IDend = $percentRange;
		$lineRange = $percentRange;
		$Halfs = $percentHalfs;
		$Tenths = $percentTenths;
		$Class = "Percent";
        if($Ruler == true){
			$IDstart = $percentRange+1;
			$IDend = $rulerRange + $percentRange;
			$lineRange = $rulerRange;
			$Halfs = $rulerHalfs;
			$Tenths = $rulerTenths;
            $small = 0-$small;
            $medium = 0-$medium; 
            $large = 0-$large;
            $percent =" ";
            $textGap=0-$textGap+7;
            $Class = "Ruler";
        }
        for($i=0; $i <= $lineRange; $i++){
            if($i==0 || $i%10 == 0){
?>
            <line   id="<?php echo($IDstart); ?>" class="<?php echo($Class); ?>Line"
                    x1="<?php echo($i*$lineDistance+$lineX); ?>" y1="<?php echo($Ystart); ?>"
                    x2="<?php echo($i*$lineDistance+$lineX); ?>" y2="<?php echo($Ystart-$large); ?>"
                    style="stroke:black; stroke-width:2;"
                    visibility="visible" />

            <text   id="<?php echo($IDstart); ?>" class="<?php echo($Class); ?>Text"
                    x="<?php echo($i*$lineDistance+$lineX-4); ?>" y="<?php echo($Ystart+$textGap); ?>"
                    style="font-size: 12.8px;"                    visibility="visible">
                    <?php echo($i.$percent); ?> </text>

            
<?php
            }
            else if ($i%5==0 && $Halfs==true){ 
?>             
            <line   id="<?php echo($IDstart); ?>" class="<?php echo($Class); ?>Line"
                    x1="<?php echo($i*$lineDistance+$lineX); ?>" y1="<?php echo($Ystart); ?>"
                    x2="<?php echo($i*$lineDistance+$lineX); ?>" y2="<?php echo($Ystart-$medium); ?>"
                    style="stroke:black; stroke-width:2;"
                    visibility="visible" />

<?php
            }
            else if ($Tenths == true){
?>
            <line   id="<?php echo($IDstart); ?>" class="<?php echo($Class); ?>Line"
                    x1="<?php echo($i*$lineDistance+$lineX); ?>" y1="<?php echo($Ystart); ?>"
                    x2="<?php echo($i*$lineDistance+$lineX); ?>" y2="<?php echo($Ystart-$small); ?>"
                    style="stroke:black; stroke-width:2;"
                    visibility="visible" />
<?php                   
            }
            else{
				$skippedLines++;
			}
            $IDstart++;
        }
        $actualLineNumber=$lineRange - $skippedLines;
        if($Ruler == true){
			$rulerLineNumber = $actualLineNumber; // Anzahl der Striche auf dem Lineal
		} else {
			$percentLineNumber = $actualLineNumber; // Anzahl der Striche auf dem Prozentband
		}
    }
    


    function drawAuxLines(){
        global	$numberOfAuxLines, $numberOfCoupledAuxLines, $PBx, $PBy,
				$height, $auxHiddenHeight, $distanceScale, $distanceAuxLines, 
				$pointerArrowHalfWidth, $pointerHiddenHalfWidth, $pointerRadius, 
				$pointerPercentArrowColor, $pointerRulerArrowColor, $pointerCoupledArrowColor;
        
		$visibility = "hidden";
        for ($i=1; $i<=$numberOfAuxLines; $i++){
?>

         <svg  id="percentAuxLineGroup_<?php echo($i); ?>" class="percentAuxLine"
			x="<?php echo($PBx+5*$i); ?>" y="<?php echo($PBy-$auxHiddenHeight); ?>"
            visibility="<?php echo($visibility); ?>" >

			<rect		id = "percentAuxHiddenArea_<?php echo($i); ?>"
						x = "0" y="0"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($auxHiddenHeight); ?>"
						visibility = "hidden"
						/>

			<line 		id = "percentAuxLine_<?php echo($i); ?>"
			            stroke="black" stroke-width="2" stroke-dasharray="5, 5"
						x1="<?php echo($pointerHiddenHalfWidth); ?>" y1="0" 
						x2="<?php echo($pointerHiddenHalfWidth); ?>" y2="<?php echo($auxHiddenHeight+$height+ ($distanceScale-$height)/2); ?>"
						/>
						
			<circle		id = "percentAuxCircle_<?php echo($i); ?>"
						cx = "<?php echo($pointerHiddenHalfWidth); ?>" cy = "<?php echo($auxHiddenHeight+$height+ ($distanceScale-$height)/2); ?>" r = "<?php echo($pointerRadius); ?>"
						fill = "<?php echo($pointerPercentArrowColor); ?>"
						/>
						
			<polygon 	points="
							<?php echo($pointerHiddenHalfWidth-$pointerRadius); ?>,<?php echo($auxHiddenHeight+$height+ ($distanceScale-$height)/2); ?>
							<?php echo($pointerHiddenHalfWidth+$pointerRadius); ?>,<?php echo($auxHiddenHeight+$height+ ($distanceScale-$height)/2); ?>
							<?php echo($pointerHiddenHalfWidth); ?>,<?php echo($auxHiddenHeight+$distanceScale-$distanceAuxLines); ?>
						" 
						fill="<?php echo($pointerPercentArrowColor); ?>"
						/>
			
        </svg>  

         <svg  id="rulerAuxLineGroup_<?php echo($i); ?>" class="rulerAuxLine"
			x="<?php echo($PBx+10*$i); ?>" y="<?php echo($PBy+$height+$distanceAuxLines); ?>"
            visibility="<?php echo($visibility); ?>" >



			<line 		id = "rulerAuxLine_<?php echo($i); ?>"
			            stroke="black" stroke-width="2" stroke-dasharray="5, 5"
						x1="3" y1="0" 
						x2="3" y2="<?php echo($distanceScale+$auxHiddenHeight-$distanceAuxLines); ?>"
						/>
        </svg>

 
 <?php
        }
        for ($i=1; $i<=$numberOfCoupledAuxLines; $i++){
 ?>
         <svg  id="coupledAuxLineGroup_<?php echo($i); ?>" class="coupledAuxLine"
			x="<?php echo($PBx+7*$i); ?>" y="<?php echo($PBy-$auxHiddenHeight); ?>"
			visibility="<?php echo($visibility); ?>" >



			<line 		id = "coupledAuxLine_<?php echo($i); ?>"
			            stroke="black" stroke-width="2" stroke-dasharray="5, 5"
						x1="3" y1="0" 
						x2="3" y2="<?php echo($height+$distanceScale+$auxHiddenHeight+$auxHiddenHeight); ?>"
						/>
        </svg>
 <?php
		}
    }

 ?>

<svg   width="1200" height="850" style="-moz-user-selection: none">

    

    <rect   id="Prozentband"
            x="<?php echo($PBx); ?>"    		y="<?php echo($PBy); ?>"
            width="<?php echo($width); ?>"		height="<?php echo($height); ?>"
            fill="#FF4040" />
    
<?php 
    // Zeichnet Linien für das Prozentband
    // LineList Positionen: 0-200 
    // false = nicht das Lineal
    drawLines($PBline_y1, false);
?>

    <!-- Es wird ein unsichtbarer Bereich über das Prozentband+Skala gelegt, damit das Mausevent nicht durch die gezeichneten Linien gestört wird -->
    <rect   id="Prozentband-Hidden"
            x="<?php echo($PBx); ?>"    		y="<?php echo($PBy); ?>"
            width="<?php echo($width); ?>"		height="<?php echo($height); ?>"
            fill="blue"                 		style="pointer-events:all; cursor: move;"
            visibility="hidden"/>
    
    <rect   id="Above-Prozentband-Hidden"
            x="<?php echo($PBx); ?>"    		y="<?php echo($PBy-$auxHiddenHeight); ?>"
            width="<?php echo($width); ?>"      height="<?php echo($auxHiddenHeight); ?>"
            fill="blue"                 		style="pointer-events:all;"
            visibility="hidden"					onclick="addAuxLine(evt, percentAuxLineList)"/>

    <rect   id="Ruler"
            x="<?php echo($PBx); ?>"    		y="<?php echo($PBy+$distanceScale); ?>"
            width="<?php echo($width); ?>"		height="<?php echo($height); ?>"
            fill="#FFFFFF"
            stroke="black"              		stroke-width="2" />

<?php
    // Zeichnet die Linien für das Prozentband
    // LineList Positionen: 201-401 ???
    drawLines($Lline_y1, true);
?>

    <!-- Es wird ein unsichtbarer Bereich über das Ruler+Skala gelegt, damit das Mausevent nicht durch die gezeichneten Linien gestört wird -->
    <rect   id="Ruler-Hidden"
            x="<?php echo($PBx); ?>"    		y="<?php echo($PBy+$distanceScale); ?>"
            width="<?php echo($width); ?>"		height="<?php echo($height); ?>"
            fill="blue"                 		style="pointer-events:all; cursor: move;"
            visibility="hidden"/>

    <rect   id="Below-Ruler-Hidden"
            x="<?php echo($PBx); ?>"    		y="<?php echo($PBy+$distanceScale+$height); ?>"
            width="<?php echo($width); ?>"      height="<?php echo($auxHiddenHeight); ?>"
            fill="blue"                 		style="pointer-events:all;"
            visibility="hidden"					onclick="addAuxLine(evt, rulerAuxLineList)"/>

    <!-- In diesem Bereich kann durch das Mausevent 'Doppelklick' ein neuer Reiter erzeugt werden -->
    <rect   id="Between-Prozentband-Ruler"
            x="<?php echo($PBx); ?>"    		y="<?php echo($PBy+$height); ?>"
            width="<?php echo($width); ?>"		height="<?php echo($distanceScale-$height); ?>"
            fill="red"                  		style="pointer-events:all;"
            visibility="hidden"					onclick="addAuxLine(evt, coupledAuxLineList)"/>

<?php
    drawAuxLines();
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
   
    var currentX=0;
    var rulerRange = <?php echo($rulerRange); ?>;
    var rulerLineNumber = <?php echo($rulerLineNumber); ?>;
    var percentLineNumber = <?php echo($percentLineNumber); ?>;
    var percentRange = <?php echo($percentRange); ?>;
    var lineX = <?php echo($lineX); ?>;
    var coupled = false;
    var numberOfAuxLines = <?php echo($numberOfAuxLines); ?>; 
    var numberOfCoupledAuxLines = <?php echo($numberOfCoupledAuxLines); ?>; 
    var pointerHiddenHalfWidth = <?php echo($pointerHiddenHalfWidth); ?>;
    

	/* Arrays, die Status (versteckt oder sichtbar) und Position (x-Position entlang der Skalen) der Hilfslinen speichern.
	Zugriff auf den Sichtbarkeitsstatus der k-ten Hilfslinie mit blaAuxLinesStatus[k][0] (mögliche Werte: "hidden" oder "visible")
	Zugriff auf die x-Position der k-ten Hilfslinie mit blaAuxLinesStatus[k][1]
	
	// Hilfslinien für das Lineal
	var rulerAuxLinesStatus = [];
	for (var i=0; i<numberOfAuxLines; i++) {
		rulerAuxLinesStatus[i] = ["hidden", "0"];
	}
	// Hilfslinien für das Prozentband
	var percentAuxLinesStatus = [];
	for (var i=0; i<numberOfAuxLines; i++) {
		percentAuxLinesStatus[i] = ["hidden", "0"];
	}
	// Gekoppelte Hilfslinien
	var coupledAuxLinesStatus = [];
	for (var i=0; i<numberOfCoupledAuxLines; i++) {
		coupledAuxLinesStatus[i] = ["hidden", "0"];
	}
	*/
    var lineList = document.getElementsByTagName("line");   
    var textList = document.getElementsByTagName("text");
    
    
    /*Hier schöne neue Arrays mit Linien und Labels für Lineal und Prozentband getrennt*/
    var rulerLineList = document.getElementsByClassName("rulerLine");
    var rulerTextList = document.getElementsByClassName("rulerText");
    var percentLineList = document.getElementsByClassName("percentLine");
    var percentTextList = document.getElementsByClassName("percentText");
    
    var percentAuxLineList = document.getElementsByClassName("percentAuxLine");
    var rulerAuxLineList = document.getElementsByClassName("rulerAuxLine");
    var coupledAuxLineList = document.getElementsByClassName("coupledAuxLine");
    
    
    // setzt alle Linien, die über das Prozentband bzw. Lineal hinausgehen auf hidden
    for(var i=0; i<lineList.length; i++){ 
        var line = lineList.item(i);
        var pos = line.getAttribute("x1");
		if(pos >= (<?php echo($PBx); ?>+parseInt(document.getElementById("Prozentband").getAttribute("width")))){
            line.setAttribute("visibility", "hidden");
        }
    }
    // setzt alle Texte, die über das Prozentband bzw. Lineal hinausgehen auf hidden
    for (var i=0; i<textList.length; i++){
        var text = textList.item(i);
        var pos = text.getAttribute("x");
		if(pos >= (<?php echo($PBx); ?>+parseInt(document.getElementById("Prozentband").getAttribute("width")))){
            text.setAttribute("visibility", "hidden");
        }
    }
		
	// Unterbindet hoffentlich das Scrollen der Webseite, keine Ahnung, ob es funktioniert...
	document.ontouchmove = function(e){ e.preventDefault(); }
    
	$(function(){
					
		var startdragruler= false;
		 $("#Ruler-Hidden").on(TouchMouseEvent.DOWN, function(e){
			currentX=e.pageX;
			startdragruler= true;
		 });

		 $("svg").on(TouchMouseEvent.UP, function(e){
			startdragruler= false;
		 });	
		
		
		
		
		var startdragprozentband= false;
		 $("#Prozentband-Hidden").on(TouchMouseEvent.DOWN, function(e){
			currentX=e.pageX;
			startdragprozentband= true;
		 });

		 $("svg").on(TouchMouseEvent.UP, function(e){
			startdragprozentband= false;
		 });
		 
		 
		  $("svg").on(TouchMouseEvent.MOVE, function(e){
			if(startdragruler){
			  moveElement(e, false);
			 }
			 if(startdragprozentband){
			  moveElement(e, true);
			}
		   });

	});

	
	
	function addAuxLine(evt, auxLineList) {
		for (var i=0; i<auxLineList.length; i++) {
			var auxline = auxLineList[i];
			if (auxline.getAttribute("visibility")=="hidden") {
				auxline.setAttribute("x", evt.pageX-pointerHiddenHalfWidth);
				auxline.setAttribute("visibility", "visible");
				if (auxLineList == coupledAuxLineList){
					coupled = true;
				}
				break;
			} else if (i==auxLineList.length-1 ) {
				console.log("Maximum number of AuxLines reached!") // Das muss noch für den Nutzer sichtbar gemacht werden!
			}
		}
	}
	
	
	

    /* Berechnet die aktuelle Distanz zwischen zwei tatsächlich gezeichneten Linien auf dem Prozentband */
    function distanceLineProzent (){
        var intervallP = lineList.item(percentLineNumber).getAttribute("x1")-lineList.item(0).getAttribute("x1");
        var distanceP = intervallP/percentLineNumber;
        return distanceP;
    }

    /* Berechnet die aktuelle Distanz zwischen zwei tatsächlich gezeichneten Linien auf dem Lineal */
    function distanceLineRuler (){
        var intervallL = lineList.item(percentLineNumber+rulerLineNumber+1).getAttribute("x1")-lineList.item(percentLineNumber+1).getAttribute("x1");
        var distanceL = intervallL/rulerLineNumber;
        return distanceL;
    }
    
    /* Berechnet die aktuelle Distanz zwischen zwei Beschriftungen auf dem Prozentband */
    function distanceTextProzent (){
        var intervallP = lineList.item(percentLineNumber).getAttribute("x1")-lineList.item(0).getAttribute("x1");
        var distanceP = intervallP/percentRange;
        return distanceP;
    }

    /* Berechnet die aktuelle Distanz zwischen zwei Beschriftungen auf dem Lineal */
    function distanceTextRuler (){
        var intervallL = lineList.item(percentLineNumber+rulerLineNumber+1).getAttribute("x1")-lineList.item(percentLineNumber+1).getAttribute("x1");
        var distanceL = intervallL/rulerRange;
        return distanceL;
    }

    function moveElement(evt, prozentband){

        var newIntervall = evt.pageX - lineX;
        var oldIntervall = currentX - lineX;
		var scalefactor = newIntervall / oldIntervall;
		if (scalefactor >1.2) {
			scalefactor = 1.2;
		} else if (scalefactor <0.8) {
			scalefactor = 0.8;
		}	
		var newLineDistanceProzent = scalefactor * distanceLineProzent();
        var newTextDistanceProzent = scalefactor * distanceTextProzent();
		var newLineDistanceRuler = scalefactor * distanceLineRuler();
        var newTextDistanceRuler = scalefactor * distanceTextRuler();         
		
		
		if (newIntervall>0) {
			if (coupled == true && newLineDistanceProzent >= 4 && newLineDistanceProzent <= (parseInt(document.getElementById("Prozentband").getAttribute("width")) - 30) && newLineDistanceRuler >= 4 && newLineDistanceRuler <= (parseInt(document.getElementById("Ruler").getAttribute("width")) - 30) ) {
					moveLines(newLineDistanceProzent, percentLineList);
					moveText(newTextDistanceProzent, percentTextList);
					moveLines(newLineDistanceRuler, rulerLineList);
					moveText(newTextDistanceRuler, rulerTextList);
			} else if (coupled == false && prozentband == true && newLineDistanceProzent >= 4 && newLineDistanceProzent <= (parseInt(document.getElementById("Prozentband").getAttribute("width")) - 30)) {
					moveLines(newLineDistanceProzent, percentLineList);
					moveText(newTextDistanceProzent, percentTextList);
					moveAuxLines(scalefactor, percentAuxLineList);
			} else if (coupled == false && prozentband == false && newLineDistanceRuler >= 4 && newLineDistanceRuler <= (parseInt(document.getElementById("Ruler").getAttribute("width")) - 30)) {
					moveLines(newLineDistanceRuler, rulerLineList);
					moveText(newTextDistanceRuler, rulerTextList);
					moveAuxLines(scalefactor, rulerAuxLineList);
			}
		}
		//console.log("scalefactor=" + scalefactor);
        currentX = evt.pageX;
    }
	
	// TODO: Mitte statt Rand des Zeigers als Referenzpunkt
	function moveAuxLines(scalefactor, auxLineList) {
		for (var i=0; i<auxLineList.length; i++) {
			var auxline = auxLineList[i];
			if (auxline.getAttribute("visibility")=="visible") {
				var oldPosition = auxline.getAttribute("x");
				console.log(oldPosition);
				var newPosition = lineX + (oldPosition-lineX)*scalefactor;
				auxline.setAttribute("x", newPosition);
			}
		}
	}

	function moveLines(newLineDistance, arrayLines){
        var j=0;

        for (var i=0; i<arrayLines.length; i++){
            var line = arrayLines.item(i);
            var pos = j*newLineDistance+lineX;
            
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
          if (newLineDistance < 4){
              if(Math.abs(line.getAttribute("y1")-line.getAttribute("y2")) == Math.abs(<?php echo($small); ?>)){
                  line.setAttribute("visibility", "hidden");
              }
          }   
        j++; 
        }
    }

    function moveText(newTextDistance, arrayText){

    // jeder 10. Strich hat einen Text
        var l = 0;

        for (var i=0; i<arrayText.length; i++){
            var text = arrayText.item(i);
            var pos = l*newTextDistance+lineX-1;
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
            if(newTextDistance < 4){
                if ((l/10)%2 == 0){
                    text.setAttribute("visibility", "hidden");
                }
            }
        }
    }


     /* Setzt alle Einstellungen wieder zurück auf Anfang */
    function reset(){
		window.location.reload(false); 
    } 


]]>

</script>
</svg>
</body>
</html>
