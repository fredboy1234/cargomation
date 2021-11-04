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
      <h1>File Stats</h1>
      <div class="card card-widget widget-user-2 shadow-sm">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-primary">
          <div class="widget-user-image float-left">
            <i class="far fa-file"></i>
          </div>
          <!-- /.widget-user-image -->
          <h3 class="widget-user-username">File Stats</h3>
          <h5 class="widget-user-desc">Other</h5>
        </div>
        <div class="card-footer p-0">
          <ul class="nav flex-column">
            <?php 
              foreach ($this->file_stat->files[0]->rank as $key => $value):         
            ?>
            <li class="nav-item">
              <a href="#" class="nav-link">
              <?= $key; ?> <span class="float-right badge bg-primary"><?= $value; ?></span>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

