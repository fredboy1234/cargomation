<style>
#loader-wrapper {
    background: #00000047;
    z-index: 1010;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
}
#loader-wrapper .spinner-border {
    position: absolute;
    top: 50%;
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
</style>
<?php $dashboardV1 = $this->dashtheme;?>
  <!-- Main content -->
    <section class="content" style="padding-bottom: 5em;">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <?php if(isset($dashboardV1->dash) && $dashboardV1->dash ==='dash_v1' ){
          include(VIEW_PATH."_template/module/dashboard/dashboard_box_v1.php");
         }else{
          include(VIEW_PATH."_template/module/dashboard/dashboard_box_default.php");
         }
        ?>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-4 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->

            <?php if(isset($dashboardV1->dash) && $dashboardV1->dash ==='dash_v1' ){
              include(VIEW_PATH."_template/module/dashboard/dashboard_side_v1.php");
             }else{
              include(VIEW_PATH."_template/module/dashboard/dashboard_side_default.php");
            }?>
      
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
                  User List
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
                      <th>Action</th>
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
                    <td>
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary">Action</button>
                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu" style="">
                          <a class="dropdown-item btn btn-sm btn-success action" data-action="show" data-id="<?= $value->user_id; ?>">
                            <i class="fas fa-eye mr-1"></i> Show</a>
                          <a class="dropdown-item btn btn-sm btn-info action" data-action="edit" data-id="<?= $value->user_id; ?>">
                            <i class="fas fa-pencil-alt mr-1"></i> Edit</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item btn btn-sm btn-danger action" data-action="delete" data-id="<?= $value->user_id; ?>">
                            <i class="fas fa-trash-alt mr-1"></i> Delete</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <?php } ?>
                  </tbody>
                </table>
                <div class="modal fade" id="userList">
                  <div class="modal-dialog modal-lg" style="width:100%; max-width:1088px">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">User Info</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p>Loading&hellip;</p>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

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
            <?php else: ?>
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
          <?php if($this->role->id == 1): ?>
          <?php // FOR SUPER ADMIN ?>
          <?php else: ?>
          <!--start of section report-->
          <section id="lazy-tablue" class="col-lg-12 connectedSortable">
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
              </div>
              
            </div>
              <!-- /.card-body-->
            </div>   
          </section>
           <!--end of section report-->
          <?php endif; ?>

        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>

    <!-- /.content -->
  </div>
<script>
 
  var shipments = [];
  var port_loading_couint = [];
  var uid = <?=$this->uid?>;
  var rolename = <?=json_encode($this->role->role_name)?>;
  console.log(rolename);
</script>
