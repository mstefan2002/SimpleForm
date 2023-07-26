<?php 
include('include/config.php');
include('include/csrf.php'); ?>
<html>
	<head>
		<title>Form Test</title>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
		<link href="assets/css/main.css" rel="stylesheet" type="text/css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	</head>
	<body class="dark-body pb-2">
		<div style="padding: 15% 25% 15% 25%">
		<form>
			<div class="form-group">
				<label>Email address</label>
				<input type="email" class="form-control" id="input_email" placeholder="Enter a valid email address">
			</div>
			<div class="form-group">
				<label>Name</label>
				<input type="text" class="form-control" id="input_username" placeholder="Enter your Name">
			</div>
			<div class="form-group">
				<label>Choose Image</label>
				<input type="file" class="form-control" id="input_image">
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="input_consent">
				<label class="form-check-label">I accept the <a href="#">Terms of Service</a></label>
			</div>
			<input type="hidden" id="csrf_token" value="<?php echo CSRF::getToken(); ?>">
			<button type="button" class="btn btn-lg btn-primary" onclick="onSubmit()">Submit</button>
		</form>
		</div>
		<script src="assets/js/main.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	</body>
</html>