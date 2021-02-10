<?php
include_once('../simple_html_dom.php');

echo file_get_html('https://www.westpac.com.au/business-banking/services/foreign-exchange-rates/')->plaintext;
?>