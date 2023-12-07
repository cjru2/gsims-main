<?php
include_once 'config/Database.php';
include_once 'class/Category.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listCategory') {
	$category->listCategory();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getCategoryDetails') {
	$category->id = $_POST["id"];
	$category->getCategoryDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addCategory') {
	$category->categoryName = $_POST["categoryName"];
	$category->status = $_POST["status"];
	$category->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateCategory') {
	$category->id = $_POST["id"];
	$category->categoryName = $_POST["categoryName"];
	$category->status = $_POST["status"];	
	$category->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteCategory') {
	$category->id = $_POST["id"];
	$category->delete();
}
?>