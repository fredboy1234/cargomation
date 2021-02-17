<?php 
  $menuItems = [
    ['label' => 'Custom Reports', 'url' => ['#'], 'icon' => 'fas fa-file-alt', 'isActive' => '', 'badge' => ''],
    ['label' => 'Daily Exchange', 'url' => ['#'], 'icon' => 'fas fa-funnel-dollar', 'isActive' => '', 'badge' => ''],
    ['label' => 'Dashboard', 'url' => ['/'], 'icon' => 'fas fa-tachometer-alt' , 'isActive' => '', 'badge' => ''],
    ['label' => 'Doc Tracker', 'url' => ['/doctracker'], 'icon' => 'fas fa-file-import', 'isActive' => '', 'badge' => '<span class="right badge badge-danger">New</span>'],
    ['label' => 'Integration Dash', 'url' => ['#'], 'icon' => 'fas fa-cogs', 'isActive' => '', 'badge' => ''],
    ['label' => 'Transport', 'url' => ['/transport'], 'icon' => 'fas fa-truck', 'isActive' => '', 'badge' => ''],
    ['label' => 'Transport Dash', 'url' => ['#'], 'icon' => 'fas fa-truck-loading', 'isActive' => '', 'badge' => ''],
    ['label' => 'Vessel Track', 'url' => ['#'], 'icon' => 'fas fa-ship', 'isActive' => '', 'badge' => ''],
  ];
?>
<style>
  .force-hide{
    display: none !important;
  }
  .img-radius{
    border: 1px solid #fff;
  }
</style>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="/bower_components/admin-lte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">A2B Freight Hub</span>
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

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
          <?php
              $menu_1 = '<i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard
                        </p>';
            ?>
            <?php echo $this->anchor('./', $menu_1, array('class' => 'nav-link active')); ?>
          </li>
          <li class="nav-item">
            <a href="/doctracker" class="nav-link">
              <i class="nav-icon fas fa-file-import"></i>
              <p>
                Doc Tracker
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                Transport
                <span class="right badge badge-primary">Coming soon...</span>
              </p>
            </a>
          </li>
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