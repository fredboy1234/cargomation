<?php 
$results = $this->notifications['results'];
$count = ($this->notifications['count'] != 0)?$this->notifications['count']:'No';
// calcute date
function secondsToTime($datetime) {
  $dbDate = strtotime("".$datetime."");
  $endDate = time();
  $diff = $endDate - $dbDate;
  $days = floor($diff/86400);
  $hours = floor(($diff-$days*86400)/(60 * 60));
  $min = floor(($diff-($days*86400+$hours*3600))/60);
  $second = $diff - ($days*86400+$hours*3600+$min*60);
  
   if($days > 0) return $days." Days ago";
   elseif($hours > 0) return $hours." Hours ago";
   elseif($min > 0) return $min." Minutes ago";
   else return "Just now";
}

?>
  <!-- Navbar -->
  <?php $role = (isset($this->role) ? $this->role : '') ?>
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <?php if (false) : ?>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="/" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      <?php endif; ?>
    </ul>

    <?php if (false) : ?>
      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>
    <?php endif; ?>

    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <?php if($count > 0)
           echo '<span class="badge badge-danger navbar-badge">' . $count . '</span>';
           ?>
        </a>
        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?= $count; ?> Notifications</span>
          <div class="dropdown-divider"></div>
          <?php if(isset($results) && !empty($results)) : ?>
            <?php foreach ($results as $key => $value) {
                    echo '<a href="' . $value->url_link . '" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                              <img src="/bower_components/admin-lte/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                              <div class="media-body">
                                <h3 class="dropdown-item-title">
                                Brad Diesel  
                                  <span class="float-right text-sm">
                                  <i class="fas fa-' . $value->notification_icon . '"></i></span>
                                </h3>
                                <p class="text-sm">' . $value->message . '</p>
                                <p class="text-sm text-muted text-right">
                                <i class="far fa-clock mr-1"></i> ' . secondsToTime($value->created_date) . '</p>
                              </div>
                            </div>
                            <!-- Message End -->
                          </a>
                        <div class="dropdown-divider"></div>';
                } ?>
          <?php else: ?>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> No new messages
            <span class="float-right text-muted text-sm">...</span>
          </a>
          <div class="dropdown-divider"></div>
          <?php endif; ?>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <!-- Account Dropdown Menu -->
      <?php if (isset($this->data)) : ?>
        <li class="nav-item dropdown" style="border: 1px solid #cccc; border-radius: 20px;">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user-circle"></i> Account
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="/profile" class="dropdown-item">
              <!-- Account Start -->
              <div class="media mt-2 mb-2">
                <img src="<?= $this->image_profile ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    <?= $this->escapeHTML($this->data->name); ?>
                    <span class="float-right text-sm text-danger" title="Admin Account"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">Client</p>
                  <?php
                  $plan_icon = '';
                  switch ($this->user->account_info[0]->type) {
                    case 1:
                      $plan_icon = 'chess-pawn';
                      break;
                    case 2:
                      $plan_icon = 'chess-knight';
                      break;
                    case 3:
                      $plan_icon = 'chess-queen';
                      break;
                    default:
                      $plan_icon = 'gem';
                      break;
                  }
                  ?>
                  <p class="text-sm text-muted"><i class="fas fa-<?= $plan_icon; ?> mr-1"></i> <?= $this->user->account_info[0]->plan; ?> Account</p>
                </div>
              </div>
              <!-- Account End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="/profile" class="dropdown-item dropdown-footer" style="text-align: left;">
              <i class="nav-icon fas fa-user"></i> Profile
            </a>
            <a href="/profile#theme" class="dropdown-item dropdown-footer" style="text-align: left;">
              <i class="nav-icon fas fa-tree"></i> Themes
            </a>
            <a href="/profile#settings" class="dropdown-item dropdown-footer" style="text-align: left;">
              <i class="nav-icon fas fa-cog"></i> Settings
            </a>
            <div class="dropdown-divider"></div>
            <a href="/logout" class="dropdown-item dropdown-footer" style="text-align: right;">
              <i class="nav-icon fas fa-door-open"></i> Log Out
            </a>
          </div>
        </li>
      <?php endif; ?>
    </ul>



    <?php if (false) : ?>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-comments"></i>
            <span class="badge badge-danger navbar-badge">3</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <img src="/bower_components/admin-lte/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Brad Diesel
                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">Call me whenever you can...</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <img src="/bower_components/admin-lte/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    John Pierce
                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">I got your message bro</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <img src="/bower_components/admin-lte/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Nora Silvester
                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">The subject goes here</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
          </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    <?php endif; ?>
  </nav>
  <!-- /.navbar -->