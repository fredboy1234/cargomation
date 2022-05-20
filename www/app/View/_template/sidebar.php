<?php

// $menuItems = [
//   ['label' => 'Dashboard', 'url' => ['/'], 'icon' => 'fas fa-tachometer-alt' , 'isActive' => '', 'badge' => ''],
//   //['label' => 'Custom Reports', 'url' => ['#'], 'icon' => 'fas fa-file-alt', 'isActive' => '', 'badge' => ''],
//   ['label' => 'Daily Exchange', 'url' => ['/exchange'], 'icon' => 'fas fa-funnel-dollar', 'isActive' => '', 'badge' => ''],
//   ['label' => 'Doc Tracker', 'url' => ['/doctracker'], 'icon' => 'fas fa-file-import', 'isActive' => '', 'badge' => ''],
//   //['label' => 'Integration Dash', 'url' => ['#'], 'icon' => 'fas fa-cogs', 'isActive' => '', 'badge' => ''],
//   ['label' => 'Transport App', 'url' => ['/transport'], 'icon' => 'fas fa-truck', 'isActive' => '', 'badge' => ''],
//   ['label' => 'Vessel Track', 'url' => ['/vessel'], 'icon' => 'fas fa-ship', 'isActive' => '', 'badge' => ''],
//   ['label' => 'Document Developer', 'url' => ['/docdeveloper'], 'icon' => 'fas fa-layer-group', 'isActive' => '', 'badge' => ''],
//   //['label' => 'Air Track', 'url' => ['#'], 'icon' => 'fas fa-plane-departure', 'isActive' => '', 'badge' => '<span class="right badge badge-danger">Coming Soon</span>'],
// ];

$menuItems = json_decode($this->menu[0]->menu, true);

function isActive($url) {
  if($url == '/' && $_GET['url'] == 'dashboard')
    echo 'active';
  if('/'.$_GET['url'] == $url)
    echo 'active';
}
?>
<style>
  .force-hide{
    display: none !important;
  }
  .img-radius{
    border: 1px solid #fff;
  }
  .m2 img{
    width: 44%;
  }
  .main-sidebar{
    z-index: 999;
  }
</style>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="/img/logo.png" alt="CargoMationLogo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
           <span class="brand-text font-weight-light"><?=APP_NAME?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <?php if (isset($this->data)): ?>
      <div class="m1 user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?=$this->image_profile?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="/profile" class="d-block"><?= $this->escapeHTML($this->data->name); ?></a>
        </div>
      </div>
      <div class="m2 main-menu-header text-center force-hide">
						<img class="img-radius img-responsive" src="<?=$this->image_profile?>" alt="User-Profile-Image">
						<div class="user-details text-center">
              <a href="/profile" class="d-block"><?= $this->escapeHTML($this->data->name); ?></a>
						</div>
					</div>
      <?php endif; ?>

      <!-- SidebarSearch Form -->
      <div class="form-inline d-none">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-fw fa-search"></i>
            </button>
          </div>
        </div>
        <div class="sidebar-search-results">
          <div class="list-group">
            <a href="#" class="list-group-item">
              <div class="search-title">
                <b class="text-light"></b>N<b class="text-light"></b>o<b class="text-light"></b> <b class="text-light"></b>e<b class="text-light"></b>l<b class="text-light"></b>e<b class="text-light"></b>m<b class="text-light"></b>e<b class="text-light"></b>n<b class="text-light"></b>t<b class="text-light"></b> <b class="text-light"></b>f<b class="text-light"></b>o<b class="text-light"></b>u<b class="text-light"></b>n<b class="text-light"></b>d<b class="text-light"></b>!<b class="text-light"></b>
              </div>
              <div class="search-path"></div>
            </a>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <?php foreach ($menuItems as $menu): ?>
            <?php if($menu['show'] == true): ?>
            <li class="nav-item">
              <a href="<?= $menu['url']; ?>" class="nav-link <?= isActive($menu['url']); ?>">
                <i class="nav-icon fas fa-<?= $menu['icon']; ?>"></i>
                <p> <?= $menu['label']; ?> 
                <?php if(!empty($menu['badge'])): ?>
                  <span class="right badge badge-<?= $menu['badge'][0]['type']; ?>"><?= $menu['badge'][0]['text']; ?></span>
                <?php endif; ?>
                <?php if(!empty($menu['treeview'])): ?>
                <i class="right fas fa-angle-left"></i>
                <?php endif; ?>
                </p>
              </a>
              <?php if(!empty($menu['treeview'])): ?>
              <ul class="nav nav-treeview">
                <?php foreach ($menu['treeview'] as $submenu): ?>
                <li class="nav-item">
                  <a href="<?= $submenu['url']; ?>" class="nav-link <?= isActive($submenu['url']); ?>">
                    <i class="nav-icon fas fa-<?= $submenu['icon']; ?>"></i>
                    <p> <?= $submenu['label']; ?> 
                    <?php if(!empty($submenu['badge'])): ?>
                      <span class="right badge badge-<?= $submenu['badge'][0]['type']; ?>"><?= $submenu['badge'][0]['text']; ?></span>
                    <?php endif; ?>
                    <?php if(!empty($submenu['treeview'])): ?>
                    <i class="right fas fa-angle-left"></i>
                    <?php endif; ?>
                    </p>
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>
            </li>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php if(false): ?>
          <li class="nav-item">
            <a href="/shipment" class="nav-link">
              <i class="nav-icon fas fa-ship"></i>
              <p>
                Doc Tracker
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/logs" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                User Logs
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/profile" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>Profile</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/logout" class="nav-link">
              <i class="nav-icon fas fa-door-open"></i>
              <p>
                Log Out
              </p>
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
