<?php

namespace App\repository;

use App\model\User;
use App\model\ErrorLog;
use PDO;

class UserRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }


    


    public function createUser(User $user): User {
         $stmt = $this->pdo->prepare("INSERT INTO user(name, surname, email,password) VALUES (:name, :surname, :email, :password)");

        $name = $user->getName();
        $surname = $user->getSurname();
        $email = $user->getEmail();
        $password = password_hash($user->getPassword(),PASSWORD_DEFAULT);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":surname", $surname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);

        $stmt->execute();
                $userId = $this->pdo->lastInsertId();
                $user->setId($userId); 
                return $user;

    }


    
    public function isEmailInDatabase($email) :bool  {

            $stmt = $this->pdo->prepare("SELECT id FROM user WHERE email=:email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data !== false;
    }

    public function getPasswordByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT password FROM user WHERE email=:email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $userPassword = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userPassword;

    }

    public function getPasswordById($id) :string{

        $stmt = $this->pdo->prepare("SELECT password FROM user WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data["password"];
    }


    public function getUserDataByEmail($email) {
         $stmt = $this->pdo->prepare("SELECT id, name, surname, role  FROM user WHERE email=:email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userData;



    }



    public function getUserById($id) {

        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    
    public function editUserData($id, $name, $surname) {

        $stmt = $this->pdo->prepare("UPDATE user SET name=:name, surname=:surname WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":surname", $surname);
        $stmt->execute();

    }


    public function changePassword($id, $newPassword) {

        $stmt = $this->pdo->prepare("UPDATE user SET password=:password WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":password", $newPassword);
        $stmt->execute();

    }
}