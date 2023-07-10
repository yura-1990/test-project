<?php
use App\Models\Roles;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/Roles.php';

$db = new Database();
$pdo = $db->connect();

$roles = new Roles('roles', $pdo);

$result=$roles->getALL();
echo json_encode($result);