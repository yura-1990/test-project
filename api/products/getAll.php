<?php
use App\Models\Product;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Product.php';

$db = new Database();
$pdo = $db->connect();

$products = new Product('products', $pdo);

$result=$products->getAll();
echo json_encode($result);