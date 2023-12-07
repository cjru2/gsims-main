<?php
include_once 'config/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if(!$user->loggedIn()) {
	header("Location: index.php");
}
include('inc/header.php');
?>
<title>GSIMS</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/discount.js"></script>	
<script src="js/general.js"></script>
<style>
.col-sm-6 {
	float:right;
}
div.dataTables_wrapper div.dataTables_filter {
	text-align: left;
}
</style>
<?php include('inc/container.php');?>
<div class="container" style="background-color:#f4f3ef;">  
	<h2>Restaurant Management System with PHP & MySQL</h2>	
	<?php include('top_menus.php'); ?>
	<br>	
	<h4>Discount List</h4>
	<br>	
	<div> 	
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-10">
					<h3 class="panel-title"></h3>
				</div>
				<div class="col-md-2" align="right">
					<button type="button" id="adddiscount" class="btn btn-info" title="Add discount"><span class="glyphicon glyphicon-plus"></span></button>
				</div>
			</div>
		</div>
		<table id="discountListing" class="table table-bordered table-striped">
			<thead>
				<tr>						
					<th>Id</th>					
					<th>discount Name</th>					
					<th>Status</th>					
					<th>Edit</th>
					<th>Remove</th>					
				</tr>
			</thead>
		</table>
	</div>
	
	<div id="discountModal" class="modal fade">
		<div class="modal-dialog">
			<form method="post" id="discountForm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Edit discount</h4>
					</div>
					<div class="modal-body">						
						<div class="form-group">
							<div class="row">
								<label class="col-md-4 text-right">Discount Name <span class="text-Danger">*</span></label>
								<div class="col-md-8">
									<input type="text" name="discountName" id="discountName" autocomplete="off" class="form-control" required />
								</div>
							</div>
						</div>	
						
						<div class="form-group">
							<div class="row">
								<label class="col-md-4 text-right">Status <span class="text-danger">*</span></label>
								<div class="col-md-8">
									<select name="status" id="status" class="form-control">
										<option value="Enable">Enable</option>
										<option value="Disable">Disable</option>
									</select>
								</div>
							</div>
						</div>							
					</div>
					<div class="modal-footer">
						<input type="hidden" name="id" id="id" />						
						<input type="hidden" name="action" id="action" value="" />
						<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</form>
		</div>
	</div>
			
</div>
 <?php include('inc/footer.php');?>
