<?php

namespace App\Controller;
use App\service\UserService;
use App\model\ErrorLog;
use App\exception\ValidationException;
use App\exception\AuthenticationException;
use Firebase\JWT\ExpiredException;
use Exception;
use App\controller\BaseController;

class UserController extends BaseController{
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }




    public function createUser() {

        $data = $this->getJsonInput();

        if(empty($data["email"]) || empty($data["name"]) || empty($data["password"])) {
            throw new ValidationException("Nejsou vyplněna všechna povinná pole");
        }
        if(!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException("Email je ve spatnem formatu");
        }
        if(strlen($data["password"]) < 8) {
               throw new ValidationException("Heslo musi mit minimalne 8 znaku");
        }
        $token = $this->userService->createUser($data);
        $this->jsonResponse(["token" => $token]);
        }

    public function check_token($token) {
         $result =  $this->userService->check_token($token);
        $this->jsonResponse($result);
    }


    
    public function authentization() {
        $data = $this->getJsonInput();

        if(empty($data["email"]) || empty($data["password"])) {
                throw new ValidationException("Nejsou vyplněna všechna povinná pole.");
        }
        if(!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
        throw new ValidationException("neplatna forma e-mailu");
        }
        $token = $this->userService->authentizationByEmail($data["email"], $data["password"]);
        $this->jsonResponse(["token" => $token]);
    }


    public function getLoggedInUserData($token) {
            $user = $this->userService->getLoggedInUserData($token);
            echo json_encode($user);
    }

    public function editUserData($token) {
        $data = $this->getJsonInput();
        if(empty($data["password"]) || empty($data["name"]) || empty($data["surname"])) {
            throw new ValidationException("Nejsou vyplěna všechna povinná pole.");
        }
        $result = $this->userService->editUserData($token, $data["name"], $data["surname"], $data["password"]);
                 $this->jsonResponse(["success" => true]);
    }


    public function changePassword($token) {
          $data = $this->getJsonInput();

        if (empty($data["oldPassword"]) || empty($data["newPassword"])) {
           throw new ValidationException("Nejsou vyplněny všechny hodnoty");
        }
        if (strlen($data["newPassword"]) < 8) {
            throw new ValidationException("Heslo musí být delší než 8 znaků.");
        }            
        $result=  $this->userService->changePassword($token, $data["oldPassword"], $data["newPassword"]);
        $this->jsonResponse(["success" => true]);
    }

    }