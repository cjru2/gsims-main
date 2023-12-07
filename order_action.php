<?php
include_once 'config/Database.php';
include_once 'class/Order.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listOrder') {
	$order->listOrder();
}

if(!empty($_POST['action']) && $_POST['action'] == 'loadCategory') {	
	$order->loadCategory();
}

if(!empty($_POST['action']) && $_POST['action'] == 'loadTableList') {	
	$order->loadTableList();
}

if(!empty($_POST['action']) && $_POST['action'] == 'loadCategoryItem') {
	$order->categoryId = $_POST["categoryId"];
	$order->loadCategoryItem();
}

if(!empty($_POST['action']) && $_POST['action'] == 'loadItemPrice') {
	$order->itemId = $_POST["itemId"];
	$order->loadItemPrice();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getTaxRate') {	
	$order->getTaxRate();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getOrderDetails') {
	$order->id = $_POST["id"];
	$order->getOrderDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addOrder') {
	$order->tableName = $_POST["tableName"];	
	$order->itemCategory = $_POST["itemCategory"];	
	$order->items = $_POST["items"];
	$order->price = $_POST["price"];
	$order->quantity = $_POST["quantity"];
	$order->total = $_POST["total"];
	$order->status = $_POST["status"];
	$order->subTotal = $_POST["subTotal"];
	$order->taxAmount = $_POST["taxAmount"];
	$order->totalAftertax = $_POST["totalAftertax"];	
	$order->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateOrder') {
	$order->id = $_POST["id"];
	$order->tableName = $_POST["tableName"];	
	$order->itemCategory = $_POST["itemCategory"];	
	$order->items = $_POST["items"];
	$order->price = $_POST["price"];
	$order->quantity = $_POST["quantity"];
	$order->total = $_POST["total"];
	$order->status = $_POST["status"];
	$order->subTotal = $_POST["subTotal"];
	$order->taxAmount = $_POST["taxAmount"];
	$order->totalAftertax = $_POST["totalAftertax"];
	$order->itemIds = $_POST["itemIds"];
	$order->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteOrder') {
	$order->id = $_POST["id"];
	$order->delete();
}

?>