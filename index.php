<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once './core/Database.php';

$conn = new Database();

$roleSQL = "CREATE TABLE IF NOT EXISTS roles (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL);";

$conn->connect()->exec($roleSQL);

$userSQL = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT(6) UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);";

$storeSQL = "CREATE TABLE IF NOT EXISTS stores (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$conn->connect()->exec($storeSQL);

$productSQL = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT NULL,
    store_id BIGINT NULL,
    category VARCHAR(255) NULL,
    product_name VARCHAR(255)  NULL,
    data_expire TEXT  NULL,
    debit BIGINT NULL,
    credit BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP );";

$conn->connect()->exec($productSQL);