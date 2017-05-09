<html>
<head>
    <title> Prozentband </title
    <meta http-equiv="Content-Type" content="text/html;charset=utf8" />
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript" src="touchmouse.js"></script>
        
        <style type="text/css">
			svg * {
				user-select: none;
				-o-user-select: none;
				-ms-user-select: none;
				-moz-user-select: none;
				-webkit-user-select: none;
			}
</style>
</head>
<body>

<?php
    $PBx=80; // x-Koordinate für Beginn der Skalen-Umrandung
    $PBy=90; //y-Koordinate für Beginn der Skalen-Umrandung
    $height=90;  // hibt Höhe des Prozentbandes + Lineals vor
    $distanceScale=195; // gibt an wie groß der Abstand zwischen dem Prozentband und dem Lineal ist
    
    $rulerRange=200; // gibt an wieviele Linien pro skala verwendet werden
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
    
    


?>



<svg   width="1200" height="850" style="-moz-user-selection: none">

    

    <rect   id="Prozentband"
            x="<?php echo($PBx); ?>"    y="<?php echo($PBy); ?>"
            width="1030"                height="<?php echo($height); ?>"
            fill="#FF4040" />
    
<?php 
    // Zeichnet Linien für das Prozentband
    // LineList Positionen: 0-200 
    // false = nicht das Lineal
    drawLines($PBline_y1, false);
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
    drawLines($Lline_y1, true);
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
    var coupled = <?php echo(json_encode($coupled)); ?>;

   
    var lineList = document.getElementsByTagName("line");   
    var textList = document.getElementsByTagName("text");
    
    
    /*Hier schöne neue Arrays mit Linien und Labels für Lineal und Prozentband getrennt*/
    var rulerLineList = document.getElementsByClassName("rulerLine");
    var rulerTextList = document.getElementsByClassName("rulerText");
    var percentLineList = document.getElementsByClassName("percentLine");
    var percentTextList = document.getElementsByClassName("percentText");
    
    
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
    
		$(function(){
					
		var startdraglineal= false;
		 $("#Lineal-Hidden").on(TouchMouseEvent.DOWN, function(e){
			currentX=e.pageX;
			startdraglineal= true;
			//movelineal(e);                
		 });

		 $("svg").on(TouchMouseEvent.UP, function(e){
			startdraglineal= false;
		 });	
		
		
		
		
		var startdragprozentband= false;
		 $("#Prozentband-Hidden").on(TouchMouseEvent.DOWN, function(e){
			currentX=e.pageX;
			startdragprozentband= true;
			//moveprozentband(e);                
		 });

		 $("svg").on(TouchMouseEvent.UP, function(e){
			startdragprozentband= false;
		 });
		 
		 
		  $("svg").on(TouchMouseEvent.MOVE, function(e){
			if(startdraglineal){
			  moveElement(e, false);
			 }
			 if(startdragprozentband){
			  moveElement(e, true);
			}
		   });

});

    /* Berechnet die aktuelle Distanz zwischen zwei tatsächlich gezeichneten Linien auf dem Prozentband */
    function distanceLineProzent (){
        var intervallP = lineList.item(percentLineNumber).getAttribute("x1")-lineList.item(0).getAttribute("x1");
        var distanceP = intervallP/percentLineNumber;
        return distanceP;
    }

    /* Berechnet die aktuelle Distanz zwischen zwei tatsächlich gezeichneten Linien auf dem Lineal */
    function distanceLineLineal (){
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
    function distanceTextLineal (){
        var intervallL = lineList.item(percentLineNumber+rulerLineNumber+1).getAttribute("x1")-lineList.item(percentLineNumber+1).getAttribute("x1");
        var distanceL = intervallL/rulerRange;
        return distanceL;
    }

    function moveElement(evt, prozentband){

        var newIntervall = evt.pageX - lineX;
        var oldIntervall = currentX - lineX;
		var scalefactor = newIntervall / oldIntervall;
		var newLineDistanceProzent = scalefactor * distanceLineProzent();
        var newTextDistanceProzent = scalefactor * distanceTextProzent();
		var newLineDistanceLineal = scalefactor * distanceLineLineal();
        var newTextDistanceLineal = scalefactor * distanceTextLineal();         

		if (coupled == true) {
                moveLines(newLineDistanceProzent, percentLineList);
				moveText(newTextDistanceProzent, percentTextList);
				moveLines(newLineDistanceLineal, rulerLineList);
				moveText(newTextDistanceLineal, rulerTextList);
		} else if (prozentband == true) {
                moveLines(newLineDistanceProzent, percentLineList);
				moveText(newTextDistanceProzent, percentTextList);
		} else {
				moveLines(newLineDistanceLineal, rulerLineList);
				moveText(newTextDistanceLineal, rulerTextList);
        }

        currentX = evt.pageX;
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
