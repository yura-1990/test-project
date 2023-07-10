<?php
use App\Models\Store;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Store.php';

$db = new Database();
$pdo = $db->connect();

$stores = new Store('stores', $pdo);

$result=$stores->getALL();
echo json_encode($result);