<!DOCTYPE html>

<html>
<head>
   <title>test</title>
   <link href="assets/css/style.css" rel="stylesheet" media="screen">	
   <link href="assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
   <link href="assets/css/bootstrap.css" rel="stylesheet" media="screen">
   <link href="assets/css/datepicker.css" rel="stylesheet" media="screen">
   <script src="assets/js/jquery2.min.js"></script>
   <script src="assets/js/bootstrap.min.js"></script>
   <script src="assets/js/bootstrap.js"></script>
   <script src="assets/js/bootbox.js"></script>
   <script src="assets/js/bootstrap-datepicker.js"></script> 
   <script type="text/javascript" src="assets/js/script.js"></script>
	
</head>
<body>
	<span></span>
	<div class="row justify-content-center" style="margin:100px;">
		<div class="Degrade">
			<h3 style="text-align: center;">
				CGCI-CA
			</h3>
			<div class="form" >
				<div class="form-group" style="margin: 50px;">	
					<label>Date de début: </label>
					<input type="date" name="location"  class="form-control" id="date-debut" disabled>
				</div>
				<div class="form-group" style="margin: 50px;">
					<label>Date de fin: </label>
					<input type="date" name="location"  class="form-control" id="date-fin" disabled>
				</div>
			</div>
			<div class="form-group" style="text-align: center;">
            <button id='extract'  class="btn btn-primary" disabled>Extraire</button>
				
			</div>
			<div class="loader d-none" style="text-align: center;"></div>
			
		</div>
	</div>

</body>
</html>