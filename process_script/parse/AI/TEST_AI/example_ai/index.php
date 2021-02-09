<?php
	include ( '../../PdfToText.phpclass' ) ;

	function  output ( $message )
	   {
		if  ( php_sapi_name ( )  ==  'cli' )
			echo ( $message ) ;
		else
			echo ( nl2br ( $message ) ) ;
	    }

	//$file	=  'HBL' ;
	//$pdf	=  new PdfToText ( "$file.pdf" ) ;

	//output ($pdf->Text);

$pdf = new PdfToText ( 'HBL.pdf' ) ;
output($pdf -> Text);	

  






