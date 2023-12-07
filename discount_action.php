<?php
include_once 'config/Database.php';
include_once 'class/Discount.php';

$database = new Database();
$db = $database->getConnection();

$discount = new discount($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listdiscount') {
	$discount->listdiscount();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getdiscountDetails') {
	$discount->id = $_POST["id"];
	$discount->getdiscountDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'adddiscount') {
	$discount->discountName = $_POST["discountName"];
	$discount->status = $_POST["status"];
	$discount->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updatediscount') {
	$discount->id = $_POST["id"];
	$discount->discountName = $_POST["discountName"];
	$discount->status = $_POST["status"];	
	$discount->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deletediscount') {
	$discount->id = $_POST["id"];
	$discount->delete();
}
?>