$(document).ready(function(){	
	
	var UOMRecords = $('#UOMListing').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": true,
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"uom_action.php",
			type:"POST",
			data:{action:'listUOM'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 3, 4],
				"orderable":false,
			},
		],
		"pageLength": 10
	});		
	
	
	$('#addUOM').click(function(){
		$('#UOMModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#UOMModal").on("shown.bs.modal", function () {
			$('#UOMForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Add UOM");					
			$('#action').val('addUOM');
			$('#save').val('Save');
		});
	});		
	
	$("#UOMListing").on('click', '.update', function(){
		var id = $(this).attr("id");
		var action = 'getUOMDetails';
		$.ajax({
			url:'uom_action.php',
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(respData){				
				$("#UOMModal").on("shown.bs.modal", function () { 
					$('#UOMForm')[0].reset();
					respData.data.forEach(function(item){						
						$('#id').val(item['id']);						
						$('#UOMName').val(item['name']);	
						$('#status').val(item['status']);
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit UOM");
					$('#action').val('updateUOM');
					$('#save').val('Save');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#UOMModal").on('submit','#UOMForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"uom_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#UOMForm')[0].reset();
				$('#UOMModal').modal('hide');				
				$('#save').attr('disabled', false);
				UOMRecords.ajax.reload();
			}
		})
	});		

	$("#UOMListing").on('click', '.delete', function(){
		var id = $(this).attr("id");		
		var action = "deleteUOM";
		if(confirm("Are you sure you want to delete this record?")) {
			$.ajax({
				url:"uom_action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data) {					
					UOMRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
	
});

