<?php


namespace App\utils;

use App\model\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Dotenv\Dotenv;
use App\exception\ValidationException;
use Exception;

class JwtHelper {
    private string $key;

    public function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../../");
        $dotenv->load();
        $this->key = $_ENV["JWT_PASSWORD"];
    }


public function generateToken(User $user) {
   $payload = [    "userId" => $user->getId(),    "role" => $user->getRole(), "exp" => time() + 3600];

   $jwt = JWT::encode($payload, $this->key, "HS256");
   return $jwt;
}


public function validateToken($token) {
    if(!$token) {
        throw new ValidationException("Token neprišel");
    }


    if(preg_match("/Bearer\s(\S+)/", $token, $matches)) {
            $jwt = $matches[1];
            try {
             $data = JWT::decode($jwt, new Key($this->key, "HS256"));
             return $data;
            }
            catch(ExpiredException $e) {
                throw new ExpiredException("Token vypršel.");
           }
           catch(SignatureInvalidException $e) {
            throw new ValidationException("Token není platný");
           }
    }
    //token je ve spatnem formatu
    throw new ValidationException("Token je ve špatném formátu");
}


}


?>