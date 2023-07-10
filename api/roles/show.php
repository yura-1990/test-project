<?php

use App\Models\Roles;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Roles.php';

$db = new Database();
$pdo = $db->connect();

$roles = new Roles('roles', $pdo);

$id = $_GET['id'] ?? die('Something went wrong'); // use params in postman

$result=$roles->show($id);

echo json_encode($result);