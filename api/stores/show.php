<?php

use App\Models\Store;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Store.php';

$db = new Database();
$pdo = $db->connect();

$store = new Store('stores', $pdo);

$id = $_GET['id'] ?? die('Something went wrong'); // use params in postman

$result=$store->show($id);

echo json_encode($result);