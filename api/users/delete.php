<?php
use App\Models\User;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/User.php';

$db = new Database();
$pdo = $db->connect();

$users = new User('users', $pdo);

$id = $_GET['id'] ?? die('Something went wrong');

$result=$users->delete($id);

echo json_encode($result);