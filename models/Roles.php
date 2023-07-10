<?php

namespace App\Models;

require_once __DIR__ . '/../config/Auth.php';
require_once __DIR__ . '/../config/JWTToken.php';
require_once __DIR__ . '/../enum/RoleTypeEnum.php';

use App\Auth\Auth;
use App\Enum\RoleTypeEnum;

class Roles
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

    public function create($role_name)
    {
        if (Auth::user()->user_role !== RoleTypeEnum::ADMIN->value)
        {
            return ['msg' => 'You are not authorized for this action'];
        }

        $query = "INSERT INTO $this->table (role_name) VALUES (?);";
        $smtp = $this->conn->prepare($query);
        $smtp->execute([$role_name]);

        $lastInsertId = $this->conn->lastInsertId();
        $selectQuery = "SELECT * FROM $this->table WHERE id = ?;";
        $selectStatement = $this->conn->prepare($selectQuery);
        $selectStatement->execute([$lastInsertId]);
        $roles = $selectStatement->fetch(\PDO::FETCH_OBJ);

        return $roles;
    }

    public function update($id, $role_name): array
    {
        if (Auth::user()->user_role !== RoleTypeEnum::ADMIN->value)
        {
            return ['msg' => 'You are not authorized for this action'];
        }

        $query = "UPDATE $this->table SET role_name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([$role_name, $id])) {
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
        if (Auth::user()->user_role !== RoleTypeEnum::ADMIN->value)
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