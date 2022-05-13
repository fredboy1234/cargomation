<style>
.overlay{
    bottom: 25px;
    position: relative;
}
.profile-icon{
    font-size: 1.3em;
}
.note {
    background-color: #dedede;
    padding: 10px;
    margin-top: 5px;
    width: 488px;
    border-radius: 5px;
}
</style>
    <?php 
    if(!empty($this->user_settings)) {
        $document_settings = json_decode($this->user_settings[0]->document);
    } else {
        $document_settings = "";
    }
    
    if (isset($this->data)): ?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center profile-container">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="<?=$this->image_profile?>"
                                    alt="User profile picture">
                                    <div class="overlay">
                                        <a href="javascript:void(0) " class="profile-icon" title="Edit User Profile">
                                            <i class="fas fa-camera"></i>
                                        </a>
                                    </div>
                            </div>

                            <h3 class="profile-username text-center"><?= $this->escapeHTML( $this->data->name ); ?></h3>

                            <p class="text-muted text-center"><?= $this->escapeHTML( $this->data->email ); ?></p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <?php if($this->role->role_id != 4): ?>
                                <li class="list-group-item">
                                    <b>Account Type</b> <a class="float-right"><?= $this->user->account_info[0]->plan; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Users</b> <a class="float-right"><?= $this->user->user_count[0]->count; ?> /<b>
                                <?= $this->user->account_info[0]->max_users; ?></b></a>
                                </li>
                                <?php else: ?>
                                <li class="list-group-item">
                                    <b>Organization Code</b> <a class="float-right"><?= $this->user->user_info[0]->organization_code; ?></a>
                                </li>
                                <?php endif; ?>
                                <li class="list-group-item">
                                <?php 
                                switch ($this->user->account_info[0]->status) {
                                    case 0:
                                        $status = "Not Verified";
                                        $badge = 'primary';
                                        $attr = '';
                                        $icon = 'fa-user';
                                        $text = "Verify";
                                    break;
                                    case 1:
                                        $status = "Verified";
                                        $badge = 'success';
                                        $attr = 'disabled';
                                        $icon = 'fa-user-check';
                                        $text = "Verified";
                                    break;
                                    case 2:
                                        $status = "Pending";
                                        $badge = 'warning';
                                        $attr = '';
                                        $icon = 'fa-user';
                                        $text = "Pending";
                                    break;
                                    
                                    default:
                                    # code...
                                    break;
                                } ?>
                                <b>Status</b> <a class="float-right"><?= $status ?></a>
                                </li>
                                
                            </ul>
                            <?php
                                echo '<a href="#" class="btn btn-' . $badge . ' btn-block ' . $attr . '">';
                                echo '    <i class="fas ' . $icon . ' mr-1"></i><b>' . $text . '</b>';
                                echo '</a>';
                            ?>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-phone mr-1"></i> Phone </strong>

                            <p class="text-muted">
                                <?= $this->user->user_info[0]->phone; ?>
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                            <p class="text-muted"><?= $this->user_info->address . " " . $this->user_info->city . " "; ?></p>

                            <hr>

                            <strong><i class="fas fa-pencil-alt mr-1"></i> Roles</strong>

                            <p class="text-muted">
                                <!-- <span class="badge badge-danger">Sending Agent</span>
                                <span class="badge badge-success">Staff</span>
                                <span class="badge badge-info">Admin</span>
                                <span class="badge badge-warning">Client</span>
                                <span class="badge badge-primary">Importer</span> -->
                                <span class="badge badge-warning"><?=$this->role->role_name?></span>
                            </p>

                            <!-- <hr>

                            <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p> -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item d-none"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                                <li class="nav-item d-none"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                                <li class="nav-item"><a class="nav-link active" href="#information" data-toggle="tab">Information</a></li>
                                <?php if($this->role->role_id != 4): ?>
                                <li class="nav-item"><a class="nav-link" href="#theme" data-toggle="tab">Themes</a></li>
                                <li class="nav-item"><a class="nav-link" href="#misc" data-toggle="tab">Miscellaneous</a></li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                                <?php endif; ?>
                            </ul>
                        </div><!-- /.card-header -->

                        <div class="card-body">
                            <div class="tab-content">
                                <?php if(false): ?>
                                <div class="tab-pane" id="activity">
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="/bower_components/admin-lte/dist/img/user1-128x128.jpg" alt="user image">
                                        <span class="username">
                                            <a href="#">Jonathan Burke Jr.</a>
                                            <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                        </span>
                                        <span class="description">Shared publicly - 7:30 PM today</span>
                                        </div>
                                        <!-- /.user-block -->
                                        <p>
                                        Lorem ipsum represents a long-held tradition for designers,
                                        typographers and the like. Some people hate it and argue for
                                        its demise, but others ignore the hate as they create awesome
                                        tools to help create filler text for everyone from bacon lovers
                                        to Charlie Sheen fans.
                                        </p>

                                        <p>
                                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                        <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                        <span class="float-right">
                                            <a href="#" class="link-black text-sm">
                                            <i class="far fa-comments mr-1"></i> Comments (5)
                                            </a>
                                        </span>
                                        </p>

                                        <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                    </div>
                                    <!-- /.post -->
                                    <!-- Post -->
                                    <div class="post clearfix">
                                        <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="/bower_components/admin-lte/dist/img/user7-128x128.jpg" alt="User Image">
                                        <span class="username">
                                            <a href="#">Sarah Ross</a>
                                            <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                        </span>
                                        <span class="description">Sent you a message - 3 days ago</span>
                                        </div>
                                        <!-- /.user-block -->
                                        <p>
                                        Lorem ipsum represents a long-held tradition for designers,
                                        typographers and the like. Some people hate it and argue for
                                        its demise, but others ignore the hate as they create awesome
                                        tools to help create filler text for everyone from bacon lovers
                                        to Charlie Sheen fans.
                                        </p>

                                        <form class="form-horizontal">
                                        <div class="input-group input-group-sm mb-0">
                                            <input class="form-control form-control-sm" placeholder="Response">
                                            <div class="input-group-append">
                                            <button type="submit" class="btn btn-danger">Send</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    <!-- /.post -->
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="/bower_components/admin-lte/dist/img/user6-128x128.jpg" alt="User Image">
                                        <span class="username">
                                            <a href="#">Adam Jones</a>
                                            <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                        </span>
                                        <span class="description">Uploaded 5 documents - 5 days ago</span>
                                        </div>
                                        <!-- /.user-block -->
                                        <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <img class="img-fluid" src="http://placehold.it/431x288" alt="Photo">
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-6">
                                            <div class="row">
                                            <div class="col-sm-6">
                                                <img class="img-fluid mb-3" src="http://placehold.it/200x134" alt="Photo">
                                                <img class="img-fluid" src="http://placehold.it/200x134" alt="Photo">
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-sm-6">
                                                <img class="img-fluid mb-3" src="http://placehold.it/200x134" alt="Photo">
                                                <img class="img-fluid" src="http://placehold.it/200x134" alt="Photo">
                                            </div>
                                            <!-- /.col -->
                                            </div>
                                            <!-- /.row -->
                                        </div>
                                        <!-- /.col -->
                                        </div>
                                        <!-- /.row -->

                                        <p>
                                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                        <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                        <span class="float-right">
                                            <a href="#" class="link-black text-sm">
                                            <i class="far fa-comments mr-1"></i> Comments (5)
                                            </a>
                                        </span>
                                        </p>

                                        <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                    </div>
                                    <!-- /.post -->
                                </div>
                                <!-- /.tab-pane -->
                                <?php endif; ?>
                                <div class="tab-pane" id="timeline">
                                    <!-- The timeline -->
                                    <div class="timeline timeline-inverse">
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                        <span class="bg-danger">
                                            10 Feb. 2014
                                        </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                        <i class="fas fa-envelope bg-primary"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> 12:05</span>

                                            <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                            <div class="timeline-body">
                                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                            quora plaxo ideeli hulu weebly balihoo...
                                            </div>
                                            <div class="timeline-footer">
                                            <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                            </div>
                                        </div>
                                        </div>
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <div>
                                        <i class="fas fa-user bg-info"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>

                                            <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted your document request
                                            </h3>
                                        </div>
                                        </div>
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <div>
                                        <i class="fas fa-comments bg-warning"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>

                                            <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                                            <div class="timeline-body">
                                            Take me to your leader!
                                            Switzerland is small and neutral!
                                            We are more like Germany, ambitious and misunderstood!
                                            </div>
                                            <div class="timeline-footer">
                                            <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                                            </div>
                                        </div>
                                        </div>
                                        <!-- END timeline item -->
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                        <span class="bg-success">
                                            3 Jan. 2014
                                        </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                        <i class="fas fa-camera bg-purple"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> 2 days ago</span>

                                            <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new document</h3>

                                            <div class="timeline-body">
                                            <img src="http://placehold.it/150x100" alt="...">
                                            <img src="http://placehold.it/150x100" alt="...">
                                            <img src="http://placehold.it/150x100" alt="...">
                                            <img src="http://placehold.it/150x100" alt="...">
                                            </div>
                                        </div>
                                        </div>
                                        <!-- END timeline item -->
                                        <div>
                                        <i class="far fa-clock bg-gray"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="active tab-pane" id="information">
                                    <form class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Full Name</label>
                                            <div class="col-sm-10 mt-2">
                                                <span><?= $this->escapeHTML($this->data->name); ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10 mt-2">
                                                <span><?= $this->escapeHTML($this->data->email); ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Phone</label>
                                            <div class="col-sm-10 mt-2">
                                                <span><?= $this->escapeHTML($this->user_info->phone); ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Address</label>
                                            <div class="col-sm-10 mt-2">
                                                <span><?= $this->user_info->address . " " . $this->user_info->city . " "; ?></span>
                                            </div>
                                        </div>
                                        <?php if(false): ?>
                                        <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" placeholder="Name">
                                        </div>
                                        </div>
                                        <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                        </div>
                                        </div>
                                        <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputName2" placeholder="Name">
                                        </div>
                                        </div>
                                        <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                        </div>
                                        </div>
                                        <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                        </div>
                                        </div>
                                        <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <div class="checkbox">
                                            <label>
                                                <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                            </label>
                                            </div>
                                        </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group row">
                                        <div class="offset-sm-2 co">
                                            <!-- <button class="btn btn-primary">Edit</button> -->
                                            <a class="px-md-5 btn btn-primary" href="#edit-settings" data-toggle="tab">Edit</a>
                                            <!-- <button type="submit" class="btn btn-danger">Delete Account</button> -->
                                        </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                                <!--mis-->
                                <div class="tab-pane" id="misc">
                                    <form class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="hfimage" class="col-sm-2 col-form-label">Header Banner</label>
                                            <div class="col-sm-10 mt-2">
                                                <img style="width:auto; height:50px; " src="<?=$this->miscImage?>" alt="..." class="img-thumbnail">
                                                <span id="hfupload" role="button">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="footerimage" class="col-sm-2 col-form-label">Footer Banner</label>
                                            <div class="col-sm-10 mt-2">
                                                <img style="width:auto; height:50px; " src="<?=$this->miscFooter?>" alt="..." class="img-thumbnail">
                                                <span id="footerupload" role="button">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--Edit Settings-->     
                                <div class="tab-pane" id="edit-settings">
                                    <form class="form-horizontal" _lpchecked="1">
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">First Name</label>

                                            <div class="col-sm-10">
                                            <input type="text" name="firstname" class="form-control" placeholder="First Name" value="<?=$this->user_info->first_name?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">Last Name</label>

                                            <div class="col-sm-10">
                                            <input type="text" name="lastname" class="form-control" placeholder="Last Name" value="<?=$this->user_info->last_name?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">Address</label>

                                            <div class="col-sm-10">
                                            <input type="text" name="address" class="form-control" placeholder="Address" value="<?=$this->user_info->address?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">City</label>

                                            <div class="col-sm-10">
                                            <input type="text" name="city" class="form-control" placeholder="City" value="<?=$this->user_info->city?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">Zipcode</label>

                                            <div class="col-sm-10">
                                            <input type="text" name="zipcode" class="form-control" placeholder="Zipcode" value="<?=$this->user_info->postcode?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">Contact No.</label>

                                            <div class="col-sm-10">
                                            <input type="text" name="contact" class="form-control" placeholder="Contact" value="<?=$this->user_info->phone?>">
                                            </div>
                                        </div>
                                        <?php if(false): ?>
                                        <div class="form-group">
                                            <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                            <div class="col-sm-10">
                                            <input disabled="disabled" type="email" name="email" class="form-control" id="inputEmail" placeholder="Email" value="<?=$this->user_info->email?>">
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <?php if(false): ?>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                </label>
                                            </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                            <input type="hidden" name="info_id" value="<?=$this->user_info->id?>">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php if($this->role->role_id != 4): ?>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="theme">
                                    <?php
                                        if(isset($this->selectedTheme[0])){
                                            $selectedTheme=$this->selectedTheme[0]->theme;
                                        }else{
                                            $selectedTheme="default";
                                        }
                                    
                                    ?>
                                    <form class="form-horizontal" method="post" action="<?= $this->makeUrl("profile/savetheme"); ?>">
                                        <div class="form-group">
                                            <?php foreach($this->themes as $theme){?>
                                                <?php $name = str_replace(" ","_", strtolower($theme->theme_name));?>
                                                <?php $selected = ($selectedTheme==$name ? 'checked' : '')?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="theme" value="<?=$name?>" <?=$selected?>>
                                                    <label class="form-check-label"><?php echo $theme->theme_name?></label>
                                                    <?php $color = explode(",",$theme->color);?>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <?php foreach($color as $c){?>
                                                            <div class="d-inline-block" style="background-color:<?=$c?>; width:33px;height:31px; margin-left:-4px;"></div>
                                                            <?php }?>    
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">Save Theme</button>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="settings">
                                    <form method="post">
                                        <input type="hidden" id="column" name="column" value="document">
                                        <div class="card card-outline card-primary">
                                            <div class="card-header">
                                                <h3 class="card-title">DocTracker Settings</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">

                                                        <div class="form-group">
                                                        <!--
                                                            custom-control custom-switch custom-switch-off-danger custom-switch-on-success
                                                        -->
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input" id="pushDocument" name="doctracker[push_document]" <?php if (isset($document_settings->doctracker->push_document)) { echo "checked='checked'"; } ?>>
                                                                <label class="custom-control-label" for="pushDocument">Enable push to Cargowise button </label>
                                                                <p class="note">When button enabled, you will able to see an extra button in each document file. You can push each document to CargoWise. </p>
                                                            </div>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input" id="deleteDocument" name="doctracker[delete_document]" <?php if (isset($document_settings->doctracker->delete_document)) { echo "checked='checked'"; } ?>>
                                                                <label class="custom-control-label" for="deleteDocument">Enable delete to document button </label>
                                                                <p class="note">When button enabled, you will able to see a delete button in each document file. You can delete each document. </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <?php if(false): ?>
                                        <div class="card card-outline card-primary collapsed-card">
                                            <div class="card-header">
                                                <h3 class="card-title">Vessel Track Settings</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">

                                                        <div class="form-group">
                                                        <!--
                                                            custom-control custom-switch custom-switch-off-danger custom-switch-on-success
                                                        -->
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input" id="dummy1" name="">
                                                                <label class="custom-control-label" for="dummy1">Enable dark mode map </label>
                                                                <p class="note">When button enabled, tracker map will be in dark mode. </p>
                                                            </div>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input" id="dummy2" name="">
                                                                <label class="custom-control-label" for="dummy2">Show real-time vessel tracker map </label>
                                                                <p class="note">When button enabled, you will able to see the real-time vessel location in the map. </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">Save Settings</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <!-- /.tab-pane -->
                                <?php endif; ?>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                    <?php if($this->role->role_id != 4): ?>
                    <!-- TABLE: Client List -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Contact List</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                    <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0" style="height: 500px;">
                            <table class="table table-head-fixed table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email Address</th>
                                    <th>Organization Code</th>
                                    <th>Company Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            if(empty($this->contact_list)) {
                                echo "<tr><td colspan='6'><center>No contact in the list</center></td></tr>";
                            } else {                     
                                foreach ($this->contact_list as $key => $value) { 
                            ?>
                            <tr>
                                <td><?php echo $value->id; ?></td>
                                <td><?php echo $value->email_address; ?></td>
                                <td><?php echo $value->organization_code; ?></td>
                                <td><?php echo $value->company_name; ?></td>
                                <td><?php switch ($value->status) {
                                            case 0:
                                                echo 
                                                '<a href="/register/contact/' . $value->id . '" class="btn btn-sm btn-primary">
                                                <i class="fas fa-user-plus mr-1"></i> Register </a>';
                                            break;
                                            case 1:
                                                echo
                                                '<button type="button" class="btn btn-sm btn-success action" data-action="show" data-id="' . $value->id . '">
                                                <i class="fas fa-eye mr-1"></i> Show</button>
                                                <button type="button" class="btn btn-sm btn-info action" data-action="edit" data-id="' . $value->id . '">
                                                <i class="fas fa-pencil-alt mr-1"></i> Edit</button>
                                                <button type="button" class="btn btn-sm btn-danger action" data-action="delete" data-id="' . $value->id . '">
                                                <i class="fas fa-trash-alt mr-1"></i> Delete</button>';
                                            break;
                                            case 2:
                                                echo 
                                                '<a href="#" class="btn btn-sm btn-success">
                                                <i class="fas fa-user-check mr-1"></i> Approved </a>';
                                            break;
                                            case 3:
                                                echo 
                                                '<a href="#" class="btn btn-sm btn-danger">
                                                <i class="fas fa-user-slash mr-1"></i> Deleted </a>';
                                            break;
                                            
                                            default:
                                            # code...
                                            break;
                                        } 
                                    ?>
                                </td>
                            </tr>
                            <?php } 
                            } ?>
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
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <?php endif; ?>
</div>

<div class="modal fade" id="profileModal">
  <div class="modal-dialog modal-lg" style="width:100%; max-width:825px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload Profile</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <!-- <button type="file" class="btn btn-block btn-primary btn-sm"><i class="fas fa-cloud-upload-alt"></i> Upload Photo</button> -->
      </div>
      <!-- <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="hfmodal">
  <div class="modal-dialog modal-lg" style="width:100%; max-width:825px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload Image</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <!-- <button type="file" class="btn btn-block btn-primary btn-sm"><i class="fas fa-cloud-upload-alt"></i> Upload Photo</button> -->
      </div>
      <!-- <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="myModal">
  <div class="modal-dialog modal-lg" style="width:100%; max-width:1088px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Contact Info</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Loading&hellip;</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php if (false): ?>
        <button type="button" class="btn btn-primary">Save changes</button>
        <?php endif; ?>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
