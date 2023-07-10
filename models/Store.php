<?php

namespace App\Models;

require_once __DIR__ . '/../config/Auth.php';
require_once __DIR__ . '/../config/JWTToken.php';
require_once __DIR__ . '/../enum/RoleTypeEnum.php';

use App\Auth\Auth;
use App\JWT\JWTToken;
use App\Enum\RoleTypeEnum;

class Store
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

    public function show($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = ?;";
        $smtp = $this->conn->prepare($query);
        $smtp->execute([$id]);
        return $smtp->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($name)
    {
        if (!Auth::user()){
            return ['msg' => 'Unauthorized'];
        }

        $query = "INSERT INTO $this->table (name) VALUES (?);";
        $smtp = $this->conn->prepare($query);
        $smtp->execute([$name]);

        $lastInsertId = $this->conn->lastInsertId();
        $selectQuery = "SELECT * FROM $this->table WHERE id = ?;";
        $selectStatement = $this->conn->prepare($selectQuery);
        $selectStatement->execute([$lastInsertId]);

        return $selectStatement->fetch(\PDO::FETCH_OBJ);
    }

    public function update($id, $name): array
    {
        if (!Auth::user()){
            return ['msg' => 'Unauthorized'];
        }

        $query = "UPDATE $this->table SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([$name, $id])) {
            $retrieveQuery = "SELECT * FROM $this->table WHERE id = ?";
            $retrieveStmt = $this->conn->prepare($retrieveQuery);

            if ($retrieveStmt->execute([$id])) {
                return $retrieveStmt->fetch(\PDO::FETCH_ASSOC); // Display the updated data
            } else {
                return ["msg" => "Failed to retrieve updated data."];
            }
        } else {
            return ["msg" => "Failed to update data."];
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