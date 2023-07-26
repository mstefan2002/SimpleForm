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
		<div style="padding: 10% 25% 10% 25%">
			<div style="width: 200px">
				<select class="form-select" id="sortBy" onclick="fSort()">
  					<option value="0">Sort by ID</option>
  					<option value="1">Sort by Name</option>
  					<option value="2">Sort by Email</option>
				</select>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Name</th>
						<th scope="col">Email</th>
						<th scope="col">Image</th>
					</tr>
				</thead>
				<tbody id="tabel-body">
				</tbody>
			</table>
			<nav aria-label="Page navigation" id="pagNav" style="display:none">
				<ul class="pagination" id="pagination">
				</ul>
			</nav>
			<center><button type="submit" class="btn btn-lg btn-primary" onclick="download()">Download All Data</button></center>
		</div>
		<script>
<?php
$page = 0;
if(isset($_GET['page']))
	$page = intval($_GET['page']);
$page--;
if($page < 0)
	$page = 0;
$sortBy = 0;
if(isset($_GET['sortBy']))
	$sortBy = intval($_GET['sortBy']);
if($sortBy < 0 || $sortBy > 2)
	$sortBy = 0;
echo "var page={$page},sortBy={$sortBy}";?>
		</script>
		<script src="assets/js/listReg.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	</body>
</html>