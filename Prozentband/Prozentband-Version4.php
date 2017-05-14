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
	$canvasWidth=1200;
	$canvasHeight=850;
    $PBx=75; // x-Koordinate für Beginn der Skalen-Umrandung
    $PBy=290; //y-Koordinate für Beginn der Skalen-Umrandung
    $width=1040; // gibt Breite des Prozentbandes + Lineals vor
    $height=90;  // gibt Höhe des Prozentbandes + Lineals vor
    $auxHiddenHeight=90; // gibt Höhe der versteckten Bereiche oberhalb des Prozentbands und unterhalb des Lineals vor
    $distanceScale=195; // gibt an, wie groß der Abstand zwischen dem Prozentband und dem Lineal ist
    
	$distanceAuxLines=10; // gibt an, wie groß der Abstand zwischen dem Ende der Hilfslinie und der gegenüberliegenden Skala ist
    $maxNumberOfAuxLines=3; // gibt maximale Anzahl der Hilfslinien an
    
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
        
    $PBline_y1=$PBy+$height; // Linien des Prozentbandes schließen mit unterem Rand des Prozentbandes ab 
    $Lline_y1=$PBy+$distanceScale; // Linien des Lineals schließen mit oberen Rand des Lineals ab.
    $lineDistance=5; // zu Beginn kann eine Distanz zwischen den linien vorgegeben werden. Davon abhängig ist wieviele linien zu Beginn auf der Skala erscheinen.
    $lineX=$PBx+15; // x-Koordinate der 0-Linien
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
						text-anchor="middle"
						x="<?php echo($i*$lineDistance+$lineX); ?>" y="<?php echo($Ystart+$textGap); ?>"
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
        global	$maxNumberOfAuxLines, $auxLineColor, $PBx, $PBy,
				$height, $auxHiddenHeight, $distanceScale, $distanceAuxLines, 
				$pointerArrowHalfWidth, $pointerArrowHeight, $pointerHiddenHalfWidth, $pointerRadius, 
				$pointerPercentArrowColor, $pointerRulerArrowColor, $pointerCoupledArrowColor,
				$pointerStrokeColor, $pointerStrokeWidth;
        
		$visibility = "hidden"; // Debug: Auf "visible" setzen, um alle Hilfslinien anzuzeigen
		
        for ($i=1; $i<=$maxNumberOfAuxLines; $i++){
?>
		<!-- Hilfslinien für das Prozentband -->

         <svg  id="percentAuxLineGroup_<?php echo($i); ?>" class="percentAuxLine"
			x="<?php echo($PBx); ?>" y="<?php echo($PBy-$auxHiddenHeight); ?>"
            visibility="<?php echo($visibility); ?>" >
					

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

			<rect		id = "percentAuxHiddenArea_<?php echo($i); ?>"
						x = "0" y="0"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($auxHiddenHeight); ?>"
						fill = "white" fill-opacity="0.0" onclick="removeAuxLine(this)"
						/>
						
			<rect		id = "percentAuxHiddenAreaCoupling_<?php echo($i); ?>"
						x = "0" y="<?php echo($auxHiddenHeight+$height); ?>"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($distanceScale-$height-1); ?>"
						fill = "white" fill-opacity="0.0" onclick="coupleAuxLine(this)"
						/>				
        </svg>  

		<!-- Hilfslinien für das Lineal -->
         <svg  id="rulerAuxLineGroup_<?php echo($i); ?>" class="rulerAuxLine"
			x="<?php echo($PBx); ?>" y="<?php echo($PBy+$height); ?>"
            visibility="<?php echo($visibility); ?>" >

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
							
			<rect		id = "rulerAuxHiddenArea_<?php echo($i); ?>"
						x = "0" y="<?php echo($distanceScale+1); ?>"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($auxHiddenHeight-1); ?>"
						fill="white" fill-opacity="0.0" onclick="removeAuxLine(this)"
						/>
						
			<rect		id = "rulerAuxHiddenAreaCoupling_<?php echo($i); ?>"
						x = "0" y="0"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($distanceScale-$height-1); ?>"
						fill="white" fill-opacity="0.0" onclick="coupleAuxLine(this)"
						/>				
        </svg>

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
			<rect		id = "coupledAuxHiddenArea_<?php echo($i); ?>"
						x = "0" y="0"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($auxHiddenHeight); ?>"
						fill = "white" fill-opacity="0.0" onclick="decoupleAuxLine(this, 'percent')"
						/>
						
			<rect		id = "coupledAuxHiddenAreaCoupling_<?php echo($i); ?>"
						x = "0" y="<?php echo($auxHiddenHeight+$height); ?>"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($distanceScale-$height-1); ?>"
						fill = "white" fill-opacity="0.0" onclick="removeAuxLine(this)"
						/>		

			<rect		id = "coupledAuxHiddenArea_<?php echo($i); ?>"
						x = "0" y="<?php echo($auxHiddenHeight+$height+$distanceScale+1); ?>"
						width = "<?php echo(2*$pointerHiddenHalfWidth); ?>"
						height = "<?php echo($auxHiddenHeight-1); ?>"
						fill="white" fill-opacity="0.0" onclick="decoupleAuxLine(this, 'ruler')"
						/>
											
        </svg>
 <?php
		}
    }

 ?>

<svg   width="<?php echo($canvasWidth); ?>" height="<?php echo($canvasHeight); ?>" style="-moz-user-selection: none">

    

    <rect   id="Prozentband"
            x="<?php echo($PBx); ?>"    		y="<?php echo($PBy); ?>"
            width="<?php echo($width); ?>"		height="<?php echo($height); ?>"
            fill="#FF4040" />
    
<?php 
    // Zeichnet Linien für das Prozentband
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
    var maxNumberOfAuxLines = <?php echo($maxNumberOfAuxLines); ?>; 
    var pointerHiddenHalfWidth = <?php echo($pointerHiddenHalfWidth); ?>;
    var numberOfAuxLines = 0;
    var numberOfCoupledAuxLines = 0;

    /*Hier schöne neue Arrays mit Linien und Labels für Lineal und Prozentband getrennt*/
    var rulerLineList = document.getElementsByClassName("rulerLine");
    var rulerTextList = document.getElementsByClassName("rulerText");
    var percentLineList = document.getElementsByClassName("percentLine");
    var percentTextList = document.getElementsByClassName("percentText");
    
    var percentAuxLineList = document.getElementsByClassName("percentAuxLine");
    var rulerAuxLineList = document.getElementsByClassName("rulerAuxLine");
    var coupledAuxLineList = document.getElementsByClassName("coupledAuxLine");
    
		
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
		if (numberOfAuxLines < maxNumberOfAuxLines) {
			/* Gehe durch alle Hilfslinien, bis eine versteckte gefunden wird, setze diese auf die Mausposition und mache sie sichtbar */
			for (var i=0; i<auxLineList.length; i++) {
				var auxline = auxLineList[i];
				if (auxline.getAttribute("visibility")=="hidden") {
					auxline.setAttribute("x", evt.pageX-pointerHiddenHalfWidth);
					auxline.setAttribute("visibility", "visible");
					numberOfAuxLines++;
					if (auxLineList == coupledAuxLineList){
						numberOfCoupledAuxLines++;
					}
					/* Ist eine Hilfslinie erschaffen, suche nicht weiter */
					break;
				/* Wurde keine versteckte Hilfslinie gefunden, dann sind alle Hilfslinien sichtbar und somit die maximale Anzahl erreicht currently */
				} 
			}
		} else {
			console.log("Maximum number of AuxLines reached!") // Das muss noch für den Nutzer sichtbar gemacht werden!
		}
	}

	/* Diese Funktion fügt eine Hilfslinie hinzu (macht eine der unsichtbaren Hilfslinien sichtbar und setzt sie auf Mausposition) */
	function removeAuxLine(auxLineHiddenArea) {
		auxLineHiddenArea.parentNode.setAttribute("visibility", "hidden");
		if (auxLineHiddenArea.parentNode.className.baseVal == "coupledAuxLine") {
				numberOfCoupledAuxLines--;
		}
		numberOfAuxLines--;
	}
	
	function coupleAuxLine(auxLineHiddenArea) {
		auxLineHiddenArea.parentNode.setAttribute("visibility", "hidden");
		var position = auxLineHiddenArea.parentNode.getAttribute("x");
		for (var i=0; i<coupledAuxLineList.length; i++) {
			var auxline = coupledAuxLineList[i];
			if (auxline.getAttribute("visibility")=="hidden") {
				auxline.setAttribute("x", position);
				auxline.setAttribute("visibility", "visible");
				/* Ist eine Hilfslinie erschaffen, suche nicht weiter */
				break;
			/* Wurde keine versteckte Hilfslinie gefunden, dann sind alle Hilfslinien sichtbar und somit die maximale Anzahl erreicht currently */
			} 
		}
		numberOfCoupledAuxLines++;
	}
	
	/* Diese Funktion verwandelt eine gekoppelte Hilfslinie in eine einfache Hilfslinie für die Skala targetScale(=ruler oder =percent) */
	function decoupleAuxLine(auxLineHiddenArea, targetScale) {
		auxLineHiddenArea.parentNode.setAttribute("visibility", "hidden");
		var position = auxLineHiddenArea.parentNode.getAttribute("x");
		if (targetScale == "ruler") {
			var auxLineList = rulerAuxLineList;
		} else if (targetScale == "percent") {
			var auxLineList = percentAuxLineList;
		} else {
			console.log("Die Funktion decoupleAuxLine wurde mit unerwarteten Argumenten aufgerufen!")
		}
		for (var i=0; i<auxLineList.length; i++) {
			var auxline = auxLineList[i];
			if (auxline.getAttribute("visibility")=="hidden") {
				auxline.setAttribute("x", position);
				auxline.setAttribute("visibility", "visible");
				/* Ist eine Hilfslinie erschaffen, suche nicht weiter */
				break;
			/* Wurde keine versteckte Hilfslinie gefunden, dann sind alle Hilfslinien sichtbar und somit die maximale Anzahl erreicht currently */
			} 
		}
		numberOfCoupledAuxLines--;				
	}
	
	/* Diese Funktion wird aufgerufen, wenn an einer der Skalen mit der Maus gezogen wird. */
    function moveElement(evt, prozentband){

        var newIntervall = evt.pageX - lineX;
        var oldIntervall = currentX - lineX;
		var scalefactor = newIntervall / oldIntervall;
		if (scalefactor >1.2) {
			scalefactor = 1.2;
		} else if (scalefactor <0.8) {
			scalefactor = 0.8;
		}	
		var newLineDistanceProzent = scalefactor * ( percentLineList.item(percentLineList.length-1).getAttribute("x1")-percentLineList.item(0).getAttribute("x1") )/percentLineList.length;
		var newTextDistanceProzent = scalefactor * ( percentTextList.item(percentTextList.length-1).getAttribute("x")-percentTextList.item(0).getAttribute("x") )/percentTextList.length;        
		var newLineDistanceRuler = scalefactor * ( rulerLineList.item(rulerLineList.length-1).getAttribute("x1")-rulerLineList.item(0).getAttribute("x1") )/rulerLineList.length;
 		var newTextDistanceRuler = scalefactor * ( rulerTextList.item(rulerTextList.length-1).getAttribute("x")-rulerTextList.item(0).getAttribute("x") )/rulerTextList.length;
		
		if (newIntervall>0) {
			if (numberOfCoupledAuxLines > 0 && newLineDistanceProzent >= 4 && newLineDistanceProzent <= (parseInt(document.getElementById("Prozentband").getAttribute("width")) - 30) && newLineDistanceRuler >= 4 && newLineDistanceRuler <= (parseInt(document.getElementById("Ruler").getAttribute("width")) - 30) ) {
					moveLines(scalefactor, percentLineList);
					moveText(scalefactor, percentTextList);
					moveLines(scalefactor, rulerLineList);
					moveText(scalefactor, rulerTextList);
					moveAuxLines(scalefactor, percentAuxLineList);
					moveAuxLines(scalefactor, rulerAuxLineList);
					moveAuxLines(scalefactor, coupledAuxLineList);
			} else if (numberOfCoupledAuxLines == 0 && prozentband == true && newTextDistanceProzent >= 15 && newLineDistanceProzent <= (parseInt(document.getElementById("Prozentband").getAttribute("width")) - 30)) {
					moveLines(scalefactor, percentLineList);
					moveText(scalefactor, percentTextList);
					moveAuxLines(scalefactor, percentAuxLineList);
			} else if (numberOfCoupledAuxLines == 0 && prozentband == false && newTextDistanceRuler >= 15 && newLineDistanceRuler <= (parseInt(document.getElementById("Ruler").getAttribute("width")) - 30)) {
					moveLines(scalefactor, rulerLineList);
					moveText(scalefactor, rulerTextList);
					moveAuxLines(scalefactor, rulerAuxLineList);
			}
		}
		//console.log("scalefactor=" + scalefactor);
        currentX = evt.pageX;
    }
	
	/* Funktion zum Bewegen der Hilfslinien */
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
	
	/* Funktion zum Bewegen der Skalenstriche */
	function moveLines(scalefactor, arrayLines){
		var newLineDistance = ( arrayLines.item(arrayLines.length-1).getAttribute("x1")-arrayLines.item(0).getAttribute("x1") )/arrayLines.length;
        for (var i=0; i<arrayLines.length; i++){
            var line = arrayLines.item(i);
            var oldPosition = line.getAttribute("x1");
			var newPosition = lineX + (oldPosition-lineX)*scalefactor;
            line.setAttribute("x1", newPosition);
            line.setAttribute("x2", newPosition);
			// stehen die Linien zu dicht aneinander werdem alle 'small'-Linien nicht sichtbar
			if(Math.abs(line.getAttribute("y1")-line.getAttribute("y2")) == Math.abs(<?php echo($small); ?>)){
				if (newLineDistance < 4){
					line.setAttribute("visibility", "hidden");
				} else {
					line.setAttribute("visibility", "visible");	
				}   
			}
		}
	}

	/* Funktion zum Bewegen der Skalenlabels */
    function moveText(scalefactor, arrayText){
		
		// Berechne Abstand zwischen benachbarten Textlabels
		var newTextDistance = ( arrayText.item(arrayText.length-1).getAttribute("x")-arrayText.item(0).getAttribute("x") )/arrayText.length;

        for (var i=0; i<arrayText.length; i++){
            var text = arrayText.item(i);
            var oldPosition = text.getAttribute("x");
			var newPosition = lineX + (oldPosition-lineX)*scalefactor;
            text.setAttribute("x", newPosition);
            // stehen die Texte zu dicht aneinander wird jeder 2. nicht sichtbar
            if (i%2 != 0){
				if(newTextDistance < 30){
                    text.setAttribute("visibility", "hidden");
                } else {
					text.setAttribute("visibility", "visible");	
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
<!-- Versteckt alles, was hinter dem rechten Ende der Skalen ist, indem ein weißes Rechteck passender Größe 
	in den Vordergrund gelegt wird (ggf. Füllfarbe an Hintergrundfarbe anpassen)-->
    <rect   id="Hide"
            x="<?php echo($PBx+$width); ?>"    						y="0"
            width="<?php echo($canvasWidth-$width-$PBx); ?>"		height="<?php echo($canvasHeight); ?>"
            fill="white" />
</svg>
</body>
</html>
