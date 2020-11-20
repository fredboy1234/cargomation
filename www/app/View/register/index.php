    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-offset-4 col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading mb-3">
                        <h3 class="panel-title">Create a New Client Account</h3>
                    </div>
                    <div class="panel-body">
                        <form action="<?= $this->makeUrl("register/_register"); ?>" method="post">
                            <div class="form-group">
                                <label for="forename-input">First Name <span class="text-danger">*</span></label>
                                <input type="text" id="forename-input" class="form-control" name="first-name" />
                            </div>
                            <div class="form-group">
                                <label for="first-name-input">Last Name <span class="text-danger">*</span></label>
                                <input type="text" id="last-name-input" class="form-control" name="last-name" />
                            </div>                        
                            <div class="form-group">
                                <label for="email-input">Email <span class="text-danger">*</span></label>
                                <input type="text" id="email-input" class="form-control" name="email" />
                            </div>
                            <div class="form-group">
                                <label>Subscription <span class="text-danger">*</span></label>
                                <select class="form-control" id="subcription-input"  name="subcription">
                                    <option> </option>
                                    <option value="1">Basic</option>
                                    <option value="2">Standard</option>
                                    <option value="3">Premium</option>
                                </select>
                            </div>
                            <?php if(false): ?>
                            <div class="form-group">
                                <label for="password-input">Password <span class="text-danger">*</span></label>
                                <input type="password" id="password-input" class="form-control" name="password" />
                            </div>
                            <div class="form-group">
                                <label for="password-repeat-input">Password (Repeat) <span class="text-danger">*</span></label>
                                <input type="password" id="password-repeat-input" class="form-control" name="password_repeat" />
                            </div>
                            <?php endif; ?>
                            <input type="hidden" name="csrf_token" value="<?php echo App\Utility\Token::generate(); ?>" />
                            <button type="submit" class="btn btn-primary">Register</button>
                            <a href="<?= $this->makeURL("login"); ?>" class="btn btn-link">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</div>