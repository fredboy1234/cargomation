<?php 
include("connection.php");
session_start(); 
if(isset($_SESSION['PID'])){
echo "<script>
window.location.href='/auth_home';
</script>";	
}
 ?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>A2b Freight Hub</title>
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
  <link rel="stylesheet" href="resource_view/style.css">
  <link rel="stylesheet" href="resource_view/particles.css">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,700;0,800;1,600&display=swap" rel="stylesheet"> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
<style>

.ml5 {
  position: relative;
  font-weight: 300;
  font-size: 3em;
  color: #402d2d;
}

.ml5 .text-wrapper {
  position: relative;
  display: inline-block;
  padding-top: 0.1em;
  padding-right: 0.05em;
  padding-bottom: 0.15em;
  line-height: 1em;
}

.ml5 .line {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  margin: auto;
  height: 3px;
  width: 100%;
  background-color: white;
  transform-origin: 0.5 0;
}

.ml5 .ampersand {
  font-family: Baskerville, serif;
  font-style: italic;
  font-weight: 400;
  width: 1em;
  margin-right: -0.1em;
  margin-left: -0.1em;
}

.ml5 .letters {
  display: inline-block;
  opacity: 0;
}
.swal2-popup {
  font-size: 0.90em !important;
}


</style>
</head>

<body>
<!-- partial:index.partial.html -->
 <div id="particles-js"></div>
<form id="a2b_form">
<label>
<h1 class="ml5">
  <span class="text-wrapper">
    <span style="color:#4682B4;" class="line line1"></span>
    <span style="color:#4682B4;" class="letters letters-left"><img src="assets/images/a2b.png"></span>
    <span style="color:#4682B4;" class="letters letters-center">FREIGHT</span>
    <span style="color:#4682B4;" class="letters letters-right">HUB</span>
    <span style="color:#4682B4;" class="line line2"></span>
  </span>
</h1>
</label>
  <label>
    <input id="a2_email" name="a2_email_" type="email" required />
    <div  class="label-text">Email</div>
  </label>
  <label>
    <input id="a2_pwhash_" name="a2_pwhash_" type="password" required />
    <div class="label-text">Password</div>
	 <button type="submit">Login</button>
	
  </label>
</form>

<!-- partial -->

  <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
  <script src="assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
  <script src="assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
  <script>
  
$( "#a2b_form" ).submit(function( event ) {
		//$('#myModal2').modal('show'); 
		
		event.preventDefault(); 
		$.ajax({
					url:"functions/firewall_log_auth_ClientUser.php",
					method:"POST",
					data:new FormData(this),
					contentType:false,
					processData:false,
					success:function(data){ 
				    
						if(data == '1'){
							
						 event.preventDefault();
						 $('#a2b_form').css('display','none');
						  Swal.fire({
							type: 'success',
							title: 'You are successfully logged in',
							text: '',
							timer: 5000,
							footer: ''
						});
						
						setTimeout(function() { 
						window.location.href = "/dashboard_user";
						}, 3000);
									 
						}
						
						else{
					
						$('#a2b_form').css('display','none');
						 Swal.fire({
							type: 'error',
							title: 'Incorrect Email/Password !',
							text: '',
							timer: 3000,
							footer: ''
						});
						setTimeout(function() { 
						$('#a2b_form').css('display','');
						}, 3000);						
						}
					}
				});
});
 anime.timeline({loop: true})
  .add({
    targets: '.ml5 .line',
    opacity: [0.5,1],
    scaleX: [0, 1],
    easing: "easeInOutExpo",
    duration: 1700
  }).add({
    targets: '.ml5 .line',
    duration: 1600,
    easing: "easeOutExpo",
    translateY: (el, i) => (-0.625 + 0.625*2*i) + "em"
  }).add({
    targets: '.ml5 .ampersand',
    opacity: [0,1],
    scaleY: [0.5, 1],
    easing: "easeOutExpo",
    duration: 1600,
    offset: '-=600'
  }).add({
    targets: '.ml5 .letters-left',
    opacity: [0,1],
    translateX: ["0.5em", 0],
    easing: "easeOutExpo",
    duration: 2000,
    offset: '-=300'
  }).add({
    targets: '.ml5 .letters-center',
    opacity: [0,1],
    translateX: ["-0.5em", 0],
    easing: "easeOutExpo",
    duration: 1000,
    offset: '-=600'
  }).add({
    targets: '.ml5 .letters-right',
    opacity: [0,1],
    translateX: ["-0.5em", 0],
    easing: "easeOutExpo",
    duration: 1000,
    offset: '-=600'
  }).add({
    targets: '.ml5',
    opacity: 0,
    duration: 2000,
    easing: "easeOutExpo",
    delay: 10000
  });
  
  particlesJS("particles-js", {"particles":{"number":{"value":6,"density":{"enable":true,"value_area":800}},"color":{"value":"#505157"},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":6},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.3,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":29.170799196552856,"random":false,"anim":{"enable":true,"speed":10,"size_min":40,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#ffffff","opacity":1,"width":2},"move":{"enable":true,"speed":8,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"grab"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":false});var count_particles, stats, update; stats = new Stats; stats.setMode(0); stats.domElement.style.position = 'absolute'; stats.domElement.style.left = '0px'; stats.domElement.style.top = '0px'; document.body.appendChild(stats.domElement); count_particles = document.querySelector('.js-count-particles'); update = function() { stats.begin(); stats.end(); if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) { count_particles.innerText = window.pJSDom[0].pJS.particles.array.length; } requestAnimationFrame(update); }; requestAnimationFrame(update);;
  </script>
</body>
</html>
