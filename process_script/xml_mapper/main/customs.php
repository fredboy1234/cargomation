<?php
if(isset($GLOBALS['client_email'])){
	$user = $client_email;
	###pass custom job xml to main mapper CW_XML
			$custom_path = glob("E:/A2BFREIGHT_MANAGER/$user/CW_XML/CW_CUSTOMS/IN/*.xml");
			usort($custom_path, fn($c, $d) => filemtime($c) - filemtime($d));
			foreach($custom_path as $fname) {
				$cw_xml = "E:/A2BFREIGHT_MANAGER/$user/CW_XML/";
			if(!file_exists($cw_xml.$fname)){
			rename($fname, $cw_xml . pathinfo($fname, PATHINFO_BASENAME));
		}
	}
}
