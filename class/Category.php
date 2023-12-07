<?php
class Category {	
   
    private $categoryTable = 'restaurant_category';	
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function listCategory(){			
		
		$sqlQuery = "
			SELECT id, name, status
			FROM ".$this->categoryTable." ";
						
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
	
		while ($category = $result->fetch_assoc()) { 				
			$rows = array();			
			$rows[] = $category['id'];			
			$rows[] = $category['name'];
			$rows[] = $category['status'];
			$rows[] = '<button type="button" name="update" id="'.$category["id"].'" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit"></span></button>';			
			$rows[] = '<button type="button" name="delete" id="'.$category["id"].'" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete"></span></button>';
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
		
		if($this->categoryName && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->categoryTable."(`name`, `status`)
				VALUES(?, ?)");
		
			$this->categoryName = htmlspecialchars(strip_tags($this->categoryName));
			$this->status = htmlspecialchars(strip_tags($this->status));
			
			$stmt->bind_param("ss", $this->categoryName, $this->status);
			
			if($stmt->execute()){
				return true;
			}		
		}
	}
	
	public function update(){
		
		if($this->id && $this->categoryName && $_SESSION["userid"]) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->categoryTable." 
			SET name = ?, status = ?
			WHERE id = ?");
	 
			$this->categoryName = htmlspecialchars(strip_tags($this->categoryName));
			$this->status = htmlspecialchars(strip_tags($this->status));
								
			$stmt->bind_param("ssi", $this->categoryName, $this->status, $this->id);
			
			if($stmt->execute()){				
				return true;
			}			
		}	
	}	
	
	public function getCategoryDetails(){
		if($this->id && $_SESSION["userid"]) {			
					
			$sqlQuery = "
			SELECT id, name, status
			FROM ".$this->categoryTable." WHERE id = ? ";	
					
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$records = array();		
			while ($category = $result->fetch_assoc()) { 				
				$rows = array();	
				$rows['id'] = $category['id'];				
				$rows['name'] = $category['name'];				
				$rows['status'] = $category['status'];					
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
				DELETE FROM ".$this->categoryTable." 
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