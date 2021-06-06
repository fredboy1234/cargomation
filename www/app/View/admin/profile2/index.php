    <?php if (isset($this->data)): ?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="/bower_components/admin-lte/dist/img/user2-160x160.jpg"
                            alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center"><?= $this->escapeHTML($this->data->name); ?></h3>

                    <p class="text-muted text-center"><?= $this->escapeHTML($this->data->email); ?></p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                        <b>Account Type</b> <a class="float-right"><?= $this->user->account_info[0]->plan; ?></a>
                        </li>
                        <li class="list-group-item">
                        <b>Users</b> <a class="float-right"><?= $this->user->account_info[0]->user_count; ?>/<b>
                        <?= $this->user->account_info[0]->max_users; ?></b></a>
                        </li>
                        <li class="list-group-item">
                        <?php switch ($this->user->account_info[0]->status) {
                            case 1:
                                $status = "Verified";
                                $badge = 'success';
                                $attr = 'disabled';
                            break;
                            case 2:
                                $status = "Pending";
                                $badge = 'warning';
                                $attr = '';
                            break;
                            
                            default:
                            # code...
                            break;
                        } ?>
                        <b>Status</b> <a class="float-right"><?= $status ?></a>
                        </li>
                    </ul>

                    <?php
                        echo '<a href="/register" class="btn btn-' . $badge . ' btn-block ' . $attr . '">';
                        echo '    <i class="fas fa-user-check mr-1"></i><b>' . $status . '</b>';
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

                <p class="text-muted"><?= $this->user->user_addr[0]->address; ?></p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Roles</strong>

                <p class="text-muted">
                    <span class="badge badge-danger">Sending Agent</span>
                    <span class="badge badge-success">Staff</span>
                    <span class="badge badge-info">Admin</span>
                    <span class="badge badge-warning">Client</span>
                    <span class="badge badge-primary">Importer</span>
                </p>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
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
                    <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                    <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                    <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
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

                    <div class="tab-pane" id="settings">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
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
                            <label for="inputName" class="col-sm-2 col-form-label">Contact</label>
                            <div class="col-sm-10 mt-2">
                                <span>N/A</span>
                            </div>
                        </div>
                        <?php if(false): ?>
                        <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputName" placeholder="Name">
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
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Edit</button>
                            <button type="submit" class="btn btn-danger">Delete Account</button>
                        </div>
                        </div>
                    </form>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <?php endif; ?>
</div>