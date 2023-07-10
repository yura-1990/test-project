<?php

use App\Models\Product;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Product.php';

$db = new Database();
$pdo = $db->connect();

$product = new Product('products', $pdo);

$parent_id = $_POST['parent_id'] ?? die('Something went wrong');
$store_id = $_POST['store_id'] ?? die('Something went wrong');
$category = $_POST['category'] ?? die('Something went wrong');
$product_name = $_POST['product_name'] ?? die('Something went wrong');
$data_expire = $_POST['data_expire'] ?? die('Something went wrong');
$debit = $_POST['debit'] ?? die('Something went wrong');
$credit = $_POST['credit'] ?? die('Something went wrong');
$id = $_POST['id'] ?? die('Something went wrong');

$result=$product->update($parent_id, $store_id, $category, $product_name, $data_expire, $debit, $credit, $id);

echo json_encode($result);