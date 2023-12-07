<?php
include_once 'config/Database.php';
include_once 'class/UOM.php';

$database = new Database();
$db = $database->getConnection();

$UOM = new UOM($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listUOM') {
	$UOM->listUOM();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getUOMDetails') {
	$UOM->id = $_POST["id"];
	$UOM->getUOMDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addUOM') {
	$UOM->UOMName = $_POST["UOMName"];
	$UOM->status = $_POST["status"];
	$UOM->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateUOM') {
	$UOM->id = $_POST["id"];
	$UOM->UOMName = $_POST["UOMName"];
	$UOM->status = $_POST["status"];	
	$UOM->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteUOM') {
	$UOM->id = $_POST["id"];
	$UOM->delete();
}
?>