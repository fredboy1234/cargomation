<?php

$file_src = "/document/fileviewer/" . $this->user_id . "/" . $this->doc_id;

?>
<div class="container">
  <div class="row">
    <div class="col-md-7">
    	<?php
    			/* Most browsers no longer support Java Applets and Plug-ins.
    			   ActiveX controls are no longer supported in any browsers.
				   The support for Shockwave Flash has also been turned off in modern browsers.
				   You may use "iframe" or any other third party plugins
    			*/ 
		  ?>
        <embed src="<?= $file_src; ?>" width="100%" height="500px">
    </div>
    <div class="col-md-5 bg-primary">
            
    </div>
  </div>
</div>

