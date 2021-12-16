<?php

// Include the main TCPDF library (search for installation path).
require_once('./tcpdf/tcpdf.php');


// create new PDF document

$pdf = new TCPDF("P", 'px', "Letter", true, 'UTF-8', false, false);
//var_dump('$fontname');exit;
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetDisplayMode(100);
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 058', PDF_HEADER_STRING);



// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// ---------------------------------------------------------

// add a page
$pdf->AddPage();
// set font
//$fontFileName = '';
//$fontpath  = "./fonts/Amiri-" . $fontFileName . "Regular.ttf";
//
//$fontname = TCPDF_FONTS::addTTFfont($fontpath, 'TrueTypeUnicode', '', 96);
//
//$pdf->SetFont($fontname, '', 14, '', false);
//$fontpath  = "./fonts/LobsterTwo-" . $fontFileName . "Regular.ttf";
//
//$fontname = TCPDF_FONTS::addTTFfont($fontpath, 'TrueTypeUnicode', '', 96);
//
//$pdf->SetFont($fontname, '', 14, $fontpath, false);
// NOTE: Uncomment the following line to rasterize SVG image using the ImageMagick library.
//$pdf->setRasterizeVectorImages(true);


$pdf->ImageSVG($file='testsvg.svg', $x=0, $y=0);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('test.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+