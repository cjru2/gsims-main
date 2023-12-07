<?php
class promo {	
   
    private $promoTable = 'restaurant_promo';	
    private $itemTable = 'restaurant_items';
	private $categoryTable = 'restaurant_category';
	private $promoproductsTable = 'restaurant_promo_products';		
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function listpromo(){			
		
		$sqlQuery = "
			SELECT id, name, product_description, status
			FROM ".$this->promoTable." ";
						
		if(!empty($_POST["order"])){
			$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= ' ORDER BY id ASC ';
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
	
		while ($promo = $result->fetch_assoc()) { 				
			$rows = array();			
			$rows[] = $promo['id'];			
			$rows[] = $promo['name'];
			$rows[] = $promo['product_description'];
			$rows[] = $promo['status'];
			$rows[] = '<button type="button" name="update" id="'.$promo["id"].'" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit"></span></button>';			
			$rows[] = '<button type="button" name="delete" id="'.$promo["id"].'" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete"></span></button>';
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
		
		if($this->promoName && $_SESSION["userid"]) {
            $str=rand(100000,999999);
            $result = sha1($str);
            $code = substr($result, 0, 7);
			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->promoTable."(`name`, `status`, `product_description`, `promocode`, `price`) VALUES(?, ?, ?, ?, ?)");
		
			$this->promoName = htmlspecialchars(strip_tags($this->promoName));
			$this->status = htmlspecialchars(strip_tags($this->status));
			$this->promoDescription = htmlspecialchars(strip_tags($this->promoDescription));
			$this->promoPrice = htmlspecialchars(strip_tags($this->promoPrice));
			$status="Enable";

			$stmt->bind_param("sssss", $this->promoName, $this->status, $this->promoDescription,$code, $this->promoPrice);
			
                if($stmt->execute()){			
					try {
						$lastInsertId = $this->conn->insert_id;				
						$stmt1 = $this->conn->prepare("
							INSERT INTO ".$this->promoproductsTable."(`promoid`, `productid`, `quantity`, `status`, `categoryid`) VALUES(?,?,?,?,?)");					
						foreach($this->itemCategory as $key => $value) {	
							$stmt1->bind_param("iiisi", $lastInsertId, $this->items[$key], $this->quantity[$key], $status, $this->itemCategory[$key]);
	
							if ($stmt1->execute()) {
								echo json_encode(['success' => 'Success insert']);
							}else{
								echo json_encode(['error' => 'Error data: ' . $stmt1->error]);
								exit();
							}
						}
					}	
                   catch (Exception $e){
					echo json_encode(['error'=>'Caught exception: ',  $e->getMessage(), "\n"]);
				   }
                }
		}
	}
	
	public function update(){
		
		if($this->id && $this->promoName && $_SESSION["userid"]) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->promoTable." 
			SET name = ?, status = ?, product_description = ?, price = ?
			WHERE id = ?");
	 
			$this->promoName = htmlspecialchars(strip_tags($this->promoName));
			$this->promoDescription = htmlspecialchars(strip_tags($this->promoDescription));
			$this->promoPrice = htmlspecialchars(strip_tags($this->promoPrice));
			$this->status = htmlspecialchars(strip_tags($this->status));
								
			$stmt->bind_param("ssssi", $this->promoName, $this->status, $this->promoDescription, $this->promoPrice , $this->id);
			
			if($stmt->execute()){			
				try {		
					$this->deleteOrderItems( $this->id);	
					$stmt1 = $this->conn->prepare("
						INSERT INTO ".$this->promoproductsTable."(`promoid`, `productid`, `quantity`, `status`, `categoryid`) VALUES(?,?,?,?,?)");					
					foreach($this->itemCategory as $key => $value) {	
						$stmt1->bind_param("iiisi", $this->id, $this->items[$key], $this->quantity[$key], $this->status, $this->itemCategory[$key]);

						if ($stmt1->execute()) {
							echo json_encode(['success' => 'Success insert']);
						}else{
							echo json_encode(['error' => 'Error data: ' . $stmt1->error]);
							exit();
						}
					}
				}	
			   catch (Exception $e){
				echo json_encode(['error'=>'Caught exception: ',  $e->getMessage(), "\n"]);
			   }
			}		
		}	
	}	

	public function deleteOrderItems($order_id){
		
		$stmt = $this->conn->prepare("
		DELETE FROM ".$this->promoproductsTable." 
		WHERE promoid = ?");			

		$stmt->bind_param("i", $order_id);

		if($stmt->execute()){				
			return true;
		}
	}

	public function updatePromo(){
		
		if($this->id && $this->promoName && $_SESSION["userid"]) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->promoTable." 
			SET name = ?, status = ?
			WHERE id = ?");
	 
			$this->promoName = htmlspecialchars(strip_tags($this->promoName));
			$this->promoDescription = htmlspecialchars(strip_tags($this->promoDescription));
			$this->status = htmlspecialchars(strip_tags($this->status));
			$status= 'Enable';
			$stmt->bind_param("sss", $this->promoName, $this->promoDescription, $this->status);			
			if($stmt->execute()){
				$this->deleteOrderItems($this->id);				
				$stmt1 = $this->conn->prepare("
					INSERT INTO ".$this->promoproductsTable."(`promoid`, `productid`, `quantity`, `status`)
					VALUES(?,?,?,?)");					
				foreach($this->itemCategory as $key => $value) {					
					$stmt1->bind_param("iiis", $this->id, $this->items[$key], $this->quantity[$key],$status);
					if ($stmt1->execute()) {
						echo json_encode(['success' => 'Success update']);
					}else{
						echo json_encode(['error' => 'Error data: ' . $stmt1->error]);
						exit();
					}
				}				
				return true;
			}			
		}
	}
	
	public function getpromoDetails(){
		if($this->id && $_SESSION["userid"]) {			
					
			$sqlQuery = "
			SELECT restaurant_promo.id, restaurant_promo.name, restaurant_promo.product_description, restaurant_promo.status, restaurant_promo_products.productid,quantity,categoryid,price FROM ".$this->promoTable." LEFT JOIN ".$this->promoproductsTable." on ".$this->promoTable.".id=".$this->promoproductsTable.".promoid WHERE ".$this->promoTable.".id = ? ";	
					
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			
			if($stmt->execute()){
				$result = $stmt->get_result();				
				$records = array();		
				while ($promo = $result->fetch_assoc()) { 				
					$rows = array();	
					$rows['id'] = $promo['id'];				
					$rows['name'] = $promo['name'];		
					$rows['promoDescription'] = $promo['product_description'];				
					$rows['productid'] = $promo['productid'];			
					$rows['categoryid'] = $promo['categoryid'];			
					$rows['quantity'] = $promo['quantity'];				
					$rows['promoPrice'] = $promo['price'];					
					$rows['status'] = $promo['status'];					
					$records[] = $rows;
				}		
				$output = array(			
					"data"	=> 	$records
				);
				echo json_encode($output);
			}

			else{
				echo json_encode(['error' => 'Error data: ' . $stmt->error]);
				exit();
			}
		
		}
	}
	

	public function delete(){
		if($this->id && $_SESSION["userid"]) {			

			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->promoTable." 
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt->bind_param("i", $this->id);

			if($stmt->execute()){				
				return true;
			}
		}
	} 

    function getProducts(){		
		$stmt = $this->conn->prepare("
		SELECT id, name 
		FROM ".$this->itemTable);				
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}

    function getItemCategory(){		
		$stmt = $this->conn->prepare("
		SELECT id, name 
		FROM ".$this->categoryTable);				
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
}
?>