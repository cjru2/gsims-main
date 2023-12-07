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
<script src="js/tables.js"></script>	
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
	<h2>Ginabell's Sales And Inventory System</h2>	
	<?php include('top_menus.php'); ?>
	<br>	
	<h4>Table List</h4>
	
	<div> 	
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-10">
					<h3 class="panel-title"></h3>
				</div>
				<div class="col-md-2" align="right">
					<button type="button" id="addTable" class="btn btn-info" title="Add Table"><span class="glyphicon glyphicon-plus"></span></button>
				</div>
			</div>
		</div>
		<table id="tableListing" class="table table-bordered table-striped">
			<thead>
				<tr>						
					<th>Id</th>					
					<th>Table Name</th>
					<th>Capacity</th>						
					<th>Status</th>					
					<th></th>
					<th></th>					
				</tr>
			</thead>
		</table>
	</div>
	
	<div id="tableModal" class="modal fade">
		<div class="modal-dialog">
			<form method="post" id="tableForm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Tables</h4>
					</div>
					<div class="modal-body">						
						<div class="form-group">
							<div class="row">
								<label class="col-md-4 text-right">Table Name <span class="text-danger">*</span></label>
								<div class="col-md-8">
									<input type="text" name="tableName" id="tableName" autocomplete="off" class="form-control" required />
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<label class="col-md-4 text-right">Capacity <span class="text-danger">*</span></label>
								<div class="col-md-8">
									<input type="number" name="capacity" id="capacity" autocomplete="off" class="form-control" required />
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