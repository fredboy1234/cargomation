<style>

.map {
  height: 100%;
  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
}

.animated-icon {
  background: #002147;
  border-radius: 10px;
  height: 10px;
  position: relative;
  width: 10px;
}

.animated-icon::before {
    animation: blink 1s infinite ease-out;
    border-radius: 60px;
    box-shadow: inset 0 0 0 1px #002147;
    content: "";
    height: 10px;
    left: 50%;
    opacity: 1;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 10px;
}


@keyframes blink {
  100% {
    height: 30px;
    opacity: 0;
    width: 30px;
  }
}
.animated-icon{
  width: 20px;
  height: 20px;
  /* background-color: rgb(224 52 14 / 50%); */
  border-radius: 50%;
  box-shadow: 0px 0px 4px white;
  transition: all 1s;
}
.seacol{
  background-color: rgba(52, 255, 93, 0.5);
  height: 37px !important;
  width: 37px !important;
}
.aircol{
  background-color: rgba(60, 150, 247, 0.5);
}
#sidebardash{
  height: 250px;
   background: unset;
  border: none;
}
#sidebardash ul,#sidebardash ul li{
  background: #130f0f3b !important;
  color: #ffffff;
}
.leaflet-sidebar.right.visible {
    width: 250px;
}
.tablue{
  border: 1px solid;
}
</style>
<?php $mode = $this->container_mode;?>
  <!-- Main content -->
    <section class="content" style="padding-bottom: 5em;">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <h3 class="text-dark"><?php echo $this->total_shipment?></h3>

                <p>Total Shipments</p>
              </div>
              <div class="icon">
                <i class="ion ion-cube text-dark"></i>
              </div>
              <a href="/doctracker" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <h3 class="text-success"><?=$this->sea_shipment?></h3>

                <p>Sea Shipments</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-boat text-success"></i>
              </div>
              <a href="/doctracker?transport_mode=sea" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <h3 class="text-primary"><?=$this->count_air?></h3>

                <p>Air Shipments</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-plane text-primary"></i>
              </div>
              <a href="/doctracker?transport_mode=air" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <h3 class="text-danger"><?=$this->not_arrived?><sup style="font-size: 20px"></sup></h3>

                <p>Shipments Pending Arrival</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-warning text-danger"></i>
              </div>
              <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-4 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->

            <?php if(true): ?>
            <!-- File -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-file-alt mr-1"></i>
                  Document Stats
                </h3>
                <div class="card-tools">
                  <!-- Buttons, labels, and many other things can be placed here! -->
                  <!-- Here is a label for example -->
                  <span class="badge badge-primary d-none">New</span>
                  <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <!-- Info Boxes Style 2 -->
                  <div class="info-box mb-3 bg-light">
                    <span class="info-box-icon text-success"><i class="far fa-thumbs-up"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Total Uploaded Files</span>
                      <span class="info-box-number"><?= $this->document_stats['total_files'][0]->count; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                  <div class="info-box mb-3 bg-light">
                    <span class="info-box-icon text-danger"><i class="fas fa-exclamation-triangle"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Document for Approval</span>
                      <span class="info-box-number"><?= $this->document_stats['pending_files'][0]->count; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                  <div class="info-box mb-3 bg-light">
                    <span class="info-box-icon text-info"><i class="far fa-check-square"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Requested Documents</span>
                      <span class="info-box-number"><?= $this->document_stats['new_request'][0]->count; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                  <div class="info-box mb-3 bg-light">
                    <span class="info-box-icon text-warning"><i class="far fa-edit"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Document for Update</span>
                      <span class="info-box-number"><?= $this->document_stats['update_request'][0]->count; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                * Notes:
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
            <?php endif; ?>
      
      <?php if(false): ?>
            <!-- Calendar -->
            <div class="card bg-gradient-white">
              <div class="card-header border-0">

                <h3 class="card-title">
                  <i class="far fa-calendar-alt"></i>
                  Calendar
                </h3>
                <!-- tools card -->
                <div class="card-tools">
                  <!-- button with a dropdown -->
                  <div class="btn-group d-none">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                    <i class="fas fa-bars"></i></button>
                    <div class="dropdown-menu" role="menu">
                      <a href="#" class="dropdown-item">Add new event</a>
                      <a href="#" class="dropdown-item">Clear events</a>
                      <div class="dropdown-divider"></div>
                      <a href="#" class="dropdown-item">View calendar</a>
                    </div>
                  </div>
                  <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-primary btn-sm d-none" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body pt-0">
                <!--The calendar -->
                <div id="calendar" style="width: 100%"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card --> 
            <?php endif; ?>

            <?php if($this->role->id == 1): ?>
            <!-- File -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-list mr-1"></i>
                  User Log
                </h3>
                <div class="card-tools">
                  <!-- Buttons, labels, and many other things can be placed here! -->
                  <!-- Here is a label for example -->
                  <span class="badge badge-primary d-none">New</span>
                  <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 729px;">
                <table class="table table-head-fixed table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Role</th>
                      <th>IP Address</th>
                      <th>Action</th>
                      <th>Duration</th>
                    </tr>
                  </thead>
                  <?php foreach ($this->user_log as $key => $value) { ?>
                  <tr>
                    <td><?php echo $value->id; ?></td>
                    <td><?php echo $value->first_name . " " . $value->last_name; ?></td>
                    <td><?php switch ($value->role) {
                                case 1:
                                  $role = "Admin";
                                break;
                                case 2:
                                    $role = "Client";
                                  break;
                                case 3:
                                    $role = "Staff";
                                  break;
                                case 4:
                                    $role = "Customer";
                                  break;
                                
                                default:
                                  # code...
                                  break;
                              } 
                          echo "<b>" . $role . "</b>" ;
                        ?>
                    </td>
                    <td><?php echo $value->ip_address; ?></td>
                    <td><?php echo $value->action; ?></td>
                    <td>
                      <?php 
                        $duration = "";
                        $total_seconds = $value->duration;
                        if($total_seconds != NULL) {
                          $hours = intval($total_seconds / 3600);
                          $minutes = intval(($total_seconds % 3600) / 60);
                          $seconds = intval($total_seconds % 60);
                          
                          if($hours >= 1) {
                            $duration .= $hours . " hours ";
                          }

                          if($minutes >= 1) {
                            $duration .= $minutes . " minutes ";
                          }

                          if($seconds >= 1) {
                            $duration .= $seconds . " seconds ";
                          }


                        } else {
                          $duration .= "-";
                        }


                        echo $duration;
                      ?>
                    </td>
                  </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                * Notes:
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
            <?php endif; ?>

            <?php if(false): ?>
            <!-- DIRECT CHAT -->
            <div class="card direct-chat direct-chat-primary">
              <div class="card-header">
                <h3 class="card-title">Direct Chat</h3>

                <div class="card-tools">
                  <span data-toggle="tooltip" title="3 New Messages" class="badge badge-primary">3</span>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts"
                          data-widget="chat-pane-toggle">
                    <i class="fas fa-comments"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                  <!-- Message. Default to the left -->
                  <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left">Alexander Pierce</span>
                      <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="/bower_components/admin-lte/dist/img/user1-128x128.jpg" alt="message user image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                      Is this template really for free? That's unbelievable!
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->

                  <!-- Message to the right -->
                  <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-right">Sarah Bullock</span>
                      <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="/bower_components/admin-lte/dist/img/user3-128x128.jpg" alt="message user image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                      You better believe it!
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->

                  <!-- Message. Default to the left -->
                  <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left">Alexander Pierce</span>
                      <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="/bower_components/admin-lte/dist/img/user1-128x128.jpg" alt="message user image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                      Working with AdminLTE on a great new app! Wanna join?
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->

                  <!-- Message to the right -->
                  <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-right">Sarah Bullock</span>
                      <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="/bower_components/admin-lte/dist/img/user3-128x128.jpg" alt="message user image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                      I would love to.
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->

                </div>
                <!--/.direct-chat-messages-->

                <!-- Contacts are loaded here -->
                <div class="direct-chat-contacts">
                  <ul class="contacts-list">
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="/bower_components/admin-lte/dist/img/user1-128x128.jpg">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Count Dracula
                            <small class="contacts-list-date float-right">2/28/2015</small>
                          </span>
                          <span class="contacts-list-msg">How have you been? I was...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="/bower_components/admin-lte/dist/img/user7-128x128.jpg">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Sarah Doe
                            <small class="contacts-list-date float-right">2/23/2015</small>
                          </span>
                          <span class="contacts-list-msg">I will be waiting for...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="/bower_components/admin-lte/dist/img/user3-128x128.jpg">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Nadia Jolie
                            <small class="contacts-list-date float-right">2/20/2015</small>
                          </span>
                          <span class="contacts-list-msg">I'll call you back at...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="/bower_components/admin-lte/dist/img/user5-128x128.jpg">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Nora S. Vans
                            <small class="contacts-list-date float-right">2/10/2015</small>
                          </span>
                          <span class="contacts-list-msg">Where is your new...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="/bower_components/admin-lte/dist/img/user6-128x128.jpg">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            John K.
                            <small class="contacts-list-date float-right">1/27/2015</small>
                          </span>
                          <span class="contacts-list-msg">Can I take a look at...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="/bower_components/admin-lte/dist/img/user8-128x128.jpg">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Kenneth M.
                            <small class="contacts-list-date float-right">1/4/2015</small>
                          </span>
                          <span class="contacts-list-msg">Never mind I found...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                  </ul>
                  <!-- /.contacts-list -->
                </div>
                <!-- /.direct-chat-pane -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <form action="#" method="post">
                  <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                      <button type="button" class="btn btn-primary">Send</button>
                    </span>
                  </div>
                </form>
              </div>
              <!-- /.card-footer-->
            </div>
            <!--/.direct-chat -->
            <?php endif; ?>

            <?php if(false): ?>
            <!-- solid sales graph -->
            <div class="card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Sales Graph
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-transparent">
                <div class="row">
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Mail-Orders</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Online</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">In-Store</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
            <?php endif; ?>

          </section>
          <!-- /.Left col -->

          <!-- Right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-8 connectedSortable">

            <?php if($this->role->id == 1): ?>
            <!-- TABLE: Client List -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-user"></i>
                  Client List
                </h3>

                <div class="card-tools">
                  <!-- button with a dropdown -->
                  <div class="btn-group d-none">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                    <i class="fas fa-bars"></i></button>
                    <div class="dropdown-menu" role="menu">
                      <a href="#" class="dropdown-item">Add new event</a>
                      <a href="#" class="dropdown-item">Clear events</a>
                      <div class="dropdown-divider"></div>
                      <a href="#" class="dropdown-item">View calendar</a>
                    </div>
                  </div>
                  <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-primary btn-sm d-none" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                  <div class="input-group input-group-sm d-none" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 729px;">
                <table class="table table-head-fixed table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <?php foreach ($this->users as $key => $value) { ?>
                  <tr>
                    <td><?php echo $value->id; ?></td>
                    <td>
                      <div class="user-panel d-flex">
                        <div class="image">
                        <?php $profilepic = '/img/default-profile.png';?>
                        <?php if(!empty($value->image_src)){
                          $profilepic = base64_decode($value->image_src);
                        }?>
                          <img src="<?=$profilepic?>" class="img-circle elevation-2" alt="member image">
                        </div>
                        <div class="info">
                          <?php echo $value->first_name . " " . $value->last_name; ?>
                          <p class="text-muted m-b-0">Client From: <?php echo $value->city?></p>
                        </div>
                      </div>
                    </td>
                    <td><?php echo $value->email; ?></td>
                    <td><?php switch ($value->role) {
                                case 1:
                                  $role = "Admin";
                                break;
                                case 2:
                                    $role = "Client";
                                  break;
                                case 3:
                                    $role = "Staff";
                                  break;
                                case 4:
                                    $role = "Customer";
                                  break;
                                
                                default:
                                  # code...
                                  break;
                              } 
                          echo "<b>" . $role . "</b>" ;
                        ?>
                    </td>
                    <td><?php switch ($value->status) {
                                case 0:
                                  $status = "Pending";
                                  $badge = 'warning';
                                break;
                                case 1:
                                    $status = "Verified";
                                    $badge = 'success';
                                  break;
                                case 2:
                                    $status = "Pending";
                                    $badge = 'warning';
                                  break;
                                
                                default:
                                  # code...
                                  break;
                              } 
                          echo '<span class="badge badge-' . $badge . '">' . $status . '</span>'
                        ?>
                    </td>
                  </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">

              <?php
                $user_count = $this->user->user_count[0]->count;
                $user_max = $this->user->account_info[0]->max_users;
                if($user_count >= $user_max && $this->role->id != 1) {
                  echo "<span class='text-danger'><b>Note:</b> Maximum number of user reach, please upgrade your subscription plan.</span>";
                } else { ?>
                <a href="/register" class="btn btn-sm btn-info float-left">
                <i class="fas fa-plus"> </i>
                  Add New User
                </a>
              <?php } ?>
              <?php if(false): ?>
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Users</a>
              <?php endif; ?>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
            <?php endif; ?>

            <?php if(true): ?>
            <!-- Map card -->            
            <div class="card bg-gradient-default" style="display: block;">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="far fa-map mr-1"></i>
                  Shipments
                </h3>
                <!-- card tools -->
                <div class="card-tools">
                  <button type="button"
                          class="btn btn-primary btn-sm daterange invisible"
                          data-toggle="tooltip"
                          title="Date range">
                    <i class="far fa-calendar-alt"></i>
                  </button>
                  <button type="button"
                          class="btn btn-primary btn-sm"
                          data-card-widget="collapse"
                          data-toggle="tooltip"
                          title="Collapse">
                    <i class="fas fa-minus "></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <div class="card-body p-0">
                <!-- <div id="dashmap d-none" class="map" style="position: relative; height: 495px;"></div>
                 -->
                <div id="chartdiv" class="map" style="position: relative; height: 495px;">
                <div class="spinner-border" role="status" style="position: absolute;bottom: 50%;right: 50%;">
                            <span class="sr-only">Loading...</span>
                        </div>
                </div>
                <div id="sidebardash" class="d-none">
                    <?php $dashSHIP = json_decode( $this->shipment_with_port) ?>
                    <?php $current = array();?>
                    <ul class="list-group">
                      <?php foreach($dashSHIP as $shipe){?>
                        <?php if( !in_array($shipe->port_loading,$current)){ ?>
                          <?php $outclass = str_replace(' ','_',strtolower($shipe->port_loading));?>
                          <li id="<?=$outclass?>" class="list-group-item mapside-list"><a href="javascript:void(0)" class="text-white"><?=$shipe->port_loading?></a></li>
                        <?php $current[] = $shipe->port_loading; } ?>
                      <?php } ?>
                    </ul>
                </div>
              </div>
              <!-- /.card-body-->
              <div class="card-footer bg-transparent invisible d-none">
                <div class="row">
                  <div class="col-4 text-center">
                    <div id="sparkline-1"></div>
                    <div class="text-white">Visitors</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <div id="sparkline-2"></div>
                    <div class="text-white">Online</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <div id="sparkline-3"></div>
                    <div class="text-white">Sales</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            <!-- /.card -->
            <?php endif; ?>
          </section>
          <!--start of section report-->
          <section class="col-lg-12 connectedSortable" style="max-height: 50px;">
            <div class="card bg-gradient-default" style="display: block;">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="far fa-map mr-1"></i>
                  Analytics
                </h3>
                <!-- card tools -->
                <div class="card-tools">
                  <button type="button"
                          class="btn btn-primary btn-sm daterange invisible"
                          data-toggle="tooltip"
                          title="Date range">
                    <i class="far fa-calendar-alt"></i>
                  </button>
                  <button type="button"
                          class="btn btn-primary btn-sm"
                          data-card-widget="collapse"
                          data-toggle="tooltip"
                          title="Collapse">
                    <i class="fas fa-minus "></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <div class="card-body p-0">
                <!-- <div id="dashmap d-none" class="map" style="position: relative; height: 495px;"></div>
                 -->
                 <div style=position:relative><div class=tableauPlaceholder id=viz1646066982039 style=position:relative><object class=tableauViz style=display:none><param name=host_url value=https%3A%2F%2Fpublic.tableau.com%2F><param name=embed_code_version value=3><param name=site_root value=""><param name=name value=Cargomation_ReportHub&#47;Dashboard1><param name=tabs value=no><param name=toolbar value=yes><param name=animate_transition value=yes><param name=display_static_image value=yes><param name=display_spinner value=yes><param name=display_overlay value=yes><param name=display_count value=yes><param name=language value=en-US><param name=filter value="publish=yes"><param name=filter value="User Id=<?php echo $this->uid?>"></object></div><div class=toolbarWorkaround style=position:absolute;background-color:#fff;width:100%;bottom:0;z-index:10;height:35px></div></div><script>var divElement=document.getElementById("viz1646066982039"),vizElement=divElement.getElementsByTagName("object")[0];800<divElement.offsetWidth||500<divElement.offsetWidth?(vizElement.style.width="100%",vizElement.style.height=.75*divElement.offsetWidth+"px"):(vizElement.style.width="100%",vizElement.style.height="2627px");var scriptElement=document.createElement("script");scriptElement.src="https://public.tableau.com/javascripts/api/viz_v1.js",vizElement.parentNode.insertBefore(scriptElement,vizElement)</script>
              </div>
              <!-- /.card-body-->
            </div>   
      </section>
           <!--end of section report-->

        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>

    <!-- /.content -->
  </div>
<script>
  var chart_data = <?=json_encode($this->container_mode);?>;
  var shipments = <?=$this->shipment_with_port?>;
  var port_loading_couint = <?=$this->port_loading_count?>;
</script>
