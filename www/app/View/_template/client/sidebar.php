<?php

$menuItems = [
  ['label' => 'Dashboard', 'url' => ['/'], 'icon' => 'fas fa-tachometer-alt' , 'isActive' => '', 'badge' => ''],
  ['label' => 'Doc Tracker', 'url' => ['/doctracker'], 'icon' => 'fas fa-file-import', 'isActive' => '', 'badge' => '<span class="right badge badge-danger">New</span>'],
  ['label' => 'Transport', 'url' => ['#'], 'icon' => 'fas fa-truck', 'isActive' => '', 'badge' => ''],
];

function isActive($url) {
  if($url == '/' && $_GET['url'] == 'dashboard')
    echo 'active';
  if('/'.$_GET['url'] == $url)
    echo 'active';
}
?>

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
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/bower_components/admin-lte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="/profile" class="d-block"><?= $this->escapeHTML($this->data->name); ?></a>
        </div>
      </div>
      <?php endif; ?>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <?php foreach ($menuItems as $key => $menu): ?>
          <li class="nav-item">
            <a href="<?= $menu['url'][0]; ?>" class="nav-link <?= isActive($menu['url'][0]); ?>">
              <i class="nav-icon <?= $menu['icon']; ?>"></i>
              <p> <?= $menu['label']; ?> <?= $menu['badge']; ?></p>
            </a>
          </li>
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