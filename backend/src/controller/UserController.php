<?php

namespace App\Controller;
use App\service\UserService;
use App\model\ErrorLog;
use App\exception\ValidationException;
use App\exception\AuthenticationException;
use Firebase\JWT\ExpiredException;
use Exception;

class UserController {
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }




    public function createUser() {

        $data = json_decode(file_get_contents("php://input"), true);

        if(empty($data["email"]) || empty($data["name"]) || empty($data["password"])) {
        echo json_encode(["error" => "Nejsou vyplněna všechna povinná pole"]);
        return;
        }
        if(!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Email je ve spatnem formatu"]);
        return;
        }
        if(strlen($data["password"]) < 8) {
        echo json_encode(["error" => "Heslo musi mit minimalne 8 znaku"]);
        return;
        }
 

        try {
            $token = $this->userService->createUser($data);

            if (!$token) {
                http_response_code(409);
                echo json_encode(["error" => "Tento e-mail je již zaregistrovaný, prosím přihlašte se."]);
                return;
            }
            
            http_response_code(200);
            echo json_encode(["token" => $token]);
            
            }
            catch(Exception $e) {
                http_response_code(500);
                ErrorLog::logError($e);
               echo json_encode(["error" => "Chyba na straně serveru."]);
            }
    
        }


    public function check_token($token) {

       try {
         $result =  $this->userService->check_token($token);
        echo json_encode($result);
       }
       catch(ExpiredException $e) {
        echo json_encode(["error" => $e->getMessage(), "expired" => true]);
        http_response_code(401);
       }
       catch(ValidationException $e) {
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(400);
       } 
       catch(Exception $e) {
        http_response_code(500);
        ErrorLog::logError($e);
        echo json_encode(["error" => "Chyba na straně serveru."]);
       }
    }

    
    
    
    public function authentization() {
        $data = json_decode(file_get_contents("php://input"), true);

        if(empty($data["email"]) || empty($data["password"])) {
                            http_response_code(401);
        echo json_encode(["error" => "Nejsou vyplněna všechna povinná pole."]);
        return;
        }
        if(!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
                            http_response_code(401);
        echo json_encode(["error" => "neplatna forma emailu"]);
        return;
        }
        try {
            $token = $this->userService->authentizationByEmail($data["email"], $data["password"]);
            if (!$token) {
                http_response_code(401);
                echo json_encode(["error" => "neplatne prihlasovaci udaje"]);
                return;
            }

            echo json_encode(["token" => $token]);

        }
        catch(Exception $e) {
            ErrorLog::logError($e);
            http_response_code(500);
            echo json_encode(["error" => "chyban a straně serveru."]);
        };
        
    }


    public function getLoggedInUserData($token) {
        try {
            $user = $this->userService->getLoggedInUserData($token);
 
            echo json_encode($user);
        }
        catch(ExpiredException $e) {
            echo json_encode(["error" => $e->getMessage(), "expired" => true]);
            http_response_code(401);
        }
        catch(Exception $e) {
            ErrorLog::logError($e);
        }

    }

    public function editUserData($token) {

        try {
        $input = json_decode(file_get_contents("php://input"), true);

        if(empty($input["password"]) || empty($input["name"]) || empty($input["surname"])) {
            throw new ValidationException("Nejsou vyplěna všechna povinná pole.");
        }



        $result = $this->userService->editUserData($token, $input["name"], $input["surname"], $input["password"]);
         echo json_encode(["success" => true]);
        }
        catch(ValidationException | AuthenticationException $e) {
            echo json_encode(["error" => $e->getMessage()]);
            http_response_code(400);
        }
        catch(Exception $e) {
            http_response_code(500);
            ErrorLog::logError($e);
             echo json_encode(["error" => "Chyba na straně serveru."]);
        }





    
    }


    public function changePassword($token) {
       try {
        $input = json_decode(file_get_contents("php://input"),true);

        if (empty($input["oldPassword"]) || empty($input["newPassword"])) {
           throw new ValidationException("Nejsou vyplněny všechny hodnoty");
        }
        if (strlen($input["newPassword"]) < 8) {
            throw new ValidationException("Heslo musí být delší než 8 znaků.");
        }            
        
        $result=  $this->userService->changePassword($token, $input["oldPassword"],$input["newPassword"]);


        echo json_encode(["success" => true]);
        }
        catch(ValidationException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        }
        catch(AuthenticationException $e) {
            echo json_encode(["error" => $e->getMessage()]);
            http_response_code(401);
        }
        catch(Exception $e) {
            ErrorLog::logError($e);
              http_response_code(500);
            echo json_encode(["error" => "chyba na straně serveru." . $e->getMessage()]);
        }
        

    }

    }