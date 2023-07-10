<?php

namespace App\Models;

require_once __DIR__ . '/../config/Auth.php';

use App\Auth\Auth;

class Product
{
    private $conn;
    private string $table;

    public function __construct(string $table, $conn)
    {
        $this->table = $table;
        $this->conn = $conn;
    }

    public function getAll()
    {
        $query = "SELECT * FROM $this->table";
        $smtp = $this->conn->prepare($query);
        $smtp->execute();
        return $smtp->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create($product_name, $parent_id, $store_id, $category, $data_expire, $debit, $credit)
    {
        if (!Auth::user()){
            return ['msg' => 'Unauthorized'];
        }

        $query = "INSERT INTO $this->table (product_name, parent_id, store_id, category, data_expire, debit, credit) VALUES (?,?,?,?,?,?,?);";
        $stmt = $this->conn->prepare($query);

        if ($parent_id == "" && $store_id == ""){
            $stmt->execute([$product_name, null, null, $category, $data_expire, intval($debit), intval($credit)]);
        }elseif ($parent_id == ""){
            $stmt->execute([$product_name, null, $store_id, $category, $data_expire, intval($debit), intval($credit)]);
        } elseif ($store_id == ""){
            $stmt->execute([$product_name, $parent_id, null, $category, $data_expire, intval($debit), intval($credit)]);
        } else {
            $stmt->execute([$product_name, $parent_id, $store_id, $category, $data_expire, intval($debit), intval($credit)]);
        }

        $lastInsertId = $this->conn->lastInsertId();
        $selectQuery = "SELECT * FROM $this->table WHERE id = ?;";
        $selectStatement = $this->conn->prepare($selectQuery);
        $selectStatement->execute([$lastInsertId]);

        return $selectStatement->fetch(\PDO::FETCH_OBJ);
    }


    public function show($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = ?;";
        $smtp = $this->conn->prepare($query);
        $smtp->execute([$id]);
        return $smtp->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($parent_id, $store_id, $category, $product_name, $data_expire, $debit, $credit, $id): array
    {
        if (!Auth::user()){
            return ['msg' => 'Unauthorized'];
        }

        $query = "UPDATE $this->table SET parent_id = ?, store_id = ?, category = ?, product_name = ?, data_expire = ?, debit = ?, credit = ?
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if ($parent_id == "" && $store_id == ""){
            $stmt->execute([null, null, $category, $product_name, $data_expire, intval($debit), intval($credit), $id]);
        }elseif ($parent_id == ""){
            $stmt->execute([null, $store_id, $category, $product_name, $data_expire, intval($debit), intval($credit), $id]);
        } elseif ($store_id == ""){
            $stmt->execute([$parent_id, null, $category, $product_name, $data_expire, intval($debit), intval($credit), $id]);
        } else {
            $stmt->execute([$parent_id, $store_id, $category, $product_name, $data_expire, intval($debit), intval($credit), $id]);
        }

        $retrieveQuery = "SELECT * FROM $this->table WHERE id = ?";
        $retrieveStmt = $this->conn->prepare($retrieveQuery);

        if ($retrieveStmt->execute([$id])) {
            return $retrieveStmt->fetch(\PDO::FETCH_ASSOC); // Display the updated data
        } else {
            return ["msg" => "Failed to retrieve updated data."];
        }
    }

    public function delete($id): array
    {
        if (!Auth::user())
        {
            return ['msg' => 'You are not authorized for this action'];
        }

        $deleteQuery = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($deleteQuery);

        if ($stmt->execute([$id])) {
            return ['msg' => "Data deleted successfully."];
        } else {
            return ['msg' =>  "Failed to delete data."];
        }
    }

}