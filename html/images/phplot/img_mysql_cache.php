<?php
// include plot
include ("phplot.php");
$graph = new PHPlot;

/* options: bars (text-data), stackedbars (text-data), error bar (), thinbarline (), lines (linear-linear), pie (text-linear) [2D or 3D], linepoints (linear-linear), points (linear-linear), area (linear-linear), squared ()
	EX:
	text-data: ('label', y1, y2, y3, ...)
    text-data-single: ('label', data), for some pie charts
	text-data-pie: ()
    data-data: ('label', x, y1, y2, y3, ...)
    data-data-error: ('label', x1, y1, e1+, e2-, y2, e2+, e2-, y3, e3+, e3-, ...) */
$graph->SetDataType("linear-linear");

// specify plot: for linepoints (linear-linear), when multiple lines (same X, different Y) have to be specified, just enter the expanded array in the format (X label, same X, Y1, Y2). also, add color to the SetDataColors array OR include plot data
$data = array(
array("",1,0.002353,0.001297,0.001008),
array("",2,0.001024,0.000942,0.000879),
array("",3,0.001156,0.000957,0.000995),
array("",4,0.001208,0.00093,0.000976),
array("",5,0.001043,0.002002,0.000909),
array("",6,0.000935,0.000899,0.001023),
array("",7,0.001034,0.003052,0.000963),
array("",8,0.001339,0.005614,0.00277),
array("",9,0.001524,0.001978,0.000961),
array("",10,0.001461,0.004403,0.001075),
);

$graph->SetDataValues($data);

// specify plotting area details [out of (400, 300) pixels fixed in phplot.php and phplot_data.php]
$graph->SetImageArea(400,300);

// options: see SetDataType
$graph->SetPlotType("linepoints");
$graph->SetTitleFontSize("2");

// note: limit title length to avoid spillover
$graph->SetTitle( "MySQL Cache Test");

// maximum X and Y coordinates on the linear graph: x1,y1,x2,y2
$graph->SetPlotAreaWorld(0,0,10,0.01);
$graph->SetPlotBgColor("white");
$graph->SetPlotBorderType("none");
$graph->SetBackgroundColor("white");

// define X axis 
$graph->SetXLabel("Number");
$graph->SetHorizTickIncrement("10");
$graph->SetXGridLabelType("");
// define Y axis 
$graph->SetVertTickIncrement("0.1");
$graph->SetPrecisionY("0");
$graph->SetLightGridColor("blue");
$graph->SetYGridLabelType("Time");

// define data display color
$graph->SetDataColors(array("red","blue","green"), array("black"));

$graph->DrawGraph();
?>