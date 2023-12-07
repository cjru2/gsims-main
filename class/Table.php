<?php
class Table {	
   
    private $tablesTable = 'restaurant_table';	
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function listTables(){			
		
		$sqlQuery = "
			SELECT id, name, capacity, status
			FROM ".$this->tablesTable." ";
						
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
	
		while ($tables = $result->fetch_assoc()) { 				
			$rows = array();			
			$rows[] = $tables['id'];			
			$rows[] = $tables['name'];
			$rows[] = $tables['capacity'];
			$rows[] = $tables['status'];
			$rows[] = '<button type="button" name="update" id="'.$tables["id"].'" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit"></span></button>';			
			$rows[] = '<button type="button" name="delete" id="'.$tables["id"].'" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete"></span></button>';
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

			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->tablesTable."(`name`, `capacity`, `status`)
				VALUES(?, ?, ?)");
		
			$this->tableName = htmlspecialchars(strip_tags($this->tableName));
			$this->capacity = htmlspecialchars(strip_tags($this->capacity));
			$this->status = htmlspecialchars(strip_tags($this->status));
			
			$stmt->bind_param("sis", $this->tableName, $this->capacity, $this->status);
			
			if($stmt->execute()){
				return true;
			}		
		}
	}
	
	public function update(){
		
		if($this->id && $this->tableName && $_SESSION["userid"]) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->tablesTable." 
			SET name = ?, capacity = ?, status = ?
			WHERE id = ?");
	 
			$this->tableName = htmlspecialchars(strip_tags($this->tableName));
			$this->capacity = htmlspecialchars(strip_tags($this->capacity));
			$this->status = htmlspecialchars(strip_tags($this->status));
								
			$stmt->bind_param("sisi", $this->tableName, $this->capacity, $this->status, $this->id);
			
			if($stmt->execute()){				
				return true;
			}			
		}	
	}	
	
	public function getTableDetails(){
		if($this->id && $_SESSION["userid"]) {			
					
			$sqlQuery = "
			SELECT id, name, capacity, status
			FROM ".$this->tablesTable." WHERE id = ? ";	
					
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$records = array();		
			while ($category = $result->fetch_assoc()) { 				
				$rows = array();	
				$rows['id'] = $category['id'];				
				$rows['name'] = $category['name'];
				$rows['capacity'] = $category['capacity'];						
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
				DELETE FROM ".$this->tablesTable." 
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