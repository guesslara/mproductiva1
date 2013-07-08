<?php
    //print_r($_GET);
    
    $valores=$_GET["valoresGrafica"];
    $valores=explode(",",$valores);
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
 $MyData->setAxisName(0,"Porcentajes");
 $MyData->addPoints(array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"),"Labels");
 $MyData->setSerieDescription("Labels","Months");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage(800,230,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Draw the background */ 
 $Settings = array("R"=>255, "G"=>255, "B"=>255, "Dash"=>1, "DashR"=>255, "DashG"=>255, "DashB"=>255);
 $myPicture->drawFilledRectangle(0,0,800,230,$Settings); 

 /* Overlay with a gradient */ 
 //$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 $myPicture->drawGradientArea(0,0,800,230,DIRECTION_VERTICAL,$Settings); 
 
 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,799,229,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/verdana.ttf","FontSize"=>11));
 //$myPicture->drawText(150,35,"Average temperature",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/verdana.ttf","FontSize"=>8));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,790,200);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>255,"GridG"=>255,"GridB"=>255,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
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
?>