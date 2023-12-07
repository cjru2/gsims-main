	<?php
class Order {	
   
    private $orderTable = 'restaurant_order';
	private $orderItemTable = 'restaurant_order_item';
	private $tableTable = 'restaurant_table';
	private $itemTable = 'restaurant_items';
	private $categoryTable = 'restaurant_category';	
	private $taxTable = 'restaurant_tax';	
	private $conn;	
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function listOrder(){			
		
		$sqlQuery = "
			SELECT orders.id, orders.gross_amount, orders.tax_amount, orders.net_amount, orders.created, orders.created_by, orders.status, tables.name AS table_name
			FROM ".$this->orderTable." orders 
			LEFT JOIN ".$this->tableTable." tables ON orders.table_id = tables.id ";
						
		if(!empty($_POST["order"])){
			$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= ' ORDER BY orders.id DESC ';
		}
		
		if($_POST["length"] != -1){
			$sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}
		
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();	
		
		$stmtTotal = $this->conn->prepare($sqlQuery);
		$stmtTotal->execute();
		$allResult = $stmtTotal->get_result();
		$allRecords = $allResult->num_rows;
		
		$displayRecords = $result->num_rows;
		$records = array();	
	
		while ($order = $result->fetch_assoc()) { 				
			$rows = array();			
			$rows[] = $order['id'];			
			$rows[] = $order['table_name'];
			$rows[] = "$".$order['gross_amount'];
			$rows[] = "$".$order['tax_amount'];
			$rows[] = "$".$order['net_amount'];
			$rows[] = $order['created'];
			$rows[] = $order['created_by'];
			$rows[] = $order['status'];
			$rows[] = '<button type="button" name="update" id="'.$order["id"].'" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit"></span></button>';			
			$rows[] = '<button type="button" name="delete" id="'.$order["id"].'" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete"></span></button>';
			$records[] = $rows;
		}
		
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$displayRecords,
			"iTotalDisplayRecords"	=>  $allRecords,
			"data"	=> 	$records
		);
		
		echo json_encode($output);
	}
	
	public function insert(){
		
		if($this->tableName && $_SESSION["userid"]) {
			
			$stmt2 = $this->conn->prepare("
			UPDATE ".$this->tableTable." 
			SET status = ?
			WHERE id = ?");

			$status2 = "Disable";
	 
			$stmt2->bind_param("si", $status2, $this->tableName);
			$stmt2->execute();

			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->orderTable."(`table_id`, `gross_amount`, `tax_amount`, `net_amount`, `created_by`, `status`)
				VALUES(?, ?, ?, ?, ?, ?)");		
			$this->tableName = htmlspecialchars(strip_tags($this->tableName));
			$this->subTotal = htmlspecialchars(strip_tags($this->subTotal));
			$this->taxAmount = htmlspecialchars(strip_tags($this->taxAmount));
			$this->totalAftertax = htmlspecialchars(strip_tags($this->totalAftertax));
			$this->status = htmlspecialchars(strip_tags($this->status));

			$stmt->bind_param("ssssss", $this->tableName, $this->subTotal, $this->taxAmount, $this->totalAftertax, $_SESSION["role"], $this->status);
			
			if($stmt->execute()){		
				$lastInsertId = $this->conn->insert_id;				
				$stmt1 = $this->conn->prepare("
					INSERT INTO ".$this->orderItemTable."(`order_id`, `category_id`, `item_id`, `quantity`, `rate`, `amount`)
					VALUES(?,?,?,?,?,?)");					
				foreach($this->itemCategory as $key => $value) {					
					$stmt1->bind_param("iiiiss", $lastInsertId, $this->itemCategory[$key], $this->items[$key], $this->quantity[$key], $this->price[$key], $this->total[$key]);
					$stmt1->execute();
				}
				return true;
			}		
		}
	}
	
	public function update(){
		
		if($this->id && $this->tableName && $_SESSION["userid"]) {
			
			$stmt2 = $this->conn->prepare("
			UPDATE ".$this->tableTable." 
			SET status = ?
			WHERE id = ?");

			$status2 = "Enable";
	 
			$stmt2->bind_param("si", $status2, $this->tableName);
			$stmt2->execute();
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->orderTable." 
			SET table_id = ?, gross_amount = ?, tax_amount = ?, net_amount = ?, status = ?
			WHERE id = ?");
	 
			$this->tableName = htmlspecialchars(strip_tags($this->tableName));
			$this->subTotal = htmlspecialchars(strip_tags($this->subTotal));
			$this->taxAmount = htmlspecialchars(strip_tags($this->taxAmount));
			$this->totalAftertax = htmlspecialchars(strip_tags($this->totalAftertax));
			$this->status = htmlspecialchars(strip_tags($this->status));

			$stmt->bind_param("sssssi", $this->tableName, $this->subTotal, $this->taxAmount, $this->totalAftertax, $this->status, $this->id);			
			if($stmt->execute()){
				$this->deleteOrderItems($this->id);				
				$stmt1 = $this->conn->prepare("
					INSERT INTO ".$this->orderItemTable."(`order_id`, `category_id`, `item_id`, `quantity`, `rate`, `amount`)
					VALUES(?,?,?,?,?,?)");					
				foreach($this->itemCategory as $key => $value) {					
					$stmt1->bind_param("iiiiss", $this->id, $this->itemCategory[$key], $this->items[$key], $this->quantity[$key], $this->price[$key], $this->total[$key]);
					$stmt1->execute();
				}				
				return true;
			}			
		}	
	}	
	
	public function deleteOrderItems($order_id){
		
		$stmt = $this->conn->prepare("
		DELETE FROM ".$this->orderItemTable." 
		WHERE order_id = ?");			

		$stmt->bind_param("i", $order_id);

		if($stmt->execute()){				
			return true;
		}
	}
	
	public function getOrderDetails(){		
		if($this->id && $_SESSION["userid"]) {	

			$sqlQuery = "
			SELECT orders.id, orders.table_id, orders.gross_amount, orders.tax_amount, orders.net_amount, orders.created, orders.created_by, orders.status, 
			items.category_id, items.item_id, items.quantity, items.rate, items.amount
			FROM ".$this->orderTable." orders 
			LEFT JOIN ".$this->orderItemTable." items ON items.order_id = orders.id			
			WHERE orders.id = ?";		
			
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$records = array();				
			while ($order = $result->fetch_assoc()) { 				
				$rows = array();			
				$rows['id'] = $order['id'];		
				$rows['table_id'] = $order['table_id'];
				$rows['gross_amount'] = $order['gross_amount'];
				$rows['tax_amount'] = $order['tax_amount'];	
				$rows['net_amount'] = $order['net_amount'];	
				$rows['created'] = $order['created'];
				$rows['created_by'] = $order['created_by'];
				$rows['status'] = $order['status'];
				$rows['category_id'] = $order['category_id'];	
				$rows['item_id'] = $order['item_id'];	
				$rows['rate'] = $order['rate'];
				$rows['quantity'] = $order['quantity'];
				$rows['amount'] = $order['amount'];				
				$records[] = $rows;				
			}		
			$output = array(			
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}
	

	public function delete(){
		if($this->id && $_SESSION["userid"]) {			

			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->orderTable." 
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt->bind_param("i", $this->id);

			if($stmt->execute()){				
				$stmt = $this->conn->prepare("
				DELETE FROM ".$this->orderItemTable." 
				WHERE order_id = ?");			

				$stmt->bind_param("i", $this->id);

				if($stmt->execute()){				
					return true;
				}
			}
		}
	} 
	
	function loadCategoryItem(){	
		if($this->categoryId) {
			$sqlQuery = "
				SELECT id, name
				FROM ".$this->itemTable." 			
				WHERE category_id = ? AND status = 'Enable'";				
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->categoryId);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$html = '<option value="">--Select--</option>';		
			while ($item = $result->fetch_assoc()) { 				
				$html .= "<option value='".$item['id']."'>".$item['name']."</option>";
			}
			echo $html;			
		}			
	}
	
	function loadCategory(){		
		$sqlQuery = "
			SELECT id, name
			FROM ".$this->categoryTable;		
		$stmt = $this->conn->prepare($sqlQuery);		
		$stmt->execute();
		$result = $stmt->get_result();				
		$html = '<option value="">--Select--</option>';		
		while ($category = $result->fetch_assoc()) { 				
			$html .= "<option value='".$category['id']."'>".$category['name']."</option>";
		}
		echo $html;			
				
	}
	
	function loadItemPrice(){	
		if($this->itemId) {
			$sqlQuery = "
				SELECT price
				FROM ".$this->itemTable." 			
				WHERE id = ?";				
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->itemId);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$item = $result->fetch_assoc();		
			echo $item['price'];	
		}			
	}
	
	public function getTaxRate(){	

		$sqlQuery = "
		SELECT id, tax_name, percentage
		FROM ".$this->taxTable;		
				
		$stmt = $this->conn->prepare($sqlQuery);		
		$stmt->execute();
		$result = $stmt->get_result();				
		$records = array();		
		while ($order = $result->fetch_assoc()) { 				
			$rows = array();	
			$rows['id'] = $order['id'];				
			$rows['tax_name'] = $order['tax_name'];
			$rows['percentage'] = $order['percentage'];								
			$records[] = $rows;
		}		
		$output = array(			
			"data"	=> 	$records
		);
		echo json_encode($output);		
	}
	
	function getTable(){		
		$stmt = $this->conn->prepare("
		SELECT id, name, status FROM restaurant_table");				
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
	
	function loadTableList(){		
		$sqlQuery = "
			SELECT id, name, status
			FROM ".$this->tableTable;		
		$stmt = $this->conn->prepare($sqlQuery);		
		$stmt->execute();
		$result = $stmt->get_result();				
		$html = '<option value="">--Select--</option>';		
		while ($table = $result->fetch_assoc()) { 				
			$html .= "<option value='".$table['id']."'>".$table['name']."</option>";
		}
		echo $html;			
				
	}
	
}
?>