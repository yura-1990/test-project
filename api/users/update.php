<?php

use App\Models\User;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../core/Database.php';
include_once '../../models/User.php';

$db = new Database();
$pdo = $db->connect();

$users = new User('testproject.users', $pdo);

$name = $_POST['name'] ?? die('Something went wrong');
$id = $_POST['id'] ?? die('Something went wrong');

if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $email = $_POST['email'] ?? die('Something went wrong');
} else {
    die(json_encode(["email" => 'email should contains @ and . symbols']));
}

if (strlen($_POST['password']) >= 8) {
    $password = $_POST['password'] ?? die('Something went wrong');
} else {
    die(json_encode(["password" => 'password should contains more than 8 symbols']));
}

$result=$users->update($name, $email, $password, $id);
echo json_encode($result);