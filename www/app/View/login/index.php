<?php

use App\Utility\Config;
use App\Utility\Flash;

?>
<body class="hold-transition login-page">
<div id="feedback" class="container">
<?php if (($danger = Flash::danger())): ?>
    <div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> <?= $this->escapeHTML($danger); ?></div>
    <?php
endif;
if (($info = Flash::info())):
    ?>
    <div class="alert alert-info" role="alert"><strong>Heads up!</strong> <?= $this->escapeHTML($info); ?></div>
    <?php
endif;
if (($success = Flash::success())):
    ?>
    <div class="alert alert-success" role="alert"><strong>Success!</strong> <?= $this->escapeHTML($success); ?></div>
    <?php
endif;
if (($warning = Flash::warning())):
    ?>
    <div class="alert alert-warning" role="alert"><strong>Warning!</strong> <?= $this->escapeHTML($warning); ?></div>
    <?php
endif;
if (($errors = Flash::session(Config::get("SESSION_ERRORS")))):
    ?>
<div class="alert alert-danger" role="alert">
    <h4>Errors:</h4>
    <ul>
        <?php foreach ($errors as $key => $values): ?>
            <?php foreach ($values as $value): ?>
                <li><?= $this->escapeHTML($value); ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
</div>
<div class="login-box">
  <div class="login-logo">
    <a href="bower_components/admin-lte/index2.html"><b>A2B</b> Hub</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session.</p>

      <form action="<?= $this->makeUrl("login/_login"); ?>" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <input type="hidden" name="csrf_token" value="<?php echo App\Utility\Token::generate(); ?>" />
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="bower_components/admin-lte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="bower_components/admin-lte/dist/js/adminlte.min.js"></script>

</body>
</html>
