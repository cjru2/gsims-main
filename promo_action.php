<?php
include_once 'config/Database.php';
include_once 'class/Promo.php';

$database = new Database();
$db = $database->getConnection();

$promo = new promo($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listpromo') {
	$promo->listpromo();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getpromoDetails') {
	$promo->id = $_POST["id"];
	$promo->getpromoDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updatePromo') {
	$promo->id = $_POST["id"];
	$promo->promoName = $_POST["promoName"];
	$promo->promoDescription = $_POST["promoDescription"];
	$promo->promoPrice = $_POST["promoPrice"];
    $promo->itemCategory = $_POST["itemCategory"];	
	$promo->items = $_POST["items"];
	$promo->quantity = $_POST["quantity"];
	$promo->status = $_POST["status"];
	$promo->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addpromo') {
	$promo->promoName = $_POST["promoName"];
	$promo->promoDescription = $_POST["promoDescription"];
	$promo->promoPrice = $_POST["promoPrice"];
    $promo->itemCategory = $_POST["itemCategory"];	
	$promo->items = $_POST["items"];
	$promo->quantity = $_POST["quantity"];
	$promo->status = $_POST["status"];
	$promo->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updatepromo') {
	$promo->id = $_POST["id"];
	$promo->promoName = $_POST["promoName"];
	$promo->status = $_POST["status"];	
	$promo->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deletepromo') {
	$promo->id = $_POST["id"];
	$promo->delete();
}
?>