$(document).ready(function(){	
	
	var discountRecords = $('#discountListing').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": true,
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"discount_action.php",
			type:"POST",
			data:{action:'listdiscount'},
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
	
	
	$('#adddiscount').click(function(){
		$('#discountModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#discountModal").on("shown.bs.modal", function () {
			$('#discountForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Add discount");					
			$('#action').val('adddiscount');
			$('#save').val('Save');
		});
	});		
	
	$("#discountListing").on('click', '.update', function(){
		var id = $(this).attr("id");
		var action = 'getdiscountDetails';
		$.ajax({
			url:'discount_action.php',
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(respData){				
				$("#discountModal").on("shown.bs.modal", function () { 
					$('#discountForm')[0].reset();
					respData.data.forEach(function(item){						
						$('#id').val(item['id']);						
						$('#discountName').val(item['name']);	
						$('#status').val(item['status']);
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit discount");
					$('#action').val('updatediscount');
					$('#save').val('Save');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#discountModal").on('submit','#discountForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"discount_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#discountForm')[0].reset();
				$('#discountModal').modal('hide');				
				$('#save').attr('disabled', false);
				discountRecords.ajax.reload();
			}
		})
	});		

	$("#discountListing").on('click', '.delete', function(){
		var id = $(this).attr("id");		
		var action = "deletediscount";
		if(confirm("Are you sure you want to delete this record?")) {
			$.ajax({
				url:"discount_action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data) {					
					discountRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
	
});

