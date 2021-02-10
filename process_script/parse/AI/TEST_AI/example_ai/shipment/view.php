<?php
    include ( '../../../PdfToText.phpclass' ) ;

    function  output ( $message )
       {
        if  ( php_sapi_name ( )  ==  'cli' )
            echo ( $message ) ;
        else
            echo ( nl2br ( $message ) ) ;
        }

    //$file =  'HBL' ;
    //$pdf  =  new PdfToText ( "$file.pdf" ) ;

    //output ($pdf->Text);
$value = $_GET['file'];
$pdf = new PdfToText ( $value) ;
output($pdf -> Text);