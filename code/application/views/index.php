<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>COMING SOON</title>
	<link rel="shortcut icon" href="<?php echo IMG;?>favicon.ico" />
	<!-- FONTS -->
	<link href='http://fonts.googleapis.com/css?family=Leckerli+One' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Signika:400,300,600,700' rel='stylesheet' type='text/css'>
	<!-- EXTERNAL STYLESHEETS -->
	<link href="<?php echo CSS;?>font-awesome-4.2.0/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<!-- ANIMATION -->
	<link href="<?php echo CSS;?>animation.css" rel="stylesheet" type="text/css" />
	<!-- MAIN STYLESHEETS -->
	<link rel="stylesheet" href="<?php echo CSS;?>main.css">
</head>
<body>
	<!-- ANIMATION -->
	<div class="fix-wrp">
		<div class="animate-wrp">
			<div class="sky">
				<div class="car-wheels"></div>
				<div class="car">
					<div class="msg"><b>We are on our way!</b></div>
				</div>
				<div class="car-wheels c1"></div>
				<div class="car1 c1"></div>
				<div class="cloud"></div>
				<div class="cloud2"></div>
				<div class="cloud1"></div>
				<div class="grass1"></div>
				<div class="grass"></div>
				<div class="grass2"></div>
				<div class="mountain"></div>
				<div class="mountain1"></div>
				<div class="tree"></div>
				<div class="tree-front"></div>
				<div class="road"></div>
				<div class="road-front"></div>
			</div>	
		</div>
	</div>
	<!--/animate-wrp -->

	<!-- MAIN WRAPPER -->
	<div class="main-wrapper">
		<!-- CONTAINER -->
		<div class="container">
			
			<!-- ERROR TITLE -->
			<div class="outer-wrapper">
            	<img src="<?php echo IMG;?>logo.png" width="250" height="147" alt="RideHub">
                <span>WE ARE LAUNCHING SOON</span>
          	</div>
			<!--/outer-wrapper -->

			<!-- SORRY -->
			<!--<div class="message">
				<p>Unfortunately the page you were looking for could not be found.</p><br>
				<p>Take a look around the rest of our site.</p>
			</div>-->
			
			<!-- NAVIGATION LINKS -->
			<!--<div class="nav-wrapper">
				<a href="#">Home</a>
				<a href="#">Service</a>
				<a href="#">Portfolio</a>
				<a href="#">Contact us</a>
			</div>-->
			<!--/nav-wrapper -->
			
			<!-- SOCIAL LINKS -->
			<div class="social-links">
				<a href="#"><i class="fa fa-facebook"></i></a>
				<a href="#"><i class="fa fa-twitter"></i></a>
				<a href="#"><i class="fa fa-google-plus"></i></a>
	    </div>
			<!--/social-links -->
			<p class="copyrights">Copyright © 2014 RH Tech Pvt. Ltd. All Rights Reserved</p>
		</div>
		<!--/container -->
	</div>
	<!--/main-wrapper -->
	
	<!-- COMMON SCRIPT -->
	<script src="<?php echo JS;?>jquery-1.11.1.min.js"></script>
	<script>
		function mainWindow(){
			$(".main-wrapper").css({
				width: $('html').width(),
				height: $('html').height() > $(window).height() ? $('html').height() : $(window).height()  
			});
		}
		$(document).ready(function() {mainWindow();});
		$(window).resize(function(event) {mainWindow();});

		function animateWindow(){
			$(".animate-wrp").css({
				width: $(window).width(),
				height: $('.main-wrapper').height()
			});
		}
		$(document).ready(function() {animateWindow();});
		$(window).resize(function(event) {animateWindow();});
	</script>
</body>
</html>