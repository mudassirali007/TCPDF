<?php


class Font
{
    public $fontName;
    public $fontStyle;
    public $fontWeight;
    public $textDecoration;
}

function xml_attribute($object, $attribute)
{
    if (isset($object[$attribute])){
        return (string)$object[$attribute];
    }
}
function getFontFromXML($decoded_xml)
{
    $fontNameArr = array();
    foreach ($decoded_xml as $i => $xmlList) {
        $fontFamilyArr = $xmlList->text?$xmlList->text:$xmlList->g->text;

        $fontName       = (xml_attribute($fontFamilyArr, 'font-family'));
        $fontStyle      = (xml_attribute($fontFamilyArr, 'font-style'));
        $fontWeight     = (xml_attribute($fontFamilyArr, 'font-weight'));
        $textDecoration = (xml_attribute($fontFamilyArr, 'text-decoration'));

        if ( ! in_array($fontName, $fontNameArr) && $fontName){

            $localFont                 = new Font();
            $localFont->fontName       = $fontName;
            $localFont->fontStyle      = $fontStyle;
            $localFont->fontWeight     = $fontWeight;
            $localFont->textDecoration = $textDecoration;

            array_push($fontNameArr, $localFont);
        }
        if(isset($xmlList->g)){
            $fontNameArr = array_merge($fontNameArr,getFontFromXML($xmlList->g));
        }

        if(isset($xmlList->text->tspan)){
                foreach ($xmlList->text->tspan as $i => $xmlList) {
                    $fontFamilyArr = $xmlList;
                    $fontName       = (xml_attribute($fontFamilyArr, 'font-family'));
                    $fontStyle      = (xml_attribute($fontFamilyArr, 'font-style'));
                    $fontWeight     = (xml_attribute($fontFamilyArr, 'font-weight'));
                    $textDecoration = (xml_attribute($fontFamilyArr, 'text-decoration'));
                    if ( ! in_array($fontName, $fontNameArr) && $fontName){

                        $localFont                 = new Font();
                        $localFont->fontName       = $fontName;
                        $localFont->fontStyle      = $fontStyle;
                        $localFont->fontWeight     = $fontWeight;
                        $localFont->textDecoration = $textDecoration;

                        array_push($fontNameArr, $localFont);
                    }
                }
        }

    }
    return array_unique($fontNameArr, SORT_REGULAR);
}
function addFontInPDF($pdf, $fontNameArr)
{
    $fontpath         = "./fonts/";
    $fontpathRealPath = realpath($fontpath) . "/";
    foreach ($fontNameArr as $localFont) {
        $fontFamily     = $localFont->fontName;
        $fontStyle      = $localFont->fontStyle;
        $fontWeight     = $localFont->fontWeight;
        $textDecoration = $localFont->textDecoration;

        if ($fontFamily != '' && strlen($fontFamily) > 0){
            $folderName = str_replace(" ", "_", $fontFamily);

            $fontFileName = str_replace(" ", "", $fontFamily);
            $fontFileExt = "Regular";
            if ($fontStyle == "italic"){
                $fontFileExt = "Italic";
            }
            if ($fontWeight == "bold"){
                $fontFileExt = "Bold";
            }
            if ($fontStyle == "italic" && $fontWeight == "bold"){
                $fontFileExt = "BoldItalic";
            }


            $fontpath  = $fontpathRealPath . $fontFileName . "-" . $fontFileExt . ".ttf";
            if (file_exists($fontpath)){
                $fontname = TCPDF_FONTS::addTTFfont($fontpath, 'TrueTypeUnicode', '', 96);
            } else {
                $fontpath  = $fontpathRealPath . $folderName . "/" . $fontFileName . "-Regular.ttf";

                if (file_exists($fontpath)){
                    $fontname = TCPDF_FONTS::addTTFfont($fontpath, 'TrueTypeUnicode', '', 96);
                }
            }


            $font_style = "";
            if ($fontStyle == "italic"){
                $font_style = "I";
            }
            if ($fontWeight == "bold"){
                $font_style .= "B";
            }
            if ($textDecoration == "underline"){
                $font_style .= "U";
            }


            if ($fontWeight == ""){
                if ((substr($fontname, -1) == 'I') AND (substr($fontname, -2, 1) == 'B')) {
                    $fontname = substr($fontname, 0, -2).'I';
                } elseif (substr($fontname, -1) == 'B') {
                    $fontname = substr($fontname, 0, -1);
                }
            }


            if (isset($fontname)){
                $pdf->SetFont($fontname, $font_style, '', '', false);
            }
        }
    }

    return $pdf;
}