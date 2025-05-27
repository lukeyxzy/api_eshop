<?php

namespace App\service;

use App\repository\UserRepository;
use App\model\User;
use App\utils\JwtHelper;
use App\exception\AuthenticationException;


class UserService{
    private UserRepository $userRepository;
    private JwtHelper $jwtHelper;

    public function __construct(UserRepository $userRepository, JwtHelper $jwtHelper) {
        $this->userRepository = $userRepository;
        $this->jwtHelper = $jwtHelper;
    }



    public function createUser($userData) { 
        $result = $this->isEmailInDatabase($userData["email"]);
        if(!$result) {
            return false;
        }

 
        $user = new User($userData["name"], $userData["surname"],$userData["email"], $userData["password"]);

        $userWithDbId = $this->userRepository->createUser($user);

        $token = $this->jwtHelper->generateToken($userWithDbId);
        return $token;
    }



private function isEmailInDatabase($email)  {
       return $this->userRepository->isEmailInDatabase($email);
}

    public function authentizationByEmail($email, $password) {
    
    $passwordObject = $this->userRepository->getPasswordByEmail($email);

            // email není v databázi
            if (!$passwordObject) {
                return false;
            }
            // heslo nesedí s emailem
            if(!password_verify($password, $passwordObject["password"])) {
                  return false;
            }

    $userData = $this->userRepository->getUserDataByEmail($email);


     $user = new User($userData["name"],$userData["surname"],$email ,$passwordObject["password"],$userData["role"], $userData["id"]);

     $token = $this->jwtHelper->generateToken($user);
     return $token;
    }



    public function getLoggedInUserData($token) {
    
    $tokenData = $this->jwtHelper->validateToken($token);

    $userData = $this->userRepository->getUserById($tokenData->userId);

    $user = new User($userData["name"],$userData["surname"],$userData["email"] ,$userData["password"],$tokenData->role, $tokenData->userId);

    return $user;

    }


    
    public function editUserData($token, $name, $surname, $password) {
        //validace tokenu
         $userData = $this->jwtHelper->validateToken($token);

        // validace hesla
        $this->authentizationById($userData->userId, $password);

        // požadavek na změnu
        return $this->userRepository->editUserData($userData->userId, $name, $surname);

    }

    function authentizationById($id, $password) {
    
    $passwordFromDb = $this->userRepository->getPasswordById($id);

            // id není v databázi
            if (!$passwordFromDb) {
                throw new Exception("Uživatel nenalezen v databázi podle Id z tokenu");
            }
            // heslo nesedí s emailem
            if(!password_verify($password, $passwordFromDb)) {
                throw new AuthenticationException("Původní heslo je neplatné");
            }
            return true;
    }



    public function changePassword($token, $oldPassword, $newPassword) {
        //validace tokenu

        $userData = $this->jwtHelper->validateToken($token);
        
         // validace hesla
        $this->authentizationById($userData->userId, $oldPassword);

        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $this->userRepository->changePassword($userData->userId, $hashed_password);

    }


    public function check_token($token) {
        if(!$token) {
         throw new ValidationException("Token chybí");
        }
        
        $result = $this->jwtHelper->validateToken($token);
        return $result;
    }
 
}