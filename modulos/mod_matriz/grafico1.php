<?php
    //print_r($_GET);
    //exit;
    
    $valores=$_GET["valoresGrafica"];
    $valores=explode(",",$valores);
    $diaFinalMes=UltimoDia(date("Y"),$_GET["mes"]);
    $dias="";
    for($i=0;$i<$diaFinalMes;$i++){
        if($dias==""){
            $dias=$i+1;
        }else{
            $dias=$dias.",".($i+1);
        }
    }
    //echo $dias;
    $dias=explode(",",$dias);
    //exit;
/* CAT:Area Chart */

 /* pChart library inclusions */
 include("../../recursos/graficas2/class/pData.class.php");
 include("../../recursos/graficas2/class/pDraw.class.php");
 include("../../recursos/graficas2/class/pImage.class.php");

 /* Create and populate the pData object */
 $MyData = new pData();  
 $MyData->addPoints($valores,"Cumplimiento");
 //$MyData->addPoints(array(3,12,15,8,5,5),"Probe 2");
 //$MyData->addPoints(array(2,7,5,18,15,22),"Probe 3");
 $MyData->setSerieTicks("Probe 2",4);
 $MyData->setAxisName(0,"Porcentajes %");
 $MyData->addPoints($dias,"Labels");
 $MyData->setSerieDescription("Labels","Months");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage(800,330,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Draw the background */ 
 $Settings = array("R"=>255, "G"=>255, "B"=>255, "Dash"=>1, "DashR"=>255, "DashG"=>255, "DashB"=>255);
 $myPicture->drawFilledRectangle(0,0,800,330,$Settings); 

 /* Overlay with a gradient */ 
 //$Settings = array("StartR"=>0, "StartG"=>0, "StartB"=>0, "EndR"=>210, "EndG"=>210, "EndB"=>210, "Alpha"=>80);
 //$myPicture->drawGradientArea(0,0,800,230,DIRECTION_VERTICAL,$Settings); 
 
 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,799,329,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/verdana.ttf","FontSize"=>11));
 //$myPicture->drawText(150,35,"Average temperature",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/verdana.ttf","FontSize"=>8));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,790,300);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>134,"GridG"=>103,"GridB"=>103,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Write the chart legend */
 $myPicture->drawLegend(540,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the area chart */
 $myPicture->drawAreaChart();

 /* Draw a line and a plot chart on top */
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 $myPicture->drawLineChart();
 $myPicture->drawPlotChart(array("PlotBorder"=>TRUE,"PlotSize"=>3,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawAreaChart.simple.png");
 
 function UltimoDia($anho,$mes){ 
    if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
            $dias_febrero = 29; 
    } else { 
            $dias_febrero = 28; 
    } 
    if(($mes==1) || ($mes==3) || ($mes==5) || ($mes==7) || ($mes==8) || ($mes==10) || ($mes==12)){
            $dias_mes="31";
    }else if(($mes==4) ||($mes==6) ||($mes==9) ||($mes==11)){
            $dias_mes="30";
    }else if($mes==2){
            $dias_mes=$dias_febrero;
    }
    return $dias_mes;
 }
?>