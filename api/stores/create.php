<?php

use App\Models\Store;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Store.php';

$db = new Database();
$pdo = $db->connect();

$stores = new Store('stores', $pdo);

$name = $_POST['name'] ?? die('Something went wrong');

$result=$stores->create($name);
echo json_encode($result);
