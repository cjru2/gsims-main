<?php
include_once 'config/Database.php';
include_once 'class/Table.php';

$database = new Database();
$db = $database->getConnection();

$table = new Table($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listTables') {
	$table->listTables();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getTableDetails') {
	$table->id = $_POST["id"];
	$table->getTableDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addTable') {
	$table->tableName = $_POST["tableName"];
	$table->capacity = $_POST["capacity"];
	$table->status = $_POST["status"];
	$table->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateTable') {
	$table->id = $_POST["id"];
	$table->tableName = $_POST["tableName"];
	$table->capacity = $_POST["capacity"];
	$table->status = $_POST["status"];	
	$table->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteTable') {
	$table->id = $_POST["id"];
	$table->delete();
}
?>