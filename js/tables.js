$(document).ready(function(){	
	
	var categoryRecords = $('#tableListing').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": true,
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"table_action.php",
			type:"POST",
			data:{action:'listTables'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 4, 5],
				"orderable":false,
			},
		],
		"pageLength": 10
	});		
	
	
	$('#addTable').click(function(){
		$('#tableModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#tableModal").on("shown.bs.modal", function () {
			$('#tableForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Add Table");					
			$('#action').val('addTable');
			$('#save').val('Save');
		});
	});		
	
	$("#tableListing").on('click', '.update', function(){
		var id = $(this).attr("id");
		var action = 'getTableDetails';
		$.ajax({
			url:'table_action.php',
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(respData){				
				$("#tableModal").on("shown.bs.modal", function () { 
					$('#tableForm')[0].reset();
					respData.data.forEach(function(item){						
						$('#id').val(item['id']);						
						$('#tableName').val(item['name']);
						$('#capacity').val(item['capacity']);						
						$('#status').val(item['status']);
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit table");
					$('#action').val('updateTable');
					$('#save').val('Save');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#tableModal").on('submit','#tableForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"table_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#tableForm')[0].reset();
				$('#tableModal').modal('hide');				
				$('#save').attr('disabled', false);
				categoryRecords.ajax.reload();
			}
		})
	});		

	$("#tableListing").on('click', '.delete', function(){
		var id = $(this).attr("id");		
		var action = "deleteTable";
		if(confirm("Are you sure you want to delete this record?")) {
			$.ajax({
				url:"table_action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data) {					
					categoryRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
	
});

