<?php
class discount {	
   
    private $discountTable = 'restaurant_discount';	
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function listdiscount(){			
		
		$sqlQuery = "
			SELECT id, name, status
			FROM ".$this->discountTable." ";
						
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
	
		while ($discount = $result->fetch_assoc()) { 				
			$rows = array();			
			$rows[] = $discount['id'];			
			$rows[] = $discount['name'];
			$rows[] = $discount['status'];
			$rows[] = '<button type="button" name="update" id="'.$discount["id"].'" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit"></span></button>';			
			$rows[] = '<button type="button" name="delete" id="'.$discount["id"].'" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete"></span></button>';
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
		
		if($this->discountName && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->discountTable."(`name`, `status`)
				VALUES(?, ?)");
		
			$this->discountName = htmlspecialchars(strip_tags($this->discountName));
			$this->status = htmlspecialchars(strip_tags($this->status));
			
			$stmt->bind_param("ss", $this->discountName, $this->status);
			
			if($stmt->execute()){
				return true;
			}		
		}
	}
	
	public function update(){
		
		if($this->id && $this->discountName && $_SESSION["userid"]) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->discountTable." 
			SET name = ?, status = ?
			WHERE id = ?");
	 
			$this->discountName = htmlspecialchars(strip_tags($this->discountName));
			$this->status = htmlspecialchars(strip_tags($this->status));
								
			$stmt->bind_param("ssi", $this->discountName, $this->status, $this->id);
			
			if($stmt->execute()){				
				return true;
			}			
		}	
	}	
	
	public function getdiscountDetails(){
		if($this->id && $_SESSION["userid"]) {			
					
			$sqlQuery = "
			SELECT id, name, status
			FROM ".$this->discountTable." WHERE id = ? ";	
					
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$records = array();		
			while ($discount = $result->fetch_assoc()) { 				
				$rows = array();	
				$rows['id'] = $discount['id'];				
				$rows['name'] = $discount['name'];				
				$rows['status'] = $discount['status'];					
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
				DELETE FROM ".$this->discountTable." 
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt->bind_param("i", $this->id);

			if($stmt->execute()){				
				return true;
			}
		}
	} 
}
?>