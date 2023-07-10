<?php

namespace App\Models;

require_once __DIR__ . '/../config/Auth.php';
require_once __DIR__ . '/../config/JWTToken.php';
require_once __DIR__ . '/../enum/RoleTypeEnum.php';

use App\Auth\Auth;
use App\JWT\JWTToken;
use App\Enum\RoleTypeEnum;

class User
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

    public function create($name, $email, $password): array
    {
        $query = "SELECT COUNT(*) FROM $this->table WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();

        if ($count > 0){
            return ["msg" => "$email is exist"];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role_id = RoleTypeEnum::USER->value;

        $query = "INSERT INTO $this->table (name, email, password, role_id) VALUES (?, ?, ?, ?);";
        $smtp = $this->conn->prepare($query);
        $smtp->execute([$name, $email, $hashedPassword, $role_id]);

        $lastInsertId = $this->conn->lastInsertId();
        $selectQuery = "SELECT * FROM $this->table WHERE id = ?;";
        $selectStatement = $this->conn->prepare($selectQuery);
        $selectStatement->execute([$lastInsertId]);
        $user = $selectStatement->fetch(\PDO::FETCH_OBJ);

        $user = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
        ];

        $jwt = new JWTToken();
        $token = $jwt->generate($user);

        return [$user, $token];
    }

    public function login($email, $password): array
    {
        $query = "SELECT * FROM $this->table WHERE email = ?;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (password_verify($password, $user['password'])){
            $jwt = new JWTToken();
            $token = $jwt->generate($user);

            return [$user, $token];
        }

        return ['msg' => 'email is not exist in list'];
    }

    public function getMe(){
        $userID = Auth::user();
        $query = "SELECT * FROM users WHERE id = ?";
        $statement = $this->conn->prepare($query);
        $statement->execute([$userID->user_id]);

        return $statement->fetch(\PDO::FETCH_OBJ);
    }

    public function update($name, $email, $password, $id)
    {
        if (Auth::user()->user_role !== RoleTypeEnum::ADMIN->value)
        {
            return ['msg' => 'You are not authorized for this action'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE $this->table SET name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([$name, $email, $hashedPassword, $id])) {
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

    public function delete($id)
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