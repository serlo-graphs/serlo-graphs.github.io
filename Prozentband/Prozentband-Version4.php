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
    
    $pointerRadius=10;
    $pointerHiddenHalfWidth=20;
    $pointerArrowHalfWidth=10;
    $pointerArrowHeight=15;
    $pointerRulerArrowColor="green";
    $pointerPercentArrowColor="blue";
    $pointerCoupledArrowColor="red";
    $auxLineColor="#AAAAAA";
    $pointerStrokeColor="black";
    $pointerStrokeWidth="2";
    
    $rulerRange=400; // gibt an wieviele Linien pro skala verwendet werden
    $percentRange=400; // gibt an wieviele Linien pro skala verwendet werden
    
    $rulerDensityCondition = 1;
    $percentDensityCondition = 50;
    
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
        global $PBx, $percentRange, $rulerRange, $percentDensityCondition, $rulerDensityCondition, $PBline_y1, $lineDistance, $lineX, $small, $medium, $large, $rulerLineNumber, $percentLineNumber;
		$skippedLines=0;
        $percent = "%";
        $textGap = -($large+5);
		$IDstart = 0;
		$IDend = $percentRange;
		$lineRange = $percentRange;
		$densityCondition = $percentDensityCondition;
		$Class = "Percent";
        if($Ruler == true){
			$IDstart = $percentRange+1;
			$IDend = $rulerRange + $percentRange;
			$lineRange = $rulerRange;
			$densityCondition = $rulerDensityCondition;
            $small = 0-$small;
            $medium = 0-$medium; 
            $large = 0-$large;
            $percent =" ";
            $textGap=0-$textGap+7;
            $Class = "Ruler";
        }
        for($i=0; $i <= $lineRange; $i++){
			if ($i%$densityCondition==0) {
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
				else if ($i%5==0){ 
	?>             
				<line   id="<?php echo($IDstart); ?>" class="<?php echo($Class); ?>Line"
						x1="<?php echo($i*$lineDistance+$lineX); ?>" y1="<?php echo($Ystart); ?>"
						x2="<?php echo($i*$lineDistance+$lineX); ?>" y2="<?php echo($Ystart-$medium); ?>"
						style="stroke:black; stroke-width:2;"
						visibility="visible" />

	<?php
				}
				else {
	?>
				<line   id="<?php echo($IDstart); ?>" class="<?php echo($Class); ?>Line"
						x1="<?php echo($i*$lineDistance+$lineX); ?>" y1="<?php echo($Ystart); ?>"
						x2="<?php echo($i*$lineDistance+$lineX); ?>" y2="<?php echo($Ystart-$small); ?>"
						style="stroke:black; stroke-width:2;"
						visibility="visible" />
	<?php                   
				}
			} else {
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
        global	$numberOfAuxLines, $numberOfCoupledAuxLines, $auxLineColor, $PBx, $PBy,
				$height, $auxHiddenHeight, $distanceScale, $distanceAuxLines, 
				$pointerArrowHalfWidth, $pointerArrowHeight, $pointerHiddenHalfWidth, $pointerRadius, 
				$pointerPercentArrowColor, $pointerRulerArrowColor, $pointerCoupledArrowColor,
				$pointerStrokeColor, $pointerStrokeWidth;
        
		$visibility = "hidden"; // Debug: Auf "visible" setzen, um alle Hilfslinien anzuzeigen
		
        for ($i=1; $i<=$numberOfAuxLines; $i++){
?>
		<!-- Hilfslinien für das Prozentband -->

         <svg  id="percentAuxLineGroup_<?php echo($i); ?>" class="percentAuxLine"
			x="<?php echo($PBx); ?>" y="<?php echo($PBy-$auxHiddenHeight); ?>"
            visibility="<?php echo($visibility); ?>" >

			<rect		id = "percentAuxHiddenArea_<?php echo($i); ?>"
						x = "0" y="0"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($auxHiddenHeight); ?>"
						visibility = "hidden"
						/>
						
			<rect		id = "percentAuxHiddenAreaCoupling_<?php echo($i); ?>"
						x = "0" y="<?php echo($auxHiddenHeight+$height); ?>"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($distanceScale-$height); ?>"
						visibility = "hidden"
						/>						

			<line 		id = "percentAuxLine_<?php echo($i); ?>"
			            stroke="<?php echo($auxLineColor); ?>" stroke-width="2" stroke-dasharray="5, 5"
						x1="<?php echo($pointerHiddenHalfWidth); ?>" y1="0" 
						x2="<?php echo($pointerHiddenHalfWidth); ?>" y2="<?php echo($auxHiddenHeight+$distanceScale-$distanceAuxLines-$pointerArrowHeight); ?>"
						/>
						
			<circle		id = "percentAuxCircle_<?php echo($i); ?>"
						cx = "<?php echo($pointerHiddenHalfWidth); ?>" cy = "<?php echo($auxHiddenHeight+$height+ ($distanceScale-$height)/2); ?>" r = "<?php echo($pointerRadius); ?>"
						fill = "<?php echo($pointerPercentArrowColor); ?>" stroke="<?php echo($pointerStrokeColor); ?>" stroke-width="<?php echo($pointerStrokeWidth); ?>"
						/>
						
			<polygon 	id = "percentAuxTriangle_<?php echo($i); ?>"
						points="
							<?php echo($pointerHiddenHalfWidth-$pointerArrowHalfWidth); ?>,<?php echo($auxHiddenHeight+$distanceScale-$distanceAuxLines-$pointerArrowHeight); ?>
							<?php echo($pointerHiddenHalfWidth+$pointerArrowHalfWidth); ?>,<?php echo($auxHiddenHeight+$distanceScale-$distanceAuxLines-$pointerArrowHeight); ?>
							<?php echo($pointerHiddenHalfWidth); ?>,<?php echo($auxHiddenHeight+$distanceScale-$distanceAuxLines); ?>
						" 
						fill="<?php echo($pointerPercentArrowColor); ?>" stroke="<?php echo($pointerStrokeColor); ?>" stroke-width="<?php echo($pointerStrokeWidth); ?>"
						/>
			
        </svg>  

		<!-- Hilfslinien für das Lineal -->
         <svg  id="rulerAuxLineGroup_<?php echo($i); ?>" class="rulerAuxLine"
			x="<?php echo($PBx); ?>" y="<?php echo($PBy+$height); ?>"
            visibility="<?php echo($visibility); ?>" >

			<rect		id = "rulerAuxHiddenArea_<?php echo($i); ?>"
						x = "0" y="<?php echo($distanceScale); ?>"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($auxHiddenHeight); ?>"
						visibility = "hidden"
						/>
						
			<rect		id = "rulerAuxHiddenAreaCoupling_<?php echo($i); ?>"
						x = "0" y="0"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($distanceScale-$height); ?>"
						visibility = "hidden"
						/>	

			<line 		id = "rulerAuxLine_<?php echo($i); ?>"
			            stroke="<?php echo($auxLineColor); ?>" stroke-width="2" stroke-dasharray="5, 5"
						x1="<?php echo($pointerHiddenHalfWidth); ?>" y1="<?php echo($distanceAuxLines+$pointerArrowHeight); ?>" 
						x2="<?php echo($pointerHiddenHalfWidth); ?>" y2="<?php echo($distanceScale+$auxHiddenHeight); ?>"
						/>
						
			<circle		id = "rulerAuxCircle_<?php echo($i); ?>"
						cx = "<?php echo($pointerHiddenHalfWidth); ?>" cy = "<?php echo(($distanceScale-$height)/2); ?>" r = "<?php echo($pointerRadius); ?>"
						fill = "<?php echo($pointerRulerArrowColor); ?>" stroke="<?php echo($pointerStrokeColor); ?>" stroke-width="<?php echo($pointerStrokeWidth); ?>"
						/>
						
			<polygon 	id = "rulerAuxTriangle_<?php echo($i); ?>"
						points="
							<?php echo($pointerHiddenHalfWidth-$pointerArrowHalfWidth); ?>,<?php echo($distanceAuxLines+$pointerArrowHeight); ?>
							<?php echo($pointerHiddenHalfWidth+$pointerArrowHalfWidth); ?>,<?php echo($distanceAuxLines+$pointerArrowHeight); ?>
							<?php echo($pointerHiddenHalfWidth); ?>,<?php echo($distanceAuxLines); ?>
						" 
						fill="<?php echo($pointerRulerArrowColor); ?>" stroke="<?php echo($pointerStrokeColor); ?>" stroke-width="<?php echo($pointerStrokeWidth); ?>"
						/>						
        </svg>

 
 <?php
        }
        for ($i=1; $i<=$numberOfCoupledAuxLines; $i++){
 ?>
		<!-- Hilfslinien für beide Skalen (gekoppelt) -->
         <svg  id="coupledAuxLineGroup_<?php echo($i); ?>" class="coupledAuxLine"
			x="<?php echo($PBx); ?>" y="<?php echo($PBy-$auxHiddenHeight); ?>"
			visibility="<?php echo($visibility); ?>" >



			<line 		id = "coupledAuxLine_<?php echo($i); ?>"
			            stroke="<?php echo($auxLineColor); ?>" stroke-width="2" stroke-dasharray="5, 5"
						x1="<?php echo($pointerHiddenHalfWidth); ?>" y1="0" 
						x2="<?php echo($pointerHiddenHalfWidth); ?>" y2="<?php echo($height+$distanceScale+$auxHiddenHeight+$auxHiddenHeight); ?>"
						/>
						
			<circle		id = "coupledAuxCircle_<?php echo($i); ?>"
						cx = "<?php echo($pointerHiddenHalfWidth); ?>" cy = "<?php echo($auxHiddenHeight+$height+ ($distanceScale-$height)/2); ?>" r = "<?php echo($pointerRadius); ?>"
						fill = "<?php echo($pointerCoupledArrowColor); ?>" stroke="<?php echo($pointerStrokeColor); ?>" stroke-width="<?php echo($pointerStrokeWidth); ?>"
						/>
						
			<polygon 	id = "coupledAuxQuadrangle_<?php echo($i); ?>"
						points="
							<?php echo($pointerHiddenHalfWidth-$pointerArrowHalfWidth); ?>,<?php echo($auxHiddenHeight+$height+	$distanceAuxLines+$pointerArrowHeight); ?>
							<?php echo($pointerHiddenHalfWidth); ?>,<?php echo($auxHiddenHeight+$distanceAuxLines+$height); ?>
							<?php echo($pointerHiddenHalfWidth+$pointerArrowHalfWidth); ?>,<?php echo($auxHiddenHeight+$height+ $distanceAuxLines+$pointerArrowHeight); ?>
						" 
						fill="<?php echo($pointerCoupledArrowColor); ?>" stroke="<?php echo($pointerStrokeColor); ?>" stroke-width="<?php echo($pointerStrokeWidth); ?>"
						/>	
						
			<polygon 	id = "coupledAuxQuadrangle_<?php echo($i); ?>"
						points="
							<?php echo($pointerHiddenHalfWidth-$pointerArrowHalfWidth); ?>,<?php echo($auxHiddenHeight+$distanceScale-$distanceAuxLines-$pointerArrowHeight); ?>
							<?php echo($pointerHiddenHalfWidth+$pointerArrowHalfWidth); ?>,<?php echo($auxHiddenHeight+$distanceScale-$distanceAuxLines-$pointerArrowHeight); ?>
							<?php echo($pointerHiddenHalfWidth); ?>,<?php echo($auxHiddenHeight+$distanceScale-$distanceAuxLines); ?>						
						" 
						fill="<?php echo($pointerCoupledArrowColor); ?>" stroke="<?php echo($pointerStrokeColor); ?>" stroke-width="<?php echo($pointerStrokeWidth); ?>"
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

	
	/* Diese Funktion fügt eine Hilfslinie hinzu (macht eine der unsichtbaren Hilfslinien sichtbar und setzt sie auf Mausposition) */
	function addAuxLine(evt, auxLineList) {
		/* Gehe durch alle Hilfslinien, bis eine versteckte gefunden wird, setze diese auf die Mausposition und mache sie sichtbar */
		for (var i=0; i<auxLineList.length; i++) {
			var auxline = auxLineList[i];
			if (auxline.getAttribute("visibility")=="hidden") {
				auxline.setAttribute("x", evt.pageX-pointerHiddenHalfWidth);
				auxline.setAttribute("visibility", "visible");
				/* Falls eine beidseitige Hilfslinie hinzugefügt wird, sollen die Skalen gekoppelt skalieren */
				if (auxLineList == coupledAuxLineList){
					coupled = true;
				}
				/* Ist eine Hilfslinie erschaffen, suche nicht weiter */
				break;
			/* Wurde keine versteckte Hilfslinie gefunden, dann sind alle Hilfslinien sichtbar und somit die maximale Anzahl erreicht currently */
			} else if ( i==auxLineList.length-1 ) {
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
		var newLineDistanceRuler = scalefactor * distanceLineRuler();		
		
		if (newIntervall>0) {
			if (coupled == true && newLineDistanceProzent >= 4 && newLineDistanceProzent <= (parseInt(document.getElementById("Prozentband").getAttribute("width")) - 30) && newLineDistanceRuler >= 4 && newLineDistanceRuler <= (parseInt(document.getElementById("Ruler").getAttribute("width")) - 30) ) {
					moveLines(newLineDistanceProzent, percentLineList);
					moveText(scalefactor, percentTextList);
					moveLines(newLineDistanceRuler, rulerLineList);
					moveText(scalefactor, rulerTextList);
					moveAuxLines(scalefactor, percentAuxLineList);
					moveAuxLines(scalefactor, rulerAuxLineList);
					moveAuxLines(scalefactor, coupledAuxLineList);
			} else if (coupled == false && prozentband == true && newLineDistanceProzent >= 4 && newLineDistanceProzent <= (parseInt(document.getElementById("Prozentband").getAttribute("width")) - 30)) {
					moveLines(newLineDistanceProzent, percentLineList);
					moveText(scalefactor, percentTextList);
					moveAuxLines(scalefactor, percentAuxLineList);
			} else if (coupled == false && prozentband == false && newLineDistanceRuler >= 4 && newLineDistanceRuler <= (parseInt(document.getElementById("Ruler").getAttribute("width")) - 30)) {
					moveLines(newLineDistanceRuler, rulerLineList);
					moveText(scalefactor, rulerTextList);
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
				// oldPosition ist die Position des linken Rands des Zeigerbereichs
				var oldPosition = auxline.getAttribute("x");
				// correctedOldPosition ist die Position der Mitte des Zeigerbereichs (=Position der Hilfslinie)
				var correctedOldPosition = +oldPosition + +pointerHiddenHalfWidth;
				// newPosition ist die neue Position der Hilfslinie
				var newPosition = lineX + (correctedOldPosition-lineX)*scalefactor;
				// correctedNewPosition ist die neue Position des linken Rands des Zeigerbereichs
				var correctedNewPosition = +newPosition - +pointerHiddenHalfWidth;
				// Übrigens: Die zusätzlichen Pluszeichen vor den Variablen konvertieren sie zu Integern, sodass die Adition entsprechend ausgeführt wird
				auxline.setAttribute("x", correctedNewPosition);
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

    function moveText(scalefactor, arrayText){
		
		// Berechne Abstand zwischen benachbarten Textlabels
		var newTextDistance = ( arrayText.item(arrayText.length-1).getAttribute("x")-arrayText.item(0).getAttribute("x") )/arrayText.length;

        for (var i=0; i<arrayText.length; i++){
            var text = arrayText.item(i);
            var oldPosition = text.getAttribute("x");
			var newPosition = lineX + (oldPosition-lineX)*scalefactor;
            text.setAttribute("x", newPosition);
        
            // Setzt Text sichtbar/nicht sichtbar, abhängig davon, ob sie noch auf dem Prozentband/Lineal liegen oder nicht
            if(newPosition >= (<?php echo($PBx); ?>+parseInt(document.getElementById("Prozentband").getAttribute("width")))-20){
                text.setAttribute("visibility", "hidden");
			} else {
                text.setAttribute("visibility", "visible");
            }

            // stehen die Texte zu dicht aneinander wird jeder 2. nicht sichtbar

            if(newTextDistance < 30){
                if (i%2 != 0){
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
