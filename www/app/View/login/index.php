<div class="login-box">
    <div class="card">
        <h3 class="row align-items-center justify-content-center text-primary p-4">A2B HUB LOGIN</h3>
        <div class="card-body login-card-body">
            <form action="<?= $this->makeUrl("login/_login"); ?>" method="post">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Username">
                    <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                    </div>
                </div>
                <div class="checkbox">
                    <label for="remember">
                        <input type="checkbox" id="remember" name="remember" /> Remember me
                    </label>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo App\Utility\Token::generate(); ?>" />
                <button type="submit" class="btn btn-primary">Sign In</button>
                <a href="<?= $this->makeURL("register"); ?>" class="btn btn-link">Register</a>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->