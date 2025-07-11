<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Main CSS-->
	<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/main.css">
	<!-- Font-icon css-->
	<link rel="stylesheet" type="text/css"
		href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Login - Diskominfo Kota Kendari</title>
</head>

<body>
	<section class="material-half-bg">
		<div class="cover"></div>
	</section>
	{{ $slot }}
	<!-- Essential javascripts for application to work-->
	<script src="{{ asset('admin') }}/js/jquery-3.2.1.min.js"></script>
	<script src="{{ asset('admin') }}/js/popper.min.js"></script>
	<script src="{{ asset('admin') }}/js/bootstrap.min.js"></script>
	<script src="{{ asset('admin') }}/js/main.js"></script>
	<!-- The javascript plugin to display page loading on top-->
	<script src="{{ asset('admin') }}/js/plugins/pace.min.js"></script>
	<script type="text/javascript">
		// Login Page Flipbox control
		$('.login-content [data-toggle="flip"]').click(function() {
			$('.login-box').toggleClass('flipped');
			return false;
		});
	</script>
</body>

</html>
