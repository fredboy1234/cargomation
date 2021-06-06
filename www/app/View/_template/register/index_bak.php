<style>
/* Make circles that indicate the steps of the form: */
[class^="step"] {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

/* Mark the active step: */
[class^="step"].active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step4.finish {
  background-color: #4CAF50;
}
</style>


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading mb-3">
                            <h3 class="panel-title">Create a New Client Account</h3>
                        </div>
                        <div class="panel-body">
                            <form id="register_form" action="<?= $this->makeUrl("register/_register"); ?>" method="post">
                                <div class="col-12 col-lg-6 col-md-8 col-sm-12">
                                    <div class="card card-primary card-outline card-outline-tabs">
                                        <div class="card-header p-0 border-bottom-0">
                                            <ul class="nav nav-tabs" id="tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="false">Login Details</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="false">Personal Details</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="finish-tab" data-toggle="tab" href="#finish" role="tab" aria-controls="finish" aria-selected="false">Finish</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="tabContent">
                                                <div class="tab-pane fade active show" id="login" role="tabpanel" aria-labelledby="login-tab">
                                                    <div class="form-group">
                                                        <label for="email-input">Email <span class="text-danger">*</span></label>
                                                        <input type="text" id="email-input" class="form-control" name="email" />
                                                        <span id="error_email" class="text-danger"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password-input">Password <span class="text-danger">*</span></label>
                                                        <input type="password" id="password-input" class="form-control" name="password" />
                                                        <span id="error_password" class="text-danger"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password-repeat-input">Password (Repeat) <span class="text-danger">*</span></label>
                                                        <input type="password" id="password-repeat-input" class="form-control" name="password_repeat" />
                                                        <span id="error_password_repeat" class="text-danger"></span>
                                                    </div>
                                                    <div align="center">
                                                        <button type="button" name="btn_login" id="btn_login" class="btn btn-info btn-md">Next</button>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                                    <div class="form-group">
                                                        <label for="first-name-input">First Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="first-name-input" class="form-control" name="first-name" />
                                                        <span id="error_first_name" class="text-danger"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="last-name-input">Last Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="last-name-input" class="form-control" name="last-name" />
                                                        <span id="error_last_name" class="text-danger"></span>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label for="address-input">Address <span class="text-danger">*</span></label>
                                                        <input type="text" id="address-input" class="form-control" name="address" />
                                                        <span id="error_address" class="text-danger"></span>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label for="contact-num-input">Contact No. <span class="text-danger">*</span></label>
                                                        <input type="text" id="contact-num-input" class="form-control" name="contact-num" />
                                                        <span id="error_contact_num" class="text-danger"></span>
                                                    </div> 
                                                    <div align="center">
                                                        <input type="hidden" name="csrf_token" value="<?php echo App\Utility\Token::generate(); ?>" />
                                                        <button type="button" name="previous_btn_personal" id="previous_btn_personal" class="btn btn-default btn-md">Previous</button>
                                                        <button type="submit" name="btn_personal" id="btn_personal" class="btn btn-success btn-md">Register</button>
                                                        <!-- <a href="<?= $this->makeURL("login"); ?>" class="btn btn-link">Cancel</a> -->
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="finish" role="tabpanel" aria-labelledby="finish-tab">
                                                    <div class="form-group">
                                                        <center id="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>
                                                    </div>
                                                </div>
                                                <!-- Circles which indicates the steps of the form: -->
                                                <div style="text-align:center;margin-top:40px;">
                                                    <span class="step1" style="display: none"></span>
                                                    <span class="step2 active"></span>
                                                    <span class="step3"></span>
                                                    <span class="step4"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>