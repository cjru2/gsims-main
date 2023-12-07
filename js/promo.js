$(document).ready(function(){	
	
	var promoRecords = $('#promoListing').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": true,
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"promo_action.php",
			type:"POST",
			data:{action:'listpromo'},
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
	
	
	$('#addpromo').click(function(){
		$('#promoModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#promoModal").on("shown.bs.modal", function () {
			$('#promoForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Add promo");					
			$('#action').val('addpromo');
			$('#save').val('Save');
		});
	});		
	
	$("#promoModal").on('submit','#promoForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"promo_action.php",
			method:"POST",
			data:formData,
			success:function(data){		
				console.log(data);		
				$('#promoForm')[0].reset();
				$('#promoModal').modal('hide');				
				$('#save').attr('disabled', false);
				promoRecords.ajax.reload();
			},
			error: function(data){
				console.log(data)
			}
		})
	});		

	$("#promoListing").on('click', '.delete', function(){
		var id = $(this).attr("id");		
		var action = "deletepromo";
		if(confirm("Are you sure you want to delete this record?")) {
			$.ajax({
				url:"promo_action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data) {					
					promoRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	

	$(document).on('change', "[id^=itemCategory_]", function(){	
		var id = $(this).attr('id');
		id = id.replace("itemCategory_",'');
        var categoryId = $(this).val();		
        if(categoryId != '') {
            $.ajax({
                url:"order_action.php",
                method:"POST",
                data:{action:'loadCategoryItem', categoryId:categoryId},
                success:function(data) {
                    $('#items_'+id).html(data);
                }
            });
        }
    });
	
	$(document).on('click', '#checkAll', function() {          	
		$(".itemRow").prop("checked", this.checked);
	});	
	$(document).on('click', '.itemRow', function() {  	
		if ($('.itemRow:checked').length == $('.itemRow').length) {
			$('#checkAll').prop('checked', true);
		} else {
			$('#checkAll').prop('checked', false);
		}
	});  
	
	var count = $(".itemRow").length;
	$(document).on('click', '#addRows', function() { 
		count++;
		var htmlRows = itemHTMLRows(count);		
		$('#orderItem').append(htmlRows);
		loadCategoryList(count);		
	}); 
	$(document).on('click', '#removeRows', function(){
		$(".itemRow:checked").each(function() {
			$(this).closest('tr').remove();
		});
		$('#checkAll').prop('checked', false);
	});		

	function itemHTMLRows(count) {
		var htmlRows = '';
		htmlRows += '<tr>';
		htmlRows += '<td><input class="itemRow" type="checkbox"></td>'; 	
		htmlRows += '<td><select name="itemCategory[]" id="itemCategory_'+count+'" class="form-control"></select></td>';          
		htmlRows += '<td><select name="items[]" id="items_'+count+'" class="form-control"></select></td>';	
		htmlRows += '<td><input type="number" name="quantity[]" id="quantity_'+count+'" class="form-control quantity" autocomplete="off"><input type="hidden" name="itemIds[]" id="itemIds_'+count+'" class="form-control"></td>';
		htmlRows += '</tr>';
		return htmlRows;
	}

	function loadCategoryList(id) {	
		$.ajax({
			url:"order_action.php",
			method:"POST",
			data:{action:'loadCategory'},
			success:function(data) {
				$('#itemCategory_'+id).html(data);
			}
		});
	}

	$("#orderModal").on('submit','#orderForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"order_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#orderForm')[0].reset();
				$('#orderModal').modal('hide');				
				$('#save').attr('disabled', false);
				orderRecords.ajax.reload();
			}
		})
	});	

	$("#promoListing").on('click', '.update', function(){
		var id = $(this).attr("id");
		var action = 'getpromoDetails';
		$.ajax({
			url:'promo_action.php',
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(respData){			
				console.log(respData);
				$("#promoModal").on("shown.bs.modal", function () { 
					$('#promoForm')[0].reset();
					var categoryHTML = $('#itemCategory_1').html();
					console.log(categoryHTML),'categoryHTML';
					var itemCount = 1;	
					respData.data.forEach(function(item){	
						$('#id').val(item['id']);
						$('#promoName').val(item['name']);						
						$('#promoDescription').val(item['promoDescription']);
						$('#promoPrice').val(item['promoPrice']);						
						$('#status').val(item['status']);					
							
						if(itemCount == 1) {					
							$('#itemCategory_'+itemCount).val(item['categoryid']);													
							$('#itemCategory_'+itemCount).trigger('change');		
							setTimeout(function () {								
								$('#items_1').val(item['productid']);																
							}, itemCount*150);
							$('#quantity_'+count).val(item['quantity']);
							$('#itemIds'+count).val(item['productid']);	

						} else if(itemCount > 1) {
							count++;
							var htmlRows = itemHTMLRows(count);
							$('#orderItem').append(htmlRows);
							$('#itemCategory_'+count).html(categoryHTML);
							setTimeout(function () {								
								$('#itemCategory_'+count).val(item['categoryid']);
								$('#itemCategory_'+count).trigger('change');																	
							}, itemCount*100);							
							setTimeout(function () {
								$('#items_'+count).val(item['productid']);															
							}, itemCount*150);						
							$('#quantity_'+count).val(item['quantity']);
							$('#itemIds'+count).val(item['item_id']); 							
						}
						itemCount++;
						
					});
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit Promo");
					$('#action').val('updatePromo');
					$('#save').val('Save');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			},error:function(data){
				console.log(data)
			}
		});
	});
	
$("#promoModal").on("hidden.bs.modal", function () {
	location.reload();
});



});