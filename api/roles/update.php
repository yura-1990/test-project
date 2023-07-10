<?php

use App\Models\Roles;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Roles.php';

$db = new Database();
$pdo = $db->connect();

$roles = new Roles('roles', $pdo);

$role_name = $_POST['role_name'] ?? die('Something went wrong');
$id = $_POST['id'] ?? die('Something went wrong');

$result=$roles->update($id, $role_name);

echo json_encode($result);