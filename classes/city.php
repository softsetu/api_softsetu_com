<?php
include 'Config.php';
include 'Database.php';
include 'CityClass.php';
$CityClass = new CityClass;
$rows = $CityClass->FetchGroupBy();
$i = 1;
foreach ($rows as $d) {
	echo $i++.'-'.$d['state'].'<br>';
	$CityClass->insT($d['state']);
}
?>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>City Manage</title>

	   
    
	<!-- Bootstrap Stylesheet -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
	<!-- Bootstrap Script -->
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    $('#example').DataTable();
	$('#example1').DataTable();


	$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

} );
</script>	
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h2 class="text-center">Manage City Data From <?php echo $_GET['to']; ?> To <?php echo $_GET['from']; ?></h2>
			<form action="" method="get">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="">To</label>
							<input type="text" class="form-control" name="to">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="">From</label>
							<input type="text" class="form-control" name="from">
						</div>
					</div>
					<div class="col-md-3">
							<br>
							<button class="btn btn-primary mt-2">Get Data</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<section class="container-fluid" style="padding: 20px;">
<div class="row">
	<div class="col-md-1">
		
	</div>
	<div class="col-md-11">
		<div class="row">
			<div class="col-md-2">
		<input type="checkbox" id="checkAll"> <label style="font-size: 20px;" class="form-check-label" for="checkAll"> Check ALL</label>
	</div>
		</div>
	</div>
</div>	

<form action="" method="POST">

<div class="row">

<div class="col-md-12">
	

<?php
$DB = new CityClass;
$rows = $DB->master_city($_GET['to'], $_GET['from']);
// print_r($rows);

foreach ($rows as $row) { ?>
<div class="row" style="border-top: 1px solid #666;">
	<div class="col-md-1">
		
	</div>
	<div class="col-md-4">
		<div class="form-check">
		  <input class="form-check-input" name="city[]" value="<?php echo $row['id']; ?>" type="checkbox" id="<?php echo $row['id']; ?>">
		  <label class="form-check-label" for="<?php echo $row['id']; ?>">
		    <span class="text-primary">City : </span><?php echo $row['city'].' '.$row['dist'].' '.$row['state']; ?>
		  </label>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-check">
		  <p><span class="text-success">Dist : </span><?php echo $row['dist']; ?></p>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-check">
		  <p><span class="text-danger">State : </span><?php echo $row['state']; ?></p>
		</div>
	</div>
</div>
<?php } ?>

</div>
</div>

<button type="submit" name="submit" class="btn btn-danger">Deactive Data</button>

</form>

<?php if (isset($_POST['submit'])){
	error_reporting(0);
	$data = $_POST['city'];
	$num = $DB->Upd($data);

	if ($num == 1) {
		echo '<script>alert("Deactivated...");window.location="city.php?to='.$_GET['to'].'&from='.$_GET['from'].'"</script>';
	}

}
?>










	<!-- <div class="table-responsive">
		<table class="table table-striped text-center display" id="example">
			<thead>
				<tr><th>Arjun</th></tr>
			</thead>
			<tbody>
		
		<tr>
			<td>Arjun</td>
		</tr>
	
			</tbody>
		</table>
	</div> -->
</section>

</script>
<h4></h4>
</body>
</html>