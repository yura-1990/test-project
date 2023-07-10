<?php

use App\Models\User;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/User.php';

$db = new Database();
$pdo = $db->connect();

$users = new User('users', $pdo);

$email = $_POST['email'] ?? die('Email went wrong');
$password = $_POST['password'] ?? die('Password went wrong');

$result=$users->login($email, $password);
echo json_encode($result);