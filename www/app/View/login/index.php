<?php

use App\Utility\Config;
use App\Utility\Flash;

?>
<body id="body" class="hold-transition login-page">
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


<style>
canvas {
  display: block;
  vertical-align: bottom;
}

#body {
  position: absolute;
  width: 100%;
  height: 100%;
  background: url(/img/background.png);
  background-position: bottom;
  background-repeat: no-repeat;
  background-color: #2f2f2f;
  background-size: cover;
}

.login-box {
	position: absolute;
	top: 50%;
	right: 50%;
	transform: translate(50%,-50%);
	font-family: 'Open Sans', sans-serif;
}

.card {
	background: rgba(0, 0, 0, 0.75);
	text-shadow: 0px 0px 2px #131415;
}

.card-body {
	background: transparent;
}

.input-group-text {
  background: #fff !important;
  color: #000 !important;
}

h1 {
	font-size: 2.25em;
	font-weight: 700;
	letter-spacing: -1px;
}
</style>

<div class="login-box">
  <div class="login-logo">
    <!-- <a href="bower_components/admin-lte/index2.html" style="color: #FFF;"><b>A2B</b> Freight Hub</a>-->
    <a href="bower_components/admin-lte/index2.html" style="color: #FFF;"><br>CargoMation</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session.</p>

      <form id="login" method="post">
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
      <?php if(false): ?>
        <a href="forgot-password.html">I forgot my password</a>
      <?php endif; ?>
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
<script>
$(document).ready(function(){
  var color = '#007bff';
  var maxParticles = 100;

  // ParticlesJS Config.
  particlesJS('body', {
    'particles': {
      'number': {
        'value': maxParticles,
        'density': {
          'enable': false,
          'value_area': (maxParticles * 2) * 2
        }
      },
      'color': {
        'value': color
      },
      'shape': {
        'type': 'circle',
        'stroke': {
          'width': 0,
          'color': '#000000'
        },
        'polygon': {
          'nb_sides': 10
        },
      },
      'opacity': {
        'value': 0.5,
        'random': false,
        'anim': {
          'enable': false,
          'speed': 1,
          'opacity_min': 0.1,
          'sync': false
        }
      },
      'size': {
        'value': 3,
        'random': true,
        'anim': {
          'enable': false,
          'speed': 40,
          'size_min': 0.1,
          'sync': false
        }
      },
      'line_linked': {
        'enable': true,
        'distance': 150,
        'color': color,
        'opacity': 1,
        'width': 1
      },
      'move': {
        'enable': true,
        'speed': 2,
        'direction': 'none',
        'random': false,
        'straight': false,
        'out_mode': 'out',
        'bounce': false,
        'attract': {
          'enable': false,
          'rotateX': 600,
          'rotateY': 1200
        }
      }
    },
    'interactivity': {
      'detect_on': 'canvas',
      'events': {
        'onhover': {
          'enable': true,
          'mode': 'grab'
        },
        'onclick': {
          'enable': true,
          'mode': 'push'
        },
        'resize': true
      },
      'modes': {
        'grab': {
          'distance': 140,
          'line_linked': {
            'opacity': 1
          }
        },
        'bubble': {
          'distance': 400,
          'size': 40,
          'duration': 2,
          'opacity': 8,
          'speed': 3
        },
        'repulse': {
          'distance': 200,
          'duration': 0.4
        },
        'push': {
          'particles_nb': 4
        },
        'remove': {
          'particles_nb': 2
        }
      }
    },
    'retina_detect': true
  });

  $("#login").submit(function(event){
      event.preventDefault();

      var email = $('input[type="email"]').val().trim();
      var password = $('input[type="password"]').val().trim();
      var csrf_token = $('input[type="hidden"]').val().trim();

      if( email != "" && password != "" ){
          $.ajax({
              url:'<?= $this->makeUrl("/login/_login"); ?>',
              type:'post',
              data:{email:email,password:password,csrf_token:csrf_token},
              beforeSend: function() {
                $('.card').hide();
                $('.login-logo').append('<center id="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw text-primary"></i>'+
                '<span class="sr-only">Loading...</span> </center>');
              },
              success:function(response){
                console.log(response);
                location.reload();
              }
          });
      }
  });
});
</script>
</body>
</html>
