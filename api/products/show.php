<?php

use App\Models\Product;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Product.php';

$db = new Database();
$pdo = $db->connect();

$product = new Product('products', $pdo);

$id = $_GET['id'] ?? die('Something went wrong'); // use params in postman

$result=$product->show($id);

echo json_encode($result);