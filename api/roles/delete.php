<?php

use App\Models\Roles;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Roles.php';

$db = new Database();
$pdo = $db->connect();

$catalog = new Roles('roles' ,$pdo);

$id = $_GET['id'] ?? die('Something went wrong');

$result=$catalog->delete($id);

echo json_encode($result);
