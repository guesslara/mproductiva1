<?php
    //print_r($_GET);
    //exit;
    
    $valores=$_GET["valoresGrafica"];
    $metaProductiva=$_GET["metaProductiva"];
    $valoresProductividad=$_GET["valoresProductividad"];
    $valores=explode(",",$valores);
    $valoresProductividad=explode(",",$valoresProductividad);
    $diaFinalMes=UltimoDia(date("Y"),$_GET["mes"]);
    $dias=""; $metaP=""; 
    for($i=0;$i<$diaFinalMes;$i++){
        if($dias==""){
            $dias=$i+1; $metaP=$metaProductiva;
        }else{
            $dias=$dias.",".($i+1); $metaP=$metaP.",".$metaProductiva;
        }
    }    
    $dias=explode(",",$dias);
    $metaP=explode(",",$metaP);
/* CAT:Line chart */

 /* pChart library inclusions */
 include("../../recursos/graficas2/class/pData.class.php");
 include("../../recursos/graficas2/class/pDraw.class.php");
 include("../../recursos/graficas2/class/pImage.class.php");

 /* Create and populate the pData object */
 $MyData = new pData();  
 $MyData->addPoints($valores,"Cumplimiento");
 $MyData->addPoints($valoresProductividad,"Productividad");
 $MyData->addPoints($metaP,"Meta Productiva");
 $MyData->setSerieWeight("Probe 1",2);
 $MyData->setSerieTicks("Probe 2",4);
 $MyData->setSerieTicks("Probe 3",6);
 $MyData->setAxisName(0,"Valores");
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
 //$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 //$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
 //$myPicture->drawGradientArea(0,0,800,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,799,329,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/verdana.ttf","FontSize"=>11,"R"=>255,"G"=>255,"B"=>255));
 //$myPicture->drawText(10,16,"Average recorded temperature",array("FontSize"=>11,"Align"=>TEXT_ALIGN_BOTTOMLEFT));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../../recursos/graficas2/fonts/verdana.ttf","FontSize"=>7,"R"=>0,"G"=>0,"B"=>0));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,785,298);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Enable shadow computing */
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Draw the line chart */
 $myPicture->drawLineChart();
 $myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Write the chart legend */
 $myPicture->drawLegend(590,9,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>0,"FontG"=>0,"FontB"=>0));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawLineChart.plots.png");
 
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